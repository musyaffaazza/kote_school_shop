@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">Promo</span>
        </nav>
    </div>

    {{-- HEADER BANNER IMAGE --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <div class="overflow-hidden rounded-[2rem] border border-[#edd8cf] shadow-sm">
            <img src="/images/Promo/SemesterBaru.jpeg" alt="Semester Baru, Semangat Baru!" class="w-full h-auto object-cover" />
        </div>
    </div>

    {{-- EXCLUSIVE PROMO SECTION --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-10">
        <div class="border-b border-[#edd8cf] pb-4 mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-[#21140b] sm:text-3xl">Penawaran Eksklusif</h2>
            <p class="text-sm text-[#7b6558] mt-1">Temukan berbagai promo menarik untuk menemani waktu santaimu di Kote Coffee.</p>
        </div>

        <!-- Promo Cards Grid (2 Columns Horizontal Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($promos as $promo)
                @php
                    $imageUrl = $promo->gambar ? asset($promo->gambar) : '/images/Promo/SemesterBaru.jpeg';
                @endphp

                <div class="flex flex-col sm:flex-row overflow-hidden rounded-2xl sm:rounded-[1.5rem] border border-[#edd8cf]/60 bg-white shadow-[0_8px_30px_rgba(33,20,11,0.03)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_15px_35px_rgba(33,20,11,0.06)]">
                    
                    <!-- Left Image (16:9 on mobile, 1:1 on tablet/desktop) -->
                    <div class="w-full sm:w-1/3 aspect-[16/9] sm:aspect-square relative overflow-hidden bg-[#fbf1e8] shrink-0">
                        <img src="{{ $imageUrl }}" alt="{{ $promo->nama_promo }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105" />
                    </div>

                    <!-- Right Content -->
                    <div class="p-4 sm:p-6 flex-1 flex flex-col justify-between text-left space-y-4">
                        <div class="space-y-2">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#ab7a55]">
                                {{ $promo->jenis_label }}
                            </span>

                            <h3 class="text-base sm:text-lg font-bold text-[#21140b] leading-snug">
                                {{ $promo->nama_promo }}
                            </h3>

                            @if($promo->deskripsi)
                                <p class="text-xs text-[#7b6558] leading-relaxed">
                                    {{ $promo->deskripsi }}
                                </p>
                            @endif

                            {{-- Voucher Code Box if available --}}
                            @if($promo->kode_voucher)
                                <div class="mt-2 flex items-center justify-between gap-2 p-2 rounded-xl bg-[#faf7f2] border border-[#edd8cf]/80">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">KODE:</span>
                                        <span class="font-mono text-xs font-black text-[#21140b] tracking-wider truncate select-all">{{ $promo->kode_voucher }}</span>
                                    </div>
                                    <button type="button" onclick="copyVoucherCode('{{ $promo->kode_voucher }}', this)" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#21140b] text-white hover:bg-[#3d2a1f] text-[10px] font-bold transition-all cursor-pointer shadow-xs active:scale-95 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span class="btn-copy-text">Salin</span>
                                    </button>
                                </div>
                            @endif

                            {{-- Periode Berlaku --}}
                            <p class="text-xs font-semibold text-[#7b6558] flex items-center gap-1.5 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-[#a2785d] shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <span>{{ $promo->periode_formatted }}</span>
                            </p>
                        </div>

                        <div class="pt-2">
                            @if($promo->kode_voucher)
                                <button type="button" onclick="usePromoCode('{{ $promo->kode_voucher }}')" class="w-full rounded-xl bg-[#21140b] py-2.5 text-xs font-bold text-white transition-all hover:bg-[#3d2a1f] active:scale-95 cursor-pointer uppercase tracking-wider shadow-sm text-center block">
                                    Gunakan Promo
                                </button>
                            @else
                                <a href="{{ route('menu') }}" class="w-full rounded-xl bg-[#21140b] py-2.5 text-xs font-bold text-white transition-all hover:bg-[#3d2a1f] active:scale-95 cursor-pointer uppercase tracking-wider shadow-sm text-center block">
                                    Jelajahi Menu
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#faf5f0] text-[#a2785d] border border-[#ede6df] shadow-sm mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#21140b]">Belum Ada Promo Aktif</h3>
                    <p class="text-xs text-[#8f7664] mt-1 max-w-md mx-auto">Saat ini belum ada promo yang tersedia. Pantau terus halaman ini untuk mendapatkan penawaran spesial berikutnya!</p>
                    <div class="mt-6">
                        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#21140b] px-6 py-3 text-xs font-bold text-white hover:bg-[#3d2a1f] transition-all uppercase tracking-wider shadow-sm">
                            Jelajahi Menu
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function usePromoCode(code) {
    if (code) {
        localStorage.setItem('koteshop_applied_promo', code);
        
        let hasCartItems = false;
        try {
            if (typeof getCart === 'function') {
                hasCartItems = getCart().length > 0;
            } else {
                const saved = localStorage.getItem('koteshop_cart');
                hasCartItems = saved && JSON.parse(saved).length > 0;
            }
        } catch (e) {
            hasCartItems = false;
        }

        if (typeof showToast === 'function') {
            showToast(`Kode Promo "${code}" berhasil diaktifkan!`, 'success');
        }

        setTimeout(() => {
            if (hasCartItems) {
                window.location.href = "{{ route('checkout') }}?code=" + encodeURIComponent(code);
            } else {
                window.location.href = "{{ route('menu') }}?promo=" + encodeURIComponent(code);
            }
        }, 500);
    }
}

function copyVoucherCode(code, btn) {
    localStorage.setItem('koteshop_applied_promo', code);

    if (!navigator.clipboard) {
        const tempInput = document.createElement('input');
        tempInput.value = code;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        triggerCopyFeedback(code, btn);
        return;
    }

    navigator.clipboard.writeText(code).then(() => {
        triggerCopyFeedback(code, btn);
    }).catch(() => {
        triggerCopyFeedback(code, btn);
    });
}

function triggerCopyFeedback(code, btn) {
    const span = btn.querySelector('.btn-copy-text');
    const originalText = span ? span.textContent : 'Salin';
    if (span) span.textContent = 'Tersalin!';
    btn.classList.remove('bg-[#21140b]');
    btn.classList.add('bg-emerald-700');

    if (typeof showToast === 'function') {
        showToast(`Kode "${code}" berhasil disalin & diaktifkan!`);
    }

    setTimeout(() => {
        if (span) span.textContent = originalText;
        btn.classList.remove('bg-emerald-700');
        btn.classList.add('bg-[#21140b]');
    }, 2000);
}
</script>
@endsection
