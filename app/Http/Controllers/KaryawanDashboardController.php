<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KaryawanDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $search = $request->query('search');

        // Ringkasan Operasional Karyawan
        $transaksiHariIni = Pesanan::validRevenue()->whereDate('tanggal_pesan', $today)->count();
        $pesananHariIni = Pesanan::whereDate('tanggal_pesan', $today)->count();
        $pendapatanHariIni = (int) Pesanan::validRevenue()->whereDate('tanggal_pesan', $today)->sum('total_harga');
        $menungguVerifikasi = Pembayaran::where('status', 'menunggu')->count();
        $pesananSelesai = Pesanan::where('status_pesanan', 'selesai')->whereDate('tanggal_pesan', $today)->count();

        $operationalStats = [
            'transaksiHariIni' => $transaksiHariIni,
            'pesananHariIni' => $pesananHariIni,
            'pendapatanHariIni' => 'Rp'.number_format($pendapatanHariIni, 0, ',', '.'),
            'menungguVerifikasi' => $menungguVerifikasi,
            'pesananSelesai' => $pesananSelesai,
        ];

        // Weekly chart data (7 hari terakhir dari tanggal acuan)
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = (clone $today)->subDays($i);
            $weeklyLabels[] = $date->locale('id')->translatedFormat('D');
            $sum = (int) Pesanan::validRevenue()->whereDate('tanggal_pesan', $date->toDateString())->sum('total_harga');
            $weeklyData[] = $sum;
        }

        // Hourly volume pada tanggal terpilih (08:00 - 20:00)
        $hourlyLabels = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
        $hourlyData = [];
        foreach ($hourlyLabels as $timeStr) {
            $hour = intval(substr($timeStr, 0, 2));
            $count = Pesanan::whereDate('tanggal_pesan', $today)
                ->whereTime('tanggal_pesan', '>=', sprintf('%02d:00:00', $hour))
                ->whereTime('tanggal_pesan', '<', sprintf('%02d:00:00', $hour + 2))
                ->count();
            $hourlyData[] = $count;
        }

        // Distribution of active/selected date orders
        $totalOrders = Pesanan::whereDate('tanggal_pesan', $today)->count();
        $diprosesCount = Pesanan::whereDate('tanggal_pesan', $today)->where('status_pesanan', 'diproses')->count();
        $sedangDibuatCount = Pesanan::whereDate('tanggal_pesan', $today)->where('status_pesanan', 'sedang_dibuat')->count();
        $siapSelesaiCount = Pesanan::whereDate('tanggal_pesan', $today)->whereIn('status_pesanan', ['siap_diambil', 'selesai'])->count();

        $distribution = [
            'total' => $totalOrders,
            'diproses' => $diprosesCount,
            'sedang_dibuat' => $sedangDibuatCount,
            'siap_selesai' => $siapSelesaiCount,
        ];

        $query = Pesanan::with(['user', 'detailPesanan.menu', 'pembayaran']);

        if ($search) {
            $orderIdSearch = $search;
            if (preg_match('/-([0-9]+)$/', $search, $matches)) {
                $orderIdSearch = intval($matches[1]);
            }
            $query->where(function ($q) use ($search, $orderIdSearch) {
                $q->where('id_pesanan', 'like', "%{$search}%")
                    ->orWhere('id_pesanan', $orderIdSearch)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('detailPesanan.menu', function ($mq) use ($search) {
                        $mq->where('nama_menu', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->query('date')) {
            $query->whereDate('tanggal_pesan', $today);
        }

        $recentOrders = $query->latest('tanggal_pesan')->take(6)->get();

        $pesananTerkini = [];
        foreach ($recentOrders as $order) {
            $menuNames = $order->detailPesanan->map(fn ($d) => $d->jumlah.'x '.($d->menu?->nama_menu ?? 'Menu'))->join(', ');

            $isWaitingPayment = ($order->pembayaran && $order->pembayaran->status === 'menunggu');

            $statusColor = match (true) {
                $order->status_pesanan === 'dibatalkan' => 'red',
                $isWaitingPayment => 'yellow',
                $order->status_pesanan === 'diproses' => 'yellow',
                $order->status_pesanan === 'sedang_dibuat' => 'brown',
                in_array($order->status_pesanan, ['siap_diambil', 'selesai']) => 'green',
                default => 'yellow',
            };

            $statusText = match (true) {
                $order->status_pesanan === 'dibatalkan' => 'Dibatalkan',
                $isWaitingPayment => 'Menunggu Verifikasi',
                $order->status_pesanan === 'diproses' => 'Diproses',
                $order->status_pesanan === 'sedang_dibuat' => 'Sedang Dibuat',
                $order->status_pesanan === 'siap_diambil' => 'Siap Diambil',
                $order->status_pesanan === 'selesai' => 'Selesai',
                default => ucfirst($order->status_pesanan),
            };

            $aksiText = match (true) {
                $order->status_pesanan === 'dibatalkan' => 'Lihat',
                $isWaitingPayment => 'Verifikasi',
                $order->status_pesanan === 'diproses' => 'Mulai Buat',
                $order->status_pesanan === 'sedang_dibuat' => 'Siap Diambil',
                $order->status_pesanan === 'siap_diambil' => 'Selesaikan',
                default => 'Lihat',
            };

            $aksiColor = match (true) {
                $order->status_pesanan === 'sedang_dibuat' => 'medium',
                default => 'dark',
            };

            $aksiLink = $isWaitingPayment ? route('karyawan.verifikasi', ['search' => $order->id_pesanan]) : route('karyawan.pesanan');

            $pesananTerkini[] = [
                'id' => $order->id_pesanan,
                'kode' => $order->order_number,
                'waktu' => $order->tanggal_pesan->format('H:i A'),
                'menu' => $menuNames ?: 'Menu Pesanan',
                'tipe' => $order->user?->nama ?? 'Guest',
                'statusColor' => $statusColor,
                'status' => $statusText,
                'aksiColor' => $aksiColor,
                'aksi' => $aksiText,
                'aksiLink' => $aksiLink,
            ];
        }

        return view('karyawan.dashboard', compact(
            'pesananTerkini',
            'operationalStats',
            'weeklyLabels',
            'weeklyData',
            'hourlyLabels',
            'hourlyData',
            'distribution'
        ));
    }

    /**
     * Tampilkan halaman manajemen menu untuk karyawan.
     */
    public function menu(Request $request): View
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_menu', 'like', "%{$search}%");
        }

        if ($request->filled('kategori') && $request->input('kategori') !== 'semua') {
            $query->where('kategori', $request->input('kategori'));
        }

        $menus = $query->orderBy('nama_menu')->paginate(3)->withQueryString();
        $categories = Menu::select('kategori')->distinct()->pluck('kategori');

        return view('karyawan.menu', [
            'menus' => $menus,
            'categories' => $categories,
            'filterCategory' => $request->input('kategori', 'semua'),
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Update stok dan status menu via AJAX.
     */
    public function updateMenuStock(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'stok' => 'required|integer|min:0',
            'status' => 'required|string|in:tersedia,habis',
        ]);

        $menu = StockService::updateMenuStock($menu->id_menu, $validated['stok'], $validated['status']);

        return response()->json([
            'success' => true,
            'message' => "Stok {$menu->nama_menu} berhasil diperbarui!",
            'stok' => $menu->stok,
            'status' => $menu->status,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        if ($user) {
            $user->update($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user' => $validated,
        ]);
    }

    /**
     * Tampilkan daftar verifikasi pembayaran.
     */
    public function verifikasi(Request $request): View
    {
        $search = $request->query('search');

        $pembayaranList = Pembayaran::with(['pesanan.user', 'pesanan.detailPesanan.menu'])
            ->where('status', 'menunggu')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pesanan', function ($q) use ($search) {
                    $q->where('id_pesanan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        return view('karyawan.verifikasi', compact('pembayaranList', 'search'));
    }

    /**
     * Setujui pembayaran dan potong stok menu secara otomatis.
     */
    public function setujuiPembayaran(Pembayaran $pembayaran)
    {
        DB::transaction(function () use ($pembayaran) {
            $isAlreadySuccess = ($pembayaran->status === 'berhasil');

            $pembayaran->status = 'berhasil';
            $pembayaran->tanggal_bayar = now();
            $pembayaran->save();

            if ($pembayaran->pesanan) {
                $pembayaran->pesanan->status_pesanan = 'diproses';
                $pembayaran->pesanan->save();

                // Kurangi stok jika belum pernah disetujui
                if (! $isAlreadySuccess) {
                    StockService::deductStockForOrder($pembayaran->pesanan);
                }
            }
        });

        return redirect()->route('karyawan.verifikasi')->with('success', 'Pembayaran berhasil disetujui dan stok menu diperbarui!');
    }

    /**
     * Tolak pembayaran dan kembalikan stok jika sebelumnya berhasil.
     */
    public function tolakPembayaran(Pembayaran $pembayaran)
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

        return redirect()->route('karyawan.verifikasi')->with('success', 'Pembayaran berhasil ditolak!');
    }

    /**
     * Tampilkan riwayat transaksi (pesanan).
     */
    public function riwayat(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'semua');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Query pesanan
        $query = Pesanan::with(['user', 'pembayaran', 'detailPesanan.menu']);

        // Search filter
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($inner) use ($search) {
                $inner->where('id_pesanan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    });
            });
        });

        // Status filter
        if ($status === 'berhasil') {
            $query->whereIn('status_pesanan', ['selesai', 'diproses', 'sedang_dibuat', 'siap_diambil']);
        } elseif ($status === 'dibatalkan') {
            $query->where('status_pesanan', 'dibatalkan');
        }

        // Date range filter
        $query->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('tanggal_pesan', '>=', $startDate);
        });
        $query->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('tanggal_pesan', '<=', $endDate);
        });

        // Get transactions
        $transactions = $query->orderBy('tanggal_pesan', 'desc')->paginate(5)->withQueryString();

        // Calculate bottom cards stats
        $totalSalesToday = Pesanan::validRevenue()
            ->whereDate('tanggal_pesan', now()->toDateString())
            ->sum('total_harga');

        $totalTransactionsQuery = Pesanan::whereIn('status_pesanan', ['selesai', 'diproses', 'sedang_dibuat', 'siap_diambil']);
        $totalTransactionsQuery->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('tanggal_pesan', '>=', $startDate);
        })->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('tanggal_pesan', '<=', $endDate);
        });
        $totalSuccessCount = $totalTransactionsQuery->count();

        $uniqueCustomersQuery = Pesanan::whereIn('status_pesanan', ['selesai', 'diproses', 'sedang_dibuat', 'siap_diambil']);
        $uniqueCustomersQuery->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('tanggal_pesan', '>=', $startDate);
        })->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('tanggal_pesan', '<=', $endDate);
        });
        $uniqueCustomersCount = $uniqueCustomersQuery->distinct('id_user')->count('id_user');

        return view('karyawan.riwayat', compact(
            'transactions',
            'search',
            'status',
            'startDate',
            'endDate',
            'totalSalesToday',
            'totalSuccessCount',
            'uniqueCustomersCount'
        ));
    }

    /**
     * Dapatkan detail struk transaksi dalam format JSON.
     *
     * @return JsonResponse
     */
    public function struk(Pesanan $pesanan): JsonResponse
    {
        $pesanan->load(['user', 'detailPesanan.menu', 'pembayaran']);

        $subtotal = $pesanan->detailPesanan->sum(function ($detail) {
            return $detail->jumlah * $detail->harga;
        });

        // Standar biaya layanan aplikasi Kote Shop (Rp 2.000)
        $serviceFee = 2000;

        // Hitung diskon dan biaya layanan yang konsisten dengan sistem checkout
        if ($pesanan->total_harga < ($subtotal + $serviceFee)) {
            $biayaLayanan = $serviceFee;
            $diskon = ($subtotal + $serviceFee) - (float) $pesanan->total_harga;
        } else {
            $biayaLayanan = max(0, (float) $pesanan->total_harga - (float) $subtotal);
            $diskon = 0;
        }

        $metodePembayaran = match (strtolower($pesanan->metode_pembayaran ?? '')) {
            'qris' => 'QRIS',
            'tunai' => 'Tunai',
            'transfer_bank', 'transfer' => 'Transfer Bank',
            default => ucfirst($pesanan->metode_pembayaran ?? '-'),
        };

        $statusLabel = match ($pesanan->status_pesanan) {
            'selesai' => 'Selesai',
            'diproses', 'sedang_dibuat', 'siap_diambil' => 'Berhasil',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($pesanan->status_pesanan),
        };

        return response()->json([
            'success' => true,
            'order_number' => $pesanan->order_number,
            'tanggal' => $pesanan->tanggal_pesan->locale('id')->translatedFormat('d M Y'),
            'waktu' => $pesanan->tanggal_pesan->format('H:i'),
            'pelanggan' => $pesanan->user?->nama ?? 'Guest',
            'kasir' => Auth::user()?->nama ?? 'Staff',
            'metode_pembayaran' => $metodePembayaran,
            'tipe_pesanan' => $pesanan->tipe_pesanan ?? 'ambil_di_toko',
            'tipe_pesanan_label' => $pesanan->tipe_pesanan_label ?? 'Ambil di Toko',
            'alamat_pengiriman' => $pesanan->alamat_pengiriman,
            'status' => $statusLabel,
            'catatan_pesanan' => $pesanan->catatan,
            'items' => $pesanan->detailPesanan->map(function ($detail) {
                return [
                    'nama' => $detail->menu->nama_menu,
                    'opsi' => $detail->opsi,
                    'catatan' => $detail->catatan,
                    'jumlah' => $detail->jumlah,
                    'harga' => number_format($detail->harga, 0, ',', '.'),
                    'subtotal' => number_format($detail->subtotal, 0, ',', '.'),
                ];
            }),
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'diskon' => number_format($diskon, 0, ',', '.'),
            'diskon_nominal' => $diskon,
            'biaya_layanan' => number_format($biayaLayanan, 0, ',', '.'),
            'pajak' => number_format($biayaLayanan, 0, ',', '.'),
            'total' => number_format($pesanan->total_harga, 0, ',', '.'),
            'total_item' => $pesanan->detailPesanan->sum('jumlah'),
        ]);
    }

    /**
     * Tampilkan halaman daftar pesanan (antrean) untuk karyawan.
     * Hanya pesanan yang pembayarannya telah disetujui (status: berhasil) yang masuk ke antrean kerja.
     */
    public function pesanan(Request $request): View
    {
        $search = $request->query('search');

        $query = Pesanan::with(['user', 'detailPesanan.menu', 'pembayaran'])
            ->whereHas('pembayaran', function ($q) {
                $q->where('status', 'berhasil');
            })
            ->whereIn('status_pesanan', ['diproses', 'sedang_dibuat', 'siap_diambil']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_pesanan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('detailPesanan.menu', function ($mq) use ($search) {
                        $mq->where('nama_menu', 'like', "%{$search}%");
                    });
            });
        }

        $allOrders = $query->orderBy('tanggal_pesan', 'asc')->get();

        // Group orders by status
        $diproses = $allOrders->where('status_pesanan', 'diproses');
        $sedangDibuat = $allOrders->where('status_pesanan', 'sedang_dibuat');
        $siapDiambil = $allOrders->where('status_pesanan', 'siap_diambil');

        return view('karyawan.pesanan', compact('diproses', 'sedangDibuat', 'siapDiambil', 'search'));
    }

    /**
     * Update status pesanan (e.g. diproses -> sedang_dibuat -> siap_diambil -> selesai, atau dibatalkan).
     */
    public function updateOrderStatus(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:diproses,sedang_dibuat,siap_diambil,selesai,dibatalkan',
        ]);

        DB::transaction(function () use ($pesanan, $validated) {
            $previousStatus = $pesanan->status_pesanan;
            $pesanan->status_pesanan = $validated['status'];
            $pesanan->save();

            // Jika status dibatalkan dan pembayaran sudah berhasil (stok sebelumnya terpotong), kembalikan stok
            if ($validated['status'] === 'dibatalkan' && $previousStatus !== 'dibatalkan') {
                if ($pesanan->pembayaran && $pesanan->pembayaran->status === 'berhasil') {
                    StockService::restoreStockForOrder($pesanan);
                }
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui!',
                'status' => $pesanan->status_pesanan,
            ]);
        }

        $statusLabels = [
            'sedang_dibuat' => 'Pesanan sekarang sedang dibuat.',
            'siap_diambil' => 'Pesanan siap diambil.',
            'selesai' => 'Pesanan telah selesai.',
            'dibatalkan' => 'Pesanan dibatalkan.',
        ];

        $message = $statusLabels[$validated['status']] ?? 'Status pesanan berhasil diperbarui!';

        return redirect()->route('karyawan.pesanan')->with('success', $message);
    }
}
