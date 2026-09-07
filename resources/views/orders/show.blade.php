@extends('layouts.app')

@section('content')
@php
    $subtotal = $pesanan->detailPesanan->sum('subtotal');
    $serviceFee = 2000;
    $discount = max(0, ($subtotal + $serviceFee) - $pesanan->total_harga);
    
    $status = $pesanan->status_pesanan;
    $isProcessing = ($status === 'diproses');
    $isMaking = ($status === 'sedang_dibuat');
    $isReady = ($status === 'siap_diambil');
    $isCompleted = ($status === 'selesai');
    $isCancelled = ($status === 'dibatalkan');
    $isInProgress = in_array($status, ['diproses', 'sedang_dibuat', 'siap_diambil']);

    $showTrackingView = ($isInProgress || $isCompleted || $isCancelled) && !request()->has('receipt');
@endphp

@if($showTrackingView)
{{-- ============================================================ --}}
{{-- TRACKING VIEW — Sedang Diproses / Dibuat / Siap / Selesai     --}}
{{-- ============================================================ --}}
<div class="w-full pb-16 md:pb-24">

    {{-- QUEUE NUMBER BANNER --}}
    @if($isInProgress)
        <div class="w-full {{ $isReady ? 'bg-[#e8f5e9] border-b border-[#c8e6c9]' : 'bg-[#f5ebe2] border-b border-[#e8ddd3]' }} transition-all">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-8 text-center">
                <p class="text-[11px] font-bold {{ $isReady ? 'text-[#2e7d32]' : 'text-[#8f7664]' }} tracking-[0.15em] uppercase">NOMOR ANTRIAN ANDA</p>
                <h2 class="text-5xl sm:text-6xl font-extrabold {{ $isReady ? 'text-[#1b5e20]' : 'text-[#1b140e]' }} tracking-tight mt-2">
                    A{{ str_pad($pesanan->id_pesanan % 100, 2, '0', STR_PAD_LEFT) }}
                </h2>

                @if($isReady)
                    <p class="text-xs sm:text-[13px] font-bold text-[#2e7d32] mt-3 flex items-center justify-center gap-1.5 animate-bounce">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @if($pesanan->tipe_pesanan === 'diantar')
                            Pesanan Anda sedang diantar ke lokasi Anda!
                        @else
                            Pesanan Anda sudah siap! Silakan ambil di kasir / pick-up counter.
                        @endif
                    </p>
                @elseif($isMaking)
                    <p class="text-xs sm:text-[13px] font-semibold text-[#8b5a2b] mt-3 flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mohon tunggu, pesanan Anda sedang disiapkan dan diracik oleh barista kami.
                    </p>
                @else
                    <p class="text-xs sm:text-[13px] font-semibold text-[#a2785d] mt-3 flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pesanan Anda masuk antrean dan akan segera dibuat oleh barista.
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-5">
        <nav class="text-[11px] font-semibold text-[#8f7664] flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">›</span>
            <a href="{{ route('orders.index') }}" class="hover:text-[#21140b] transition-colors">Riwayat Pesanan</a>
            <span class="text-[#edd8cf]">›</span>
            <span class="text-[#21140b]">Detail Pesanan {{ $pesanan->order_number }}</span>
        </nav>
    </div>

    {{-- PAGE TITLE & STATUS BAR --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-5">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1b140e] tracking-tight">Detail Pesanan</h1>
        <div class="flex flex-wrap items-center gap-3 mt-2">
            <span class="text-xs font-semibold text-[#8f7664]">Order ID: {{ $pesanan->order_number }}</span>
            
            @if($isReady)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e0f2fe] px-3 py-1 text-[11px] font-bold text-[#0284c7]">
                    <span class="h-2 w-2 rounded-full bg-[#0284c7] animate-ping"></span>
                    {{ $pesanan->tipe_pesanan === 'diantar' ? 'Sedang Diantar' : 'Siap Diambil' }}
                </span>
            @elseif($isMaking)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fff4e5] px-3 py-1 text-[11px] font-bold text-[#b45309]">
                    <span class="h-2 w-2 rounded-full bg-[#b45309] animate-pulse"></span>
                    Sedang Dibuat
                </span>
            @elseif($isProcessing)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#ffecd9] px-3 py-1 text-[11px] font-bold text-[#c05e2b]">
                    <span class="h-2 w-2 rounded-full bg-[#c05e2b] animate-pulse"></span>
                    Sedang Diproses
                </span>
            @elseif($isCompleted)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e6f4ea] px-3 py-1 text-[11px] font-bold text-[#137333]">
                    <span class="h-2 w-2 rounded-full bg-[#137333]"></span>
                    Selesai
                </span>
            @elseif($isCancelled)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fce8e6] px-3 py-1 text-[11px] font-bold text-[#c5221f]">
                    <span class="h-2 w-2 rounded-full bg-[#c5221f]"></span>
                    Dibatalkan
                </span>
            @endif
            <span class="text-xs font-medium text-[#8f7664]">{{ $pesanan->tanggal_pesan->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>

        @if($isCancelled)
            <div class="mt-4 p-4 bg-[#fdf3f2] border border-[#fce8e6] rounded-2xl max-w-sm text-left">
                <p class="text-[10px] font-bold text-[#c5221f] tracking-[0.12em] uppercase">ALASAN PEMBATALAN</p>
                <p class="text-xs font-medium text-[#8c2d28] mt-1">Stok bahan tidak tersedia atau pesanan dibatalkan</p>
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- LEFT COLUMN: Item List + Tracking (Span 7) --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- DAFTAR ITEM CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-extrabold text-[#1b140e]">Daftar Item</h3>
                        <a href="{{ route('orders.show', $pesanan->id_pesanan) }}?receipt=true" class="inline-flex items-center gap-1.5 rounded-full border border-[#ede6df] bg-white px-4 py-2 text-[11px] font-bold text-[#4a3d35] hover:bg-[#faf5f0] transition-all">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Lihat Struk
                        </a>
                    </div>

                    <div class="space-y-5">
                        @foreach($pesanan->detailPesanan as $detail)
                            @php
                                $optionsText = $detail->opsi ?? '';
                                if ($detail->catatan) {
                                    $optionsText = $optionsText ? $optionsText . ' • Catatan: "' . $detail->catatan . '"' : 'Catatan: "' . $detail->catatan . '"';
                                }
                            @endphp
                            <div class="flex items-start gap-3.5">
                                <img
                                    src="{{ asset($detail->menu->gambar) }}"
                                    alt="{{ $detail->menu->nama_menu }}"
                                    class="h-14 w-14 rounded-xl object-cover shrink-0 bg-[#fbf1e8]"
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-bold text-[#1b140e]">{{ $detail->menu->nama_menu }}</h4>
                                            @if($isReady)
                                                <p class="text-[10px] font-bold text-[#0284c7] mt-0.5 flex items-center gap-1">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {{ $pesanan->tipe_pesanan === 'diantar' ? 'Sedang Diantar' : 'Siap Diambil' }}
                                                </p>
                                            @elseif($isMaking)
                                                <p class="text-[10px] font-bold text-[#b45309] mt-0.5 flex items-center gap-1">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Sedang Diracik Barista
                                                </p>
                                            @elseif($isProcessing)
                                                <p class="text-[10px] font-bold text-[#c05e2b] mt-0.5 flex items-center gap-1">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Menunggu Antrean
                                                </p>
                                            @endif
                                            @if($optionsText)
                                                <p class="text-[11px] text-[#8f7664] font-medium mt-0.5">{{ $optionsText }}</p>
                                            @endif
                                        </div>
                                        <span class="text-sm font-extrabold text-[#1b140e] shrink-0 whitespace-nowrap">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center justify-center mt-2 h-5 w-5 rounded-md bg-[#f5ebe2] text-[10px] font-extrabold text-[#5a4d42]">
                                        {{ $detail->jumlah }}x
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if($pesanan->catatan)
                            <div class="mt-4 p-3 bg-[#faf2eb] border border-[#eeded3] rounded-xl text-left">
                                <p class="text-[9px] font-bold text-[#8c5a3c] tracking-wider uppercase">Catatan Keseluruhan:</p>
                                <p class="text-xs font-semibold text-[#5a4d42] mt-0.5 italic">"{{ $pesanan->catatan }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- LACAK PESANAN CARD --}}
                @if($isInProgress || $isCompleted)
                    <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm p-5 sm:p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-base font-extrabold text-[#1b140e]">Lacak Pesanan</h3>
                            @if($isInProgress)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#8f7664]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Update Real-Time
                                </span>
                            @endif
                        </div>

                        @php
                            $orderTime = $pesanan->tanggal_pesan;
                            $paymentTime = $pesanan->pembayaran?->tanggal_bayar ? $pesanan->pembayaran->tanggal_bayar->format('H:i') : $orderTime->copy()->addMinute()->format('H:i');
                            
                            $steps = [
                                [
                                    'label' => 'Pesanan Diterima',
                                    'time' => $orderTime->format('H:i'),
                                    'desc' => null,
                                    'active' => true,
                                    'completed' => true,
                                    'current' => false,
                                ],
                                [
                                    'label' => 'Pembayaran Dikonfirmasi',
                                    'time' => $paymentTime,
                                    'desc' => 'Pembayaran telah diverifikasi karyawan',
                                    'active' => true,
                                    'completed' => true,
                                    'current' => false,
                                ],
                                [
                                    'label' => 'Sedang Diproses',
                                    'time' => null,
                                    'desc' => 'Pesanan masuk antrean dapur',
                                    'active' => in_array($status, ['diproses', 'sedang_dibuat', 'siap_diambil', 'selesai']),
                                    'completed' => in_array($status, ['sedang_dibuat', 'siap_diambil', 'selesai']),
                                    'current' => $status === 'diproses',
                                ],
                                [
                                    'label' => 'Sedang Dibuat',
                                    'time' => null,
                                    'desc' => 'Pesanan sedang disiapkan & diracik barista kami',
                                    'active' => in_array($status, ['sedang_dibuat', 'siap_diambil', 'selesai']),
                                    'completed' => in_array($status, ['siap_diambil', 'selesai']),
                                    'current' => $status === 'sedang_dibuat',
                                ],
                                [
                                    'label' => $pesanan->tipe_pesanan === 'diantar' ? 'Sedang Diantar' : 'Siap Diambil',
                                    'time' => null,
                                    'desc' => $pesanan->tipe_pesanan === 'diantar' ? ('Pesanan siap & sedang diantar ke ' . ($pesanan->alamat_pengiriman ?: 'lokasi Anda')) : 'Pesanan siap diambil di counter kasir',
                                    'active' => in_array($status, ['siap_diambil', 'selesai']),
                                    'completed' => $status === 'selesai',
                                    'current' => $status === 'siap_diambil',
                                ],
                                [
                                    'label' => 'Selesai',
                                    'time' => null,
                                    'desc' => $pesanan->tipe_pesanan === 'diantar' ? 'Pesanan telah diantarkan ke tujuan' : 'Pesanan telah diserahkan',
                                    'active' => $status === 'selesai',
                                    'completed' => $status === 'selesai',
                                    'current' => false,
                                ],
                            ];
                        @endphp

                        <div class="relative pl-4">
                            @foreach($steps as $i => $step)
                                <div class="relative flex gap-4 {{ !$loop->last ? 'pb-6' : '' }}">
                                    {{-- Vertical Line --}}
                                    @if(!$loop->last)
                                        <div class="absolute left-[7px] top-[20px] bottom-0 w-[2px] {{ $step['completed'] ? 'bg-[#1b140e]' : 'bg-[#e3dcd5]' }}"></div>
                                    @endif

                                    {{-- Dot --}}
                                    <div class="relative z-10 shrink-0 mt-0.5">
                                        @if(isset($step['current']) && $step['current'])
                                            {{-- Current step — animated outlined circle --}}
                                            <div class="relative flex h-4 w-4 items-center justify-center">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#a2785d] opacity-75"></span>
                                                <div class="h-4 w-4 rounded-full border-[3px] border-[#a2785d] bg-white"></div>
                                            </div>
                                        @elseif($step['completed'])
                                            {{-- Completed — filled dark circle --}}
                                            <div class="h-4 w-4 rounded-full bg-[#1b140e] flex items-center justify-center">
                                                <svg class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @else
                                            {{-- Inactive — gray circle --}}
                                            <div class="h-4 w-4 rounded-full bg-[#e3dcd5]"></div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="min-w-0 -mt-0.5">
                                        <p class="text-sm font-bold {{ ($step['active'] && !isset($step['current'])) || (isset($step['current']) && $step['current']) ? 'text-[#1b140e]' : 'text-[#b5a49a]' }} {{ isset($step['current']) && $step['current'] ? 'text-[#a2785d]' : '' }}">
                                            {{ $step['label'] }}
                                        </p>
                                        @if($step['time'])
                                            <p class="text-[11px] text-[#8f7664] font-medium mt-0.5">{{ $step['time'] }}</p>
                                        @endif
                                        @if($step['desc'])
                                            <p class="text-[11px] text-[#8f7664] font-medium mt-0.5">{{ $step['desc'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: Payment + Price Summary (Span 5) --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- PAYMENT METHOD & PICKUP / DELIVERY INFO CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm p-5 sm:p-6 space-y-5">
                    {{-- Metode Pembayaran --}}
                    <div>
                        <p class="text-[10px] font-bold text-[#b5a49a] tracking-[0.12em] uppercase">METODE PEMBAYARAN</p>
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            <span class="text-sm font-bold text-[#1b140e]">{{ $pesanan->metode_pembayaran }}</span>
                        </div>
                    </div>

                    <hr class="border-t border-[#ede6df]/60" />

                    {{-- Informasi Pengambilan / Pengantaran --}}
                    <div>
                        <p class="text-[10px] font-bold text-[#b5a49a] tracking-[0.12em] uppercase">
                            {{ $pesanan->tipe_pesanan === 'diantar' ? 'INFORMASI PENGANTARAN' : 'INFORMASI PENGAMBILAN' }}
                        </p>
                        <div class="flex items-start gap-2 mt-2">
                            @if($pesanan->tipe_pesanan === 'diantar')
                                <svg class="h-4 w-4 text-[#8f7664] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                                <div>
                                    <p class="text-sm font-bold text-[#1b140e]">Diantar</p>
                                    <p class="text-[11px] text-[#8f7664] font-medium mt-0.5 leading-relaxed">{{ $pesanan->alamat_pengiriman ?: 'Lokasi pengantaran pelanggan' }}</p>
                                </div>
                            @else
                                <svg class="h-4 w-4 text-[#8f7664] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-bold text-[#1b140e]">Ambil di Toko</p>
                                    <p class="text-[11px] text-[#8f7664] font-medium mt-0.5 leading-relaxed">Kote School Shop, Jl. Pendidikan No. 12</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RINCIAN HARGA CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm p-5 sm:p-6">
                    <h3 class="text-base font-extrabold text-[#1b140e] mb-4">Rincian Harga</h3>

                    <div class="space-y-3 text-[13px]">
                        <div class="flex justify-between">
                            <span class="text-[#8f7664] font-medium">Subtotal</span>
                            <span class="text-[#1b140e] font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#8f7664] font-medium">Pajak & Layanan</span>
                            <span class="text-[#1b140e] font-semibold">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between">
                                <span class="text-[#8f7664] font-medium">Diskon</span>
                                <span class="text-emerald-600 font-semibold">-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <hr class="border-t border-[#ede6df]/60 my-4" />

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-extrabold text-[#1b140e] tracking-[0.05em] uppercase">TOTAL TRANSAKSI</span>
                        <span class="text-lg sm:text-xl font-extrabold text-[#8b5a2b]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex gap-3">
                    @if($isCompleted)
                        <a
                            href="{{ route('ulasan.create', $pesanan->id_pesanan) }}"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-white border border-[#ede6df] hover:bg-[#faf5f0] text-[#4a3d35] py-3 text-[12px] font-bold transition-all active:scale-[0.98] cursor-pointer"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Tulis Ulasan
                        </a>
                    @endif
                    <a
                        href="{{ route('menu') }}"
                        class="w-full flex-1 flex items-center justify-center gap-2 rounded-xl bg-[#1b140e] hover:bg-[#32271d] text-white py-3 text-[12px] font-bold transition-all active:scale-[0.98] shadow-sm cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        {{ $isCancelled ? 'Pesan Kembali' : 'Pesan Lagi' }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@else
{{-- ============================================================ --}}
{{-- PAYMENT RESULT VIEW — Berhasil / Gagal / Menunggu            --}}
{{-- ============================================================ --}}
<div class="w-full pb-16 md:pb-24">
    {{-- MAIN CONTAINER --}}
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 mt-12 text-center">

        {{-- CIRCULAR LOGO WITH BADGE --}}
        <div class="flex flex-col items-center justify-center">
            <div class="relative flex items-center justify-center h-36 w-36 rounded-full bg-[#ebdcd0]/40 border-4 border-white shadow-[0_8px_30px_rgba(33,20,11,0.07)]">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Kote Coffee Logo"
                    class="h-[5.5rem] w-[5.5rem] object-contain"
                />

                {{-- Dynamic Status Badge --}}
                @if($pesanan->pembayaran && $pesanan->pembayaran->status === 'berhasil')
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-[#1b140e] text-white border-[3px] border-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                @elseif($pesanan->pembayaran && $pesanan->pembayaran->status === 'gagal')
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white border-[3px] border-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                @else
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-white border-[3px] border-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- HEADING & SUBTITLE --}}
        <div class="mt-7 space-y-2">
            @if($pesanan->pembayaran && $pesanan->pembayaran->status === 'berhasil')
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1b140e] tracking-tight">
                    Pembayaran Berhasil
                </h1>
                <p class="text-xs sm:text-[13px] font-medium text-[#8f7664] max-w-sm mx-auto leading-relaxed">
                    Terima kasih atas pesanan Anda. Kami sedang menyiapkan racikan terbaik untuk Anda.
                </p>
            @elseif($pesanan->pembayaran && $pesanan->pembayaran->status === 'gagal')
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1b140e] tracking-tight">
                    Pembayaran Ditolak
                </h1>
                <p class="text-xs sm:text-[13px] font-medium text-[#8f7664] max-w-sm mx-auto leading-relaxed">
                    Maaf, bukti pembayaran Anda ditolak. Silakan hubungi karyawan kami atau kirim bukti pembayaran baru di riwayat pesanan.
                </p>
            @else
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1b140e] tracking-tight">
                    Menunggu Verifikasi
                </h1>
                <p class="text-xs sm:text-[13px] font-medium text-[#8f7664] max-w-sm mx-auto leading-relaxed">
                    Pembayaran Anda sedang diperiksa oleh karyawan kami. Silakan tunggu beberapa saat.
                </p>
            @endif
        </div>

        {{-- WHITE DETAILS CARD --}}
        <div class="max-w-md sm:max-w-lg mx-auto rounded-[2rem] bg-white border border-[#ede6df]/60 p-6 sm:p-8 shadow-[0_15px_40px_rgba(33,20,11,0.04)] mt-8">
            {{-- ORDER NO / DATE ROW --}}
            <div class="flex items-center justify-between text-left">
                <div>
                    <p class="text-[9px] font-bold text-[#b5a49a] tracking-[0.12em] uppercase">ORDER NUMBER</p>
                    <p class="text-xs sm:text-sm font-extrabold text-[#1b140e] mt-0.5">{{ $pesanan->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-[#b5a49a] tracking-[0.12em] uppercase">DATE & TIME</p>
                    <p class="text-xs sm:text-sm font-extrabold text-[#1b140e] mt-0.5">
                        {{ $pesanan->tanggal_pesan->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            <hr class="border-t border-dashed border-[#ede6df] my-5" />

            {{-- ITEMS LIST --}}
            <div class="space-y-4">
                @foreach($pesanan->detailPesanan as $detail)
                    @php
                        $optionsText = $detail->opsi ?? '';
                        if ($detail->catatan) {
                            $optionsText = $optionsText ? $optionsText . ' • Catatan: "' . $detail->catatan . '"' : 'Catatan: "' . $detail->catatan . '"';
                        }
                    @endphp
                    <div class="flex items-center justify-between gap-4 py-1">
                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                            <img
                                src="{{ asset($detail->menu->gambar) }}"
                                alt="{{ $detail->menu->nama_menu }}"
                                class="h-14 w-14 rounded-2xl object-cover shrink-0 bg-[#fbf1e8] border border-[#ede6df]/60 shadow-[0_2px_8px_rgba(33,20,11,0.04)]"
                            />
                            <div class="min-w-0 text-left">
                                <h4 class="text-xs sm:text-sm font-extrabold text-[#1b140e] truncate">
                                    {{ $detail->menu->nama_menu }}
                                </h4>
                                @if($optionsText)
                                    <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5 leading-none">
                                        {{ $optionsText }}
                                    </p>
                                @endif
                                <p class="text-[10px] text-[#8f7664] font-semibold mt-1">
                                    Qty: {{ $detail->jumlah }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs sm:text-sm font-extrabold text-[#1b140e]">
                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="border-t border-dashed border-[#ede6df] my-5" />

            {{-- PRICE SUMMARY --}}
            <div class="space-y-2.5 text-xs font-bold text-[#8f7664] text-left">
                <div class="flex justify-between">
                    <span class="font-semibold">Subtotal</span>
                    <span class="text-[#8f7664]">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Biaya Layanan</span>
                    <span class="text-[#8f7664]">Rp{{ number_format($serviceFee, 0, ',', '.') }}</span>
                </div>
                @if($discount > 0)
                    <div class="flex justify-between">
                        <span class="font-semibold">Diskon</span>
                        <span class="text-emerald-600">-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- TOTAL AMOUNT BOX --}}
            <div class="bg-[#faf2eb]/80 border border-[#f5ece4]/60 rounded-[1.25rem] p-4 sm:p-5 flex items-center justify-between mt-6">
                <div class="text-left">
                    <p class="text-[9px] font-bold text-[#8f7664] tracking-[0.12em] uppercase">TOTAL AMOUNT</p>
                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                        <span class="inline-block rounded-md bg-[#ebdcd0]/70 px-2 py-0.5 text-[9px] font-extrabold text-[#5a4d42] uppercase tracking-wider">
                            {{ $pesanan->metode_pembayaran }}
                        </span>
                        <span class="inline-block rounded-md {{ $pesanan->tipe_pesanan === 'diantar' ? 'bg-blue-100 text-blue-800' : 'bg-[#e8ddd3] text-[#5a4d42]' }} px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider">
                            {{ $pesanan->tipe_pesanan_label }}
                        </span>
                    </div>
                    @if($pesanan->tipe_pesanan === 'diantar' && $pesanan->alamat_pengiriman)
                        <p class="text-[10px] text-[#8f7664] font-medium mt-1 leading-tight max-w-[200px] truncate">
                            📍 {{ $pesanan->alamat_pengiriman }}
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <span class="text-xl sm:text-2xl font-extrabold text-[#8b5a2b]">
                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-row items-center justify-center gap-3 max-w-md sm:max-w-lg mx-auto mt-8">
            @if($isCompleted)
                <a
                    href="{{ route('ulasan.create', $pesanan->id_pesanan) }}"
                    class="flex-1 flex items-center justify-center gap-1.5 rounded-full bg-[#8c6239] hover:bg-[#724e2c] text-white py-3.5 text-xs font-bold transition-all active:scale-[0.99] shadow-sm cursor-pointer"
                >
                    <span>⭐ Beri Ulasan</span>
                </a>
            @endif
            <a
                href="{{ route('orders.index') }}"
                class="flex-1 flex items-center justify-center rounded-full bg-[#1b140e] hover:bg-[#32271d] text-white py-3.5 text-xs font-bold transition-all active:scale-[0.99] shadow-sm cursor-pointer"
            >
                Status Pesanan
            </a>
            <a
                href="{{ route('home') }}"
                class="flex-1 flex items-center justify-center rounded-full bg-white border border-[#1b140e] hover:bg-[#fdfaf7] text-[#1b140e] py-3.5 text-xs font-bold transition-all active:scale-[0.99] cursor-pointer"
            >
                Beranda
            </a>
        </div>

    </div>
</div>
@endif

{{-- REAL-TIME POLLING FOR ORDERS IN PROGRESS --}}
@if($isInProgress)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderId = @json($pesanan->id_pesanan);
    const initialStatus = @json($pesanan->status_pesanan);

    function checkOrderStatus() {
        fetch(`/orders/${orderId}/status`)
            .then(res => res.json())
            .then(data => {
                if (data.status_pesanan && data.status_pesanan !== initialStatus) {
                    window.location.reload();
                }
            })
            .catch(err => console.error("Error checking order status:", err));
    }

    // Auto-check every 3 seconds
    setInterval(checkOrderStatus, 3000);
});
</script>
@endif
@endsection
