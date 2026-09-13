@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-8 pb-16 space-y-6">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-semibold text-[#8f7664] flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
        <span class="text-[#edd8cf] font-normal">/</span>
        <span class="text-[#21140b]">Riwayat Pesanan</span>
    </nav>

    {{-- Page Title --}}
    <h1 class="text-2xl font-extrabold text-[#21140b]">Riwayat Pesanan</h1>

    {{-- Filter Tabs --}}
    @php
        $currentStatus = $status ?? 'semua';
        $tabs = [
            'semua' => 'Semua',
            'selesai' => 'Selesai',
            'diproses' => 'Proses',
            'dibatalkan' => 'Dibatalkan',
        ];
    @endphp
    <div class="flex items-center gap-6 border-b border-[#ede6df]">
        @foreach($tabs as $key => $label)
            <a href="{{ route('orders.index', $key === 'semua' ? [] : ['status' => $key]) }}"
               class="pb-2.5 text-sm font-semibold border-b-2 transition-colors {{ $currentStatus === $key ? 'text-[#21140b] border-[#a2785d]' : 'text-[#8f7664] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Order List --}}
    <div class="space-y-4">
        @forelse($pesanan as $order)
            @php
                $isCancelled = $order->status_pesanan === 'dibatalkan';
                $isEven = ($order->id_pesanan % 2 === 0);
                $pembayaran = $order->pembayaran;
                $isPendingPayment = $pembayaran && $pembayaran->status === 'menunggu';
                $hasUploadedBukti = $pembayaran && $pembayaran->bukti_transfer;
            @endphp
            <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm px-4 py-4 sm:px-6 sm:py-5">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    {{-- Left Side: Menu Photo + Order Info --}}
                    <div class="flex items-start gap-4">
                        @php
                            $topMenu = $order->top_menu;
                            $topMenuImg = $order->top_menu_image;
                        @endphp
                        {{-- Menu Photo Container --}}
                        <div class="flex h-14 w-14 sm:h-16 sm:w-16 shrink-0 items-center justify-center rounded-2xl bg-[#f5ebe2] border border-[#ede6df]/80 overflow-hidden shadow-2xs relative">
                            @if($topMenuImg)
                                <img src="{{ $topMenuImg }}" alt="{{ $topMenu?->nama_menu ?? 'Menu' }}" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105" />
                            @elseif($isCancelled)
                                {{-- Cancelled icon fallback --}}
                                <svg class="h-6 w-6 text-[#b5a49a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                </svg>
                            @else
                                {{-- Coffee cup icon fallback --}}
                                <svg class="h-6 w-6 text-[#a2785d]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Order Details --}}
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                @php
                                    $detailRoute = ($pembayaran && $pembayaran->status === 'menunggu')
                                        ? route('payment', $order->id_pesanan)
                                        : route('orders.show', $order->id_pesanan);
                                @endphp
                                <a href="{{ $detailRoute }}" class="text-sm font-extrabold text-[#21140b] hover:text-[#a2785d] transition-colors">{{ $order->order_number }}</a>
                                @if($order->status_pesanan === 'selesai')
                                    <span class="inline-flex items-center rounded-full bg-[#e6f4ea] px-2.5 py-0.5 text-[10px] font-bold text-[#137333]">Selesai</span>
                                @elseif($order->status_pesanan === 'sedang_dibuat')
                                    <span class="inline-flex items-center rounded-full bg-[#fff4e5] px-2.5 py-0.5 text-[10px] font-bold text-[#b45309]">Sedang Dibuat</span>
                                @elseif($order->status_pesanan === 'siap_diambil')
                                    <span class="inline-flex items-center rounded-full bg-[#e0f2fe] px-2.5 py-0.5 text-[10px] font-bold text-[#0284c7]">
                                        {{ $order->tipe_pesanan === 'diantar' ? 'Sedang Diantar' : 'Siap Diambil' }}
                                    </span>
                                @elseif($order->status_pesanan === 'diproses')
                                    <span class="inline-flex items-center rounded-full bg-[#ffecd9] px-2.5 py-0.5 text-[10px] font-bold text-[#c05e2b]">Sedang Diproses</span>
                                @elseif($order->status_pesanan === 'dibatalkan')
                                    <span class="inline-flex items-center rounded-full bg-[#fce8e6] px-2.5 py-0.5 text-[10px] font-bold text-[#c5221f]">Dibatalkan</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-[#f5ebe2] px-2.5 py-0.5 text-[10px] font-bold text-[#8f7664]">{{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}</span>
                                @endif

                                {{-- Fulfillment Type Badge --}}
                                @if($order->tipe_pesanan === 'diantar')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">🛵 Diantar</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-[#fbf1e8] px-2 py-0.5 text-[10px] font-bold text-[#8c5a3c]">🏬 Ambil di Toko</span>
                                @endif

                                {{-- Payment Status Badge --}}
                                @if($pembayaran)
                                    @if($pembayaran->status === 'menunggu')
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 1 18 0z" /></svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($pembayaran->status === 'berhasil')
                                        <span class="inline-flex items-center rounded-full bg-[#e6f4ea] px-2.5 py-0.5 text-[10px] font-bold text-[#137333]">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Pembayaran Terverifikasi
                                        </span>
                                    @elseif($pembayaran->status === 'gagal')
                                        <span class="inline-flex items-center rounded-full bg-[#fce8e6] px-2.5 py-0.5 text-[10px] font-bold text-[#c5221f]">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Pembayaran Ditolak
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-xs text-[#8f7664] font-medium">
                                {{ $order->tanggal_pesan->translatedFormat('d F Y, H:i') }}
                            </p>
                            <p class="text-xs text-[#4a3d35] font-medium italic">
                                {{ $order->items_summary }}
                            </p>
                        </div>
                    </div>

                    {{-- Right Side: Total + Actions --}}
                    <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-1.5 shrink-0 pt-3 sm:pt-0 border-t border-[#ede6df]/40 sm:border-none">
                        <div class="text-left sm:text-right">
                            <span class="block text-[10px] sm:text-[11px] font-semibold text-[#8f7664]">Total Pesanan</span>
                            <span class="text-base sm:text-lg font-extrabold text-[#8b5a2b]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-2 sm:mt-1">
                            @if($order->status_pesanan === 'selesai')
                                @if(in_array($order->id_pesanan, $sudahDiulasOrderIds ?? []))
                                    <span class="inline-flex items-center justify-center rounded-full bg-[#e6f4ea] text-[#137333] px-3 py-1.5 text-[11px] font-bold">
                                        ✓ Sudah Diulas
                                    </span>
                                @else
                                    <a href="{{ route('ulasan.create', $order->id_pesanan) }}" class="inline-flex items-center justify-center rounded-full bg-[#8c6239] hover:bg-[#724e2c] text-white px-3 py-1.5 text-[11px] font-bold transition-all shadow-2xs">
                                        ⭐ Ulasan
                                    </a>
                                @endif
                            @endif
                            <a href="{{ $detailRoute }}" class="inline-flex items-center justify-center rounded-full border border-[#a2785d] bg-white px-4 py-1.5 text-[11px] font-bold text-[#a2785d] hover:bg-[#fbf1e8] transition-all">
                                Detail
                            </a>
                            <button type="button" onclick="openDeleteOrderModal('{{ $order->id_pesanan }}', '{{ $order->order_number }}')" class="inline-flex items-center justify-center gap-1.5 rounded-full border border-[#ede6df] bg-white px-3 py-1.5 text-[11px] font-bold text-[#8f7664] hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all cursor-pointer shadow-2xs active:scale-95" title="Hapus Riwayat Pesanan">
                                <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 space-y-4">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fbf1e8]">
                    <svg class="h-9 w-9 text-[#c5ab98]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-sm font-extrabold text-[#21140b]">Belum ada pesanan</p>
                    <p class="text-xs text-[#8f7664] font-medium mt-1">Pesanan Anda akan muncul di sini.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pesan Lagi Button --}}
    <a href="{{ route('menu') }}" class="flex items-center justify-center gap-2.5 w-full rounded-full bg-[#21140b] py-4 text-sm font-bold text-white shadow-sm hover:bg-[#3d2a1f] transition-all active:scale-[0.99]">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        Pesan Lagi
    </a>

</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-order-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] pl-[max(1rem,env(safe-area-inset-left))] pr-[max(1rem,env(safe-area-inset-right))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop with blur --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteOrderModal()"></div>

    {{-- Modal Panel --}}
    <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-[0_25px_60px_rgba(33,20,11,0.25)] border border-[#ede6df] transform transition-all space-y-5">
        {{-- Warning Icon --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        {{-- Text Content --}}
        <div class="space-y-2">
            <h3 class="text-base font-extrabold text-[#21140b]">Hapus Riwayat Pesanan</h3>
            <p class="text-xs text-[#8a7b6e] font-semibold leading-relaxed">
                Apakah Anda yakin ingin menghapus pesanan <span id="delete-order-number" class="font-extrabold text-[#21140b]"></span> dari riwayat? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="button" onclick="closeDeleteOrderModal()" class="flex-1 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-xs font-extrabold text-[#7b6558] py-3.5 rounded-full cursor-pointer transition-colors active:scale-[0.98]">
                Batal
            </button>
            <form id="delete-order-form" action="" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold py-3.5 rounded-full cursor-pointer transition-colors shadow-sm shadow-red-200 active:scale-[0.98]" style="background-color: #dc2626;">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteOrderModal(id, orderNumber) {
        const modal = document.getElementById('delete-order-modal');
        const form = document.getElementById('delete-order-form');
        const numberSpan = document.getElementById('delete-order-number');

        if (modal && form && numberSpan) {
            form.action = '/orders/' + id;
            numberSpan.textContent = orderNumber;
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteOrderModal() {
        const modal = document.getElementById('delete-order-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
