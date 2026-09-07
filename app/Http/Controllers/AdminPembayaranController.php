<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPembayaranController extends Controller
{
    /**
     * Tampilkan halaman manajemen pembayaran
     */
    public function index(Request $request): View
    {
        // 1. Hitung Statistik Pembayaran
        $totalPembayaranMasuk = Pembayaran::validRevenue()->sum('nominal');
        $menungguVerifikasi = Pembayaran::where('status', 'menunggu')->count();
        $ditolak = Pembayaran::where('status', 'gagal')->count();

        $stats = [
            'totalPembayaranMasuk' => number_format($totalPembayaranMasuk, 0, ',', '.'),
            'menungguVerifikasi' => $menungguVerifikasi,
            'ditolak' => $ditolak,
        ];

        // 2. Query Riwayat Transaksi
        $query = Pembayaran::with(['pesanan.user', 'pesanan.detailPesanan.menu']);

        // Search Filter (mencari berdasarkan ID pesanan atau nama pelanggan)
        if ($request->filled('search')) {
            $search = $request->input('search');

            // Ekstrak ID pesanan numerik jika format pencarian adalah #ORD-YYYYMMDD-XXX
            $orderIdSearch = $search;
            if (preg_match('/-([0-9]+)$/', $search, $matches)) {
                $orderIdSearch = intval($matches[1]);
            }

            $query->where(function ($q) use ($search, $orderIdSearch) {
                $q->where('id_pesanan', 'like', "%{$search}%")
                    ->orWhere('id_pesanan', $orderIdSearch)
                    ->orWhereHas('pesanan.user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Tab dan Status Filter
        $tab = $request->input('tab', 'riwayat'); // 'riwayat' atau 'metode'
        $statusFilter = $request->input('status', 'semua'); // 'semua', 'menunggu', 'terverifikasi', 'ditolak'

        if ($statusFilter === 'menunggu') {
            $query->where('status', 'menunggu');
        } elseif ($statusFilter === 'terverifikasi') {
            $query->where('status', 'berhasil');
        } elseif ($statusFilter === 'ditolak') {
            $query->where('status', 'gagal');
        }

        $pembayaranList = $query->orderByDesc('id_pembayaran')->paginate(10)->withQueryString();

        // 3. Ambil Pengaturan Metode Pembayaran
        $paymentSettings = Pembayaran::getSettings();

        return view('admin.pembayaran.index', compact(
            'stats',
            'pembayaranList',
            'paymentSettings',
            'tab',
            'statusFilter'
        ));
    }

    /**
     * Setujui pembayaran dan potong stok menu secara otomatis.
     */
    public function setujui(Pembayaran $pembayaran): RedirectResponse
    {
        DB::transaction(function () use ($pembayaran) {
            $isAlreadySuccess = ($pembayaran->status === 'berhasil');

            $pembayaran->status = 'berhasil';
            $pembayaran->tanggal_bayar = now();
            $pembayaran->save();

            if ($pembayaran->pesanan) {
                $pembayaran->pesanan->status_pesanan = 'diproses';
                $pembayaran->pesanan->save();

                // Kurangi stok jika sebelumnya belum berstatus berhasil
                if (! $isAlreadySuccess) {
                    StockService::deductStockForOrder($pembayaran->pesanan);
                }
            }
        });

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil disetujui dan stok menu diperbarui!');
    }

    /**
     * Tolak pembayaran dan kembalikan stok jika sebelumnya berhasil.
     */
    public function tolak(Pembayaran $pembayaran): RedirectResponse
    {
        DB::transaction(function () use ($pembayaran) {
            $previousStatus = $pembayaran->status;

            $pembayaran->status = 'gagal';
            $pembayaran->save();

            if ($pembayaran->pesanan) {
                $pembayaran->pesanan->status_pesanan = 'dibatalkan';
                $pembayaran->pesanan->save();

                // Kembalikan stok jika sebelumnya berstatus berhasil
                if ($previousStatus === 'berhasil') {
                    StockService::restoreStockForOrder($pembayaran->pesanan);
                }
            }
        });

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditolak!');
    }

    /**
     * Perbarui pengaturan metode pembayaran
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'method_type' => 'required|string|in:qris,transfer_bank,tunai',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'merchant_id' => 'nullable|string|max:100',
            'merchant_name' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:100',
            'nama_pemilik' => 'nullable|string|max:100',
        ]);

        $settings = Pembayaran::getSettings();
        $methodType = $request->input('method_type');

        if ($methodType === 'qris') {
            $settings['qris']['status'] = $request->has('status');
            $settings['qris']['merchant_id'] = $request->input('merchant_id', $settings['qris']['merchant_id']);
            $settings['qris']['merchant_name'] = $request->input('merchant_name', $settings['qris']['merchant_name']);

            if ($request->hasFile('qr_image')) {
                $file = $request->file('qr_image');
                $filename = 'QR_Pembayaran_'.time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                $settings['qris']['qr_image'] = 'images/'.$filename;
            }
        } elseif ($methodType === 'transfer_bank') {
            $settings['transfer_bank']['status'] = $request->has('status');
            $settings['transfer_bank']['bank_name'] = $request->input('bank_name', $settings['transfer_bank']['bank_name']);
            $settings['transfer_bank']['no_rekening'] = $request->input('no_rekening', $settings['transfer_bank']['no_rekening']);
            $settings['transfer_bank']['nama_pemilik'] = $request->input('nama_pemilik', $settings['transfer_bank']['nama_pemilik']);
        } elseif ($methodType === 'tunai') {
            $settings['tunai']['status'] = $request->has('status');
        }

        Pembayaran::saveSettings($settings);

        return redirect()->route('admin.pembayaran.index', ['tab' => 'metode'])->with('success', 'Metode pembayaran berhasil diperbarui!');
    }
}
