@extends('layouts.karyawan')

@section('page-title', 'Dashboard Karyawan')

@section('content')
<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    {{-- FILTER ROW --}}
    <div class="relative z-30 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white/40 p-2 rounded-2xl border border-[#ede6df]/40 shadow-sm backdrop-blur-sm">
        <form method="GET" action="{{ route('karyawan.dashboard') }}" class="flex flex-1 max-w-md w-full items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl pl-10 pr-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all" />
            </div>
            <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap">
                Cari
            </button>
        </form>
        <div class="flex gap-2.5 items-center justify-end w-full sm:w-auto shrink-0">
            {{-- Date selector dropdown --}}
            <div class="relative w-full sm:w-auto" id="karyawan-date-dropdown-wrapper">
                <button type="button" id="btn-karyawan-date" class="flex items-center justify-between gap-3 w-full sm:w-auto bg-[#fdfbf9] border border-[#e8ded5] text-xs font-bold rounded-xl px-4 py-2.5 text-[#21140b] hover:bg-white hover:border-[#3d2a1f]/30 transition-all cursor-pointer shadow-sm">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>
                            @if(request('date'))
                                @if(request('date') === date('Y-m-d'))
                                    Hari Ini ({{ \Carbon\Carbon::parse(request('date'))->locale('id')->translatedFormat('d M Y') }})
                                @elseif(request('date') === date('Y-m-d', strtotime('-1 day')))
                                    Kemarin ({{ \Carbon\Carbon::parse(request('date'))->locale('id')->translatedFormat('d M Y') }})
                                @else
                                    {{ \Carbon\Carbon::parse(request('date'))->locale('id')->translatedFormat('d M Y') }}
                                @endif
                            @else
                                Pilih Tanggal
                            @endif
                        </span>
                    </span>
                    <svg class="h-3.5 w-3.5 text-[#8f7664] transition-transform duration-200" id="karyawan-date-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Menu Popup --}}
                <div id="karyawan-date-menu" class="absolute right-0 mt-2 w-72 rounded-2xl bg-white border border-[#ede6df] shadow-2xl z-50 p-4 space-y-4 hidden">
                    {{-- Pilihan Cepat --}}
                    <div class="space-y-2">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#8f7664]">Pilihan Cepat:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('karyawan.dashboard', array_merge(request()->except('date'), ['date' => date('Y-m-d')])) }}"
                               class="flex items-center justify-center py-2 px-3 rounded-xl border text-xs font-bold transition-all {{ request('date') === date('Y-m-d') || !request('date') ? 'bg-[#3d2a1f] text-white border-[#3d2a1f]' : 'bg-[#fdfbf9] text-[#5b4f45] border-[#e8ded5] hover:bg-[#faf5f0]' }}">
                                Hari Ini
                            </a>
                            <a href="{{ route('karyawan.dashboard', array_merge(request()->except('date'), ['date' => date('Y-m-d', strtotime('-1 day'))])) }}"
                               class="flex items-center justify-center py-2 px-3 rounded-xl border text-xs font-bold transition-all {{ request('date') === date('Y-m-d', strtotime('-1 day')) ? 'bg-[#3d2a1f] text-white border-[#3d2a1f]' : 'bg-[#fdfbf9] text-[#5b4f45] border-[#e8ded5] hover:bg-[#faf5f0]' }}">
                                Kemarin
                            </a>
                        </div>
                    </div>

                    {{-- Form Tanggal Kustom --}}
                    <form method="GET" action="{{ route('karyawan.dashboard') }}" class="space-y-3 pt-3 border-t border-[#ede6df]/60">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div>
                            <label for="custom-date-input" class="text-[10px] font-extrabold uppercase tracking-wider text-[#8f7664] block mb-1.5">Pilih Tanggal Spesifik:</label>
                            <input type="date"
                                   id="custom-date-input"
                                   name="date"
                                   value="{{ request('date', date('Y-m-d')) }}"
                                   required
                                   class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-bold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all cursor-pointer" />
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span>Terapkan Tanggal</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Reset Date & Search Filter Button --}}
            @if(request('date') || request('search'))
            <a href="{{ route('karyawan.dashboard') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e8ded5] text-[#21140b] hover:bg-[#d5c6b8] transition-all cursor-pointer shadow-sm" title="Hapus Filter Tanggal">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            @endif

            {{-- Refresh icon --}}
            <a href="{{ route('karyawan.dashboard') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#21140b] text-white hover:bg-[#3d2a1f] transition-all cursor-pointer shadow-sm" title="Segarkan Data">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </a>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Card 1: Tren Penjualan Mingguan --}}
        <div class="rounded-2xl bg-white p-6 shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Tren Penjualan Mingguan</h3>
                    <p class="text-[11px] text-[#8f7664] font-semibold mt-0.5">{{ (request('date') ? \Carbon\Carbon::parse(request('date')) : \Carbon\Carbon::now())->locale('id')->translatedFormat('F Y') }}</p>
                </div>
                <span class="text-[#a2785d]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </span>
            </div>
            {{-- Chart Canvas --}}
            <div class="relative h-72 w-full mt-6">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>

        {{-- Card 2: Volume Pesanan Per Jam --}}
        <div class="rounded-2xl bg-white p-6 shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Volume Pesanan Per Jam</h3>
                    <p class="text-[11px] text-[#8f7664] font-semibold mt-0.5">{{ request('date') ? \Carbon\Carbon::parse(request('date'))->locale('id')->translatedFormat('d F Y') : 'Rata-rata Harian (08:00 - 20:00)' }}</p>
                </div>
                <span class="text-[#a2785d]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </span>
            </div>
            {{-- Chart Canvas --}}
            <div class="relative h-72 w-full mt-6">
                <canvas id="hourlyVolumeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW: PESANAN TERKINI + DISTRIBUSI PESANAN --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">
        {{-- PESANAN TERKINI TABLE --}}
        <div class="rounded-2xl bg-white p-6 shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-extrabold text-[#21140b]">Pesanan Terkini</h3>
                <a href="{{ route('karyawan.pesanan') }}" class="text-xs font-bold text-[#a2785d] hover:text-[#21140b] transition-colors">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[520px]">
                    <thead>
                        <tr class="border-b border-[#ede6df] bg-[#faf7f4]">
                            <th class="py-3 px-4 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider rounded-l-xl">No. Pesanan</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Menu</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Status</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f5f0eb]">
                        @forelse($pesananTerkini as $pesanan)
                        <tr class="group hover:bg-[#fdfaf7] transition-colors">
                            {{-- No. Pesanan --}}
                            <td class="py-4 px-4">
                                <p class="text-sm font-extrabold text-[#21140b]">{{ $pesanan['kode'] }}</p>
                                <p class="text-[11px] text-[#8f7664] mt-0.5">{{ $pesanan['waktu'] }}</p>
                            </td>
                            {{-- Menu --}}
                            <td class="py-4 px-4">
                                <p class="text-sm font-bold text-[#21140b]">{{ $pesanan['menu'] }}</p>
                                <p class="text-[11px] font-semibold text-[#a2785d] mt-0.5">
                                    {{ $pesanan['tipe'] }}{{ isset($pesanan['meja']) ? ' • Table ' . $pesanan['meja'] : '' }}
                                </p>
                            </td>
                            {{-- Status Badge --}}
                            <td class="py-4 px-4">
                                @php
                                    $statusClasses = match($pesanan['statusColor']) {
                                        'red' => 'bg-[#fdf2f2] text-[#9b2c2c] border-[#fbe3e3]',
                                        'yellow' => 'bg-[#faf3eb] text-[#8c5a3c] border-[#f3e7db]',
                                        'brown' => 'bg-[#fbf2eb] text-[#482a17] border-[#eeded3]',
                                        default => 'bg-[#f0fdf4] text-[#15803d] border-[#dcfce7]',
                                    };
                                @endphp
                                <span class="inline-block rounded-xl border px-3 py-1.5 text-[11px] font-bold {{ $statusClasses }}">
                                    {{ $pesanan['status'] }}
                                </span>
                            </td>
                            {{-- Aksi Button --}}
                            <td class="py-4 px-4">
                                @if($pesanan['isActionable'] ?? true)
                                    @php
                                        $aksiClasses = match($pesanan['aksiColor'] ?? 'dark') {
                                            'medium' => 'bg-[#8c6239] hover:bg-[#6f4e2c] text-white',
                                            'dark' => 'bg-[#21140b] hover:bg-[#3d2a1f] text-white',
                                            default => 'bg-[#21140b] hover:bg-[#3d2a1f] text-white',
                                        };
                                    @endphp
                                    <a href="{{ $pesanan['aksiLink'] ?? route('karyawan.pesanan') }}" class="rounded-xl px-4 py-2 text-[11px] font-bold transition-all active:scale-95 cursor-pointer {{ $aksiClasses }} inline-block text-center shadow-sm">
                                        {{ $pesanan['aksi'] }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-[11px] font-bold bg-[#faf7f5] text-[#a89584] border border-[#ede6df] cursor-not-allowed select-none opacity-80">
                                        {{ $pesanan['aksi'] ?? '-' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-xs font-semibold text-[#8f7664]">
                                Belum ada pesanan terkini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT COLUMN: DISTRIBUSI PESANAN --}}
        <div class="rounded-2xl bg-white p-6 shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-[#21140b] mb-6">Distribusi Pesanan</h3>
                
                {{-- Donut Chart Container --}}
                <div class="relative flex items-center justify-center h-48 w-full">
                    <canvas id="distributionChart" class="max-w-[170px] max-h-[170px]"></canvas>
                    {{-- Inner absolute label for total --}}
                    <div class="absolute flex flex-col items-center justify-center">
                        <span class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $distribution['total'] ?? 0 }}</span>
                        <span class="text-[10px] font-semibold text-[#8f7664] uppercase mt-0.5">Total Order</span>
                    </div>
                </div>
            </div>

            {{-- Legend List --}}
            <div class="space-y-2 mt-6">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#21140b]"></span>
                        <span class="font-bold text-[#8f7664]">Diproses</span>
                    </div>
                    <span class="font-extrabold text-[#21140b]">{{ $distribution['diproses'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#8c6239]"></span>
                        <span class="font-bold text-[#8f7664]">Sedang Dibuat</span>
                    </div>
                    <span class="font-extrabold text-[#21140b]">{{ $distribution['sedang_dibuat'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#e8ded5]"></span>
                        <span class="font-bold text-[#8f7664]">Siap / Selesai</span>
                    </div>
                    <span class="font-extrabold text-[#21140b]">{{ $distribution['siap_selesai'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Initialize Chart.js with Database Data --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const weeklyLabels = @json($weeklyLabels);
        const weeklyData = @json($weeklyData);
        const hourlyLabels = @json($hourlyLabels);
        const hourlyData = @json($hourlyData);
        const distData = [
            {{ $distribution['diproses'] ?? 0 }},
            {{ $distribution['sedang_dibuat'] ?? 0 }},
            {{ $distribution['siap_selesai'] ?? 0 }}
        ];

        const ctxWeekly = document.getElementById('weeklySalesChart');
        if (ctxWeekly) {
            new Chart(ctxWeekly.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        data: weeklyData,
                        backgroundColor: function(context) {
                            const max = Math.max(...weeklyData);
                            return (max > 0 && weeklyData[context.dataIndex] === max) ? '#21140b' : '#e8ded5';
                        },
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
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
                            border: { display: false },
                            grid: { color: '#f5f0eb' },
                            beginAtZero: true,
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: 'bold' },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // --- hourlyVolumeChart ---
        const ctxHourly = document.getElementById('hourlyVolumeChart');
        if (ctxHourly) {
            new Chart(ctxHourly.getContext('2d'), {
                type: 'line',
                data: {
                    labels: hourlyLabels,
                    datasets: [{
                        data: hourlyData,
                        borderColor: '#8c6239',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return Number(context.raw) + ' Order';
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
                            border: { display: false },
                            grid: { display: false },
                            beginAtZero: true,
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: 'bold' },
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // --- distributionChart ---
        const ctxDist = document.getElementById('distributionChart');
        if (ctxDist) {
            const totalDist = distData.reduce((a, b) => a + b, 0);
            new Chart(ctxDist.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Diproses', 'Sedang Dibuat', 'Siap / Selesai'],
                    datasets: [{
                        data: totalDist === 0 ? [1, 0, 0] : distData,
                        backgroundColor: totalDist === 0 ? ['#ede6df'] : ['#21140b', '#8c6239', '#e8ded5'],
                        borderWidth: 0,
                        cutout: '80%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: totalDist > 0 }
                    }
                }
            });
        }

        // --- Date Dropdown Toggle & Outside Click ---
        const btnDate = document.getElementById('btn-karyawan-date');
        const menuDate = document.getElementById('karyawan-date-menu');
        const arrowDate = document.getElementById('karyawan-date-arrow');

        if (btnDate && menuDate) {
            btnDate.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = menuDate.classList.contains('hidden');
                menuDate.classList.toggle('hidden');
                if (arrowDate) {
                    arrowDate.style.transform = isHidden ? 'rotate(180deg)' : '';
                }
            });

            // Prevent dropdown click from closing itself
            menuDate.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Close dropdown on click outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#karyawan-date-dropdown-wrapper')) {
                    menuDate.classList.add('hidden');
                    if (arrowDate) {
                        arrowDate.style.transform = '';
                    }
                }
            });
        }
    });
</script>
@endsection
