@extends('layouts.karyawan')

@section('page-title', 'Daftar Pesanan')

@section('header-search')
<div class="hidden lg:block flex-1 max-w-xs mx-4 relative">
    <form action="{{ route('karyawan.pesanan') }}" method="GET">
        <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search Order ID..." class="w-full bg-[#ebdcd0]/75 border-transparent text-xs rounded-xl pl-10 pr-4 py-2 text-[#21140b] placeholder-[#8f7664]/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 transition-all" />
    </form>
</div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- HEADER ROW --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="max-w-xl">
            <p class="text-xs font-semibold text-[#8f7664] leading-relaxed">
                Kelola antrean pesanan secara real-time. Pastikan kualitas pelayanan tetap terjaga untuk setiap pelanggan.
            </p>
        </div>
        <div class="flex items-center gap-1 bg-white/60 p-1 rounded-xl border border-[#ede6df] shadow-sm shrink-0 self-end sm:self-auto">
            <a href="{{ route('karyawan.pesanan') }}" class="rounded-lg px-4 py-1.5 text-xs font-extrabold bg-white text-[#21140b] shadow-sm transition-all">
                Antrean
            </a>
            <a href="{{ route('karyawan.riwayat') }}" class="rounded-lg px-4 py-1.5 text-xs font-bold text-[#8f7664] hover:text-[#21140b] transition-all">
                Riwayat
            </a>
        </div>
    </div>

    {{-- KANBAN BOARD GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- COLUMN 1: DIPROSES --}}
        <div class="space-y-4">
            {{-- Column Header --}}
            <div class="flex items-center justify-between border-b-2 border-[#8c6239]/20 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-orange-50 text-[#8c6239] shadow-sm">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Diproses</h3>
                </div>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#21140b] text-[10px] font-extrabold text-white">
                    {{ $diproses->count() }}
                </span>
            </div>

            {{-- Cards Container --}}
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                @forelse($diproses as $pesanan)
                    <div class="bg-white border border-[#ede6df] shadow-[0_2px_8px_rgba(33,20,11,0.02)] rounded-2xl p-5 hover:shadow-md transition-all space-y-4">
                        {{-- Card Header --}}
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-extrabold text-[#21140b] flex items-center gap-1.5 min-w-0">
                                <span class="shrink-0">{{ $pesanan->order_number }}</span>
                                <span class="text-[#8f7664] font-bold truncate max-w-[100px]" title="{{ $pesanan->user?->nama ?? 'Guest' }}">
                                    • {{ $pesanan->user?->nama ? implode(' ', array_slice(explode(' ', $pesanan->user->nama), 0, 2)) : 'Guest' }}
                                </span>
                            </span>
                            <span class="flex items-center gap-1 font-bold text-[#8f7664] shrink-0">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $pesanan->tanggal_pesan->diffForHumans(null, true) }} ago</span>
                            </span>
                        </div>

                        {{-- Fulfillment Type Badge --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($pesanan->tipe_pesanan === 'diantar')
                                <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-blue-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                                    Diantar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg bg-[#faf2eb] border border-[#eeded3] px-2 py-0.5 text-[10px] font-bold text-[#8c5a3c]">
                                    🏬 Ambil di Toko
                                </span>
                            @endif
                        </div>

                        {{-- Delivery Location Box --}}
                        @if($pesanan->tipe_pesanan === 'diantar' && $pesanan->alamat_pengiriman)
                            <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-2.5 text-left">
                                <p class="text-[9px] font-extrabold text-blue-700 uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    Lokasi Antar:
                                </p>
                                <p class="mt-0.5 text-xs font-bold text-[#1b140e]">
                                    {{ $pesanan->alamat_pengiriman }}
                                </p>
                            </div>
                        @endif

                        {{-- Items List --}}
                        <div class="space-y-2.5">
                            @foreach($pesanan->detailPesanan as $detail)
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <p class="text-xs font-extrabold text-[#21140b]">{{ $detail->jumlah }}x {{ $detail->menu?->nama_menu }}</p>
                                        @if($detail->catatan)
                                            <p class="text-[10px] text-[#8c5a3c] font-medium italic mt-0.5">Note: "{{ $detail->catatan }}"</p>
                                        @endif
                                    </div>
                                    @if($detail->opsi)
                                        <span class="text-[10px] font-semibold text-[#8f7664] italic text-right shrink-0">
                                            {{ $detail->opsi }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan Box --}}
                        @if($pesanan->catatan_lengkap)
                            <div class="rounded-xl bg-[#faf2eb] border border-[#eeded3] p-3">
                                <p class="text-[9px] font-extrabold text-[#8c5a3c] uppercase tracking-wider">Catatan Pesanan:</p>
                                <p class="mt-1 text-xs font-semibold text-[#5a4d42] italic">
                                    "{{ $pesanan->catatan_lengkap }}"
                                </p>
                            </div>
                        @endif

                        {{-- Action Button --}}
                        <form action="{{ route('karyawan.pesanan.update-status', $pesanan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="sedang_dibuat" />
                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold py-2.5 transition-all active:scale-95 shadow-sm cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Mulai Buat</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-[#ede6df] p-8 text-center bg-white/30">
                        <p class="text-xs font-bold text-[#8f7664]">Tidak ada antrean baru</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- COLUMN 2: SEDANG DIBUAT --}}
        <div class="space-y-4">
            {{-- Column Header --}}
            <div class="flex items-center justify-between border-b-2 border-[#8c6239]/20 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-orange-50 text-[#8c6239] shadow-sm">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12v5a6 6 0 01-6 6H12a6 6 0 01-6-6v-5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12h2a2 2 0 002-2V8a2 2 0 00-2-2h-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2M12 3v2M15 3v2" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Sedang Dibuat</h3>
                </div>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#21140b] text-[10px] font-extrabold text-white">
                    {{ $sedangDibuat->count() }}
                </span>
            </div>

            {{-- Cards Container --}}
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                @forelse($sedangDibuat as $pesanan)
                    <div class="bg-white border border-[#ede6df] shadow-[0_2px_8px_rgba(33,20,11,0.02)] rounded-2xl p-5 hover:shadow-md transition-all space-y-4">
                        {{-- Card Header --}}
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-extrabold text-[#21140b] flex items-center gap-1.5 min-w-0">
                                <span class="shrink-0">{{ $pesanan->order_number }}</span>
                                <span class="text-[#8f7664] font-bold truncate max-w-[100px]" title="{{ $pesanan->user?->nama ?? 'Guest' }}">
                                    • {{ $pesanan->user?->nama ? implode(' ', array_slice(explode(' ', $pesanan->user->nama), 0, 2)) : 'Guest' }}
                                </span>
                            </span>
                            <span class="flex items-center gap-1 font-bold text-[#8f7664] shrink-0">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $pesanan->tanggal_pesan->diffForHumans(null, true) }} ago</span>
                            </span>
                        </div>

                        {{-- Fulfillment Type Badge --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($pesanan->tipe_pesanan === 'diantar')
                                <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-blue-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                                    Diantar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg bg-[#faf2eb] border border-[#eeded3] px-2 py-0.5 text-[10px] font-bold text-[#8c5a3c]">
                                    🏬 Ambil di Toko
                                </span>
                            @endif
                        </div>

                        {{-- Delivery Location Box --}}
                        @if($pesanan->tipe_pesanan === 'diantar' && $pesanan->alamat_pengiriman)
                            <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-2.5 text-left">
                                <p class="text-[9px] font-extrabold text-blue-700 uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    Lokasi Antar:
                                </p>
                                <p class="mt-0.5 text-xs font-bold text-[#1b140e]">
                                    {{ $pesanan->alamat_pengiriman }}
                                </p>
                            </div>
                        @endif

                        {{-- Items List --}}
                        <div class="space-y-2.5">
                            @foreach($pesanan->detailPesanan as $detail)
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <p class="text-xs font-extrabold text-[#21140b]">{{ $detail->jumlah }}x {{ $detail->menu?->nama_menu }}</p>
                                        @if($detail->catatan)
                                            <p class="text-[10px] text-[#8c5a3c] font-medium italic mt-0.5">Note: "{{ $detail->catatan }}"</p>
                                        @endif
                                    </div>
                                    @if($detail->opsi)
                                        <span class="text-[10px] font-semibold text-[#8f7664] italic text-right shrink-0">
                                            {{ $detail->opsi }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan Box --}}
                        @if($pesanan->catatan_lengkap)
                            <div class="rounded-xl bg-[#faf2eb] border border-[#eeded3] p-3">
                                <p class="text-[9px] font-extrabold text-[#8c5a3c] uppercase tracking-wider">Catatan Pesanan:</p>
                                <p class="mt-1 text-xs font-semibold text-[#5a4d42] italic">
                                    "{{ $pesanan->catatan_lengkap }}"
                                </p>
                            </div>
                        @endif

                        {{-- Action Button --}}
                        <form action="{{ route('karyawan.pesanan.update-status', $pesanan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="siap_diambil" />
                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#8c6239] hover:bg-[#6f4e2c] text-white text-xs font-bold py-2.5 transition-all active:scale-95 shadow-sm cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Selesai Dibuat</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-[#ede6df] p-8 text-center bg-white/30">
                        <p class="text-xs font-bold text-[#8f7664]">Belum ada pesanan yang dibuat</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- COLUMN 3: SIAP DIAMBIL --}}
        <div class="space-y-4">
            {{-- Column Header --}}
            <div class="flex items-center justify-between border-b-2 border-[#8c6239]/20 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-orange-50 text-[#8c6239] shadow-sm">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-extrabold text-[#21140b]">Siap Diambil / Diantar</h3>
                </div>
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#21140b] text-[10px] font-extrabold text-white">
                    {{ $siapDiambil->count() }}
                </span>
            </div>

            {{-- Cards Container --}}
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                @forelse($siapDiambil as $pesanan)
                    <div class="bg-white border border-[#ede6df] shadow-[0_2px_8px_rgba(33,20,11,0.02)] rounded-2xl p-5 hover:shadow-md transition-all space-y-4">
                        {{-- Card Header --}}
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-extrabold text-[#21140b] flex items-center gap-1.5 min-w-0">
                                <span class="shrink-0">{{ $pesanan->order_number }}</span>
                                <span class="text-[#8f7664] font-bold truncate max-w-[100px]" title="{{ $pesanan->user?->nama ?? 'Guest' }}">
                                    • {{ $pesanan->user?->nama ? implode(' ', array_slice(explode(' ', $pesanan->user->nama), 0, 2)) : 'Guest' }}
                                </span>
                            </span>
                            <span class="flex items-center gap-1 font-bold text-[#8f7664] shrink-0">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $pesanan->tanggal_pesan->diffForHumans(null, true) }} ago</span>
                            </span>
                        </div>

                        {{-- Fulfillment Type Badge --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($pesanan->tipe_pesanan === 'diantar')
                                <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-blue-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                                    Diantar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg bg-[#faf2eb] border border-[#eeded3] px-2 py-0.5 text-[10px] font-bold text-[#8c5a3c]">
                                    🏬 Ambil di Toko
                                </span>
                            @endif
                        </div>

                        {{-- Delivery Location Box --}}
                        @if($pesanan->tipe_pesanan === 'diantar' && $pesanan->alamat_pengiriman)
                            <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-2.5 text-left">
                                <p class="text-[9px] font-extrabold text-blue-700 uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    Lokasi Antar:
                                </p>
                                <p class="mt-0.5 text-xs font-bold text-[#1b140e]">
                                    {{ $pesanan->alamat_pengiriman }}
                                </p>
                            </div>
                        @endif

                        {{-- Items List --}}
                        <div class="space-y-2.5">
                            @foreach($pesanan->detailPesanan as $detail)
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <p class="text-xs font-extrabold text-[#21140b]">{{ $detail->jumlah }}x {{ $detail->menu?->nama_menu }}</p>
                                        @if($detail->catatan)
                                            <p class="text-[10px] text-[#8c5a3c] font-medium italic mt-0.5">Note: "{{ $detail->catatan }}"</p>
                                        @endif
                                    </div>
                                    @if($detail->opsi)
                                        <span class="text-[10px] font-semibold text-[#8f7664] italic text-right shrink-0">
                                            {{ $detail->opsi }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan Box --}}
                        @if($pesanan->catatan_lengkap)
                            <div class="rounded-xl bg-[#faf2eb] border border-[#eeded3] p-3">
                                <p class="text-[9px] font-extrabold text-[#8c5a3c] uppercase tracking-wider">Catatan Pesanan:</p>
                                <p class="mt-1 text-xs font-semibold text-[#5a4d42] italic">
                                    "{{ $pesanan->catatan_lengkap }}"
                                </p>
                            </div>
                        @endif

                        {{-- Payment Confirmed Badge --}}
                        <div class="flex items-center gap-2 rounded-xl bg-[#faf5f0] border border-[#f3e7db] px-3 py-2">
                            <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span class="text-[10px] font-extrabold text-[#8c5a3c]">Payment Confirmed</span>
                        </div>

                        {{-- Action Button --}}
                        <form action="{{ route('karyawan.pesanan.update-status', $pesanan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="selesai" />
                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#1a1210] hover:bg-black text-white text-xs font-bold py-2.5 transition-all active:scale-95 shadow-sm cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>Selesaikan</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-[#ede6df] p-8 text-center bg-white/30">
                        <p class="text-xs font-bold text-[#8f7664]">Belum ada pesanan yang siap diambil</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
