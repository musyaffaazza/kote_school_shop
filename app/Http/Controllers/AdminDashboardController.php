<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pengeluaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the financial report and admin dashboard.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 1. Calculate Core Revenue and Operational Metrics directly from Database
        $pendapatanHariIniRaw = (int) Pesanan::validRevenue()->whereDate('tanggal_pesan', $today)->sum('total_harga');
        $pendapatanKemarinRaw = (int) Pesanan::validRevenue()->whereDate('tanggal_pesan', $yesterday)->sum('total_harga');

        $pendapatanMingguIniRaw = (int) Pesanan::validRevenue()->whereBetween('tanggal_pesan', [$startOfWeek, $endOfWeek])->sum('total_harga');
        $pendapatanMingguLaluRaw = (int) Pesanan::validRevenue()->whereBetween('tanggal_pesan', [$startOfLastWeek, $endOfLastWeek])->sum('total_harga');

        $pendapatanBulanIniRaw = (int) Pesanan::validRevenue()->whereBetween('tanggal_pesan', [$startOfMonth, $endOfMonth])->sum('total_harga');
        $pendapatanBulanLaluRaw = (int) Pesanan::validRevenue()->whereBetween('tanggal_pesan', [$startOfLastMonth, $endOfLastMonth])->sum('total_harga');

        $totalPenjualanRaw = (int) Pesanan::validRevenue()->sum('total_harga');
        $totalPengeluaranRaw = (int) Pengeluaran::sum('jumlah');
        $labaBersihRaw = $totalPenjualanRaw - $totalPengeluaranRaw;
        $marginLaba = $totalPenjualanRaw > 0 ? ($labaBersihRaw / $totalPenjualanRaw) * 100 : 0;

        $totalTransaksiCount = Pesanan::validRevenue()->count();
        $transaksiHariIniCount = Pesanan::validRevenue()->whereDate('tanggal_pesan', $today)->count();
        $transaksiKemarinCount = Pesanan::validRevenue()->whereDate('tanggal_pesan', $yesterday)->count();

        $produkTerjualCount = (int) DetailPesanan::whereHas('pesanan', function ($q) {
            $q->validRevenue();
        })->sum('jumlah');

        $produkTerjualHariIni = (int) DetailPesanan::whereHas('pesanan', function ($q) use ($today) {
            $q->validRevenue()->whereDate('tanggal_pesan', $today);
        })->sum('jumlah');

        $produkTerjualKemarin = (int) DetailPesanan::whereHas('pesanan', function ($q) use ($yesterday) {
            $q->validRevenue()->whereDate('tanggal_pesan', $yesterday);
        })->sum('jumlah');

        // Dynamic Trend Calculations
        $trendHariIni = $this->calculateTrend($pendapatanHariIniRaw, $pendapatanKemarinRaw, 'kemarin');
        $trendMingguIni = $this->calculateTrend($pendapatanMingguIniRaw, $pendapatanMingguLaluRaw, 'minggu lalu');
        $trendBulanIni = $this->calculateTrend($pendapatanBulanIniRaw, $pendapatanBulanLaluRaw, 'bulan lalu');
        $trendTransaksi = $this->calculateTrend($transaksiHariIniCount, $transaksiKemarinCount, 'kemarin');
        $trendProduk = $this->calculateTrend($produkTerjualHariIni, $produkTerjualKemarin, 'kemarin');

        // Top Selling Products query
        $produkTerlaris = Menu::withSum(['detailPesanan as total_terjual' => function ($q) {
            $q->whereHas('pesanan', function ($pq) {
                $pq->validRevenue();
            });
        }], 'jumlah')
            ->orderByDesc('total_terjual')
            ->take(4)
            ->get();

        $stats = [
            'totalPenjualan' => 'Rp'.number_format($totalPenjualanRaw, 0, ',', '.'),
            'totalPenjualanRaw' => $totalPenjualanRaw,
            'trendPenjualan' => $trendBulanIni['text'],

            'totalPengeluaran' => 'Rp'.number_format($totalPengeluaranRaw, 0, ',', '.'),
            'totalPengeluaranRaw' => $totalPengeluaranRaw,

            'labaBersih' => 'Rp'.number_format($labaBersihRaw, 0, ',', '.'),
            'labaBersihRaw' => $labaBersihRaw,
            'marginLaba' => $marginLaba,

            'totalPesanan' => (string) $totalTransaksiCount,
            'totalPesananRaw' => $totalTransaksiCount,
            'trendPesanan' => $trendTransaksi['text'],

            'pendapatanHariIni' => 'Rp'.number_format($pendapatanHariIniRaw, 0, ',', '.'),
            'pendapatanHariIniRaw' => $pendapatanHariIniRaw,
            'trendPendapatan' => $trendHariIni['text'],
            'trendPendapatanHariIni' => $trendHariIni,

            'pendapatanMingguIni' => 'Rp'.number_format($pendapatanMingguIniRaw, 0, ',', '.'),
            'pendapatanMingguIniRaw' => $pendapatanMingguIniRaw,
            'trendPendapatanMingguIni' => $trendMingguIni,

            'pendapatanBulanIni' => 'Rp'.number_format($pendapatanBulanIniRaw, 0, ',', '.'),
            'pendapatanBulanIniRaw' => $pendapatanBulanIniRaw,
            'trendPendapatanBulanIni' => $trendBulanIni,

            'totalTransaksi' => (string) $totalTransaksiCount,
            'totalTransaksiRaw' => $totalTransaksiCount,
            'trendTransaksi' => $trendTransaksi,

            'produkTerjual' => (string) $produkTerjualCount,
            'produkTerjualRaw' => $produkTerjualCount,
            'trendProduk' => $trendProduk['text'],
        ];

        // 2. Build Dynamic Chart Data (Revenue & Transaction Count)
        $chartData = [
            7 => $this->getDailyChartData(7),
            14 => $this->getDailyChartData(14),
            30 => $this->getDailyChartData(30),
            90 => $this->getCurrentMonthChartData(),
            'month' => $this->getCurrentMonthChartData(),
            'year' => $this->getMonthlyChartData((int) Carbon::now()->year),
        ];

        // 3. Query Transactions with Filtering
        $query = Pesanan::with(['user', 'pembayaran', 'detailPesanan.menu']);

        // Search Filter (ID pesanan, Order Number, or Customer Name)
        $search = $request->query('search');
        if ($request->filled('search')) {
            $orderIdSearch = $search;
            if (preg_match('/-([0-9]+)$/', $search, $matches)) {
                $orderIdSearch = intval($matches[1]);
            }

            $query->where(function ($q) use ($search, $orderIdSearch) {
                $q->where('id_pesanan', 'like', "%{$search}%")
                    ->orWhere('id_pesanan', $orderIdSearch)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Period & Date Range Filter
        $periode = $request->query('periode', 'semua');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($periode === 'hari_ini' || $periode === 'today') {
            $query->whereDate('tanggal_pesan', $today);
        } elseif ($periode === 'minggu_ini' || $periode === 'this_week') {
            $query->whereBetween('tanggal_pesan', [$startOfWeek, $endOfWeek]);
        } elseif ($periode === 'bulan_ini' || $periode === 'this_month') {
            $query->whereBetween('tanggal_pesan', [$startOfMonth, $endOfMonth]);
        } elseif ($startDate && $endDate) {
            $query->whereBetween('tanggal_pesan', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->whereDate('tanggal_pesan', '>=', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->whereDate('tanggal_pesan', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // Payment Method Filter
        $metode = $request->query('metode', 'semua');
        if ($request->filled('metode') && $metode !== 'semua') {
            $metodeLower = strtolower($metode);
            if ($metodeLower === 'transfer_bank' || $metodeLower === 'transfer') {
                $query->where(function ($mq) {
                    $mq->whereRaw('LOWER(metode_pembayaran) LIKE ?', ['%transfer%'])
                        ->orWhereHas('pembayaran', function ($pq) {
                            $pq->whereRaw('LOWER(metode) LIKE ?', ['%transfer%']);
                        });
                });
            } else {
                $query->where(function ($mq) use ($metodeLower) {
                    $mq->whereRaw('LOWER(metode_pembayaran) LIKE ?', ["%{$metodeLower}%"])
                        ->orWhereHas('pembayaran', function ($pq) use ($metodeLower) {
                            $pq->whereRaw('LOWER(metode) LIKE ?', ["%{$metodeLower}%"]);
                        });
                });
            }
        }

        // Payment Status Filter
        $statusPembayaran = $request->query('status_pembayaran', 'semua');
        if ($request->filled('status_pembayaran') && $statusPembayaran !== 'semua') {
            $query->whereHas('pembayaran', function ($pq) use ($statusPembayaran) {
                $pq->where('status', $statusPembayaran);
            });
        }

        // Order Status Filter
        $statusPesanan = $request->query('status_pesanan', 'semua');
        if ($request->filled('status_pesanan') && $statusPesanan !== 'semua') {
            $query->where('status_pesanan', $statusPesanan);
        }

        // Filtered summary before pagination
        $filteredOrders = (clone $query)->get();
        $filteredSummary = [
            'totalCount' => $filteredOrders->count(),
            'validRevenue' => (int) $filteredOrders->filter(function ($order) {
                return $order->status_pesanan !== 'dibatalkan'
                    && $order->pembayaran
                    && $order->pembayaran->status === 'berhasil';
            })->sum('total_harga'),
            'totalItems' => (int) $filteredOrders->sum(function ($order) {
                return $order->detailPesanan->sum('jumlah');
            }),
        ];

        // Paginate latest transactions
        $transaksiTerbaru = $query->orderByDesc('tanggal_pesan')->paginate(10)->withQueryString();

        // 4. Quick Recent Orders (5 latest for widget compatibility)
        $latestFive = Pesanan::with(['user', 'pembayaran', 'detailPesanan.menu'])
            ->orderByDesc('tanggal_pesan')
            ->take(5)
            ->get();

        $pesananTerbaru = [];
        foreach ($latestFive as $order) {
            $statusType = match ($order->status_pesanan) {
                'selesai' => 'completed',
                'diproses', 'sedang_dibuat', 'siap_diambil' => 'processing',
                'dibatalkan' => 'pending',
                default => 'pending',
            };

            $statusText = match ($order->status_pesanan) {
                'selesai' => 'COMPLETED',
                'sedang_dibuat' => 'MAKING',
                'siap_diambil' => 'READY',
                'diproses' => 'PROCESSING',
                'dibatalkan' => 'CANCELLED',
                default => strtoupper($order->status_pesanan),
            };

            $pesananTerbaru[] = [
                'no' => '#'.$order->id_pesanan,
                'kode' => $order->order_number,
                'waktu' => $order->tanggal_pesan->format('H:i A').' • '.($order->user?->nama ?? 'Guest'),
                'status' => $statusText,
                'statusType' => $statusType,
                'harga' => 'Rp'.number_format($order->total_harga, 0, ',', '.'),
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'transaksiTerbaru',
            'pesananTerbaru',
            'filteredSummary',
            'produkTerlaris',
            'search',
            'periode',
            'startDate',
            'endDate',
            'metode',
            'statusPembayaran',
            'statusPesanan'
        ));
    }

    /**
     * Generate daily chart data for a specified number of days.
     */
    private function getDailyChartData(int $days): array
    {
        $startDate = Carbon::today()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        $orders = Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->get(['id_pesanan', 'tanggal_pesan', 'total_harga']);

        $grouped = $orders->groupBy(fn ($order) => $order->tanggal_pesan->format('Y-m-d'));

        $labels = [];
        $revenue = [];
        $transactions = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->format('Y-m-d');
            $dayOrders = $grouped->get($key, collect());

            $labels[] = $date->locale('id')->translatedFormat('d M');
            $revenue[] = (int) $dayOrders->sum('total_harga');
            $transactions[] = $dayOrders->count();
        }

        return [
            'labels' => $labels,
            'data' => $revenue,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'subtitle' => "Tren pendapatan {$days} hari terakhir",
        ];
    }

    /**
     * Generate daily chart data for the current month.
     */
    private function getCurrentMonthChartData(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $orders = Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startOfMonth, $endOfMonth])
            ->get(['id_pesanan', 'tanggal_pesan', 'total_harga']);

        $grouped = $orders->groupBy(fn ($order) => $order->tanggal_pesan->format('Y-m-d'));

        $labels = [];
        $revenue = [];
        $transactions = [];

        $daysInMonth = Carbon::now()->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::now()->startOfMonth()->addDays($day - 1);
            $key = $date->format('Y-m-d');
            $dayOrders = $grouped->get($key, collect());

            $labels[] = (string) $day;
            $revenue[] = (int) $dayOrders->sum('total_harga');
            $transactions[] = $dayOrders->count();
        }

        return [
            'labels' => $labels,
            'data' => $revenue,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'subtitle' => 'Tren pendapatan bulan '.Carbon::now()->locale('id')->translatedFormat('F Y'),
        ];
    }

    /**
     * Generate monthly chart data for a specified year.
     */
    private function getMonthlyChartData(int $year): array
    {
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $orders = Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startOfYear, $endOfYear])
            ->get(['id_pesanan', 'tanggal_pesan', 'total_harga']);

        $grouped = $orders->groupBy(fn ($order) => (int) $order->tanggal_pesan->format('n'));

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $labels = [];
        $revenue = [];
        $transactions = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthOrders = $grouped->get($m, collect());
            $labels[] = $monthNames[$m - 1];
            $revenue[] = (int) $monthOrders->sum('total_harga');
            $transactions[] = $monthOrders->count();
        }

        return [
            'labels' => $labels,
            'data' => $revenue,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'subtitle' => "Tren pendapatan bulanan tahun {$year}",
        ];
    }

    /**
     * Calculate trend indicator dynamically.
     */
    private function calculateTrend(float|int $current, float|int $previous, string $periodLabel): array
    {
        if ($previous > 0) {
            $percent = (($current - $previous) / $previous) * 100;
            $isPositive = $percent >= 0;
            $formatted = ($isPositive ? '+' : '').number_format($percent, 1, ',', '.')."% dari {$periodLabel}";
        } else {
            if ($current > 0) {
                $isPositive = true;
                $formatted = "+100% dari {$periodLabel}";
            } else {
                $isPositive = true;
                $formatted = "0% dari {$periodLabel}";
            }
        }

        return [
            'text' => $formatted,
            'isPositive' => $isPositive,
        ];
    }
}
