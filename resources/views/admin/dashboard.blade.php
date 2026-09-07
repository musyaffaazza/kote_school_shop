@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    {{-- FILTER ROW (SEARCH & DATE SELECTOR) --}}
    <div class="relative z-30 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white/40 p-2 rounded-2xl border border-[#ede6df]/45 shadow-sm backdrop-blur-sm">
        {{-- Search Input --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex w-full sm:max-w-sm items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search data..." class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl pl-9 pr-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all" />
            </div>
            <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap">
                Cari
            </button>
        </form>
        {{-- Date dropdown filter --}}
        <div class="relative w-full sm:w-auto" id="date-dropdown-wrapper">
            <button id="btn-date-dropdown" class="flex items-center justify-between gap-3 w-full sm:w-auto bg-[#fdfbf9] border border-[#e8ded5] text-xs font-bold rounded-xl px-4 py-2.5 text-[#21140b] focus:outline-none hover:bg-white transition-all cursor-pointer">
                <span id="date-dropdown-label">7 Hari Terakhir</span>
                <svg class="h-3.5 w-3.5 text-[#8f7664] transition-transform" id="date-dropdown-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {{-- Dropdown Menu --}}
            <div id="date-dropdown-menu" class="absolute right-0 mt-2 w-48 rounded-xl bg-white border border-[#ede6df] shadow-lg z-10 py-1 hidden">
                <button data-range="7" class="date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#21140b] hover:bg-[#faf5f0] transition-colors cursor-pointer">7 Hari Terakhir</button>
                <button data-range="14" class="date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#8f7664] hover:bg-[#faf5f0] transition-colors cursor-pointer">14 Hari Terakhir</button>
                <button data-range="30" class="date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#8f7664] hover:bg-[#faf5f0] transition-colors cursor-pointer">30 Hari Terakhir</button>
                <button data-range="90" class="date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#8f7664] hover:bg-[#faf5f0] transition-colors cursor-pointer">Bulan Ini</button>
            </div>
        </div>
    </div>

    {{-- STAT CARDS (TOP ROW) --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Penjualan --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-[#8f7664] uppercase tracking-wider">Total Penjualan</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['totalPenjualan'] }}</p>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                        <span>↗ {{ $stats['trendPenjualan'] }}</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/60 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Pesanan --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-[#8f7664] uppercase tracking-wider">Total Pesanan</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['totalPesanan'] }}</p>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                        <span>↗ {{ $stats['trendPesanan'] }}</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/60 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-[#8f7664] uppercase tracking-wider">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['pendapatanHariIni'] }}</p>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                        <span>↗ {{ $stats['trendPendapatan'] }}</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/60 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Produk Terjual --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-[#8f7664] uppercase tracking-wider">Produk Terjual</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['produkTerjual'] }}</p>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                        <span>↗ {{ $stats['trendProduk'] }}</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/60 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 9H6a1 1 0 00-1 1v5a5 5 0 005 5h3a5 5 0 005-5v-1h.5a3 3 0 000-6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v2M12 3v2M16 3v2" opacity="0.4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- SECONDARY OPERATIONAL & FINANCIAL STATS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        {{-- Total Pengeluaran --}}
        <div class="rounded-2xl bg-white p-4 border border-[#ede6df]/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#8f7664] uppercase tracking-wider">Total Pengeluaran</p>
                <p class="text-lg font-extrabold text-red-600 mt-0.5">{{ $stats['totalPengeluaran'] }}</p>
            </div>
            <a href="{{ route('admin.laporan-keuangan.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>

        {{-- Laba Bersih --}}
        <div class="rounded-2xl bg-white p-4 border border-[#ede6df]/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#8f7664] uppercase tracking-wider">Laba Bersih (Estimasi)</p>
                <p class="text-lg font-extrabold {{ $stats['labaBersihRaw'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-0.5">{{ $stats['labaBersih'] }}</p>
            </div>
            <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $stats['labaBersihRaw'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>

    {{-- MIDDLE ROW: GRAFIK PENJUALAN + PESANAN TERBARU --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Grafik Penjualan (Span 2) --}}
        <div class="lg:col-span-2 rounded-2xl bg-white p-6 border border-[#ede6df]/60 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-[#21140b]">Grafik Penjualan</h3>
                    <p id="chart-subtitle" class="text-xs text-[#8f7664] font-semibold mt-0.5">Tren pendapatan 7 hari terakhir</p>
                </div>
                {{-- Toggle Pill (Line vs Bar) --}}
                <div class="inline-flex rounded-xl bg-[#faf5f0] p-1 border border-[#e8dfd5]">
                    <button id="btn-chart-line" class="rounded-lg px-4 py-1.5 text-xs font-bold bg-[#3d2a1f] text-white shadow-sm cursor-pointer transition-all">Line</button>
                    <button id="btn-chart-bar" class="rounded-lg px-4 py-1.5 text-xs font-bold text-[#8f7664] hover:text-[#21140b] cursor-pointer transition-all">Bar</button>
                </div>
            </div>

            {{-- Chart Container --}}
            <div class="relative h-80 sm:h-96 w-full mt-6">
                <canvas id="salesChart" class="w-full h-full"></canvas>
            </div>
        </div>

        {{-- Pesanan Terbaru (Span 1) --}}
        <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/60 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-extrabold text-[#21140b]">Pesanan Terbaru</h3>
                    <a href="{{ route('admin.pembayaran.index') }}" class="text-xs font-bold text-[#8b5a2b] hover:text-[#3d2a1f] transition-colors">Lihat Semua</a>
                </div>

                <div class="space-y-4">
                    @forelse($pesananTerbaru as $order)
                    <div class="flex items-center justify-between gap-3 border-b border-[#faf7f2] pb-3 last:border-b-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            {{-- Index Badge --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#fef2e8] text-[11px] font-extrabold text-[#8b5a2b]">
                                {{ $order['no'] }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-extrabold text-[#21140b] truncate">{{ $order['kode'] }}</p>
                                <p class="text-[10px] font-medium text-[#8f7664] mt-0.5">{{ $order['waktu'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $statusBadgeClass = match($order['statusType']) {
                                    'completed' => 'bg-[#ecfdf5] text-emerald-700 border-emerald-100',
                                    'processing' => 'bg-[#eff6ff] text-blue-700 border-blue-100',
                                    'pending' => 'bg-[#fffbeb] text-amber-700 border-amber-100',
                                    default => 'bg-[#fafaf9] text-stone-700 border-stone-200'
                                };
                            @endphp
                            <span class="rounded-lg border px-2 py-1 text-[9px] font-extrabold tracking-wide {{ $statusBadgeClass }}">
                                {{ $order['status'] }}
                            </span>
                            <span class="text-xs font-extrabold text-[#21140b]">{{ $order['harga'] }}</span>
                        </div>
                    </div>
                    @empty
                        <p class="text-xs text-[#8f7664] font-semibold text-center py-6">Belum ada pesanan terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW: PRODUK TERLARIS --}}
    <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/60 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-extrabold text-[#21140b]">Produk Terlaris</h3>
                <p class="text-xs text-[#8f7664] font-semibold mt-0.5">Berdasarkan pesanan yang telah terverifikasi</p>
            </div>
            <a href="{{ route('admin.menu.index') }}" class="text-xs font-bold text-[#8b5a2b] hover:text-[#3d2a1f] transition-colors">Semua Menu</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @forelse($produkTerlaris as $item)
                <div class="flex items-center justify-between p-3 rounded-xl bg-[#faf7f2]/60 hover:bg-[#faf7f2] transition-colors">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->gambar }}" alt="{{ $item->nama_menu }}" class="h-10 w-10 rounded-lg object-cover bg-[#fbf1e8]" />
                        <div>
                            <p class="text-xs font-extrabold text-[#21140b]">{{ $item->nama_menu }}</p>
                            <p class="text-[11px] font-semibold text-[#8f7664]">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-extrabold text-emerald-700">
                            {{ (int) ($item->total_terjual ?? 0) }} Terjual
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-[#8f7664] font-semibold text-center py-6 col-span-full">Belum ada data penjualan produk.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Initialize Chart.js & Interactivity with Database Data --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let salesChart = null;
        let currentType = 'line';

        // Real dynamic datasets from Controller database queries
        const chartData = @json($chartData);

        function buildChart(type, range) {
            const rangeData = chartData[range] || chartData[7];

            if (salesChart) {
                salesChart.destroy();
            }

            // Update subtitle text
            document.getElementById('chart-subtitle').textContent = rangeData.subtitle;

            const gradient = ctx.createLinearGradient(0, 0, 0, 240);
            gradient.addColorStop(0, 'rgba(139, 90, 43, 0.18)');
            gradient.addColorStop(1, 'rgba(139, 90, 43, 0.00)');

            const dataset = type === 'line' ? {
                label: 'Pendapatan',
                data: rangeData.data,
                borderColor: '#8b5a2b',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5a2b',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1.5,
                pointRadius: 4.5,
                pointHoverRadius: 6
            } : {
                label: 'Pendapatan',
                data: rangeData.data,
                backgroundColor: function(context) {
                    const index = context.dataIndex;
                    const max = Math.max(...rangeData.data);
                    return (max > 0 && rangeData.data[index] === max) ? '#21140b' : '#e8ded5';
                },
                borderRadius: 6,
                borderSkipped: false,
                barPercentage: 0.55
            };

            salesChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: rangeData.labels,
                    datasets: [dataset]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#3d2a1f',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 12, weight: 'bold' },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        y: {
                            border: { dash: [5, 5] },
                            grid: { color: '#f0ebe5' },
                            beginAtZero: true,
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: 'bold' },
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    }
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initial render
        let currentRange = 7;
        buildChart('line', currentRange);

        // --- Chart Type Toggle ---
        const btnLine = document.getElementById('btn-chart-line');
        const btnBar = document.getElementById('btn-chart-bar');
        const activeClass = 'rounded-lg px-4 py-1.5 text-xs font-bold bg-[#3d2a1f] text-white shadow-sm cursor-pointer transition-all';
        const inactiveClass = 'rounded-lg px-4 py-1.5 text-xs font-bold text-[#8f7664] hover:text-[#21140b] cursor-pointer transition-all';

        btnLine.addEventListener('click', function() {
            currentType = 'line';
            btnLine.className = activeClass;
            btnBar.className = inactiveClass;
            buildChart('line', currentRange);
        });

        btnBar.addEventListener('click', function() {
            currentType = 'bar';
            btnBar.className = activeClass;
            btnLine.className = inactiveClass;
            buildChart('bar', currentRange);
        });

        // --- Date Dropdown ---
        const ddBtn = document.getElementById('btn-date-dropdown');
        const ddMenu = document.getElementById('date-dropdown-menu');
        const ddLabel = document.getElementById('date-dropdown-label');
        const ddArrow = document.getElementById('date-dropdown-arrow');
        const ddOptions = document.querySelectorAll('.date-option');

        ddBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = ddMenu.classList.contains('hidden');
            ddMenu.classList.toggle('hidden');
            ddArrow.style.transform = isHidden ? 'rotate(180deg)' : '';
        });

        ddOptions.forEach(function(opt) {
            opt.addEventListener('click', function() {
                const range = parseInt(this.dataset.range);
                currentRange = range;
                ddLabel.textContent = this.textContent.trim();
                ddMenu.classList.add('hidden');
                ddArrow.style.transform = '';

                // Reset active state on options
                ddOptions.forEach(function(o) {
                    o.className = 'date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#8f7664] hover:bg-[#faf5f0] transition-colors cursor-pointer';
                });
                opt.className = 'date-option w-full text-left px-4 py-2.5 text-xs font-bold text-[#21140b] hover:bg-[#faf5f0] transition-colors cursor-pointer';

                buildChart(currentType, currentRange);
            });
        });

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#date-dropdown-wrapper')) {
                ddMenu.classList.add('hidden');
                ddArrow.style.transform = '';
            }
        });
    });
</script>
@endsection
