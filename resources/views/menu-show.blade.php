@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <a href="{{ route('menu') }}" class="hover:text-[#21140b] transition-colors">Menu</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">{{ $menu->nama_menu }}</span>
        </nav>
    </div>

    {{-- PRODUCT DETAIL --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-start">

            {{-- LEFT: Image Gallery --}}
            <div class="space-y-4 max-w-[480px] md:max-w-none mx-auto w-full">
                {{-- Main Image --}}
                <div id="main-image-wrapper" class="aspect-square w-full overflow-hidden rounded-[2rem] border border-[#edd8cf]/60 bg-[#fbf1e8] shadow-sm">
                    <img id="main-image" src="{{ $menu->gambar }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover transition-opacity duration-300" />
                </div>

                {{-- Thumbnail Gallery --}}
                @php
                    $allImages = [];
                    if (str_starts_with($menu->gambar, '/images/')) {
                        $dirName = dirname($menu->gambar);
                        $absoluteDir = public_path($dirName);
                        if (file_exists($absoluteDir) && is_dir($absoluteDir)) {
                            $files = scandir($absoluteDir);
                            foreach ($files as $file) {
                                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'])) {
                                    $allImages[] = $dirName . '/' . $file;
                                }
                            }
                        }
                    } elseif (str_starts_with($menu->gambar, '/storage/menu/')) {
                        $folderName = dirname(str_replace('/storage/', '', $menu->gambar));
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folderName)) {
                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files($folderName);
                            foreach ($files as $file) {
                                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'])) {
                                    $allImages[] = '/storage/' . $file;
                                }
                            }
                        }
                    }

                    // Fallback to only the main image if no local images found
                    if (empty($allImages)) {
                        $allImages = [$menu->gambar];
                    } else {
                        // Ensure the main image is the first one in the list
                        $mainIndex = array_search($menu->gambar, $allImages);
                        if ($mainIndex !== false) {
                            unset($allImages[$mainIndex]);
                            array_unshift($allImages, $menu->gambar);
                        }
                    }
                @endphp

                @if(count($allImages) > 1)
                    @if(count($allImages) > 3)
                        <div class="grid grid-cols-4 gap-3">
                    @else
                        <div class="grid grid-cols-3 gap-3">
                    @endif
                        @foreach($allImages as $index => $img)
                            <button type="button"
                                    data-thumb-btn
                                    data-img="{{ $img }}"
                                    class="aspect-square overflow-hidden rounded-xl border-2 transition-all duration-200 cursor-pointer {{ $index === 0 ? 'border-[#a2785d] ring-2 ring-[#a2785d]/20' : 'border-[#edd8cf]/60 hover:border-[#c5ab98]' }}">
                                <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}" class="h-full w-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT: Product Info & Options --}}
            <div class="space-y-6 text-left">
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Category Badge --}}
                    <span class="inline-block rounded-full bg-[#fbf1e8] px-4 py-1 text-[11px] font-extrabold text-[#a2785d] uppercase tracking-wider">
                        {{ $menu->kategori }}
                    </span>

                    {{-- Stock Badge --}}
                    @if($menu->isOutOfStock())
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 border border-red-200 px-3.5 py-1 text-xs font-bold text-red-700">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            Stok Habis
                        </span>
                    @elseif($menu->isLowStock())
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3.5 py-1 text-xs font-bold text-amber-700">
                            <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Stok Terbatas (sisa {{ $menu->stok }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-bold text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Stok: {{ $menu->stok }} porsi tersedia
                        </span>
                    @endif
                </div>

                {{-- Name --}}
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#21140b] tracking-tight leading-tight">
                    {{ $menu->nama_menu }}
                </h1>

                {{-- Description --}}
                <p class="text-sm text-[#7b6558] leading-relaxed max-w-lg">
                    {{ $menu->deskripsi ?? 'Perpaduan espresso premium dengan susu Fresh yang lembut dan creamy, menciptakan keseimbangan rasa yang sempurna.' }}
                </p>

                {{-- Price --}}
                <p class="text-2xl sm:text-3xl font-extrabold text-[#a2785d] tracking-tight">
                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                </p>

                {{-- Divider --}}
                <div class="border-t border-[#edd8cf]"></div>

                {{-- LEVEL GULA (hidden for Americano) --}}
                @if(strtolower($menu->nama_menu) !== 'americano')
                <div class="space-y-3">
                    <p class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Level Gula</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['0%', '50%', '100%'] as $level)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="level_gula" value="{{ $level }}" class="peer sr-only" {{ $level === '100%' ? 'checked' : '' }} />
                                <span class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2.5 text-xs font-bold transition-all">
                                    <span class="h-2.5 w-2.5 rounded-full transition-all"></span>
                                    {{ $level }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- LEVEL ES --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Level Es</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Normal', 'Less', 'No Ice'] as $level)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="level_es" value="{{ $level }}" class="peer sr-only" {{ $level === 'Normal' ? 'checked' : '' }} />
                                <span class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2.5 text-xs font-bold transition-all">
                                    <span class="h-2.5 w-2.5 rounded-full transition-all"></span>
                                    {{ $level }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- TOPPINGS --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Toppings</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @php
                            $toppings = [
                                ['nama' => 'Extra Aren', 'harga' => 1000],
                                ['nama' => 'Extra Shot', 'harga' => 1000],
                                ['nama' => 'Extra Susu', 'harga' => 1000],
                                ['nama' => 'Oat Milk', 'harga' => 5000],
                            ];
                        @endphp
                        @foreach($toppings as $topping)
                            <label class="flex items-center justify-between rounded-xl border border-[#edd8cf] bg-white px-4 py-3 cursor-pointer transition-all hover:border-[#c5ab98] has-[:checked]:border-[#a2785d] has-[:checked]:bg-[#fbf1e8]">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="toppings[]" value="{{ $topping['nama'] }}" data-harga="{{ $topping['harga'] }}" class="h-4 w-4 rounded border-[#edd8cf] text-[#a2785d] focus:ring-[#a2785d]/30 accent-[#a2785d]" />
                                    <span class="text-xs font-bold text-[#21140b]">{{ $topping['nama'] }}</span>
                                </div>
                                <span class="text-xs font-bold text-[#a2785d]">+Rp{{ number_format($topping['harga'], 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Catatan</p>
                    <textarea name="catatan" id="menu-catatan" rows="3" placeholder="Contoh: tidak pakai gula" class="w-full rounded-xl border border-[#edd8cf] bg-[#f4ece6]/40 px-4 py-3 text-base lg:text-sm text-[#21140b] placeholder:text-[#b5a49a] focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 resize-none transition-all"></textarea>
                </div>

                {{-- QUANTITY + ADD TO CART --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 rounded-3xl bg-white p-4 border border-[#edd8cf]/40 shadow-[0_15px_30px_rgba(33,20,11,0.05)]">
                    {{-- Quantity Selector --}}
                    <div class="flex items-center justify-center rounded-full border border-[#edd8cf] bg-[#fbf1e8]/30 px-2 py-1 shrink-0">
                        <button type="button" id="qty-minus" {{ $menu->isOutOfStock() ? 'disabled' : '' }} class="flex h-10 w-10 items-center justify-center text-lg font-bold text-[#4a3d35] hover:bg-[#fbf1e8] rounded-full transition-colors cursor-pointer select-none disabled:opacity-40 disabled:cursor-not-allowed">
                            −
                        </button>
                        <span id="qty-value" class="flex h-10 w-10 items-center justify-center text-sm font-bold text-[#21140b] select-none">
                            {{ $menu->isOutOfStock() ? '0' : '1' }}
                        </span>
                        <button type="button" id="qty-plus" {{ $menu->isOutOfStock() ? 'disabled' : '' }} class="flex h-10 w-10 items-center justify-center text-lg font-bold text-[#4a3d35] hover:bg-[#fbf1e8] rounded-full transition-colors cursor-pointer select-none disabled:opacity-40 disabled:cursor-not-allowed">
                            +
                        </button>
                    </div>

                    {{-- Add to Cart Button --}}
                    @if($menu->isOutOfStock())
                        <button type="button" disabled class="flex-1 min-h-[50px] rounded-full bg-gray-200 text-gray-400 text-sm font-bold cursor-not-allowed tracking-wide">
                            Stok Habis
                        </button>
                    @else
                        <button type="button" id="add-to-cart-btn"
                                data-menu-id="{{ $menu->id_menu }}"
                                data-menu-name="{{ $menu->nama_menu }}"
                                data-menu-price="{{ $menu->harga }}"
                                data-menu-image="{{ $menu->gambar }}"
                                data-menu-stock="{{ $menu->stok }}"
                                class="flex-1 min-h-[50px] rounded-full bg-[#312217] text-white text-sm font-bold transition-all hover:bg-[#4a3525] active:scale-[0.98] cursor-pointer shadow-md shadow-[#312217]/10 tracking-wide">
                            Tambah ke Keranjang
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom radio pill styling matching screenshot */
    input[type="radio"] + span {
        background-color: #f4ece6; /* Unchecked background */
        border-color: transparent;
        color: #7b6558; /* Unchecked text color */
        transition: all 0.2s ease-in-out;
    }
    input[type="radio"] + span > span {
        border: 1px solid #b5a49a; /* Unchecked circle border */
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    input[type="radio"]:checked + span {
        background-color: #ffffff; /* Checked background */
        border-color: #a2785d; /* Checked border color */
        color: #21140b; /* Checked text color */
        box-shadow: 0 4px 12px rgba(162, 120, 93, 0.08);
    }
    input[type="radio"]:checked + span > span {
        background-color: #a2785d; /* Checked circle fill */
        border-color: #a2785d;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ── Thumbnail gallery ── */
    var mainImg = document.getElementById('main-image');
    var thumbBtns = document.querySelectorAll('[data-thumb-btn]');

    thumbBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var newSrc = btn.getAttribute('data-img');
            mainImg.style.opacity = '0';
            setTimeout(function () {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
            }, 150);

            thumbBtns.forEach(function (b) {
                b.classList.remove('border-[#a2785d]', 'ring-2', 'ring-[#a2785d]/20');
                b.classList.add('border-[#edd8cf]/60');
            });
            btn.classList.add('border-[#a2785d]', 'ring-2', 'ring-[#a2785d]/20');
            btn.classList.remove('border-[#edd8cf]/60');
        });
    });

    /* ── Quantity selector & Stock Limits ── */
    var qtyValue = document.getElementById('qty-value');
    var qtyMinus = document.getElementById('qty-minus');
    var qtyPlus  = document.getElementById('qty-plus');
    var maxStock = {{ (int) $menu->stok }};
    var isOutOfStock = {{ $menu->isOutOfStock() ? 'true' : 'false' }};
    var qty = isOutOfStock ? 0 : 1;

    if (qtyMinus && qtyPlus && qtyValue && !isOutOfStock) {
        qtyMinus.addEventListener('click', function () {
            if (qty > 1) {
                qty--;
                qtyValue.textContent = qty;
            }
        });
        qtyPlus.addEventListener('click', function () {
            if (qty < maxStock) {
                qty++;
                qtyValue.textContent = qty;
            } else {
                if (typeof showToast === 'function') {
                    showToast(`Jumlah pesanan mencapai batas stok tersedia (${maxStock} porsi).`, 'warning');
                }
            }
        });
    }

    /* ── Add to Cart Action ── */
    var addToCartBtn = document.getElementById('add-to-cart-btn');
    if (addToCartBtn && !isOutOfStock) {
        addToCartBtn.addEventListener('click', function () {
            if (qty <= 0) {
                if (typeof showToast === 'function') {
                    showToast('Stok menu ini sedang habis.', 'error');
                }
                return;
            }

            // Get selected sugar level (if available)
            var sugarInput = document.querySelector('input[name="level_gula"]:checked');
            var sugar = sugarInput ? sugarInput.value : '';

            // Get selected ice level
            var iceInput = document.querySelector('input[name="level_es"]:checked');
            var ice = iceInput ? iceInput.value : '';

            // Get selected toppings
            var toppings = [];
            var toppingsPrice = 0;
            document.querySelectorAll('input[name="toppings[]"]:checked').forEach(function (cb) {
                toppings.push(cb.value);
                toppingsPrice += parseInt(cb.getAttribute('data-harga') || 0);
            });

            // Get notes
            var notesInput = document.getElementById('menu-catatan');
            var notes = notesInput ? notesInput.value.trim() : '';

            // Construct cart item with maxStock
            var item = {
                id: addToCartBtn.getAttribute('data-menu-id'),
                name: addToCartBtn.getAttribute('data-menu-name'),
                image: addToCartBtn.getAttribute('data-menu-image'),
                price: parseInt(addToCartBtn.getAttribute('data-menu-price')) + toppingsPrice,
                qty: qty,
                maxStock: maxStock,
                sugar: sugar,
                ice: ice,
                toppings: toppings,
                notes: notes
            };

            // Call global helper
            if (typeof addToCart === 'function') {
                addToCart(item);
            } else {
                console.error('Global addToCart function not found');
            }
        });
    }
});
</script>
@endsection
