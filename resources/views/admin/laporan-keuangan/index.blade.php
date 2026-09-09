@extends($routePrefix === 'karyawan' ? 'layouts.karyawan' : 'layouts.admin')

@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    {{-- PAGE TITLE & SUBTITLE --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#21140b] tracking-tight">Laporan Keuangan</h1>
            <p class="mt-0.5 text-xs text-[#8f7664] font-medium">Ringkasan aktivitas keuangan dan performa bisnis.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openModal('modal-catat-pengeluaran')" class="inline-flex items-center gap-2 rounded-xl bg-[#2e2119] hover:bg-[#1a120c] px-4 py-2 text-xs font-bold text-white shadow-xs transition-all cursor-pointer hover:shadow active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Catat Pengeluaran
            </button>
            <button type="button" onclick="openModal('modal-kelola-pengeluaran')" class="inline-flex items-center gap-2 rounded-xl bg-white hover:bg-[#faf7f2] border border-[#ede6df] px-4 py-2 text-xs font-bold text-[#2e2119] shadow-xs transition-all cursor-pointer active:scale-95">
                <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Kelola Pengeluaran
            </button>
        </div>
    </div>

    {{-- 1. TOP SUMMARY METRIC CARDS (4 CARDS - CLEAN MINIMALIST DESIGN) --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card 1: Total Pendapatan --}}
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-[#8f7664]">Total Pendapatan</p>
                <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['totalPendapatan'] }}</p>
                <p class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                    <span>↗</span>
                    <span>{{ $stats['trendPendapatan']['text'] }}</span>
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#faf7f2] text-[#8f7664] border border-[#ede6df]/60 shrink-0">
                <svg class="h-6 w-6 text-[#a89584]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>

        {{-- Card 2: Total Pengeluaran --}}
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-[#8f7664]">Total Pengeluaran</p>
                <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['totalPengeluaran'] }}</p>
                <p class="text-[11px] font-semibold text-rose-500 flex items-center gap-1">
                    <span>↗</span>
                    <span>{{ $stats['trendPengeluaran']['text'] }}</span>
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#faf7f2] text-[#8f7664] border border-[#ede6df]/60 shrink-0">
                <svg class="h-6 w-6 text-[#d97768]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Laba Bersih (Dark Card) --}}
        <div class="rounded-2xl p-5 shadow-xs border flex items-center justify-between" style="background-color: #241812 !important; color: #ffffff !important; border-color: #3d2a1f !important;">
            <div class="space-y-1">
                <p class="text-xs font-bold text-[#b09a87] uppercase tracking-wider">Laba Bersih</p>
                <p class="text-2xl font-extrabold tracking-tight text-white">{{ $stats['labaBersih'] }}</p>
                <p class="text-[11px] font-semibold text-[#d5c6b8]">
                    Margin {{ number_format($stats['marginLaba'], 1) }}%
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white/70 border border-white/10 shrink-0">
                <svg class="h-6 w-6 text-[#d5c6b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>

        {{-- Card 4: Total Transaksi --}}
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-[#8f7664]">Total Transaksi</p>
                <p class="text-2xl font-extrabold text-[#21140b] tracking-tight">{{ $stats['totalTransaksi'] }} <span class="text-xs font-medium text-[#8f7664]">Transaksi</span></p>
                <p class="text-[11px] font-medium text-[#8f7664]">
                    Rata-rata: <strong class="text-[#21140b] font-bold">{{ $stats['aov'] }}</strong>
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#faf7f2] text-[#8f7664] border border-[#ede6df]/60 shrink-0">
                <svg class="h-6 w-6 text-[#a89584]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- 2. FILTER & EXPORT TOOLBAR --}}
    <div class="relative z-30 rounded-2xl bg-white p-4 border border-[#ede6df] shadow-sm">
        <form id="form-filter-laporan" method="GET" action="{{ route($routePrefix . '.laporan-keuangan.index') }}" class="flex flex-wrap items-center justify-between gap-3">
            {{-- Left Side: Filter Inputs --}}
            <div class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="periode" id="input-hidden-periode" value="{{ $periode }}">

                {{-- Custom Periode Dropdown --}}
                <div class="relative" id="periode-dropdown-wrapper">
                    <button type="button" id="btn-periode-dropdown" class="h-10 flex items-center justify-between gap-3 bg-[#fdfbf9] border border-[#e8ded5] hover:border-[#8b5a2b] text-xs font-bold rounded-xl px-4 text-[#21140b] focus:outline-none hover:bg-white transition-all cursor-pointer shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span id="periode-dropdown-label">
                                @switch($periode)
                                    @case('hari_ini') Hari Ini @break
                                    @case('minggu_ini') Minggu Ini @break
                                    @case('bulan_ini') Bulan Ini @break
                                    @case('tahun_ini') Tahun Ini @break
                                    @case('kustom') Kustom Rentang Tanggal @break
                                    @default Bulan Ini
                                @endswitch
                            </span>
                        </div>
                        <svg class="h-3.5 w-3.5 text-[#8f7664] transition-transform duration-200" id="periode-dropdown-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Custom Dropdown Popover Menu --}}
                    <div id="periode-dropdown-menu" class="absolute left-0 mt-2 w-52 rounded-2xl bg-white border border-[#ede6df] shadow-xl z-50 py-1.5 hidden">
                        @php
                            $periodOptions = [
                                'hari_ini' => 'Hari Ini',
                                'minggu_ini' => 'Minggu Ini',
                                'bulan_ini' => 'Bulan Ini',
                                'tahun_ini' => 'Tahun Ini',
                            ];
                        @endphp
                        @foreach($periodOptions as $val => $label)
                            <button type="button" data-value="{{ $val }}" data-label="{{ $label }}" class="periode-option w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-left transition-colors cursor-pointer {{ $periode === $val ? 'bg-[#faf5f0] text-[#8b5a2b]' : 'text-[#5a4d42] hover:bg-[#faf7f2] hover:text-[#21140b]' }}">
                                <span>{{ $label }}</span>
                                @if($periode === $val)
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="h-10 flex items-center bg-[#fdfbf9] hover:bg-white border border-[#e8ded5] hover:border-[#8b5a2b] focus-within:border-[#8b5a2b] focus-within:ring-2 focus-within:ring-[#8b5a2b]/15 rounded-xl px-3 transition-all shadow-2xs">
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="bg-transparent border-0 text-xs font-bold text-[#21140b] py-0 px-1 focus:ring-0 focus:outline-none cursor-pointer" />
                </div>

                {{-- Tanggal Selesai --}}
                <div class="h-10 flex items-center bg-[#fdfbf9] hover:bg-white border border-[#e8ded5] hover:border-[#8b5a2b] focus-within:border-[#8b5a2b] focus-within:ring-2 focus-within:ring-[#8b5a2b]/15 rounded-xl px-3 transition-all shadow-2xs">
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="bg-transparent border-0 text-xs font-bold text-[#21140b] py-0 px-1 focus:ring-0 focus:outline-none cursor-pointer" />
                </div>

                {{-- Terapkan Button --}}
                <button type="submit" class="h-10 bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs font-bold px-5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 flex items-center justify-center">
                    Terapkan
                </button>

                {{-- Reset Button --}}
                <a href="{{ route($routePrefix . '.laporan-keuangan.index') }}" class="h-10 bg-[#faf5f0] hover:bg-[#ede5dc] text-[#7b6558] hover:text-[#21140b] border border-[#e8ded5] text-xs font-bold px-4 rounded-xl transition-all cursor-pointer shadow-2xs active:scale-95 flex items-center justify-center">
                    Reset
                </a>
            </div>

            {{-- Right Side: Export Buttons (PDF & Excel) --}}
            <div class="flex items-center gap-2">
                <a href="{{ route($routePrefix . '.laporan-keuangan.export.pdf', request()->query()) }}" class="h-10 inline-flex items-center gap-1.5 bg-[#fef2f2] hover:bg-[#fee2e2] text-[#dc2626] border border-[#fca5a5]/40 text-xs font-bold px-4 rounded-xl transition-all shadow-2xs active:scale-95 cursor-pointer">
                    <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>PDF</span>
                </a>
                <a href="{{ route($routePrefix . '.laporan-keuangan.export.excel', request()->query()) }}" class="h-10 inline-flex items-center gap-1.5 bg-[#ecfdf5] hover:bg-[#d1fae5] text-[#059669] border border-[#6ee7b7]/40 text-xs font-bold px-4 rounded-xl transition-all shadow-2xs active:scale-95 cursor-pointer">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Excel</span>
                </a>
            </div>
        </form>
    </div>

    {{-- 3. GRAFIK KEUANGAN (FULL WIDTH TOP CARD) --}}
    <div class="rounded-2xl bg-white p-6 sm:p-7 border border-[#ede6df]/80 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h3 class="text-base sm:text-lg font-extrabold text-[#21140b] tracking-tight">Grafik Keuangan</h3>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#faf5f0] border border-[#e8ded5] px-2.5 py-0.5 text-[11px] font-bold text-[#8b5a2b]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#8b5a2b]"></span>
                        {{ $periodeLabel }}
                    </span>
                </div>
                <p class="text-xs text-[#8f7664] mt-1 font-medium" id="chart-sub-label">
                    Arus Kas Harian (Grafik turun ke 0 saat tidak ada aktivitas transaksi)
                </p>
            </div>

            {{-- Controls: View Mode (Harian vs Akumulasi) & Type (Area vs Bar) --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Mode Switcher: Harian vs Akumulasi --}}
                <div class="flex items-center gap-1 bg-[#faf5f0] p-1 rounded-xl border border-[#ede6df] shadow-2xs">
                    <button type="button" id="btn-chart-daily" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-[#21140b] text-white shadow-xs cursor-pointer">
                        Harian
                    </button>
                    <button type="button" id="btn-chart-cumulative" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer">
                        Akumulasi
                    </button>
                </div>

                {{-- Chart Type Switcher: Area Line vs Bar --}}
                <div class="flex items-center gap-1 bg-[#faf5f0] p-1 rounded-xl border border-[#ede6df] shadow-2xs">
                    <button type="button" id="btn-chart-type-line" class="p-1.5 text-xs font-bold rounded-lg transition-all bg-white text-[#21140b] shadow-2xs cursor-pointer" title="Grafik Garis Area">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 3v18h18" />
                        </svg>
                    </button>
                    <button type="button" id="btn-chart-type-bar" class="p-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer" title="Grafik Batang">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Quick Stats Bar right above the chart --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5 p-3.5 rounded-xl bg-[#faf7f2]/80 border border-[#ede6df]/60">
            <div class="flex items-center gap-2.5">
                <span class="h-3 w-3 rounded-full bg-[#10b981] ring-4 ring-[#10b981]/20 shrink-0"></span>
                <div>
                    <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Total Pemasukan</p>
                    <p class="text-sm font-extrabold text-[#21140b]">{{ $stats['totalPendapatan'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="h-3 w-3 rounded-full bg-[#ef4444] ring-4 ring-[#ef4444]/20 shrink-0"></span>
                <div>
                    <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Total Pengeluaran</p>
                    <p class="text-sm font-extrabold text-[#dc2626]">{{ $stats['totalPengeluaran'] }}</p>
                </div>
            </div>
            <div class="col-span-2 sm:col-span-1 flex items-center gap-2.5 pt-2 sm:pt-0 border-t sm:border-t-0 border-[#ede6df]/50">
                <span class="h-3 w-3 rounded-full bg-[#8b5a2b] ring-4 ring-[#8b5a2b]/20 shrink-0"></span>
                <div>
                    <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Laba Bersih Periode</p>
                    <p class="text-sm font-extrabold {{ $stats['labaBersihRaw'] >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">{{ $stats['labaBersih'] }}</p>
                </div>
            </div>
        </div>

        {{-- Chart Canvas Container --}}
        <div class="relative h-72 sm:h-84 w-full">
            <canvas id="financeChart" class="w-full h-full"></canvas>
        </div>

        {{-- Interactive Legend / Guide at bottom --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-[#f7f3ee] text-xs">
            <div class="flex items-center gap-6 font-semibold">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#10b981] ring-4 ring-[#10b981]/15"></span>
                    <span class="text-[#5a4d42]">Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#ef4444] ring-4 ring-[#ef4444]/15"></span>
                    <span class="text-[#5a4d42]">Pengeluaran</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-[#8f7664] font-medium">
                <svg class="h-3.5 w-3.5 text-[#a89584]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="chart-guide-text">Arahkan kursor pada grafik untuk melihat rincian tanggal & arus kas</span>
            </div>
        </div>
    </div>

    {{-- 4. PENGELUARAN PER KATEGORI (CLEAN VERTICAL LIST SESUAI MOCKUP, DITARUH DI BAWAH GRAFIK KEUANGAN) --}}
    <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/80 shadow-xs">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-extrabold text-[#21140b]">Pengeluaran per Kategori</h3>
            <span class="text-xs font-medium text-[#8f7664]">Total Biaya: <strong class="text-[#21140b] font-extrabold">{{ $stats['totalPengeluaran'] }}</strong></span>
        </div>

        {{-- Clean Category List --}}
        <div class="space-y-4">
            @forelse($pengeluaranKategori as $kat)
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-[#21140b]">{{ $kat['nama'] }}</span>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-[#21140b]">{{ $kat['formattedTotal'] }}</span>
                        <span class="text-[11px] font-semibold text-[#8f7664]">{{ $kat['persen'] }}%</span>
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="w-full bg-[#f0ebe5] h-2 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $kat['color']['bg'] }}" style="width: {{ max(3, min(100, $kat['persen'])) }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center py-6 text-xs text-[#8f7664] font-medium">Belum ada data pengeluaran untuk periode ini.</p>
            @endforelse
        </div>

        {{-- Center Button Sesuai Mockup --}}
        <div class="mt-6 pt-4 border-t border-[#f7f3ee] flex justify-center">
            <button type="button" onclick="openModal('modal-kelola-pengeluaran')" class="w-full sm:w-auto px-8 py-2.5 rounded-xl border border-[#ede6df] bg-[#faf7f2] hover:bg-[#f0ebe5] text-xs font-bold text-[#21140b] transition-all cursor-pointer shadow-2xs text-center">
                Lihat Detail Kategori
            </button>
        </div>
    </div>

    {{-- 5. RIWAYAT HARIAN (STACKED CARD) --}}
    <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-extrabold text-[#21140b]">Riwayat Harian</h3>
            <button type="button" onclick="openModal('modal-kelola-pengeluaran')" class="text-xs font-semibold text-[#8b5a2b] hover:text-[#21140b] transition-colors cursor-pointer">
                Lihat Semua
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#f0ebe5] text-[11px] font-semibold text-[#8f7664]">
                        <th class="pb-3 pr-4 font-medium">Tanggal</th>
                        <th class="pb-3 px-4 font-medium">Kategori</th>
                        <th class="pb-3 px-4 font-medium">Keterangan</th>
                        <th class="pb-3 pl-4 font-medium text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f7f3ee]">
                    @forelse($riwayatHarian as $item)
                    <tr class="hover:bg-[#faf7f2]/50 transition-colors">
                        <td class="py-3.5 pr-4 text-[#5a4d42] whitespace-nowrap">
                            {{ $item['tanggalFormatted'] }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-bold {{ $item['isPositive'] ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $item['kategori'] }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-[#21140b]">
                            {{ $item['keterangan'] }}
                        </td>
                        <td class="py-3.5 pl-4 text-right font-extrabold whitespace-nowrap {{ $item['isPositive'] ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $item['amountFormatted'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-xs text-[#8f7664] font-medium">
                            Belum ada aktivitas arus kas pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 6. RIWAYAT TRANSAKSI PEMBELI (STACKED CARD) --}}
    <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-extrabold text-[#21140b]">Riwayat Transaksi Pembeli</h3>
            <button type="button" onclick="openModal('modal-semua-transaksi')" class="text-xs font-semibold text-[#8b5a2b] hover:text-[#21140b] transition-colors cursor-pointer">
                Lihat Semua
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#f0ebe5] text-[11px] font-semibold text-[#8f7664]">
                        <th class="pb-3 pr-3 font-medium">Tanggal</th>
                        <th class="pb-3 px-3 font-medium">No. Transaksi</th>
                        <th class="pb-3 px-3 font-medium">Nama Pelanggan</th>
                        <th class="pb-3 px-3 font-medium">Menu</th>
                        <th class="pb-3 px-3 font-medium">Metode Pembayaran</th>
                        <th class="pb-3 pl-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f7f3ee]">
                    @forelse($transaksiPembeli as $order)
                    <tr class="hover:bg-[#faf7f2]/50 transition-colors">
                        <td class="py-3.5 pr-3 text-[#5a4d42] whitespace-nowrap">
                            {{ $order->tanggal_pesan ? $order->tanggal_pesan->format('d M Y') : '-' }}
                        </td>
                        <td class="py-3.5 px-3 font-extrabold text-[#21140b] whitespace-nowrap">
                            #TRX-{{ str_pad($order->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="py-3.5 px-3 font-bold text-[#21140b] whitespace-nowrap">
                            {{ $order->user?->nama ?? 'Pelanggan' }}
                        </td>
                        <td class="py-3.5 px-3 text-[#5a4d42] max-w-[220px] truncate" title="{{ $order->items_summary }}">
                            {{ $order->items_summary }}
                        </td>
                        <td class="py-3.5 px-3 text-[#5a4d42] whitespace-nowrap">
                            {{ $order->metode_pembayaran ?? ($order->pembayaran?->metode ?? 'QRIS') }}
                        </td>
                        <td class="py-3.5 pl-3 text-right font-extrabold text-[#21140b] whitespace-nowrap">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-xs text-[#8f7664] font-medium">
                            Belum ada transaksi pembeli pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Sleek Minimal Pagination --}}
        @if($transaksiPembeli instanceof \Illuminate\Pagination\LengthAwarePaginator && $transaksiPembeli->hasPages())
        <div class="flex items-center justify-between pt-4 mt-2 border-t border-[#f0ebe5] text-xs">
            <span class="text-[#8f7664] font-medium">
                Menampilkan <strong class="text-[#21140b]">{{ $transaksiPembeli->firstItem() ?? 0 }}-{{ $transaksiPembeli->lastItem() ?? 0 }}</strong> dari <strong class="text-[#21140b]">{{ $transaksiPembeli->total() }}</strong> transaksi
            </span>
            <div class="flex items-center gap-1.5">
                @if ($transaksiPembeli->onFirstPage())
                    <span class="px-3 py-1.5 rounded-xl bg-[#f5efe8] text-[#b09a87] font-semibold cursor-not-allowed text-xs">← Prev</span>
                @else
                    <a href="{{ $transaksiPembeli->previousPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-white border border-[#ede6df] text-[#21140b] font-semibold hover:bg-[#faf7f2] shadow-2xs text-xs transition-all">← Prev</a>
                @endif

                <span class="px-2 font-bold text-[#21140b] text-xs">
                    {{ $transaksiPembeli->currentPage() }} / {{ $transaksiPembeli->lastPage() }}
                </span>

                @if ($transaksiPembeli->hasMorePages())
                    <a href="{{ $transaksiPembeli->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-white border border-[#ede6df] text-[#21140b] font-semibold hover:bg-[#faf7f2] shadow-2xs text-xs transition-all">Next →</a>
                @else
                    <span class="px-3 py-1.5 rounded-xl bg-[#f5efe8] text-[#b09a87] font-semibold cursor-not-allowed text-xs">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- 7. KESIMPULAN PERIODE INI (DARK BOTTOM BANNER SESUAI MOCKUP) --}}
    <div class="rounded-2xl p-6 lg:p-8 text-white shadow-sm border" style="background-color: #241812 !important; color: #ffffff !important; border-color: #3d2a1f !important;">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            {{-- Left: Text Summary --}}
            <div class="max-w-xl space-y-2">
                <h3 class="text-base font-extrabold text-white tracking-wide">Kesimpulan Periode Ini</h3>
                <p class="text-xs text-[#d5c6b8] leading-relaxed">
                    {{ $conclusion['message'] }}
                </p>
            </div>

            {{-- Right: Formula Calculation Box --}}
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 bg-white/10 border border-white/15 p-4 rounded-2xl shrink-0">
                {{-- Pendapatan --}}
                <div class="text-center sm:text-left">
                    <p class="text-[10px] font-bold text-[#d5c6b8] uppercase tracking-wider">Pendapatan</p>
                    <p class="text-lg sm:text-xl font-extrabold text-white">{{ $conclusion['revenueShort'] }}</p>
                </div>

                <div class="text-lg font-bold text-[#b09a87] px-1">−</div>

                {{-- Pengeluaran --}}
                <div class="text-center sm:text-left">
                    <p class="text-[10px] font-bold text-[#d5c6b8] uppercase tracking-wider">Pengeluaran</p>
                    <p class="text-lg sm:text-xl font-extrabold text-rose-300">{{ $conclusion['expenseShort'] }}</p>
                </div>

                <div class="text-lg font-bold text-[#b09a87] px-1">=</div>

                {{-- Laba Bersih --}}
                <div class="text-center sm:text-left bg-white/10 border border-white/15 px-3.5 py-2 rounded-xl">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#e2b17a]">Laba Bersih</p>
                    <p class="text-lg sm:text-xl font-extrabold text-white">{{ $conclusion['profitFormatted'] }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================ --}}
{{-- MODAL: CATAT PENGELUARAN BARU                --}}
{{-- ============================================ --}}
<div id="modal-catat-pengeluaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-catat-pengeluaran')"></div>

    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden border border-[#ede6df]">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4 bg-[#faf7f2]">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#2e2119] text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Catat Pengeluaran Baru</h3>
                    <p class="text-[10px] text-[#8f7664] font-medium">Input pengeluaran operasional atau pembelian bahan</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-catat-pengeluaran')" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route($routePrefix . '.laporan-keuangan.pengeluaran.store') }}" class="p-6 space-y-4">
            @csrf
            {{-- Kategori --}}
            <div>
                <label for="input-kategori" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Kategori Pengeluaran <span class="text-rose-500">*</span></label>
                <select name="kategori" id="input-kategori" required class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer">
                    <option value="Bahan Baku">Bahan Baku (Biji Kopi, Susu, Sirup, Pastry)</option>
                    <option value="Operasional">Operasional (Cup takeaway, Sedotan, Plastik, Kebersihan)</option>
                    <option value="Peralatan">Peralatan (Service Mesin Espresso, Grinder, Utensil)</option>
                    <option value="Listrik & Internet">Listrik & Internet (PLN, WiFi Toko, PDAM)</option>
                    <option value="Lainnya">Lainnya (Marketing/Promo, Administrasi, Biaya Tak Terduga)</option>
                </select>
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="input-keterangan" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Keterangan / Nama Pengeluaran <span class="text-rose-500">*</span></label>
                <input type="text" name="keterangan" id="input-keterangan" required placeholder="Contoh: Restock Fresh Milk Diamond 24L" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all" />
            </div>

            {{-- Grid Jumlah & Tanggal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="input-jumlah" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Jumlah Nominal (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" id="input-jumlah" required min="1000" placeholder="500000" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all" />
                </div>
                <div>
                    <label for="input-tanggal" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Tanggal Pengeluaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" id="input-tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer" />
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label for="input-metode" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="input-metode" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer">
                    <option value="Tunai">Tunai (Kas Toko / Cash)</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="QRIS">QRIS / E-Wallet</option>
                    <option value="Kartu Debit">Kartu Debit</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#f0ebe5]">
                <button type="button" onclick="closeModal('modal-catat-pengeluaran')" class="rounded-xl px-4 py-2 text-xs font-semibold text-[#8f7664] hover:bg-[#faf5f0] hover:text-[#21140b] transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#2e2119] hover:bg-[#1a120c] text-white px-5 py-2 text-xs font-bold shadow-xs transition-all cursor-pointer">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: EDIT PENGELUARAN                      --}}
{{-- ============================================ --}}
<div id="modal-edit-pengeluaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-edit-pengeluaran')"></div>

    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden border border-[#ede6df]">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4 bg-[#faf7f2]">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#8b5a2b] text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Edit Catatan Pengeluaran</h3>
                    <p class="text-[10px] text-[#8f7664] font-medium">Ubah rincian pengeluaran operasional toko</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-edit-pengeluaran')" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="form-edit-pengeluaran" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Kategori --}}
            <div>
                <label for="edit-kategori" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Kategori Pengeluaran <span class="text-rose-500">*</span></label>
                <select name="kategori" id="edit-kategori" required class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer">
                    <option value="Bahan Baku">Bahan Baku (Biji Kopi, Susu, Sirup, Pastry)</option>
                    <option value="Operasional">Operasional (Cup takeaway, Sedotan, Plastik, Kebersihan)</option>
                    <option value="Peralatan">Peralatan (Service Mesin Espresso, Grinder, Utensil)</option>
                    <option value="Listrik & Internet">Listrik & Internet (PLN, WiFi Toko, PDAM)</option>
                    <option value="Lainnya">Lainnya (Marketing/Promo, Administrasi, Biaya Tak Terduga)</option>
                </select>
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="edit-keterangan" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Keterangan / Nama Pengeluaran <span class="text-rose-500">*</span></label>
                <input type="text" name="keterangan" id="edit-keterangan" required class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all" />
            </div>

            {{-- Grid Jumlah & Tanggal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-jumlah" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Jumlah Nominal (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" id="edit-jumlah" required min="1000" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all" />
                </div>
                <div>
                    <label for="edit-tanggal" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Tanggal Pengeluaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" id="edit-tanggal" required class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer" />
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label for="edit-metode" class="block text-xs font-bold text-[#5a4d42] mb-1.5">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="edit-metode" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs font-semibold rounded-xl px-3.5 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3d2a1f] focus:border-[#3d2a1f] transition-all cursor-pointer">
                    <option value="Tunai">Tunai (Kas Toko / Cash)</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="QRIS">QRIS / E-Wallet</option>
                    <option value="Kartu Debit">Kartu Debit</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#f0ebe5]">
                <button type="button" onclick="closeModal('modal-edit-pengeluaran')" class="rounded-xl px-4 py-2 text-xs font-semibold text-[#8f7664] hover:bg-[#faf5f0] hover:text-[#21140b] transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#2e2119] hover:bg-[#1a120c] text-white px-5 py-2 text-xs font-bold shadow-xs transition-all cursor-pointer">
                    Perbarui Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: KELOLA & DETAIL DAFTAR PENGELUARAN    --}}
{{-- ============================================ --}}
<div id="modal-kelola-pengeluaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-kelola-pengeluaran')"></div>

    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl overflow-hidden border border-[#ede6df] max-h-[88vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4 bg-[#faf7f2]">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#2e2119] text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Kelola & Detail Pengeluaran ({{ $periodeLabel }})</h3>
                    <p class="text-[10px] text-[#8f7664] font-medium">Semua item belanja & beban operasional yang tercatat</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeModal('modal-kelola-pengeluaran'); openModal('modal-catat-pengeluaran');" class="rounded-xl bg-[#2e2119] hover:bg-[#1a120c] text-white px-3 py-1.5 text-xs font-bold shadow-xs transition-all cursor-pointer">
                    + Tambah Baru
                </button>
                <button type="button" onclick="closeModal('modal-kelola-pengeluaran')" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Table Body --}}
        <div class="overflow-y-auto p-6 flex-1">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#f0ebe5] text-[11px] font-semibold text-[#8f7664]">
                            <th class="pb-3 pr-2 font-medium">Tanggal</th>
                            <th class="pb-3 px-2 font-medium">Kategori</th>
                            <th class="pb-3 px-2 font-medium">Keterangan</th>
                            <th class="pb-3 px-2 font-medium">Metode</th>
                            <th class="pb-3 px-2 font-medium text-right">Jumlah</th>
                            <th class="pb-3 pl-2 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f7f3ee]">
                        @forelse($allPengeluaran as $exp)
                        <tr class="hover:bg-[#faf7f2]/50 transition-colors">
                            <td class="py-3 pr-2 text-[#5a4d42] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($exp->tanggal)->format('d M Y') }}
                            </td>
                            <td class="py-3 px-2 whitespace-nowrap">
                                <span class="font-bold text-rose-500">
                                    {{ $exp->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-2 font-medium text-[#21140b]">
                                {{ $exp->keterangan }}
                            </td>
                            <td class="py-3 px-2 text-[#8f7664] whitespace-nowrap">
                                {{ $exp->metode_pembayaran ?? 'Tunai' }}
                            </td>
                            <td class="py-3 px-2 text-right font-extrabold text-rose-500 whitespace-nowrap">
                                - Rp {{ number_format((float) $exp->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3 pl-2 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Edit Button (Pencil) --}}
                                    <button type="button" onclick="openEditPengeluaranModal('{{ $exp->id_pengeluaran }}', '{{ addslashes($exp->kategori) }}', '{{ addslashes($exp->keterangan) }}', '{{ (int)$exp->jumlah }}', '{{ \Carbon\Carbon::parse($exp->tanggal)->format('Y-m-d') }}', '{{ addslashes($exp->metode_pembayaran ?? 'Tunai') }}')" class="text-[#8b5a2b] hover:text-[#21140b] p-1.5 rounded-lg hover:bg-[#faf5f0] cursor-pointer transition-colors" title="Edit Pengeluaran">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Button (Trash) --}}
                                    <form method="POST" action="{{ route($routePrefix . '.laporan-keuangan.pengeluaran.destroy', $exp->id_pengeluaran) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 cursor-pointer transition-colors" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-[#8f7664] font-medium">
                                Belum ada data pengeluaran yang tercatat pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[#f0ebe5] px-6 py-3.5 bg-[#faf7f2] flex items-center justify-between text-xs">
            <span class="text-[#8f7664] font-medium">Total: <strong class="text-[#21140b]">{{ count($allPengeluaran) }}</strong> pengeluaran ({{ $stats['totalPengeluaran'] }})</span>
            <button type="button" onclick="closeModal('modal-kelola-pengeluaran')" class="rounded-xl bg-[#2e2119] hover:bg-[#1a120c] text-white px-4 py-2 text-xs font-bold shadow-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: LIHAT SEMUA TRANSAKSI PENJUALAN       --}}
{{-- ============================================ --}}
<div id="modal-semua-transaksi" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-semua-transaksi')"></div>

    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl overflow-hidden border border-[#ede6df] max-h-[88vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4 bg-[#faf7f2]">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#2e2119] text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Daftar Lengkap Transaksi Penjualan ({{ $periodeLabel }})</h3>
                    <p class="text-[10px] text-[#8f7664] font-medium">Semua pesanan selesai yang tercatat pada periode ini</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-semua-transaksi')" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Table Body --}}
        <div class="overflow-y-auto p-6 flex-1">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#f0ebe5] text-[11px] font-semibold text-[#8f7664]">
                            <th class="pb-3 pr-2 font-medium">Tanggal</th>
                            <th class="pb-3 px-2 font-medium">No. Transaksi</th>
                            <th class="pb-3 px-2 font-medium">Nama Pelanggan</th>
                            <th class="pb-3 px-2 font-medium">Menu</th>
                            <th class="pb-3 px-2 font-medium">Metode</th>
                            <th class="pb-3 pl-2 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f7f3ee]">
                        @forelse($allPesanan as $order)
                        <tr class="hover:bg-[#faf7f2]/50 transition-colors">
                            <td class="py-3 pr-2 text-[#5a4d42] whitespace-nowrap">
                                {{ $order->tanggal_pesan ? $order->tanggal_pesan->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-2 font-extrabold text-[#21140b] whitespace-nowrap">
                                #TRX-{{ str_pad($order->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3 px-2 font-bold text-[#21140b] whitespace-nowrap">
                                {{ $order->user?->nama ?? 'Pelanggan' }}
                            </td>
                            <td class="py-3 px-2 text-[#5a4d42]">
                                {{ $order->items_summary }}
                            </td>
                            <td class="py-3 px-2 text-[#5a4d42] whitespace-nowrap">
                                {{ $order->metode_pembayaran ?? ($order->pembayaran?->metode ?? 'QRIS') }}
                            </td>
                            <td class="py-3 pl-2 text-right font-extrabold text-[#21140b] whitespace-nowrap">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-[#8f7664] font-medium">
                                Belum ada transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[#f0ebe5] px-6 py-3.5 bg-[#faf7f2] flex items-center justify-between text-xs">
            <span class="text-[#8f7664] font-medium">Total: <strong class="text-[#21140b]">{{ count($allPesanan) }}</strong> transaksi ({{ $stats['totalPendapatan'] }})</span>
            <button type="button" onclick="closeModal('modal-semua-transaksi')" class="rounded-xl bg-[#2e2119] hover:bg-[#1a120c] text-white px-4 py-2 text-xs font-bold shadow-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT: CHART.JS, MODALS & INTERACTIONS --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('financeChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const chartData = @json($chartData);

        function createGreenGradient(c) {
            const gradient = c.createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
            gradient.addColorStop(0.7, 'rgba(16, 185, 129, 0.04)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
            return gradient;
        }

        function createRedGradient(c) {
            const gradient = c.createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, 'rgba(239, 68, 68, 0.22)');
            gradient.addColorStop(0.7, 'rgba(239, 68, 68, 0.04)');
            gradient.addColorStop(1, 'rgba(239, 68, 68, 0.0)');
            return gradient;
        }

        let currentMode = 'daily'; // 'daily' (default) or 'cumulative'
        let currentType = 'line';  // 'line' (default) or 'bar'
        let financeChartInstance = null;

        function getDatasets(type, mode) {
            const isDaily = mode === 'daily';
            const pemasukanData = isDaily ? chartData.pemasukan_harian : chartData.pemasukan_akumulasi;
            const pengeluaranData = isDaily ? chartData.pengeluaran_harian : chartData.pengeluaran_akumulasi;

            if (type === 'bar') {
                return [
                    {
                        label: 'Pemasukan',
                        data: pemasukanData,
                        backgroundColor: '#10b981',
                        hoverBackgroundColor: '#059669',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        backgroundColor: '#ef4444',
                        hoverBackgroundColor: '#dc2626',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.7
                    }
                ];
            }

            return [
                {
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderColor: '#10b981',
                    borderWidth: 2.75,
                    backgroundColor: createGreenGradient(ctx),
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBorderWidth: 2.5
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#ef4444',
                    borderWidth: 2.75,
                    backgroundColor: createRedGradient(ctx),
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBorderWidth: 2.5
                }
            ];
        }

        function initOrUpdateChart() {
            if (financeChartInstance) {
                financeChartInstance.destroy();
            }

            financeChartInstance = new Chart(ctx, {
                type: currentType,
                data: {
                    labels: chartData.labels,
                    datasets: getDatasets(currentType, currentMode)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 350,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1c130d',
                            titleColor: '#f5efe8',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.12)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true,
                            boxWidth: 8,
                            boxHeight: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            callbacks: {
                                title: function(items) {
                                    if (!items.length) return '';
                                    const index = items[0].dataIndex;
                                    if (chartData.full_dates && chartData.full_dates[index]) {
                                        return chartData.full_dates[index];
                                    }
                                    return items[0].label;
                                },
                                label: function(context) {
                                    const val = Number(context.raw || 0);
                                    return ' ' + context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                                },
                                afterBody: function(items) {
                                    if (items.length < 2) return '';
                                    const rev = Number(items[0]?.raw || 0);
                                    const exp = Number(items[1]?.raw || 0);
                                    const diff = rev - exp;
                                    const prefix = diff > 0 ? '+ Rp ' : (diff < 0 ? '- Rp ' : 'Rp ');
                                    const absVal = Math.abs(diff).toLocaleString('id-ID');
                                    const label = currentMode === 'daily' ? 'Selisih Hari Ini' : 'Laba Akumulatif';
                                    return '\n' + label + ': ' + prefix + absVal;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: '600' },
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 12
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grace: '8%',
                            grid: {
                                color: '#f0ebe5'
                            },
                            border: { dash: [4, 4], display: false },
                            ticks: {
                                color: '#8f7664',
                                font: { size: 10, weight: '600' },
                                maxTicksLimit: 6,
                                callback: function(value) {
                                    if (value === 0) return 'Rp 0';
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(value % 1000000 === 0 ? 0 : 1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize Chart
        initOrUpdateChart();

        // Setup Mode Toggles (Harian vs Akumulasi)
        const btnDaily = document.getElementById('btn-chart-daily');
        const btnCumulative = document.getElementById('btn-chart-cumulative');
        const subLabel = document.getElementById('chart-sub-label');

        if (btnDaily && btnCumulative) {
            btnDaily.addEventListener('click', function() {
                if (currentMode === 'daily') return;
                currentMode = 'daily';
                btnDaily.className = 'px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-[#21140b] text-white shadow-xs cursor-pointer';
                btnCumulative.className = 'px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer';
                if (subLabel) {
                    subLabel.textContent = 'Arus Kas Harian (Grafik turun ke 0 saat tidak ada aktivitas transaksi)';
                }
                initOrUpdateChart();
            });

            btnCumulative.addEventListener('click', function() {
                if (currentMode === 'cumulative') return;
                currentMode = 'cumulative';
                btnCumulative.className = 'px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-[#21140b] text-white shadow-xs cursor-pointer';
                btnDaily.className = 'px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer';
                if (subLabel) {
                    subLabel.textContent = 'Tren Akumulasi Kas Berjalan (Total Saldo Akumulatif Periode Ini)';
                }
                initOrUpdateChart();
            });
        }

        // Setup Chart Type Toggles (Area Line vs Bar)
        const btnTypeLine = document.getElementById('btn-chart-type-line');
        const btnTypeBar = document.getElementById('btn-chart-type-bar');

        if (btnTypeLine && btnTypeBar) {
            btnTypeLine.addEventListener('click', function() {
                if (currentType === 'line') return;
                currentType = 'line';
                btnTypeLine.className = 'p-1.5 text-xs font-bold rounded-lg transition-all bg-white text-[#21140b] shadow-2xs cursor-pointer';
                btnTypeBar.className = 'p-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer';
                initOrUpdateChart();
            });

            btnTypeBar.addEventListener('click', function() {
                if (currentType === 'bar') return;
                currentType = 'bar';
                btnTypeBar.className = 'p-1.5 text-xs font-bold rounded-lg transition-all bg-white text-[#21140b] shadow-2xs cursor-pointer';
                btnTypeLine.className = 'p-1.5 text-xs font-bold rounded-lg transition-all text-[#8f7664] hover:text-[#21140b] cursor-pointer';
                initOrUpdateChart();
            });
        }

        // --- Custom Periode Dropdown ---
        const btnPeriode = document.getElementById('btn-periode-dropdown');
        const menuPeriode = document.getElementById('periode-dropdown-menu');
        const arrowPeriode = document.getElementById('periode-dropdown-arrow');
        const labelPeriode = document.getElementById('periode-dropdown-label');
        const hiddenInputPeriode = document.getElementById('input-hidden-periode');
        const optionsPeriode = document.querySelectorAll('.periode-option');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        function updateDropdownUI(val, label) {
            if (hiddenInputPeriode) hiddenInputPeriode.value = val;
            if (labelPeriode) labelPeriode.textContent = label;

            optionsPeriode.forEach(function(o) {
                const isSelected = o.dataset.value === val;
                o.className = isSelected
                    ? 'periode-option w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-left transition-colors cursor-pointer bg-[#faf5f0] text-[#8b5a2b]'
                    : 'periode-option w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-left transition-colors cursor-pointer text-[#5a4d42] hover:bg-[#faf7f2] hover:text-[#21140b]';
                
                const existingSvg = o.querySelector('svg');
                if (existingSvg) existingSvg.remove();

                if (isSelected) {
                    o.insertAdjacentHTML('beforeend', '<svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>');
                }
            });
        }

        if (btnPeriode && menuPeriode) {
            btnPeriode.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = menuPeriode.classList.contains('hidden');
                menuPeriode.classList.toggle('hidden');
                if (arrowPeriode) {
                    arrowPeriode.style.transform = isHidden ? 'rotate(180deg)' : '';
                }
            });

            optionsPeriode.forEach(function(opt) {
                opt.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = this.dataset.value;
                    const label = this.dataset.label;

                    updateDropdownUI(val, label);

                    menuPeriode.classList.add('hidden');
                    if (arrowPeriode) arrowPeriode.style.transform = '';

                    handlePeriodChange(val);
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#periode-dropdown-wrapper')) {
                    menuPeriode.classList.add('hidden');
                    if (arrowPeriode) arrowPeriode.style.transform = '';
                }
            });

            if (startInput) {
                startInput.addEventListener('change', function() {
                    updateDropdownUI('kustom', 'Kustom Rentang Tanggal');
                });
            }
            if (endInput) {
                endInput.addEventListener('change', function() {
                    updateDropdownUI('kustom', 'Kustom Rentang Tanggal');
                });
            }
        }
    });

    function handlePeriodChange(value) {
        const today = new Date();
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        const formatDate = (d) => {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        if (value === 'hari_ini') {
            startInput.value = formatDate(today);
            endInput.value = formatDate(today);
        } else if (value === 'bulan_ini') {
            const start = new Date(today.getFullYear(), today.getMonth(), 1);
            const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startInput.value = formatDate(start);
            endInput.value = formatDate(end);
        } else if (value === 'minggu_ini') {
            const dayOfWeek = today.getDay();
            const diffToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
            const start = new Date(today);
            start.setDate(today.getDate() + diffToMonday);
            const end = new Date(start);
            end.setDate(start.getDate() + 6);
            startInput.value = formatDate(start);
            endInput.value = formatDate(end);
        } else if (value === 'bulan_lalu') {
            const start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const end = new Date(today.getFullYear(), today.getMonth(), 0);
            startInput.value = formatDate(start);
            endInput.value = formatDate(end);
        } else if (value === 'tahun_ini') {
            const start = new Date(today.getFullYear(), 0, 1);
            const end = new Date(today.getFullYear(), 11, 31);
            startInput.value = formatDate(start);
            endInput.value = formatDate(end);
        }

        if (value !== 'kustom') {
            document.getElementById('form-filter-laporan').submit();
        }
    }

    function openEditPengeluaranModal(id, kategori, keterangan, jumlah, tanggal, metode) {
        closeModal('modal-kelola-pengeluaran');

        const form = document.getElementById('form-edit-pengeluaran');
        form.action = `/{{ $routePrefix }}/laporan-keuangan/pengeluaran/${id}`;

        document.getElementById('edit-kategori').value = kategori;
        document.getElementById('edit-keterangan').value = keterangan;
        document.getElementById('edit-jumlah').value = jumlah;
        document.getElementById('edit-tanggal').value = tanggal;
        document.getElementById('edit-metode').value = metode || 'Tunai';

        openModal('modal-edit-pengeluaran');
    }
</script>
@endsection
