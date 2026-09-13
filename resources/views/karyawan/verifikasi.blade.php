@extends('layouts.karyawan')

@section('page-title', 'Verifikasi Pembayaran')

@section('header-search')
<div class="hidden md:block flex-1 max-w-xs mx-4 relative">
    <form action="{{ route('karyawan.verifikasi') }}" method="GET">
        <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Order..." class="w-full bg-[#ebdcd0]/75 border-transparent text-xs rounded-xl pl-10 pr-4 py-2 text-[#21140b] placeholder-[#8f7664]/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 transition-all" />
    </form>
</div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- HEADER ROW --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-extrabold text-[#21140b]">Pesanan Menunggu Verifikasi</h3>
            <span class="rounded-lg bg-[#f0a070]/20 text-[#c85a20] px-2.5 py-1 text-[11px] font-extrabold">
                {{ $pembayaranList->count() }} Pesanan
            </span>
        </div>
        <button onclick="window.location.reload()" class="flex items-center gap-1.5 text-xs font-bold text-[#8c6239] hover:text-[#21140b] transition-colors cursor-pointer bg-transparent border-none">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18.24" />
            </svg>
            <span>Perbarui Data</span>
        </button>
    </div>

    {{-- TABLE CARD --}}
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="border-b border-[#ede6df] bg-[#faf2eb]">
                        <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider rounded-l-2xl">No. Order</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Metode</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Total</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">Bukti Transfer</th>
                        <th class="py-4 px-6 text-right text-xs font-bold text-[#8f7664] uppercase tracking-wider rounded-r-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f0eb]">
                    @forelse($pembayaranList as $pembayaran)
                        @php
                            $order = $pembayaran->pesanan;
                            $user = $order?->user;
                        @endphp
                        <tr class="group hover:bg-[#fdfaf7] transition-colors">
                            {{-- No. Order --}}
                            <td class="py-4 px-6 text-sm font-extrabold text-[#21140b]">
                                {{ $order ? $order->order_number : '-' }}
                            </td>
                            
                            {{-- Pelanggan --}}
                            <td class="py-4 px-6 text-sm font-bold text-[#21140b]">
                                {{ $user ? $user->nama : 'Guest' }}
                            </td>

                            {{-- Metode --}}
                            <td class="py-4 px-6">
                                @if(strtolower($pembayaran->metode) === 'qris')
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/60 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7" />
                                            <rect x="14" y="3" width="7" height="7" />
                                            <rect x="14" y="14" width="7" height="7" />
                                            <rect x="3" y="14" width="7" height="7" />
                                        </svg>
                                        <span>QRIS</span>
                                    </span>
                                @elseif(strtolower($pembayaran->metode) === 'tunai')
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/60 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Tunai</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/60 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <span>Transfer Bank</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Total --}}
                            <td class="py-4 px-6 text-sm font-extrabold text-[#8b5a2b]">
                                Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                            </td>

                            {{-- Bukti Transfer --}}
                            <td class="py-4 px-6">
                                @if($pembayaran->bukti_transfer)
                                    <button onclick="openVerifikasiModal('{{ $order ? $order->order_number : '-' }}', '{{ $user ? $user->nama : 'Guest' }}', '{{ $pembayaran->metode }}', 'Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}', '{{ $pembayaran->bukti_transfer }}', '{{ route('karyawan.verifikasi.setujui', $pembayaran->id_pembayaran) }}', '{{ route('karyawan.verifikasi.tolak', $pembayaran->id_pembayaran) }}')" class="h-10 w-10 rounded-lg overflow-hidden border border-[#ede6df] bg-stone-50 flex items-center justify-center cursor-pointer hover:border-[#a2785d] transition-all">
                                        <img src="{{ $pembayaran->bukti_transfer }}" alt="Bukti Transfer" class="h-full w-full object-cover">
                                    </button>
                                @else
                                    <span class="text-xs text-[#8f7664] italic">Tidak ada</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <button onclick="openVerifikasiModal('{{ $order ? $order->order_number : '-' }}', '{{ $user ? $user->nama : 'Guest' }}', '{{ $pembayaran->metode }}', 'Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}', '{{ $pembayaran->bukti_transfer }}', '{{ route('karyawan.verifikasi.setujui', $pembayaran->id_pembayaran) }}', '{{ route('karyawan.verifikasi.tolak', $pembayaran->id_pembayaran) }}')" class="rounded-xl bg-[#21140b] hover:bg-[#3d2a1f] text-white px-5 py-2.5 text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-sm">
                                        Periksa
                                    </button>
                                    <button type="button" onclick="openTolakModal('{{ $order ? $order->order_number : '-' }}', '{{ route('karyawan.verifikasi.tolak', $pembayaran->id_pembayaran) }}')" class="text-gray-400 hover:text-red-600 transition-colors p-1.5 cursor-pointer" title="Tolak Pembayaran">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f5f0eb] text-[#a2785d]">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-[#21140b]">Tidak ada pembayaran menunggu verifikasi</p>
                                    <p class="text-xs text-[#8f7664]">Semua pembayaran telah berhasil diverifikasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL VERIFIKASI PEMBAYARAN --}}
