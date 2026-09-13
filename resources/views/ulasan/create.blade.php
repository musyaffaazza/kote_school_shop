@extends('layouts.app')

@section('content')
<div class="w-full min-h-[calc(100dvh-200px)] py-10 sm:py-16 md:py-20 flex items-center justify-center px-4 sm:px-6">
    <div class="w-full max-w-md bg-white rounded-3xl sm:rounded-[2rem] border border-[#edd8cf]/60 shadow-[0_10px_35px_rgba(33,20,11,0.04)] p-6 sm:p-10 text-center">

        {{-- ALREADY REVIEWED STATE --}}
        @if($sudahDiulas)
            <div class="space-y-4 py-6">
                <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-full bg-[#e6f4ea]">
                    <svg class="h-8 w-8 text-[#137333]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#21140b] tracking-tight">
                    Ulasan Sudah Terkirim
                </h1>
                <p class="text-xs sm:text-sm font-medium text-[#8f7664] max-w-xs mx-auto leading-relaxed">
                    Kamu sudah memberikan ulasan untuk pesanan ini. Terima kasih atas masukanmu!
                </p>
                <div class="pt-2">
                    <a href="{{ route('home') }}#ulasan"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs sm:text-sm font-extrabold px-8 py-3.5 transition-all active:scale-[0.98] shadow-md shadow-[#21140b]/15 uppercase tracking-wider">
                        <span>Lihat Ulasan</span>
                        <span class="text-xs font-black">▸</span>
                    </a>
                </div>
            </div>
        @else
            {{-- FORM HEADER --}}
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#21140b] tracking-tight">
                    Berikan Ulasanmu
                </h1>
                <p class="text-xs sm:text-sm font-medium text-[#8f7664] max-w-xs mx-auto leading-relaxed">
                    Bagikan pengalamanmu menikmati kopi kami untuk membantu kami menjadi lebih baik.
                </p>
            </div>

            {{-- REVIEW FORM --}}
            <form id="form-ulasan" action="{{ isset($pesanan) ? route('ulasan.store', $pesanan->id_pesanan) : route('ulasan.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                {{-- PENILAIAN KESELURUHAN (STAR RATING) --}}
                <div class="space-y-3">
                    <p class="text-[11px] font-extrabold text-[#8f7664] tracking-[0.16em] uppercase">
                        PENILAIAN KESELURUHAN
                    </p>

                    <div class="flex items-center justify-center gap-2 sm:gap-3 py-1" id="star-rating-container">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                class="star-btn group p-1 transition-transform duration-150 hover:scale-115 active:scale-95 cursor-pointer focus:outline-none"
                                data-rating="{{ $i }}"
                                title="{{ $i }} Bintang"
                                aria-label="{{ $i }} Bintang"
                            >
                                <svg class="star-svg w-8 h-8 sm:w-9 sm:h-9 transition-colors duration-150 pointer-events-none" viewBox="0 0 24 24" fill="currentColor" style="color: #edd8cf;">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>

                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}" required />

                    <p id="star-error-msg" class="text-xs text-red-600 font-semibold hidden">
                        Silakan pilih penilaian bintang terlebih dahulu.
                    </p>
                    @error('rating')
                        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-[#ede6df]/70 my-6"></div>

                {{-- MENU YANG DIULAS --}}
                @if($menus->isNotEmpty())
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-[#21140b]">
                            Menu yang diulas
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            @foreach($menus as $menu)
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#21140b] text-white text-xs font-extrabold tracking-wide shadow-sm">
                                    {{ $menu->nama_menu }}
                                </span>
                                <input type="hidden" name="menu_ids[]" value="{{ $menu->id_menu }}" />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- KOMENTAR ATAU PESAN TAMBAHAN --}}
                <div class="space-y-2 text-left pt-2">
                    <label for="komentar" class="block text-xs font-bold text-[#21140b]">
                        Komentar atau Pesan Tambahan
                    </label>
                    <textarea
                        id="komentar"
                        name="komentar"
                        rows="4"
                        placeholder="Ceritakan pengalamanmu..."
                        required
                        class="w-full px-4 py-3.5 rounded-2xl border border-[#edd8cf] bg-[#faf5f0] text-base lg:text-sm text-[#21140b] placeholder:text-[#b5a49a] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#a2785d]/30 focus:border-[#a2785d] transition-all resize-none shadow-2xs"
                    >{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-3">
                    <button
                        type="submit"
                        id="submit-ulasan-btn"
                        class="w-full min-h-[50px] sm:min-h-[54px] rounded-full bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs sm:text-sm font-extrabold flex items-center justify-center gap-2 transition-all active:scale-[0.98] shadow-md shadow-[#21140b]/15 cursor-pointer uppercase tracking-wider"
                    >
                        <span>KIRIM ULASAN</span>
                        <span class="text-xs font-black">▸</span>
                    </button>
                </div>
            </form>
        @endif

    </div>
</div>

{{-- STAR RATING SCRIPT --}}
@if(!$sudahDiulas)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const errorMsg = document.getElementById('star-error-msg');
    const form = document.getElementById('form-ulasan');
    const container = document.getElementById('star-rating-container');

    const COLOR_ACTIVE = '#21140b';
    const COLOR_INACTIVE = '#edd8cf';

    let currentRating = parseInt(ratingInput.value) || 0;

    function renderStars(rating) {
        starButtons.forEach(btn => {
            const btnRating = parseInt(btn.getAttribute('data-rating'));
            const svg = btn.querySelector('.star-svg');
            if (svg) {
                if (btnRating <= rating) {
                    svg.style.color = COLOR_ACTIVE;
                } else {
                    svg.style.color = COLOR_INACTIVE;
                }
            }
        });
    }

    starButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function () {
            const hoverRating = parseInt(this.getAttribute('data-rating'));
            renderStars(hoverRating);
        });

        btn.addEventListener('click', function () {
            currentRating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = currentRating;
            if (errorMsg) errorMsg.classList.add('hidden');
            renderStars(currentRating);
        });
    });

    if (container) {
        container.addEventListener('mouseleave', function () {
            renderStars(currentRating);
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!currentRating || currentRating < 1) {
                e.preventDefault();
                if (errorMsg) errorMsg.classList.remove('hidden');
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Initial render
    renderStars(currentRating);
});
</script>
@endif
@endsection
