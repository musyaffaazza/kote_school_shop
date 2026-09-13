@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- TOP CONTAINER (Hero + Features Bar on Cream Background) --}}
    <div class="py-8 md:py-12 space-y-12 lg:space-y-16">

        {{-- HERO SECTION --}}
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8 lg:gap-12">
                <!-- Left Text Content -->
                <div class="space-y-6 text-left">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#edd8cf] bg-[#fbf1e8] px-3.5 py-1.5 text-xs font-bold tracking-wider text-[#a2785d] uppercase">
                        <span>☕ KOTE SCHOOL SHOP</span>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b] sm:text-4xl lg:text-5xl xl:text-6xl leading-[1.15]">
                        Nikmati Kopi Favoritmu
                    </h1>
                    <p class="max-w-xl text-base text-[#7b6558] sm:text-lg leading-relaxed">
                        Pesan Mudah, Cepat, dan Praktis. Rasakan sensasi kopi pilihan terbaik untuk menyemangati hari-harimu di sekolah.
                    </p>
                    <div class="pt-2">
                        <a href="#menu" class="inline-flex items-center justify-center gap-3 rounded-full bg-[#21140b] px-8 py-4 text-base font-bold text-white shadow-[0_12px_24px_rgba(33,20,11,0.18)] transition-all hover:bg-[#3d2a1f] active:scale-95 uppercase tracking-wider">
                            <span>Pesan Sekarang</span>
                            <x-icons.arrow-right class="h-5 w-5 text-white" />
                        </a>
                    </div>
                </div>

                <!-- Right Hero Image -->
                <div class="relative w-full max-w-xl mx-auto lg:max-w-none">
                    <div class="w-full aspect-[4/3] max-h-[460px] overflow-hidden rounded-[2rem] sm:rounded-[2.5rem] border border-[#edd8cf]/80 bg-white shadow-[0_25px_60px_rgba(33,20,11,0.12)]">
                        <img src="/images/hero_barista.jpg" alt="Kote School Shop Barista" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105" />
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES BAR (4 PILLARS) --}}
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="rounded-2xl sm:rounded-[2rem] border border-[#edd8cf]/60 bg-[#fbf1e8]/70 p-4 sm:p-8 shadow-sm">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4 md:gap-4">
                    <!-- Pillar 1 -->
                    <div class="flex flex-col items-center space-y-3 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <x-icons.star class="h-6 w-6" />
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#21140b]">Kualitas Terjamin</span>
                    </div>
                    <!-- Pillar 2 -->
                    <div class="flex flex-col items-center space-y-3 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <x-icons.wallet class="h-6 w-6" />
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#21140b]">Harga Terjangkau</span>
                    </div>
                    <!-- Pillar 3 -->
                    <div class="flex flex-col items-center space-y-3 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <x-icons.truck class="h-6 w-6" />
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#21140b]">Pengiriman Cepat</span>
                    </div>
                    <!-- Pillar 4 -->
                    <div class="flex flex-col items-center space-y-3 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <x-icons.shield class="h-6 w-6" />
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#21140b]">Pembayaran Aman</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- MENU FAVORIT SECTION (White Background with Soft Inset Shadow & Dividers) --}}
    <div class="bg-white border-y border-[#edd8cf]/50 shadow-[inset_0_12px_24px_rgba(33,20,11,0.025),inset_0_-12px_24px_rgba(33,20,11,0.025)] py-16 md:py-24">
        <section id="menu" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="flex items-center justify-between border-b border-[#edd8cf] pb-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="h-7 w-1.5 rounded-full bg-[#a2785d]"></div>
                    <h2 class="text-2xl font-bold tracking-tight text-[#21140b] sm:text-3xl">Menu Favorit</h2>
                </div>
                <a href="{{ route('menu') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#ab7a55] hover:text-[#21140b] transition-colors uppercase tracking-wider">
                    <span>Lihat Semua</span>
                    <x-icons.arrow-right class="h-4 w-4" />
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
                @foreach($favoritMenu as $item)
                    <div data-menu-name="{{ $item->nama_menu }}" data-menu-price="{{ $item->harga }}" data-menu-img="{{ $item->gambar }}" class="group relative flex flex-col justify-between overflow-hidden rounded-[1.5rem] border border-[#edd8cf]/60 bg-[#fdfaf7] p-4 shadow-[0_10px_30px_rgba(33,20,11,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(33,20,11,0.1)]">
                        <a href="{{ route('menu.show', $item) }}" class="aspect-square w-full overflow-hidden rounded-xl bg-[#fbf1e8] block relative">
                            <img src="{{ $item->gambar }}" alt="{{ $item->nama_menu }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            {{-- Stock Badge on Image --}}
                            <div class="absolute top-2.5 left-2.5">
                                @if($item->isOutOfStock())
                                    <span class="inline-flex items-center rounded-full bg-red-600/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                        Habis
                                    </span>
                                @elseif($item->isLowStock())
                                    <span class="inline-flex items-center rounded-full bg-amber-500/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                        Sisa {{ $item->stok }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-emerald-600/90 text-white px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider backdrop-blur-xs shadow-xs">
                                        Tersedia
                                    </span>
                                @endif
                            </div>
                        </a>
                        <div class="mt-4 flex flex-1 flex-col justify-between space-y-3">
                            <div>
                                <a href="{{ route('menu.show', $item) }}">
                                    <h3 class="text-base font-bold text-[#21140b] group-hover:text-[#a2785d] transition-colors line-clamp-1">{{ $item->nama_menu }}</h3>
                                </a>
                                <p class="mt-1 text-sm font-extrabold text-[#a2785d]">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">
                                    @if($item->isOutOfStock())
                                        <span class="text-red-600 font-bold">Stok Habis</span>
                                    @elseif($item->isLowStock())
                                        <span class="text-amber-600 font-bold">Stok terbatas ({{ $item->stok }} porsi)</span>
                                    @else
                                        <span class="text-[#8f7664]">Stok: {{ $item->stok }} porsi</span>
                                    @endif
                                </p>
                            </div>
                            @if($item->isOutOfStock())
                                <button disabled class="w-full rounded-xl bg-gray-100 border border-gray-200 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed uppercase tracking-wider text-center block">
                                    Habis
                                </button>
                            @else
                                <a href="{{ route('menu.show', $item) }}" class="w-full rounded-xl bg-white border border-[#edd8cf] py-2.5 text-xs font-bold text-[#21140b] transition-all hover:bg-[#21140b] hover:text-white cursor-pointer uppercase tracking-wider shadow-sm text-center block">
                                    Tambah Pesanan
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- ULASAN SECTION (Cream Background) --}}
    <div class="py-16 md:py-24">
        <section id="ulasan" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="rounded-2xl sm:rounded-[2.5rem] border border-[#edd8cf]/60 bg-[#fbf1e8]/80 p-5 sm:p-8 lg:p-12 shadow-[0_15px_40px_rgba(33,20,11,0.04)]">
                <!-- Header Section: Title Left, Rating Summary Right -->
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Left Heading -->
                    <div class="space-y-1.5 text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight text-[#21140b] sm:text-4xl">Apa Kata Mereka?</h2>
                        <p class="text-sm text-[#7b6558]">Pengalaman ngopi pelanggan KOTE SCHOOL SHOP</p>
                    </div>

                    <!-- Right Rating Box -->
                    <div class="inline-flex items-center gap-6 rounded-2xl border border-[#edd8cf]/80 bg-white px-6 py-4 shadow-md">
                        <div class="flex flex-col items-center leading-none">
                            <span class="text-3xl font-extrabold text-[#21140b]">{{ $avgRating }}</span>
                            <span class="mt-1 text-xs font-semibold text-[#8f7664]">Dari 5.0</span>
                        </div>
                        <div class="h-9 w-px bg-[#edd8cf]"></div>
                        <div class="space-y-1 pl-2">
                            <div class="flex items-center gap-0.5 text-[#a2785d]">
                                @php $roundedAvg = round((float) $avgRating); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <x-icons.star :filled="$i <= $roundedAvg" class="h-4 w-4 text-[#a2785d]" />
                                @endfor
                            </div>
                            <p class="text-xs font-semibold text-[#7b6558]">{{ $totalUlasan }} Ulasan Total</p>
                        </div>
                    </div>
                </div>

                @if(count($ulasanList) === 0)
                    {{-- Empty State --}}
                    <div class="mt-12 flex flex-col items-center justify-center py-12 text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#f4e6da]">
                            <svg class="h-10 w-10 text-[#a2785d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-[#21140b]">Belum Ada Ulasan</h3>
                        <p class="mt-2 max-w-sm text-sm text-[#7b6558]">Jadilah yang pertama memberikan ulasan! Pesan kopi favoritmu dan bagikan pengalamanmu.</p>
                    </div>
                @else
                    {{-- Reviews Grid --}}
                    <div id="ulasan-grid" class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($ulasanList as $index => $ulasan)
                            <div class="ulasan-card flex flex-col rounded-2xl border border-[#edd8cf]/60 bg-white p-4 sm:p-6 shadow-[0_6px_22px_rgba(33,20,11,0.03)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_15px_35px_rgba(33,20,11,0.08)]" @if($index >= 8) style="display: none;" @endif>
                                <!-- User Header -->
                                <div class="flex items-center gap-3.5">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-sm font-bold text-[#21140b] shadow-sm">
                                        {{ $ulasan['avatar'] }}
                                    </div>
                                    <div class="min-w-0 text-left">
                                        <h3 class="text-sm font-bold text-[#21140b] leading-snug truncate">{{ $ulasan['nama'] }}</h3>
                                        <p class="mt-0.5 text-[11px] text-[#8f7664]">{{ $ulasan['waktu'] }}</p>
                                    </div>
                                </div>

                                <!-- Star Rating -->
                                <div class="flex items-center gap-1 mt-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $ulasan['rating'])
                                            <x-icons.star :filled="true" class="h-4 w-4 text-[#a2785d]" />
                                        @else
                                            <x-icons.star :filled="false" class="h-4 w-4 text-[#a2785d]" />
                                        @endif
                                    @endfor
                                </div>

                                <!-- Review Content -->
                                <p class="mt-4 text-sm text-[#4f4136] leading-relaxed flex-1 text-left">
                                    "{{ $ulasan['teks'] }}"
                                </p>

                                {{-- Store / Admin Reply if exists --}}
                                @if(!empty($ulasan['balasan']))
                                    <div class="mt-3 rounded-xl bg-[#faf7f2] border border-[#edd8cf]/80 p-2.5 text-left text-xs space-y-1">
                                        <div class="flex items-center gap-1 text-[10px] font-extrabold text-[#8c5a3c]">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            <span>Balasan Kote Coffee</span>
                                        </div>
                                        <p class="text-[11px] text-[#4a3d35] font-semibold italic">"{{ $ulasan['balasan'] }}"</p>
                                    </div>
                                @endif

                                <!-- Purchased Menu Badge -->
                                <div class="mt-5 inline-flex items-center gap-1.5 self-start rounded-full bg-[#faf2eb] px-3.5 py-1.5 text-[11px] font-semibold text-[#5a4d42]">
                                    <span class="h-2 w-2 rounded-full bg-[#8c5a3c]"></span>
                                    <span>{{ $ulasan['menu'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Show More / Show Less Button --}}
                    @if(count($ulasanList) > 8)
                        <div class="mt-8 flex justify-center">
                            <button id="btn-show-more-ulasan" type="button" onclick="toggleMoreUlasan()" class="inline-flex items-center gap-2 rounded-full border border-[#edd8cf] bg-white px-6 py-2.5 text-sm font-bold text-[#21140b] shadow-sm transition-all hover:bg-[#fbf1e8] hover:shadow-md active:scale-[0.98] cursor-pointer">
                                <span id="show-more-text">Lihat Semua ({{ count($ulasanList) }})</span>
                                <svg id="show-more-icon" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        <script>
                            function toggleMoreUlasan() {
                                const cards = document.querySelectorAll('.ulasan-card');
                                const btn = document.getElementById('show-more-text');
                                const icon = document.getElementById('show-more-icon');
                                const hiddenCards = Array.from(cards).filter((c, i) => i >= 8);
                                const isHidden = hiddenCards[0] && hiddenCards[0].style.display === 'none';

                                hiddenCards.forEach(card => {
                                    card.style.display = isHidden ? '' : 'none';
                                });

                                if (isHidden) {
                                    btn.textContent = 'Tampilkan Lebih Sedikit';
                                    icon.style.transform = 'rotate(180deg)';
                                } else {
                                    btn.textContent = 'Lihat Semua ({{ count($ulasanList) }})';
                                    icon.style.transform = '';
                                    document.getElementById('ulasan').scrollIntoView({ behavior: 'smooth' });
                                }
                            }
                        </script>
                    @endif
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
