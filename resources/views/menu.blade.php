@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">Menu</span>
        </nav>
    </div>

    {{-- SEARCH & FILTER HEADER --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Live Search Input -->
            <div class="flex flex-1 w-full gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-[#a2785d]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text" id="menu-search-input" placeholder="Cari menu..." class="w-full min-h-[50px] pl-11 pr-4 rounded-xl bg-white border border-[#edd8cf] text-sm text-[#21140b] placeholder:text-[#b5a49a] placeholder:font-medium focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 shadow-none transition-all" />
                </div>
                <button type="button" id="menu-search-btn" class="min-h-[50px] px-6 rounded-xl bg-[#21140b] text-white text-sm font-bold flex items-center gap-2 hover:bg-[#3d2a1f] active:scale-95 transition-all shadow-md cursor-pointer whitespace-nowrap uppercase tracking-wider">
                    Cari
                </button>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <!-- Dropdown Category -->
                <div class="relative w-full md:w-56">
                    <select id="menu-category-select" class="w-full min-h-[50px] pl-4 pr-10 rounded-xl bg-white border border-[#edd8cf] text-sm text-[#21140b] font-semibold appearance-none focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 shadow-none cursor-pointer transition-all">
                        <option value="semua">Semua Kategori</option>
                        <option value="Coffee">Coffee</option>
                        <option value="Non Coffee">Non Coffee</option>
                        <option value="Tea">Tea</option>
                        <option value="Snack">Snack</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-[#8f7664]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Button -->
                <button id="menu-filter-btn" class="min-h-[50px] px-6 rounded-xl bg-[#21140b] text-white text-sm font-bold flex items-center gap-2 hover:bg-[#3d2a1f] active:scale-95 transition-all shadow-md cursor-pointer whitespace-nowrap uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </div>

    {{-- CATEGORY TABS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <div class="flex gap-2 sm:gap-6 border-b border-[#edd8cf] overflow-x-auto no-scrollbar scroll-smooth">
            <button data-tab="semua" class="menu-tab-btn px-4 pb-3 text-sm font-bold border-b-2 border-[#a2785d] text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Semua
            </button>
            <button data-tab="Coffee" class="menu-tab-btn px-4 pb-3 text-sm font-semibold border-b-2 border-transparent text-[#7b6558] hover:text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Coffee
            </button>
            <button data-tab="Non Coffee" class="menu-tab-btn px-4 pb-3 text-sm font-semibold border-b-2 border-transparent text-[#7b6558] hover:text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Non Coffee
            </button>
            <button data-tab="Tea" class="menu-tab-btn px-4 pb-3 text-sm font-semibold border-b-2 border-transparent text-[#7b6558] hover:text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Tea
            </button>
            <button data-tab="Snack" class="menu-tab-btn px-4 pb-3 text-sm font-semibold border-b-2 border-transparent text-[#7b6558] hover:text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Snack
            </button>
            <button data-tab="Dessert" class="menu-tab-btn px-4 pb-3 text-sm font-semibold border-b-2 border-transparent text-[#7b6558] hover:text-[#21140b] transition-all whitespace-nowrap cursor-pointer">
                Dessert
            </button>
        </div>
    </div>

    {{-- MENU GRID SECTION --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-8">
        <div id="menu-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($menus as $menu)
                <div data-menu-card 
                     data-menu-name="{{ strtolower($menu->nama_menu) }}" 
                     data-menu-category="{{ $menu->kategori }}" 
                     class="group relative flex flex-col justify-between overflow-hidden rounded-[1.5rem] border border-[#edd8cf]/60 bg-white p-4 shadow-[0_10px_30px_rgba(33,20,11,0.03)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_20px_40px_rgba(33,20,11,0.08)]">
                    
                    <!-- Image Wrapper -->
                    <a href="{{ route('menu.show', $menu) }}" class="relative aspect-square w-full overflow-hidden rounded-xl bg-[#fbf1e8] shadow-inner block">
                        <img src="{{ $menu->gambar }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        
                        {{-- Stock Status Badge on Image --}}
                        <div class="absolute top-2.5 left-2.5">
                            @if($menu->isOutOfStock())
                                <span class="inline-flex items-center rounded-full bg-red-600/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                    Habis
                                </span>
                            @elseif($menu->isLowStock())
                                <span class="inline-flex items-center rounded-full bg-amber-500/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                    Sisa {{ $menu->stok }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-emerald-600/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                    Tersedia
                                </span>
                            @endif
                        </div>
                    </a>
                    
                    <!-- Text Info & Actions -->
                    <div class="mt-4 flex flex-1 flex-col justify-between space-y-3.5 text-left">
                        <div class="space-y-1">
                            <a href="{{ route('menu.show', $menu) }}" class="block">
                                <h3 class="text-[15px] font-bold text-[#21140b] group-hover:text-[#a2785d] transition-colors line-clamp-1">
                                    {{ $menu->nama_menu }}
                                </h3>
                            </a>
                            <p class="text-sm font-extrabold text-[#ab7a55]">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </p>
                            
                            <div class="flex items-center justify-between gap-1 mt-1">
                                <!-- Category Badge -->
                                <span class="inline-block rounded-full bg-[#fbf1e8] px-2.5 py-0.5 text-[10px] font-bold text-[#a2785d]">
                                    {{ $menu->kategori }}
                                </span>

                                <!-- Stock Info Text -->
                                @if($menu->isOutOfStock())
                                    <span class="text-[10px] font-bold text-red-600">Stok Habis</span>
                                @elseif($menu->isLowStock())
                                    <span class="text-[10px] font-bold text-amber-600">Stok: {{ $menu->stok }}</span>
                                @else
                                    <span class="text-[10px] font-semibold text-[#8f7664]">Stok: {{ $menu->stok }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Order Button -->
                        @if($menu->isOutOfStock())
                            <button disabled class="w-full rounded-xl bg-gray-100 border border-gray-200 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed uppercase tracking-wider text-center block">
                                Habis
                            </button>
                        @else
                            <a href="{{ route('menu.show', $menu) }}" class="w-full rounded-xl bg-white border border-[#edd8cf] py-2.5 text-xs font-bold text-[#21140b] transition-all hover:bg-[#21140b] hover:text-white cursor-pointer uppercase tracking-wider shadow-sm active:scale-95 text-center block">
                                Tambah Pesanan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Database Empty State -->
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#21140b]">Menu Belum Tersedia</h3>
                    <p class="text-sm text-[#7b6558] mt-1">Silakan jalankan seeder terlebih dahulu.</p>
                </div>
            @endforelse
            
            <!-- JavaScript Dynamic Empty State -->
            <div id="empty-state-js" class="hidden col-span-full py-16 text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#21140b]">Menu Tidak Ditemukan</h3>
                <p class="text-sm text-[#7b6558] mt-1">Tidak ada menu yang sesuai dengan kriteria pencarian Anda.</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for horizontal scroll with no scrollbar */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('menu-search-input');
    const categorySelect = document.getElementById('menu-category-select');
    const tabButtons = document.querySelectorAll('.menu-tab-btn');
    const menuCards = document.querySelectorAll('[data-menu-card]');
    const emptyState = document.getElementById('empty-state-js');

    let activeCategory = 'semua';
    let searchQuery = '';

    // Function to apply filters
    function applyFilters() {
        let visibleCount = 0;

        menuCards.forEach(card => {
            const name = card.getAttribute('data-menu-name');
            const category = card.getAttribute('data-menu-category');

            const matchesCategory = (activeCategory === 'semua' || category === activeCategory);
            const matchesSearch = (searchQuery === '' || name.includes(searchQuery));

            if (matchesCategory && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && menuCards.length > 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    // Search Input Listener
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchQuery = this.value.trim().toLowerCase();
            applyFilters();
        });
    }

    // Search Button Listener
    const searchBtn = document.getElementById('menu-search-btn');
    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', function () {
            searchQuery = searchInput.value.trim().toLowerCase();
            applyFilters();
        });
    }

    // Category Dropdown Listener
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            activeCategory = this.value;
            
            // Sync with tabs
            tabButtons.forEach(btn => {
                const tabVal = btn.getAttribute('data-tab');
                if (tabVal === activeCategory) {
                    btn.classList.add('text-[#21140b]', 'border-[#a2785d]', 'font-bold');
                    btn.classList.remove('text-[#7b6558]', 'font-semibold', 'border-transparent');
                } else {
                    btn.classList.remove('text-[#21140b]', 'border-[#a2785d]', 'font-bold');
                    btn.classList.add('text-[#7b6558]', 'font-semibold', 'border-transparent');
                }
            });

            applyFilters();
        });
    }

    // Category Tabs Listener
    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            activeCategory = this.getAttribute('data-tab');

            // Sync with select dropdown
            if (categorySelect) {
                categorySelect.value = activeCategory;
            }

            // Update active tab styles
            tabButtons.forEach(btn => {
                btn.classList.remove('text-[#21140b]', 'border-[#a2785d]', 'font-bold');
                btn.classList.add('text-[#7b6558]', 'font-semibold', 'border-transparent');
            });
            this.classList.add('text-[#21140b]', 'border-[#a2785d]', 'font-bold');
            this.classList.remove('text-[#7b6558]', 'font-semibold', 'border-transparent');

            applyFilters();
        });
    });

    // Check if query parameters are present on load and set initial state
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    const searchParam = urlParams.get('search');

    if (categoryParam) {
        // Find matching tab or select
        const matchingTab = Array.from(tabButtons).find(btn => btn.getAttribute('data-tab').toLowerCase() === categoryParam.toLowerCase());
        if (matchingTab) {
            matchingTab.click();
        } else if (categorySelect) {
            // Find option case-insensitively
            const option = Array.from(categorySelect.options).find(opt => opt.value.toLowerCase() === categoryParam.toLowerCase());
            if (option) {
                categorySelect.value = option.value;
                categorySelect.dispatchEvent(new Event('change'));
            }
        }
    }

    if (searchParam) {
        if (searchInput) {
            searchInput.value = searchParam;
            searchQuery = searchParam.trim().toLowerCase();
            applyFilters();
        }
    }
});
</script>
@endsection
