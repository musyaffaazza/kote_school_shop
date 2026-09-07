<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdminLaporanKeuanganController extends Controller
{
    /**
     * Display the financial report dashboard.
     */
    public function index(Request $request): View
    {
        $data = $this->getFinancialReportData($request);
        $data['routePrefix'] = $this->resolveRoutePrefix($request);

        return view('admin.laporan-keuangan.index', $data);
    }

    /**
     * Export financial report to downloadable PDF.
     */
    public function exportPdf(Request $request): Response
    {
        $data = $this->getFinancialReportData($request, true);
        $data['routePrefix'] = $this->resolveRoutePrefix($request);

        $periodeLabel = $data['periodeLabel'];
        $cleanPeriode = preg_replace('/[^A-Za-z0-9_]/', '_', $periodeLabel);
        $filename = 'Laporan_Keuangan_KoteShop_'.$cleanPeriode.'_'.date('Ymd_His').'.pdf';

        $pdf = Pdf::loadView('admin.laporan-keuangan.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);

        return $pdf->download($filename);
    }

    /**
     * Export financial report to Excel (.xls).
     */
    public function exportExcel(Request $request): Response
    {
        $data = $this->getFinancialReportData($request, true);
        $data['routePrefix'] = $this->resolveRoutePrefix($request);

        $periodeLabel = $data['periodeLabel'];
        $cleanPeriode = preg_replace('/[^A-Za-z0-9_]/', '_', $periodeLabel);
        $filename = 'Laporan_Keuangan_KoteShop_'.$cleanPeriode.'_'.date('Ymd_His').'.xls';

        return response()
            ->view('admin.laporan-keuangan.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    /**
     * Store a newly created expense in storage.
     */
    public function storePengeluaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'nullable|string|max:50',
        ]);

        $validated['id_user'] = auth()->id();

        Pengeluaran::create($validated);

        return redirect()->back()->with('success', 'Pengeluaran baru berhasil dicatat!');
    }

    /**
     * Update the specified expense in storage.
     */
    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'nullable|string|max:50',
        ]);

        $pengeluaran->update($validated);

        return redirect()->back()->with('success', 'Catatan pengeluaran berhasil diperbarui!');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroyPengeluaran(Pengeluaran $pengeluaran): RedirectResponse
    {
        $pengeluaran->delete();

        return redirect()->back()->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }

    /**
     * Helper to compute all financial report statistics, charts, and datasets.
     */
    private function getFinancialReportData(Request $request, bool $fetchAll = false): array
    {
        $periode = $request->query('periode', 'bulan_ini');
        $customStart = $request->query('start_date');
        $customEnd = $request->query('end_date');

        $now = Carbon::now();

        // Resolve Date Range based on Period Filter
        if ($periode === 'kustom' && $customStart && $customEnd) {
            $startDate = Carbon::parse($customStart)->startOfDay();
            $endDate = Carbon::parse($customEnd)->endOfDay();
            $periode = 'kustom';
            $periodeLabel = $startDate->locale('id')->isoFormat('D MMMM Y').' - '.$endDate->locale('id')->isoFormat('D MMMM Y');
        } elseif ($periode === 'kustom') {
            $startDate = $customStart ? Carbon::parse($customStart)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $customEnd ? Carbon::parse($customEnd)->endOfDay() : Carbon::now()->endOfMonth();
            $periode = 'kustom';
            $periodeLabel = $startDate->locale('id')->isoFormat('D MMMM Y').' - '.$endDate->locale('id')->isoFormat('D MMMM Y');
        } else {
            switch ($periode) {
                case 'hari_ini':
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $periodeLabel = 'Hari Ini ('.$startDate->locale('id')->isoFormat('D MMMM Y').')';
                    break;
                case 'minggu_ini':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    $periodeLabel = 'Minggu Ini ('.$startDate->format('d M').' - '.$endDate->format('d M Y').')';
                    break;
                case 'bulan_lalu':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    $periodeLabel = 'Bulan Lalu ('.$startDate->locale('id')->isoFormat('MMMM Y').')';
                    break;
                case 'tahun_ini':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    $periodeLabel = 'Tahun Ini ('.$startDate->format('Y').')';
                    break;
                case 'semua':
                    $startDate = Carbon::createFromDate(2020, 1, 1)->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    $periodeLabel = 'Semua Riwayat';
                    break;
                case 'bulan_ini':
                default:
                    $periode = 'bulan_ini';
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $periodeLabel = 'Bulan Ini ('.$startDate->locale('id')->isoFormat('MMMM Y').')';
                    break;
            }
        }

        // Previous Period for Trend Calculations
        $durationInDays = max(1, $startDate->diffInDays($endDate) + 1);
        $prevStartDate = (clone $startDate)->subDays($durationInDays);
        $prevEndDate = (clone $startDate)->subSecond();

        // 1. Query Current Period Revenue & Expenses
        $currentRevenueRaw = (int) Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->sum('total_harga');

        $currentExpenseRaw = (int) Pengeluaran::whereBetween('tanggal', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])->sum('jumlah');

        $currentTransactionsCount = Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->count();

        // 2. Query Previous Period for Trends
        $prevRevenueRaw = (int) Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$prevStartDate, $prevEndDate])
            ->sum('total_harga');

        $prevExpenseRaw = (int) Pengeluaran::whereBetween('tanggal', [
            $prevStartDate->format('Y-m-d'),
            $prevEndDate->format('Y-m-d'),
        ])->sum('jumlah');

        $currentNetProfitRaw = $currentRevenueRaw - $currentExpenseRaw;
        $prevNetProfitRaw = $prevRevenueRaw - $prevExpenseRaw;

        // Trend calculations
        $trendPendapatan = $this->calculateTrendPercentage($currentRevenueRaw, $prevRevenueRaw, 'periode sebelumnya');
        $trendPengeluaran = $this->calculateTrendPercentage($currentExpenseRaw, $prevExpenseRaw, 'periode sebelumnya');
        $trendLaba = $this->calculateTrendPercentage($currentNetProfitRaw, $prevNetProfitRaw, 'periode sebelumnya');

        $marginLaba = $currentRevenueRaw > 0 ? ($currentNetProfitRaw / $currentRevenueRaw) * 100 : 0;
        $aov = $currentTransactionsCount > 0 ? $currentRevenueRaw / $currentTransactionsCount : 0;

        $stats = [
            'totalPendapatan' => 'Rp '.number_format($currentRevenueRaw, 0, ',', '.'),
            'totalPendapatanRaw' => $currentRevenueRaw,
            'trendPendapatan' => $trendPendapatan,

            'totalPengeluaran' => 'Rp '.number_format($currentExpenseRaw, 0, ',', '.'),
            'totalPengeluaranRaw' => $currentExpenseRaw,
            'trendPengeluaran' => $trendPengeluaran,

            'labaBersih' => 'Rp '.number_format($currentNetProfitRaw, 0, ',', '.'),
            'labaBersihRaw' => $currentNetProfitRaw,
            'trendLaba' => $trendLaba,

            'totalTransaksi' => (string) $currentTransactionsCount,
            'marginLaba' => $marginLaba,
            'aov' => 'Rp '.number_format($aov, 0, ',', '.'),
            'aovRaw' => $aov,
        ];

        // 3. Category Expenses Breakdown (Pengeluaran per Kategori)
        $categoryBreakdown = Pengeluaran::whereBetween('tanggal', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as count')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $pengeluaranKategori = [];
        $paletteColors = [
            'Bahan Baku' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'hex' => '#10b981'],
            'Operasional' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'hex' => '#3b82f6'],
            'Peralatan' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'hex' => '#f59e0b'],
            'Listrik & Internet' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'hex' => '#8b5cf6'],
            'Lainnya' => ['bg' => 'bg-stone-500', 'text' => 'text-stone-700', 'border' => 'border-stone-200', 'hex' => '#78716c'],
        ];

        foreach ($categoryBreakdown as $row) {
            $catName = $row->kategori;
            $catTotal = (int) $row->total;
            $catPercent = $currentExpenseRaw > 0 ? ($catTotal / $currentExpenseRaw) * 100 : 0;
            $theme = $paletteColors[$catName] ?? ['bg' => 'bg-stone-500', 'text' => 'text-stone-700', 'border' => 'border-stone-200', 'hex' => '#78716c'];

            $pengeluaranKategori[] = [
                'nama' => $catName,
                'total' => $catTotal,
                'formattedTotal' => 'Rp '.number_format($catTotal, 0, ',', '.'),
                'persen' => round($catPercent, 1),
                'count' => $row->count,
                'color' => $theme,
            ];
        }

        // If no expenses in period, create empty placeholders from default categories
        if (empty($pengeluaranKategori)) {
            foreach (array_keys(Pengeluaran::KATEGORI_LIST) as $catName) {
                $theme = $paletteColors[$catName] ?? ['bg' => 'bg-stone-500', 'text' => 'text-stone-700', 'border' => 'border-stone-200', 'hex' => '#78716c'];
                $pengeluaranKategori[] = [
                    'nama' => $catName,
                    'total' => 0,
                    'formattedTotal' => 'Rp 0',
                    'persen' => 0,
                    'count' => 0,
                    'color' => $theme,
                ];
            }
        }

        // 4. Financial Chart Data (Pemasukan vs Pengeluaran Series)
        $chartData = $this->buildChartTimeSeries($startDate, $endDate, $periode);

        // 5. Riwayat Harian (Daily Cashflow Stream)
        $riwayatHarian = $this->buildDailyCashflowStream($startDate, $endDate, $fetchAll ? 100 : 8);

        // 6. Riwayat Transaksi Pembeli (exclude cancelled orders)
        $pesananQuery = Pesanan::with(['user', 'pembayaran', 'detailPesanan.menu'])
            ->where('status_pesanan', '!=', 'dibatalkan')
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->orderByDesc('tanggal_pesan');

        $allPesanan = (clone $pesananQuery)->get();
        $transaksiPembeli = $fetchAll ? $allPesanan : $pesananQuery->paginate(8)->withQueryString();

        // 7. All Pengeluaran for modal / export
        $allPengeluaran = Pengeluaran::whereBetween('tanggal', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])->orderByDesc('tanggal')->get();

        // 8. Generate Smart Recommendations (Rekomendasi Bisnis & Keuangan)
        $recommendations = $this->generateRecommendations($currentRevenueRaw, $currentExpenseRaw, $currentNetProfitRaw, $marginLaba, $pengeluaranKategori, $currentTransactionsCount);

        // 9. Executive Conclusion
        $conclusion = $this->generateConclusion($startDate, $endDate, $currentRevenueRaw, $currentExpenseRaw, $currentNetProfitRaw, $marginLaba);

        return compact(
            'stats',
            'chartData',
            'pengeluaranKategori',
            'riwayatHarian',
            'transaksiPembeli',
            'allPesanan',
            'allPengeluaran',
            'recommendations',
            'conclusion',
            'periode',
            'periodeLabel',
            'startDate',
            'endDate',
            'customStart',
            'customEnd'
        );
    }

    /**
     * Build chart time series comparing revenue and expenses from actual database records.
     */
    private function buildChartTimeSeries(Carbon $startDate, Carbon $endDate, string $periode): array
    {
        $daysCount = $startDate->diffInDays($endDate) + 1;

        // If spanning more than 60 days, group by month
        $groupByMonth = $daysCount > 60 || $periode === 'tahun_ini';

        $orders = Pesanan::validRevenue()
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->get(['tanggal_pesan', 'total_harga']);

        $expenses = Pengeluaran::whereBetween('tanggal', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])->get(['tanggal', 'jumlah']);

        $labels = [];
        $revenueSeries = [];
        $expenseSeries = [];
        $revenueDaily = [];
        $expenseDaily = [];

        $runningRevenue = 0;
        $runningExpense = 0;

        if ($groupByMonth) {
            $ordersGrouped = $orders->groupBy(fn ($o) => $o->tanggal_pesan->format('Y-m'));
            $expenseGrouped = $expenses->groupBy(fn ($e) => Carbon::parse($e->tanggal)->format('Y-m'));

            $cursor = (clone $startDate)->startOfMonth();
            $endMonth = (clone $endDate)->endOfMonth();

            while ($cursor->lte($endMonth)) {
                $key = $cursor->format('Y-m');
                $labels[] = $cursor->locale('id')->isoFormat('MMM Y');

                $dayRev = (int) $ordersGrouped->get($key, collect())->sum('total_harga');
                $dayExp = (int) $expenseGrouped->get($key, collect())->sum('jumlah');

                $runningRevenue += $dayRev;
                $runningExpense += $dayExp;

                $revenueDaily[] = $dayRev;
                $expenseDaily[] = $dayExp;
                $revenueSeries[] = $runningRevenue;
                $expenseSeries[] = $runningExpense;

                $cursor->addMonth();
            }
        } else {
            $ordersGrouped = $orders->groupBy(fn ($o) => $o->tanggal_pesan->format('Y-m-d'));
            $expenseGrouped = $expenses->groupBy(fn ($e) => Carbon::parse($e->tanggal)->format('Y-m-d'));

            $cursor = clone $startDate;
            while ($cursor->lte($endDate)) {
                $key = $cursor->format('Y-m-d');
                $labels[] = $cursor->locale('id')->isoFormat('D MMM');

                $dayRev = (int) $ordersGrouped->get($key, collect())->sum('total_harga');
                $dayExp = (int) $expenseGrouped->get($key, collect())->sum('jumlah');

                $runningRevenue += $dayRev;
                $runningExpense += $dayExp;

                $revenueDaily[] = $dayRev;
                $expenseDaily[] = $dayExp;
                $revenueSeries[] = $runningRevenue;
                $expenseSeries[] = $runningExpense;

                $cursor->addDay();
            }
        }

        return [
            'labels' => $labels,
            'pemasukan' => $revenueSeries,
            'pengeluaran' => $expenseSeries,
            'pemasukan_harian' => $revenueDaily,
            'pengeluaran_harian' => $expenseDaily,
            'maxVal' => max(array_merge([100000], $revenueSeries, $expenseSeries)),
        ];
    }

    /**
     * Build combined daily cashflow stream (Riwayat Harian).
     */
    private function buildDailyCashflowStream(Carbon $startDate, Carbon $endDate, int $limit = 8): Collection
    {
        $stream = collect();

        // 1. Group Orders by Day
        $orders = Pesanan::validRevenue()
            ->with(['detailPesanan.menu'])
            ->whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->orderByDesc('tanggal_pesan')
            ->get();

        $ordersByDate = $orders->groupBy(fn ($o) => $o->tanggal_pesan->format('Y-m-d'));

        foreach ($ordersByDate as $dateStr => $dayOrders) {
            $totalDayIncome = (int) $dayOrders->sum('total_harga');
            $txCount = $dayOrders->count();

            // Sample some items
            $topItems = $dayOrders->flatMap(fn ($o) => $o->detailPesanan->map(fn ($d) => $d->menu?->nama_menu))
                ->filter()
                ->unique()
                ->take(2)
                ->implode(' & ');

            $keterangan = "Penjualan {$txCount} Pesanan".($topItems ? " ({$topItems})" : '');

            $stream->push([
                'tanggal' => Carbon::parse($dateStr),
                'tanggalFormatted' => Carbon::parse($dateStr)->locale('id')->isoFormat('D MMM Y'),
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan',
                'keterangan' => $keterangan,
                'jumlah' => $totalDayIncome,
                'isPositive' => true,
                'badgeClass' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'amountFormatted' => '+ Rp '.number_format($totalDayIncome, 0, ',', '.'),
            ]);
        }

        // 2. Individual Expenses
        $expenses = Pengeluaran::whereBetween('tanggal', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])->orderByDesc('tanggal')->get();

        foreach ($expenses as $exp) {
            $stream->push([
                'tanggal' => Carbon::parse($exp->tanggal),
                'tanggalFormatted' => Carbon::parse($exp->tanggal)->locale('id')->isoFormat('D MMM Y'),
                'tipe' => 'pengeluaran',
                'kategori' => 'Pengeluaran ('.$exp->kategori.')',
                'keterangan' => $exp->keterangan,
                'jumlah' => (int) $exp->jumlah,
                'isPositive' => false,
                'badgeClass' => 'bg-red-50 text-red-700 border-red-200',
                'amountFormatted' => '- Rp '.number_format((float) $exp->jumlah, 0, ',', '.'),
            ]);
        }

        // Sort combined stream by date descending, then take limit
        return $stream->sortByDesc(fn ($item) => $item['tanggal']->timestamp)->values()->take($limit);
    }

    /**
     * Calculate trend indicator percentage.
     */
    private function calculateTrendPercentage(float|int $current, float|int $previous, string $label): array
    {
        if ($previous > 0) {
            $percent = (($current - $previous) / $previous) * 100;
            $isPositive = $percent >= 0;
            $formatted = ($isPositive ? '+' : '').number_format($percent, 1, ',', '.')."% dari {$label}";
        } else {
            $isPositive = $current >= 0;
            $formatted = $current > 0 ? "+100% dari {$label}" : "0% dari {$label}";
        }

        return [
            'text' => $formatted,
            'isPositive' => $isPositive,
        ];
    }

    /**
     * Generate smart recommendations based on financial health indicators.
     */
    private function generateRecommendations(int $revenue, int $expense, int $profit, float $margin, array $categories, int $txCount): array
    {
        $recs = [];

        // 1. Margin Health Recommendation
        if ($margin >= 45) {
            $recs[] = [
                'type' => 'success',
                'title' => 'Margin Keuntungan Sangat Sehat ('.number_format($margin, 1).'%)',
                'description' => 'Profitabilitas kedai kopi Anda sangat prima. Pertimbangkan untuk mengalokasikan sebagian laba bersih untuk anggaran ekspansi atau promosi menu kopi unggulan.',
                'action' => 'Pertahankan Formula Menu',
            ];
        } elseif ($margin >= 20) {
            $recs[] = [
                'type' => 'info',
                'title' => 'Margin Keuntungan Stabil ('.number_format($margin, 1).'%)',
                'description' => 'Toko menghasilkan keuntungan operasional yang stabil. Disarankan menjaga stabilitas harga beli biji kopi dan kemasan takeaway agar margin tidak tergerus.',
                'action' => 'Pantau COGS Bahan Baku',
            ];
        } else {
            $recs[] = [
                'type' => 'warning',
                'title' => 'Perhatian: Margin Keuntungan Rendah ('.number_format($margin, 1).'%)',
                'description' => 'Pengeluaran operasional mendekati total pendapatan. Lakukan audit pengeluaran bahan baku atau tinjau kembali strategi harga paket menu.',
                'action' => 'Audit Pengeluaran Segera',
            ];
        }

        // 2. Highest Expense Category Recommendation
        if (! empty($categories) && $categories[0]['total'] > 0) {
            $topCat = $categories[0];
            if ($topCat['nama'] === 'Bahan Baku' && $topCat['persen'] > 50) {
                $recs[] = [
                    'type' => 'info',
                    'title' => 'Efisiensi Pembelian Bahan Baku ('.$topCat['persen'].'%)',
                    'description' => 'Bahan baku mengambil porsi terbesar. Anda dapat bernegosiasi diskon kuantiti (bulk buying) dengan supplier biji kopi dan susu untuk menekan biaya modal.',
                    'action' => 'Negosiasi Supplier',
                ];
            } elseif ($topCat['nama'] === 'Operasional' && $topCat['persen'] > 30) {
                $recs[] = [
                    'type' => 'warning',
                    'title' => 'Optimalisasi Beban Operasional ('.$topCat['persen'].'%)',
                    'description' => 'Biaya operasional cukup tinggi. Tinjau penggunaan cup takeaway, sedotan, dan pengeluaran harian lainnya agar lebih efisien.',
                    'action' => 'Kelola Stok Kemasan',
                ];
            }
        }

        // 3. Basket Size & Order Volume
        if ($txCount > 0 && ($revenue / $txCount) < 25000) {
            $recs[] = [
                'type' => 'tip',
                'title' => 'Peluang Upselling & Paket Bundling',
                'description' => 'Rata-rata nilai per transaksi (AOV) masih di bawah Rp 25.000. Terapkan strategi bundling "Kopi + Pastry" atau add-on topping untuk mendongkrak rata-rata belanja.',
                'action' => 'Buat Promo Bundling',
            ];
        }

        return $recs;
    }

    /**
     * Generate text conclusion for the period.
     */
    private function generateConclusion(Carbon $startDate, Carbon $endDate, int $revenue, int $expense, int $profit, float $margin): array
    {
        $startStr = $startDate->locale('id')->isoFormat('D MMMM Y');
        $endStr = $endDate->locale('id')->isoFormat('D MMMM Y');

        if ($revenue === 0 && $expense === 0) {
            $message = "Berdasarkan data dari {$startStr} - {$endStr}, belum ada transaksi pemasukan maupun pengeluaran operasional yang tercatat.";
            $status = 'netral';
        } elseif ($profit > 0) {
            $message = "Berdasarkan data dari {$startStr} - {$endStr}, bisnis kedai kopi KOTE SCHOOL SHOP beroperasi secara produktif dengan margin keuntungan positif sebesar ".number_format($margin, 1, ',', '.').'%.';
            $status = 'positif';
        } else {
            $message = "Berdasarkan data dari {$startStr} - {$endStr}, total pengeluaran operasional periode ini melebihi pendapatan masuk. Diperlukan penyesuaian belanja.";
            $status = 'defisit';
        }

        return [
            'dateRange' => "{$startStr} - {$endStr}",
            'message' => $message,
            'status' => $status,
            'revenueFormatted' => 'Rp '.number_format($revenue, 0, ',', '.'),
            'revenueShort' => ($revenue >= 1000000) ? number_format($revenue / 1000000, 2, ',', '.').'M' : 'Rp '.number_format($revenue, 0, ',', '.'),
            'expenseFormatted' => 'Rp '.number_format($expense, 0, ',', '.'),
            'expenseShort' => ($expense >= 1000000) ? number_format($expense / 1000000, 2, ',', '.').'M' : 'Rp '.number_format($expense, 0, ',', '.'),
            'profitFormatted' => 'Rp '.number_format($profit, 0, ',', '.'),
            'profitShort' => ($profit >= 1000000) ? number_format($profit / 1000000, 2, ',', '.').'M' : 'Rp '.number_format($profit, 0, ',', '.'),
        ];
    }

    /**
     * Resolve whether the request is made under karyawan or admin route context.
     */
    private function resolveRoutePrefix(Request $request): string
    {
        if ($request->is('karyawan*') || $request->routeIs('karyawan.*') || trim((string) $request->route()?->getPrefix(), '/') === 'karyawan') {
            return 'karyawan';
        }

        return 'admin';
    }
}