<div id="modal-verifikasi-pembayaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/45 backdrop-blur-sm transition-opacity" onclick="closeVerifikasiModal()"></div>

    {{-- Panel --}}
    <div class="relative w-full max-w-md rounded-2xl bg-white shadow-[0_25px_60px_rgba(33,20,11,0.2)] overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4">
            <h3 class="text-sm font-extrabold text-[#21140b]">Verifikasi Detail Pembayaran</h3>
            <button onclick="closeVerifikasiModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Details & Receipt Image --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <p class="font-semibold text-[#8f7664]">No. Order</p>
                    <p id="modal-order-number" class="font-extrabold text-[#21140b] mt-0.5">-</p>
                </div>
                <div>
                    <p class="font-semibold text-[#8f7664]">Pelanggan</p>
                    <p id="modal-customer" class="font-extrabold text-[#21140b] mt-0.5">-</p>
                </div>
                <div>
                    <p class="font-semibold text-[#8f7664]">Metode</p>
                    <p id="modal-method" class="font-extrabold text-[#21140b] mt-0.5">-</p>
                </div>
                <div>
                    <p class="font-semibold text-[#8f7664]">Total Nominal</p>
                    <p id="modal-total" class="font-extrabold text-[#8b5a2b] mt-0.5">-</p>
                </div>
            </div>

            <div id="modal-receipt-container" class="border-t border-[#f0ebe5] pt-4">
                <p class="text-xs font-semibold text-[#8f7664] mb-2">Bukti Pembayaran / Transfer</p>
                <div class="rounded-xl border border-[#ede6df] overflow-hidden bg-stone-50 max-h-80 flex items-center justify-center">
                    <img id="modal-receipt-img" src="" alt="Bukti Transfer" class="max-h-80 w-auto object-contain">
                </div>
            </div>

            <div id="modal-cash-notice" class="hidden border-t border-[#f0ebe5] pt-4">
                <div class="p-4 bg-[#fbf1e8]/60 border border-[#edd8cf] rounded-2xl flex items-start gap-3">
                    <div class="h-8 w-8 rounded-xl bg-[#edd8cf] flex items-center justify-center text-[#8b5a2b] shrink-0 mt-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-bold text-[#21140b]">Pembayaran Tunai di Kasir</p>
                        <p class="text-[11px] text-[#8f7664] font-medium mt-1 leading-relaxed">
                            Harap terima uang tunai dari pelanggan sesuai total nominal di atas sebelum menekan tombol <strong>Setujui Pembayaran</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 border-t border-[#f0ebe5] px-6 py-4 bg-[#faf7f5]">
            <div class="flex-1">
                <button type="button" onclick="triggerTolakFromVerification()" class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-600 py-3 rounded-xl text-xs font-bold transition-colors cursor-pointer text-center">
                    Tolak Pembayaran
                </button>
            </div>
            <form id="form-setujui" action="" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-[#21140b] hover:bg-[#3d2a1f] text-white py-3 rounded-xl text-xs font-bold transition-colors cursor-pointer text-center shadow-sm">
                    Setujui Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI TOLAK --}}
<div id="modal-konfirmasi-tolak" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/45 backdrop-blur-sm transition-opacity" onclick="closeTolakModal()"></div>

    {{-- Panel --}}
    <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-[0_25px_60px_rgba(33,20,11,0.2)] overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4">
            <h3 class="text-sm font-extrabold text-[#21140b]">Konfirmasi Penolakan</h3>
            <button onclick="closeTolakModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-6 text-center space-y-4">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="text-xs font-bold text-[#21140b] leading-relaxed">
                Apakah Anda yakin ingin menolak pembayaran untuk order <span id="tolak-order-number" class="text-red-600 font-extrabold">-</span>?
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 px-6 py-4 border-t border-[#f0ebe5] bg-[#faf7f5]">
            <button onclick="closeTolakModal()" class="flex-1 bg-white border border-[#ede6df] hover:bg-[#fbf1e8] text-[#8f7664] py-2.5 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                Batal
            </button>
            <form id="form-tolak-konfirmasi" action="" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-xs font-bold transition-colors cursor-pointer text-center shadow-sm">
                    Ya, Tolak
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentRejectUrl = '';

    function openVerifikasiModal(orderNum, customer, method, total, receiptImgSrc, approveUrl, rejectUrl) {
        document.getElementById('modal-order-number').innerText = orderNum;
        document.getElementById('modal-customer').innerText = customer;
        document.getElementById('modal-method').innerText = method;
        document.getElementById('modal-total').innerText = total;

        const receiptContainer = document.getElementById('modal-receipt-container');
        const cashNotice = document.getElementById('modal-cash-notice');
        const receiptImg = document.getElementById('modal-receipt-img');

        if (method.toLowerCase() === 'tunai') {
            if (receiptContainer) receiptContainer.classList.add('hidden');
            if (cashNotice) cashNotice.classList.remove('hidden');
        } else {
            if (cashNotice) cashNotice.classList.add('hidden');
            if (receiptContainer) receiptContainer.classList.remove('hidden');
            if (receiptImg) receiptImg.src = receiptImgSrc || 'https://placehold.co/400';
        }

        document.getElementById('form-setujui').action = approveUrl;
        currentRejectUrl = rejectUrl;

        document.getElementById('modal-verifikasi-pembayaran').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeVerifikasiModal() {
        document.getElementById('modal-verifikasi-pembayaran').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openTolakModal(orderNum, tolakUrl) {
        document.getElementById('tolak-order-number').innerText = orderNum;
        document.getElementById('form-tolak-konfirmasi').action = tolakUrl;
        document.getElementById('modal-konfirmasi-tolak').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTolakModal() {
        document.getElementById('modal-konfirmasi-tolak').classList.add('hidden');
        if (document.getElementById('modal-verifikasi-pembayaran').classList.contains('hidden')) {
            document.body.style.overflow = '';
        }
    }

    function triggerTolakFromVerification() {
        const orderNum = document.getElementById('modal-order-number').innerText;
        const rejectUrl = currentRejectUrl;
        closeVerifikasiModal();
        setTimeout(() => {
            openTolakModal(orderNum, rejectUrl);
        }, 200);
    }
</script>
@endsection
