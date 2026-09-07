@extends('layouts.admin')

@section('page-title', 'Review Management')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="text-left space-y-0.5">
        <h1 class="text-xl font-bold text-[#21140b]">Manajemen Ulasan</h1>
        <p class="text-xs text-[#9a8575]">Monitor dan kelola ulasan dari pelanggan.</p>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Total Ulasan --}}
        <div class="rounded-xl bg-white border border-[#ede6df] px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f5ede5] text-[#8c5a3c]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-[#8f7664]">Total Ulasan</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-bold text-[#21140b]">{{ number_format($totalUlasan) }}</p>
                        <span class="text-[10px] font-medium text-[#9a8575]">
                            {{ $growthPercent >= 0 ? '+' : '' }}{{ $growthPercent }}% dari minggu lalu
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rating Rata-Rata --}}
        <div class="rounded-xl bg-white border border-[#ede6df] px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f5ede5] text-[#8c5a3c]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-[#8f7664]">Rating Rata-Rata</p>
                    <div class="flex items-center gap-2">
                        <p class="text-2xl font-bold text-[#21140b]">{{ $avgRating }}</p>
                        <span class="text-xs text-[#9a8575]">/ 5</span>
                        <div class="flex items-center gap-0.5 ml-1 text-[#a2785d]">
                            @for($s = 1; $s <= 5; $s++)
                                @if($s <= round($avgRating))
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                    <svg class="h-3 w-3 text-[#ddd1c5]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ulasan Positif --}}
        <div class="rounded-xl bg-white border border-[#ede6df] px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f5ede5] text-[#8c5a3c]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-[#8f7664]">Ulasan Positif</p>
                    <p class="text-2xl font-bold text-[#21140b]">{{ $positivePercentage }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Ulasan --}}
    <div class="rounded-2xl bg-white border border-[#ede6df] shadow-sm">
        
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-[#f2ebe3] flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between rounded-t-2xl">
            <div class="space-y-0.5 text-left">
                <h2 class="text-sm font-bold text-[#21140b]">
                    Daftar Ulasan {{ $sort === 'terlama' ? 'Terlama' : ($sort === 'tertinggi' ? 'Rating Tertinggi' : ($sort === 'terendah' ? 'Rating Terendah' : 'Terbaru')) }}
                </h2>
                <p class="text-[11px] text-[#8f7664]">
                    Total {{ number_format($ulasans->total()) }} ulasan pelanggan
                </p>
            </div>

            <div class="flex items-center gap-2.5 justify-end">
                {{-- Search Box --}}
                <form action="{{ route('admin.ulasan.index') }}" method="GET" class="flex items-center">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="menu_id" value="{{ $selectedMenu }}">
                    <input type="hidden" name="rating" value="{{ $selectedRating }}">
                    <input type="hidden" name="reply_status" value="{{ $selectedReplyStatus }}">
                    <div class="relative w-44 sm:w-60">
                        <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari ulasan..."
                            class="w-full bg-[#fdfbf9] focus:bg-white border border-[#ede6df] text-xs font-semibold rounded-xl pl-10 pr-8 py-2 text-[#21140b] placeholder-[#a89584] focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]/15 focus:border-[#8b5a2b] transition-all shadow-2xs"
                        />
                        @if(!empty($search))
                            <a href="{{ route('admin.ulasan.index', array_filter(['sort' => $sort !== 'terbaru' ? $sort : null, 'menu_id' => $selectedMenu !== 'all' ? $selectedMenu : null, 'rating' => $selectedRating !== 'all' ? $selectedRating : null, 'reply_status' => $selectedReplyStatus !== 'all' ? $selectedReplyStatus : null])) }}" class="absolute inset-y-0 right-2.5 flex items-center text-[#a89584] hover:text-[#21140b] cursor-pointer" title="Hapus pencarian">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Filter Toggle Button --}}
                @php
                    $isFilterActive = ($sort !== 'terbaru' || $selectedMenu !== 'all' || $selectedRating !== 'all' || $selectedReplyStatus !== 'all' || !empty($search));
                    $activeFiltersCount = 0;
                    if ($sort !== 'terbaru') $activeFiltersCount++;
                    if ($selectedMenu !== 'all') $activeFiltersCount++;
                    if ($selectedRating !== 'all') $activeFiltersCount++;
                    if ($selectedReplyStatus !== 'all') $activeFiltersCount++;
                    if (!empty($search)) $activeFiltersCount++;
                @endphp
                <button
                    type="button"
                    onclick="toggleFilterPanel()"
                    id="filter-toggle-btn"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border text-xs font-bold transition-all cursor-pointer shadow-2xs {{ $isFilterActive ? 'bg-[#3d2a1f] text-white border-[#3d2a1f] hover:bg-[#21140b]' : 'bg-white border-[#ede6df] text-[#6b584c] hover:bg-[#faf7f2] hover:border-[#d5ccc3]' }}"
                >
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter</span>
                    @if($activeFiltersCount > 0)
                        <span class="inline-flex items-center justify-center h-4 w-4 rounded-full bg-[#8b5a2b] text-[9px] font-extrabold text-white">
                            {{ $activeFiltersCount }}
                        </span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Collapsible Filter Panel with Custom Dropdown Selectors --}}
        <div id="filter-panel" class="{{ $isFilterActive ? '' : 'hidden' }} bg-[#faf7f2] border-b border-[#ede6df] p-5 text-left transition-all relative z-30">
            <form action="{{ route('admin.ulasan.index') }}" method="GET" id="filter-form" class="space-y-4">
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    {{-- 1. URUTKAN (Sort) --}}
                    <div class="space-y-1 relative" id="dropdown-sort-wrapper">
                        <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Urutkan</label>
                        <input type="hidden" name="sort" id="input-sort" value="{{ $sort }}">
                        
                        <button
                            type="button"
                            id="btn-dropdown-sort"
                            onclick="toggleDropdown('sort')"
                            class="w-full h-10 flex items-center justify-between gap-2 bg-white border border-[#ede6df] hover:border-[#8b5a2b] text-xs font-semibold rounded-xl px-3.5 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]/15 transition-all cursor-pointer shadow-2xs"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <span id="icon-sort" class="shrink-0 text-[#8b5a2b]">
                                    @switch($sort)
                                        @case('terlama')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @break
                                        @case('tertinggi')
                                            <svg class="h-4 w-4 fill-amber-400 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @break
                                        @case('terendah')
                                            <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                                            @break
                                        @default
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endswitch
                                </span>
                                <span id="label-sort" class="truncate font-bold">
                                    @switch($sort)
                                        @case('terlama') Terlama @break
                                        @case('tertinggi') Rating Tertinggi @break
                                        @case('terendah') Rating Terendah @break
                                        @default Terbaru @break
                                    @endswitch
                                </span>
                            </div>
                            <svg id="arrow-sort" class="h-3.5 w-3.5 text-[#8f7664] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu Popover --}}
                        <div id="menu-dropdown-sort" class="dropdown-popover absolute left-0 top-full mt-1.5 w-full min-w-[210px] bg-white rounded-2xl border border-[#ede6df] shadow-xl z-50 py-1.5 hidden space-y-0.5">
                            @php
                                $sortOptions = [
                                    'terbaru' => ['label' => 'Terbaru', 'desc' => 'Ulasan paling baru masuk', 'icon' => 'clock'],
                                    'terlama' => ['label' => 'Terlama', 'desc' => 'Ulasan terlama lebih dulu', 'icon' => 'history'],
                                    'tertinggi' => ['label' => 'Rating Tertinggi', 'desc' => 'Bintang 5 ke 1', 'icon' => 'star-high'],
                                    'terendah' => ['label' => 'Rating Terendah', 'desc' => 'Bintang 1 ke 5', 'icon' => 'trend-down'],
                                ];
                            @endphp
                            @foreach($sortOptions as $val => $opt)
                                <button
                                    type="button"
                                    onclick="selectDropdownOption('sort', '{{ $val }}', '{{ $opt['label'] }}', '{{ $opt['icon'] }}')"
                                    class="dropdown-item-sort w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $sort === $val ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                    data-value="{{ $val }}"
                                >
                                    <div class="flex items-center gap-2.5">
                                        <span class="shrink-0">
                                            @if($opt['icon'] === 'clock')
                                                <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @elseif($opt['icon'] === 'history')
                                                <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @elseif($opt['icon'] === 'star-high')
                                                <svg class="h-4 w-4 fill-amber-400 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @else
                                                <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                                            @endif
                                        </span>
                                        <div>
                                            <p class="leading-tight">{{ $opt['label'] }}</p>
                                            <p class="text-[10px] text-[#9a8575] font-normal mt-0.5">{{ $opt['desc'] }}</p>
                                        </div>
                                    </div>
                                    <span class="check-icon {{ $sort === $val ? '' : 'hidden' }}">
                                        <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. MENU --}}
                    <div class="space-y-1 relative" id="dropdown-menu-wrapper">
                        <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Menu</label>
                        <input type="hidden" name="menu_id" id="input-menu" value="{{ $selectedMenu }}">
                        
                        @php
                            $selectedMenuModel = $menus->firstWhere('id_menu', $selectedMenu);
                            $selectedMenuName = $selectedMenuModel ? $selectedMenuModel->nama_menu : 'Semua Menu';
                        @endphp
                        <button
                            type="button"
                            id="btn-dropdown-menu"
                            onclick="toggleDropdown('menu')"
                            class="w-full h-10 flex items-center justify-between gap-2 bg-white border border-[#ede6df] hover:border-[#8b5a2b] text-xs font-semibold rounded-xl px-3.5 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]/15 transition-all cursor-pointer shadow-2xs"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <span id="icon-menu" class="shrink-0 text-[#8b5a2b]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </span>
                                <span id="label-menu" class="truncate font-bold">{{ $selectedMenuName }}</span>
                            </div>
                            <svg id="arrow-menu" class="h-3.5 w-3.5 text-[#8f7664] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu Popover --}}
                        <div id="menu-dropdown-menu" class="dropdown-popover absolute left-0 top-full mt-1.5 w-full min-w-[220px] max-h-60 overflow-y-auto bg-white rounded-2xl border border-[#ede6df] shadow-xl z-50 py-1.5 hidden space-y-0.5">
                            {{-- Option Semua Menu --}}
                            <button
                                type="button"
                                onclick="selectDropdownOption('menu', 'all', 'Semua Menu', 'menu-all')"
                                class="dropdown-item-menu w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedMenu === 'all' ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                data-value="all"
                            >
                                <div class="flex items-center gap-2.5 truncate">
                                    <svg class="h-4 w-4 text-[#8b5a2b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                    <span class="truncate">Semua Menu</span>
                                </div>
                                <span class="check-icon {{ $selectedMenu === 'all' ? '' : 'hidden' }}">
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                            </button>

                            @foreach($menus as $m)
                                <button
                                    type="button"
                                    onclick="selectDropdownOption('menu', '{{ $m->id_menu }}', '{{ addslashes($m->nama_menu) }}', 'menu-item')"
                                    class="dropdown-item-menu w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedMenu == $m->id_menu ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                    data-value="{{ $m->id_menu }}"
                                >
                                    <div class="flex items-center gap-2.5 truncate">
                                        <svg class="h-4 w-4 text-[#a2785d] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        <span class="truncate">{{ $m->nama_menu }}</span>
                                    </div>
                                    <span class="check-icon {{ $selectedMenu == $m->id_menu ? '' : 'hidden' }}">
                                        <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. RATING BINTANG --}}
                    <div class="space-y-1 relative" id="dropdown-rating-wrapper">
                        <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Rating Bintang</label>
                        <input type="hidden" name="rating" id="input-rating" value="{{ $selectedRating }}">
                        
                        <button
                            type="button"
                            id="btn-dropdown-rating"
                            onclick="toggleDropdown('rating')"
                            class="w-full h-10 flex items-center justify-between gap-2 bg-white border border-[#ede6df] hover:border-[#8b5a2b] text-xs font-semibold rounded-xl px-3.5 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]/15 transition-all cursor-pointer shadow-2xs"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <span id="icon-rating" class="shrink-0 flex items-center gap-0.5 text-amber-400">
                                    @if($selectedRating === 'all')
                                        <svg class="h-4 w-4 text-amber-500 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        @for($s = 1; $s <= (int)$selectedRating; $s++)
                                            <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    @endif
                                </span>
                                <span id="label-rating" class="truncate font-bold">
                                    {{ $selectedRating === 'all' ? 'Semua Bintang' : $selectedRating . ' Bintang' }}
                                </span>
                            </div>
                            <svg id="arrow-rating" class="h-3.5 w-3.5 text-[#8f7664] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu Popover --}}
                        <div id="menu-dropdown-rating" class="dropdown-popover absolute left-0 top-full mt-1.5 w-full min-w-[210px] bg-white rounded-2xl border border-[#ede6df] shadow-xl z-50 py-1.5 hidden space-y-0.5">
                            {{-- Semua Bintang --}}
                            <button
                                type="button"
                                onclick="selectDropdownOption('rating', 'all', 'Semua Bintang', 'rating-all')"
                                class="dropdown-item-rating w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedRating === 'all' ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                data-value="all"
                            >
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-amber-500 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>Semua Bintang</span>
                                </div>
                                <span class="check-icon {{ $selectedRating === 'all' ? '' : 'hidden' }}">
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                            </button>

                            @for($stars = 5; $stars >= 1; $stars--)
                                <button
                                    type="button"
                                    onclick="selectDropdownOption('rating', '{{ $stars }}', '{{ $stars }} Bintang', 'rating-{{ $stars }}')"
                                    class="dropdown-item-rating w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ (string)$selectedRating === (string)$stars ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                    data-value="{{ $stars }}"
                                >
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-0.5 text-amber-400">
                                            @for($s = 1; $s <= 5; $s++)
                                                @if($s <= $stars)
                                                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @else
                                                    <svg class="h-3.5 w-3.5 text-[#ede2d6]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs font-semibold text-[#21140b]">({{ $stars }} Bintang)</span>
                                    </div>
                                    <span class="check-icon {{ (string)$selectedRating === (string)$stars ? '' : 'hidden' }}">
                                        <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </span>
                                </button>
                            @endfor
                        </div>
                    </div>

                    {{-- 4. STATUS BALASAN --}}
                    <div class="space-y-1 relative" id="dropdown-reply-wrapper">
                        <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Status Balasan</label>
                        <input type="hidden" name="reply_status" id="input-reply" value="{{ $selectedReplyStatus }}">
                        
                        <button
                            type="button"
                            id="btn-dropdown-reply"
                            onclick="toggleDropdown('reply')"
                            class="w-full h-10 flex items-center justify-between gap-2 bg-white border border-[#ede6df] hover:border-[#8b5a2b] text-xs font-semibold rounded-xl px-3.5 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]/15 transition-all cursor-pointer shadow-2xs"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <span id="icon-reply" class="shrink-0">
                                    @switch($selectedReplyStatus)
                                        @case('belum_dibalas')
                                            <span class="flex h-2.5 w-2.5 rounded-full bg-amber-500 ring-4 ring-amber-100"></span>
                                            @break
                                        @case('sudah_dibalas')
                                            <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                                            @break
                                        @default
                                            <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                    @endswitch
                                </span>
                                <span id="label-reply" class="truncate font-bold">
                                    @switch($selectedReplyStatus)
                                        @case('belum_dibalas') Belum Dibalas @break
                                        @case('sudah_dibalas') Sudah Dibalas @break
                                        @default Semua Status @break
                                    @endswitch
                                </span>
                            </div>
                            <svg id="arrow-reply" class="h-3.5 w-3.5 text-[#8f7664] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu Popover --}}
                        <div id="menu-dropdown-reply" class="dropdown-popover absolute left-0 top-full mt-1.5 w-full min-w-[210px] bg-white rounded-2xl border border-[#ede6df] shadow-xl z-50 py-1.5 hidden space-y-0.5">
                            {{-- Semua Status --}}
                            <button
                                type="button"
                                onclick="selectDropdownOption('reply', 'all', 'Semua Status', 'reply-all')"
                                class="dropdown-item-reply w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedReplyStatus === 'all' ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                data-value="all"
                            >
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-[#8b5a2b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                    <span>Semua Status</span>
                                </div>
                                <span class="check-icon {{ $selectedReplyStatus === 'all' ? '' : 'hidden' }}">
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                            </button>

                            {{-- Belum Dibalas --}}
                            <button
                                type="button"
                                onclick="selectDropdownOption('reply', 'belum_dibalas', 'Belum Dibalas', 'reply-pending')"
                                class="dropdown-item-reply w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedReplyStatus === 'belum_dibalas' ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                data-value="belum_dibalas"
                            >
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-2.5 w-2.5 rounded-full bg-amber-500 ring-4 ring-amber-100 shrink-0"></span>
                                    <div>
                                        <p class="leading-tight">Belum Dibalas</p>
                                        <p class="text-[10px] text-amber-700 font-medium mt-0.5">Perlu ditanggapi toko</p>
                                    </div>
                                </div>
                                <span class="check-icon {{ $selectedReplyStatus === 'belum_dibalas' ? '' : 'hidden' }}">
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                            </button>

                            {{-- Sudah Dibalas --}}
                            <button
                                type="button"
                                onclick="selectDropdownOption('reply', 'sudah_dibalas', 'Sudah Dibalas', 'reply-done')"
                                class="dropdown-item-reply w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer {{ $selectedReplyStatus === 'sudah_dibalas' ? 'bg-[#faf5f0] text-[#8b5a2b] font-bold' : 'text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium' }}"
                                data-value="sudah_dibalas"
                            >
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shrink-0"></span>
                                    <div>
                                        <p class="leading-tight">Sudah Dibalas</p>
                                        <p class="text-[10px] text-emerald-700 font-medium mt-0.5">Telah diberi tanggapan</p>
                                    </div>
                                </div>
                                <span class="check-icon {{ $selectedReplyStatus === 'sudah_dibalas' ? '' : 'hidden' }}">
                                    <svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Action Bar (Reset & Terapkan) --}}
                <div class="flex items-center justify-between pt-3 border-t border-[#ede6df]">
                    <div class="text-xs text-[#8f7664]">
                        @if($isFilterActive)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#faf5f0] text-[#8b5a2b] font-semibold border border-[#e8ded5]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#8b5a2b]"></span>
                                Filter aktif diterapkan
                            </span>
                        @else
                            <span class="text-[11px] text-[#9a8575]">Pilih kriteria filter lalu tekan <strong>Terapkan Filter</strong></span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if($isFilterActive)
                            <a href="{{ route('admin.ulasan.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-[#8f7664] hover:text-[#21140b] hover:bg-white border border-transparent hover:border-[#ede6df] transition-all cursor-pointer">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs font-bold shadow-sm active:scale-95 transition-all cursor-pointer">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto rounded-b-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#faf7f2] border-b border-[#ede6df]">
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase w-12">No</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase min-w-[150px]">Pelanggan</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase min-w-[130px]">Menu</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase min-w-[100px]">Rating</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase min-w-[240px]">Komentar</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase min-w-[100px]">Tanggal</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-[#8f7664] tracking-wider uppercase text-right min-w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f2ebe3]">
                    @forelse($ulasans as $index => $ulasan)
                        @php
                            $customerName = $ulasan->user ? $ulasan->user->nama : 'Pelanggan Kote';
                            $menuName = $ulasan->menu ? $ulasan->menu->nama_menu : 'Menu Kote';
                        @endphp
                        <tr class="hover:bg-[#faf7f2]/60 transition-colors">
                            <td class="px-5 py-3 text-xs text-[#6b584c]">
                                {{ $ulasans->firstItem() + $index }}
                            </td>

                            <td class="px-5 py-3">
                                <span class="text-xs font-semibold text-[#21140b] block">{{ $customerName }}</span>
                                @if($ulasan->user && $ulasan->user->kelas)
                                    <span class="text-[10px] text-[#9a8575] block mt-0.5">{{ $ulasan->user->kelas }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-3">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-[#f6eee7] text-[11px] font-semibold text-[#6e4e37]">
                                    {{ $menuName }}
                                </span>
                            </td>

                            <td class="px-5 py-3">
                                <div class="flex items-center gap-0.5 text-[#a2785d]">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $ulasan->rating)
                                            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg class="h-3.5 w-3.5 text-[#ddd1c5]" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            </td>

                            <td class="px-5 py-3 space-y-1.5">
                                <p class="text-xs text-[#3d2a1f] leading-relaxed">
                                    {{ $ulasan->komentar }}
                                </p>

                                @if($ulasan->hasBalasan())
                                    <div class="rounded-lg bg-[#faf7f2] border border-[#ede6df] px-2.5 py-1.5 text-[11px] text-[#6b584c] space-y-0.5">
                                        <div class="flex items-center justify-between text-[10px] font-semibold text-[#8c5a3c]">
                                            <span>↳ Balasan Toko</span>
                                            @if($ulasan->tanggal_balasan)
                                                <span class="text-[#9a8575]">{{ $ulasan->tanggal_balasan->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                        <p class="italic text-[#4a3d35]">{{ $ulasan->balasan }}</p>
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-3 text-xs text-[#21140b]">
                                @if($ulasan->tanggal_ulasan)
                                    <div>{{ $ulasan->tanggal_ulasan->translatedFormat('d M Y') }}</div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        onclick="openReplyModal({{ $ulasan->id_ulasan }}, @js($customerName), @js($menuName), {{ $ulasan->rating }}, @js($ulasan->komentar), @js($ulasan->balasan ?? ''))"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#3d2a1f] hover:bg-[#21140b] text-white text-[11px] font-semibold transition-colors cursor-pointer"
                                        title="{{ $ulasan->hasBalasan() ? 'Edit Balasan' : 'Balas Ulasan' }}"
                                    >
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        {{ $ulasan->hasBalasan() ? 'Edit' : 'Balas' }}
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal({{ $ulasan->id_ulasan }}, @js($customerName), @js($menuName))"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg text-[#b09a87] hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                                        title="Hapus Ulasan"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#8f7664]">
                                <p class="text-xs font-semibold">Tidak ada ulasan yang ditemukan.</p>
                                @if($isFilterActive)
                                    <a href="{{ route('admin.ulasan.index') }}" class="inline-block mt-2 text-xs font-bold text-[#8c5a3c] hover:underline">
                                        Reset Filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-3 border-t border-[#f2ebe3] flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[#9a8575]">
                Menampilkan {{ $ulasans->firstItem() ?? 0 }}–{{ $ulasans->lastItem() ?? 0 }} dari {{ number_format($ulasans->total()) }} ulasan
            </p>

            <div class="flex items-center gap-2">
                @if($ulasans->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg border border-[#ede6df] text-xs font-medium text-[#d5ccc3] cursor-not-allowed">
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $ulasans->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#ede6df] bg-white hover:bg-[#faf7f2] text-xs font-medium text-[#21140b] transition-colors">
                        Sebelumnya
                    </a>
                @endif

                @if($ulasans->hasMorePages())
                    <a href="{{ $ulasans->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#ede6df] bg-white hover:bg-[#faf7f2] text-xs font-medium text-[#21140b] transition-colors">
                        Berikutnya
                    </a>
                @else
                    <span class="px-3 py-1.5 rounded-lg border border-[#ede6df] text-xs font-medium text-[#d5ccc3] cursor-not-allowed">
                        Berikutnya
                    </span>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- MODAL: BALAS ULASAN --}}
<div id="reply-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeReplyModal()"></div>
    <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl border border-[#ede6df] space-y-4 text-left">
        <div class="flex items-center justify-between pb-3 border-b border-[#f2ebe3]">
            <h3 class="text-base font-bold text-[#21140b]" id="reply-modal-title">Balas Ulasan Pelanggan</h3>
            <button type="button" onclick="closeReplyModal()" class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#faf7f2] text-[#8f7664] hover:text-[#21140b] cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="rounded-xl bg-[#faf7f2] p-3.5 border border-[#ebdcd0] space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span id="reply-customer-name" class="font-bold text-[#21140b]"></span>
                <span id="reply-menu-name" class="text-[11px] font-bold text-[#8c5a3c] bg-[#f6eee7] px-2 py-0.5 rounded-lg"></span>
            </div>
            <p id="reply-customer-comment" class="text-xs text-[#4a3d35] italic leading-relaxed"></p>
        </div>

        <form id="reply-form" action="" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label for="balasan-input" class="block text-xs font-bold text-[#21140b]">Tulis Balasan Toko:</label>
                <textarea
                    id="balasan-input"
                    name="balasan"
                    rows="3"
                    placeholder="Tuliskan respon atau terima kasih untuk pelanggan..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#ede6df] bg-white text-xs text-[#21140b] placeholder-[#b09a87] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#a2785d] transition-all resize-none"
                    required
                ></textarea>
            </div>

            <div class="flex items-center justify-between pt-1">
                <button type="button" id="btn-delete-reply" onclick="submitDeleteReply()" class="hidden text-xs font-bold text-red-600 hover:underline cursor-pointer">
                    Hapus Balasan
                </button>
                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" onclick="closeReplyModal()" class="px-4 py-2 rounded-xl border border-[#ede6df] bg-white hover:bg-[#faf7f2] text-xs font-bold text-[#7b6558] cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs font-bold shadow-xs cursor-pointer">
                        Simpan Balasan
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-reply-form" action="" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

{{-- MODAL: HAPUS ULASAN --}}
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl border border-[#ede6df] space-y-4">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <div class="space-y-1">
            <h3 class="text-sm font-bold text-[#21140b]">Hapus Ulasan</h3>
            <p class="text-xs text-[#8a7b6e]">
                Hapus ulasan dari <strong id="delete-customer-name" class="text-[#21140b]"></strong> untuk <strong id="delete-menu-name" class="text-[#8c5a3c]"></strong>?
            </p>
        </div>
        <div class="flex items-center gap-2 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-xs font-bold text-[#7b6558] py-2.5 rounded-xl cursor-pointer">
                Batal
            </button>
            <form id="delete-form" action="" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2.5 rounded-xl cursor-pointer shadow-xs">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleFilterPanel() {
        const panel = document.getElementById('filter-panel');
        if (panel) {
            panel.classList.toggle('hidden');
        }
    }

    function toggleDropdown(type) {
        const menu = document.getElementById(`menu-dropdown-${type}`);
        const arrow = document.getElementById(`arrow-${type}`);
        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');

        // Close all dropdowns first
        closeAllDropdowns();

        if (isHidden) {
            menu.classList.remove('hidden');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-popover').forEach(menu => {
            menu.classList.add('hidden');
        });
        document.querySelectorAll('[id^="arrow-"]').forEach(arrow => {
            arrow.style.transform = '';
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="dropdown-"]') && !e.target.closest('[id^="btn-dropdown-"]')) {
            closeAllDropdowns();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
            closeReplyModal();
            closeDeleteModal();
        }
    });

    function selectDropdownOption(type, value, label, iconType) {
        // 1. Update hidden input
        const input = document.getElementById(`input-${type}`);
        if (input) input.value = value;

        // 2. Update button label
        const labelEl = document.getElementById(`label-${type}`);
        if (labelEl) labelEl.textContent = label;

        // 3. Update button icon
        const iconEl = document.getElementById(`icon-${type}`);
        if (iconEl) {
            if (type === 'sort') {
                if (iconType === 'clock') {
                    iconEl.innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                } else if (iconType === 'history') {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                } else if (iconType === 'star-high') {
                    iconEl.innerHTML = '<svg class="h-4 w-4 fill-amber-400 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                } else {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>';
                }
            } else if (type === 'rating') {
                if (value === 'all') {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-amber-500 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                } else {
                    let starsHtml = '<div class="flex items-center gap-0.5 text-amber-400">';
                    for (let i = 1; i <= parseInt(value); i++) {
                        starsHtml += '<svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                    }
                    starsHtml += '</div>';
                    iconEl.innerHTML = starsHtml;
                }
            } else if (type === 'reply') {
                if (value === 'belum_dibalas') {
                    iconEl.innerHTML = '<span class="flex h-2.5 w-2.5 rounded-full bg-amber-500 ring-4 ring-amber-100"></span>';
                } else if (value === 'sudah_dibalas') {
                    iconEl.innerHTML = '<span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>';
                } else {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>';
                }
            } else if (type === 'menu') {
                if (value === 'all') {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>';
                } else {
                    iconEl.innerHTML = '<svg class="h-4 w-4 text-[#a2785d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>';
                }
            }
        }

        // 4. Update active classes on options in the menu
        document.querySelectorAll(`.dropdown-item-${type}`).forEach(btn => {
            const isSelected = btn.getAttribute('data-value') === String(value);
            const check = btn.querySelector('.check-icon');
            if (isSelected) {
                btn.className = `dropdown-item-${type} w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer bg-[#faf5f0] text-[#8b5a2b] font-bold`;
                if (check) check.classList.remove('hidden');
            } else {
                btn.className = `dropdown-item-${type} w-full flex items-center justify-between px-3.5 py-2.5 text-xs text-left transition-colors cursor-pointer text-[#4a3d35] hover:bg-[#faf7f2] hover:text-[#21140b] font-medium`;
                if (check) check.classList.add('hidden');
            }
        });

        // 5. Close dropdown
        closeAllDropdowns();
    }

    function openReplyModal(id, customerName, menuName, rating, comment, currentReply) {
        const modal = document.getElementById('reply-modal');
        const form = document.getElementById('reply-form');
        const deleteReplyForm = document.getElementById('delete-reply-form');
        const title = document.getElementById('reply-modal-title');
        const customerNameEl = document.getElementById('reply-customer-name');
        const menuNameEl = document.getElementById('reply-menu-name');
        const commentEl = document.getElementById('reply-customer-comment');
        const textarea = document.getElementById('balasan-input');
        const deleteReplyBtn = document.getElementById('btn-delete-reply');

        if (modal && form) {
            form.action = `/admin/ulasan/${id}/reply`;
            if (deleteReplyForm) {
                deleteReplyForm.action = `/admin/ulasan/${id}/reply`;
            }

            customerNameEl.textContent = customerName;
            menuNameEl.textContent = menuName;
            commentEl.textContent = `"${comment}"`;
            textarea.value = currentReply || '';

            if (currentReply) {
                title.textContent = 'Edit Balasan Ulasan';
                if (deleteReplyBtn) deleteReplyBtn.classList.remove('hidden');
            } else {
                title.textContent = 'Balas Ulasan Pelanggan';
                if (deleteReplyBtn) deleteReplyBtn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            setTimeout(() => textarea.focus(), 100);
        }
    }

    function closeReplyModal() {
        const modal = document.getElementById('reply-modal');
        if (modal) modal.classList.add('hidden');
    }

    function submitDeleteReply() {
        if (confirm('Hapus balasan ini?')) {
            const form = document.getElementById('delete-reply-form');
            if (form) form.submit();
        }
    }

    function openDeleteModal(id, customerName, menuName) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const customerNameEl = document.getElementById('delete-customer-name');
        const menuNameEl = document.getElementById('delete-menu-name');

        if (modal && form) {
            form.action = `/admin/ulasan/${id}`;
            if (customerNameEl) customerNameEl.textContent = customerName;
            if (menuNameEl) menuNameEl.textContent = menuName;
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        if (modal) modal.classList.add('hidden');
    }
</script>
@endsection
