<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Promo;
use App\Models\Ulasan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the order history page with optional status filtering.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $userId = auth()->user()->id_user;

        $pesanan = Pesanan::with(['detailPesanan.menu', 'pembayaran'])
            ->where('id_user', $userId)
            ->when($status && $status !== 'semua', function ($query) use ($status) {
                if ($status === 'diproses') {
                    $query->whereIn('status_pesanan', ['diproses', 'sedang_dibuat', 'siap_diambil']);
                } else {
                    $query->where('status_pesanan', $status);
                }
            })
            ->orderByDesc('tanggal_pesan')
            ->get();

        // Determine which orders have already been reviewed by order ID
        $sudahDiulasOrderIds = Ulasan::where('id_user', $userId)
            ->whereNotNull('id_pesanan')
            ->where('status', 'aktif')
            ->pluck('id_pesanan')
            ->toArray();

        return view('orders.index', compact('pesanan', 'status', 'sudahDiulasOrderIds'));
    }

    /**
     * Store a newly created order and payment with stock validation.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'tipe_pesanan' => 'nullable|string|in:ambil_di_toko,diantar',
            'alamat_pengiriman' => 'nullable|required_if:tipe_pesanan,diantar|string|max:500',
            'catatan' => 'nullable|string',
            'cart' => 'required|array',
            'cart.*.id' => 'required|integer',
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric',
            'cart.*.sugar' => 'nullable|string',
            'cart.*.ice' => 'nullable|string',
            'cart.*.toppings' => 'nullable|array',
            'cart.*.notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 1. Calculate total price
        $subtotal = 0;
        foreach ($validated['cart'] as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        $serviceFee = 2000;
        $totalHarga = $subtotal + $serviceFee;

        // Apply discount if promo was used
        $discount = 0;
        $promoCode = $request->input('promo_code');
        if ($promoCode) {
            $promo = Promo::active()
                ->valid()
                ->where('kode_voucher', strtoupper(trim($promoCode)))
                ->first();

            if ($promo) {
                if ($promo->satuan_nilai === 'persen') {
                    $discount = (int) round(($subtotal * $promo->nilai_promo) / 100);
                } else {
                    $discount = min((int) $subtotal, (int) $promo->nilai_promo);
                }
            }
        }

        if ($discount === 0 && $request->has('discount')) {
            $discount = max(0, (int) $request->input('discount', 0));
        }

        $totalHarga = max(0, $totalHarga - $discount);

        $tipePesanan = $validated['tipe_pesanan'] ?? 'ambil_di_toko';
        $alamatPengiriman = ($tipePesanan === 'diantar') ? ($validated['alamat_pengiriman'] ?? null) : null;

        // 2. Simpan Pesanan, Detail, dan Pembayaran dalam DB Transaction dengan Pessimistic Row Locking
        try {
            $pesanan = DB::transaction(function () use ($user, $validated, $totalHarga, $tipePesanan, $alamatPengiriman, $request) {
                // Validasi & kunci stok menu dalam transaksi untuk mencegah race condition
                foreach ($validated['cart'] as $item) {
                    $menu = Menu::where('id_menu', $item['id'])->lockForUpdate()->first();
                    if (! $menu) {
                        throw new \DomainException('Salah satu menu yang dipilih tidak ditemukan.');
                    }

                    if ($menu->isOutOfStock()) {
                        throw new \DomainException("Menu '{$menu->nama_menu}' saat ini sedang habis.");
                    }

                    if ($item['qty'] > $menu->stok) {
                        throw new \DomainException("Stok tidak mencukupi untuk {$menu->nama_menu}. Stok tersedia: {$menu->stok}.");
                    }
                }

                // Create order (Pesanan)
                $pesanan = new Pesanan;
                $pesanan->id_user = $user->id_user;
                $pesanan->tanggal_pesan = now();
                $pesanan->total_harga = $totalHarga;
                $pesanan->metode_pembayaran = $validated['payment_method'];
                $pesanan->tipe_pesanan = $tipePesanan;
                $pesanan->alamat_pengiriman = $alamatPengiriman;
                $pesanan->catatan = $request->input('catatan');
                $pesanan->status_pesanan = 'diproses';
                $pesanan->save();

                // Create detail_pesanan records
                foreach ($validated['cart'] as $item) {
                    $options = [];
                    if (! empty($item['sugar'])) {
                        $options[] = "Gula {$item['sugar']}";
                    }
                    if (! empty($item['ice'])) {
                        $options[] = "{$item['ice']} Ice";
                    }
                    if (! empty($item['toppings']) && is_array($item['toppings'])) {
                        $options[] = implode(', ', $item['toppings']);
                    }
                    $opsiText = ! empty($options) ? implode(', ', $options) : null;
                    $catatanText = ! empty($item['notes']) ? trim($item['notes']) : null;

                    DB::table('detail_pesanan')->insert([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_menu' => $item['id'],
                        'jumlah' => $item['qty'],
                        'opsi' => $opsiText,
                        'catatan' => $catatanText,
                        'harga' => $item['price'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ]);
                }

                // Create Pembayaran record
                $pembayaran = new Pembayaran;
                $pembayaran->id_pesanan = $pesanan->id_pesanan;
                $pembayaran->metode = $validated['payment_method'];
                $pembayaran->nominal = $totalHarga;
                $pembayaran->tanggal_bayar = now();
                $pembayaran->status = 'menunggu';
                $pembayaran->save();

                // Clear user cart
                $user->cart = [];
                $user->save();

                return $pesanan;
            });
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat!',
            'order' => $pesanan,
        ]);
    }

    /**
     * Upload bukti pembayaran for an order.
     */
    public function uploadBukti(Request $request, Pesanan $pesanan): RedirectResponse
    {
        // Ensure the order belongs to the authenticated user
        if ($pesanan->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti-pembayaran', 'public');

        $pembayaran = $pesanan->pembayaran;
        if ($pembayaran) {
            $pembayaran->bukti_transfer = '/storage/'.$path;
            $pembayaran->save();
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi karyawan.');
    }

    /**
     * Display the order details / success page.
     */
    public function show(Pesanan $pesanan): View|RedirectResponse
    {
        // Ensure the order belongs to the authenticated user
        if ($pesanan->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        // Eager load detailPesanan, menu, and pembayaran
        $pesanan->load(['detailPesanan.menu', 'pembayaran']);

        // If payment is still pending verification, redirect to payment page
        if ($pesanan->pembayaran && $pesanan->pembayaran->status === 'menunggu' && ! request()->has('receipt')) {
            return redirect()->route('payment', $pesanan->id_pesanan);
        }

        return view('orders.show', compact('pesanan'));
    }

    /**
     * Get the payment status of an order for polling.
     */
    public function getStatus(Pesanan $pesanan): JsonResponse
    {
        if ($pesanan->id_user !== auth()->user()->id_user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesanan->load('pembayaran');

        return response()->json([
            'status_pesanan' => $pesanan->status_pesanan,
            'status_pembayaran' => $pesanan->pembayaran ? $pesanan->pembayaran->status : 'belum_bayar',
        ]);
    }

    /**
     * Delete the specified order from history.
     */
    public function destroy(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        // Clean up proof of payment file if exists
        if ($pesanan->pembayaran && $pesanan->pembayaran->bukti_transfer) {
            $relativePath = str_replace('/storage/', '', $pesanan->pembayaran->bukti_transfer);
            Storage::disk('public')->delete($relativePath);
        }

        $pesanan->delete();

        return redirect()->route('orders.index')->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}
