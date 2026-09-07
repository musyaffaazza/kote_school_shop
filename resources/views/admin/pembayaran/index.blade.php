@extends('layouts.admin')

@section('page-title', 'Manajemen Pembayaran')

@section('content')
<div class="space-y-6">
    {{-- Title and Subtitle --}}
    <div class="space-y-1">
        <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b]">
            Manajemen Pembayaran
        </h1>
        <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
            @if($tab === 'riwayat')
                Kelola data transaksi pembayaran dari pelanggan Kote School Shop.
            @else
                Kelola bagaimana pelanggan membayar pesanan mereka di toko.
            @endif
        </p>
    </div>

    {{-- Tab Selector --}}
    <div class="flex border-b border-[#ede6df]/60 mb-6 gap-2">
        <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat']) }}" class="py-3 px-5 font-bold text-xs tracking-wider uppercase border-b-2 transition-all {{ $tab === 'riwayat' ? 'border-[#3d2a1f] text-[#3d2a1f]' : 'border-transparent text-[#8f7664] hover:text-[#21140b]' }}">
            Riwayat Transaksi
        </a>
        <a href="{{ route('admin.pembayaran.index', ['tab' => 'metode']) }}" class="py-3 px-5 font-bold text-xs tracking-wider uppercase border-b-2 transition-all {{ $tab === 'metode' ? 'border-[#3d2a1f] text-[#3d2a1f]' : 'border-transparent text-[#8f7664] hover:text-[#21140b]' }}">
            Metode Pembayaran
        </a>
    </div>

    @if($tab === 'riwayat')
        {{-- ========================================== --}}
        {{-- TAB: RIWAYAT TRANSAKSI                     --}}
        {{-- ========================================== --}}

        {{-- STAT CARDS (TOP ROW) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Total Pembayaran Masuk --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/60 shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-600 border border-emerald-100">+12% vs bln lalu</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs font-bold text-[#8f7664] uppercase tracking-wider">Total Pembayaran Masuk</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight mt-1">Rp{{ $stats['totalPembayaranMasuk'] }}</p>
                </div>
            </div>

            {{-- Menunggu Verifikasi --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fffbeb] text-amber-600 border border-[#fef3c7] shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs font-bold text-[#8f7664] uppercase tracking-wider">Menunggu Verifikasi</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight mt-1">{{ $stats['menungguVerifikasi'] }}</p>
                </div>
            </div>

            {{-- Ditolak --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600 border border-red-100 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs font-bold text-[#8f7664] uppercase tracking-wider">Ditolak</p>
                    <p class="text-2xl font-extrabold text-[#21140b] tracking-tight mt-1">{{ $stats['ditolak'] }}</p>
                </div>
            </div>
        </div>

        {{-- RIWAYAT TRANSAKSI SECTION --}}
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <h3 class="text-lg font-extrabold text-[#21140b] self-start md:self-auto">Riwayat Transaksi</h3>

                <div class="flex flex-wrap gap-2 w-full md:w-auto items-center justify-end">
                    {{-- Search Input --}}
                    <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex items-center gap-2 flex-1 sm:flex-initial">
                        <input type="hidden" name="tab" value="riwayat">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        <div class="relative w-full sm:w-48">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Pesanan / Nama..." class="w-full bg-white border border-[#ede6df] text-xs rounded-xl pl-9 pr-4 py-2.5 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 transition-all" />
                        </div>
                        <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95">
                            Cari
                        </button>
                    </form>

                    {{-- Actions Button --}}
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'metode']) }}" class="flex items-center justify-center gap-1.5 bg-[#8b5a2b] hover:bg-[#3d2a1f] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>Edit Metode</span>
                    </a>
                    <button onclick="showToast('Mengekspor data riwayat ke PDF...', 'info')" class="flex items-center justify-center gap-1.5 bg-white border border-[#ede6df] hover:bg-[#faf7f2] text-[#21140b] text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap">
                        <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Export PDF</span>
                    </button>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat']) }}" class="flex items-center justify-center gap-1.5 bg-white border border-[#ede6df] hover:bg-[#faf7f2] text-[#21140b] text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95">
                        <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18.24" />
                        </svg>
                        <span>Segarkan</span>
                    </a>
                </div>
            </div>

            {{-- Status Tabs Filter --}}
            <div class="flex flex-wrap gap-2 border-b border-[#ede6df]/50 pb-2">
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat', 'status' => 'semua', 'search' => request('search')]) }}" class="rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $statusFilter === 'semua' ? 'bg-[#3d2a1f] text-white' : 'bg-white border border-[#ede6df] text-[#8f7664] hover:text-[#21140b]' }}">
                    Semua
                </a>
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat', 'status' => 'menunggu', 'search' => request('search')]) }}" class="rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $statusFilter === 'menunggu' ? 'bg-[#3d2a1f] text-white' : 'bg-white border border-[#ede6df] text-[#8f7664] hover:text-[#21140b]' }}">
                    Menunggu Verifikasi
                </a>
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat', 'status' => 'terverifikasi', 'search' => request('search')]) }}" class="rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $statusFilter === 'terverifikasi' ? 'bg-[#3d2a1f] text-white' : 'bg-white border border-[#ede6df] text-[#8f7664] hover:text-[#21140b]' }}">
                    Terverifikasi
                </a>
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'riwayat', 'status' => 'ditolak', 'search' => request('search')]) }}" class="rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $statusFilter === 'ditolak' ? 'bg-[#3d2a1f] text-white' : 'bg-white border border-[#ede6df] text-[#8f7664] hover:text-[#21140b]' }}">
                    Ditolak
                </a>
            </div>

            {{-- TABLE CARD --}}
            <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] table-auto">
                        <thead>
                            <tr class="border-b border-[#ede6df] bg-[#faf2eb]">
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">NO</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">NO PESANAN</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">PELANGGAN</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">METODE</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">JUMLAH</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">BUKTI BAYAR</th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-[#8f7664] uppercase tracking-wider">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f5f0eb]">
                            @forelse($pembayaranList as $index => $pembayaran)
                                @php
                                    $order = $pembayaran->pesanan;
                                    $user = $order?->user;
                                @endphp
                                <tr class="group hover:bg-[#fdfaf7] transition-colors">
                                    {{-- NO --}}
                                    <td class="py-4 px-6 text-xs text-[#8f7664] font-bold">
                                        {{ $pembayaranList->firstItem() + $index }}
                                    </td>
                                    {{-- NO PESANAN --}}
                                    <td class="py-4 px-6 text-sm font-extrabold text-[#21140b]">
                                        {{ $order ? $order->order_number : '-' }}
                                    </td>
                                    {{-- PELANGGAN --}}
                                    <td class="py-4 px-6 text-sm font-bold text-[#21140b]">
                                        {{ $user ? $user->nama : 'Guest' }}
                                    </td>
                                    {{-- METODE --}}
                                    <td class="py-4 px-6 text-xs">
                                        @if(strtolower($pembayaran->metode) === 'qris')
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 px-2 py-1 font-bold">
                                                QRIS
                                            </span>
                                        @elseif(strtolower($pembayaran->metode) === 'tunai')
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-stone-100 text-stone-700 border border-stone-200 px-2 py-1 font-bold">
                                                TUNAI
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 px-2 py-1 font-bold">
                                                TRANSFER BANK
                                            </span>
                                        @endif
                                    </td>
                                    {{-- JUMLAH --}}
                                    <td class="py-4 px-6 text-sm font-extrabold text-[#8b5a2b]">
                                        Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}
                                    </td>
                                    {{-- BUKTI BAYAR --}}
                                    <td class="py-4 px-6">
                                        @if($pembayaran->bukti_transfer)
                                            <button onclick="openVerifikasiModal('{{ $order ? $order->order_number : '-' }}', '{{ $user ? $user->nama : 'Guest' }}', '{{ $pembayaran->metode }}', 'Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}', '{{ $pembayaran->bukti_transfer }}', '{{ route('admin.pembayaran.setujui', $pembayaran->id_pembayaran) }}', '{{ route('admin.pembayaran.tolak', $pembayaran->id_pembayaran) }}')" class="h-10 w-10 rounded-lg overflow-hidden border border-[#ede6df] bg-stone-50 flex items-center justify-center cursor-pointer hover:border-[#a2785d] transition-all">
                                                <img src="{{ asset($pembayaran->bukti_transfer) }}" alt="Bukti" class="h-full w-full object-cover">
                                            </button>
                                        @else
                                            <span class="text-xs text-[#8f7664] italic">Tidak ada</span>
                                        @endif
                                    </td>
                                    {{-- STATUS --}}
                                    <td class="py-4 px-6">
                                        @if($pembayaran->status === 'menunggu')
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 border border-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    Menunggu Verifikasi
                                                </span>
                                                <button onclick="openVerifikasiModal('{{ $order ? $order->order_number : '-' }}', '{{ $user ? $user->nama : 'Guest' }}', '{{ $pembayaran->metode }}', 'Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}', '{{ $pembayaran->bukti_transfer }}', '{{ route('admin.pembayaran.setujui', $pembayaran->id_pembayaran) }}', '{{ route('admin.pembayaran.tolak', $pembayaran->id_pembayaran) }}')" class="rounded-xl bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 text-xs font-bold transition-all cursor-pointer">
                                                    Verifikasi
                                                </button>
                                            </div>
                                        @elseif($pembayaran->status === 'berhasil')
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 border border-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Terverifikasi
                                            </span>
                                        @elseif($pembayaran->status === 'gagal')
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 border border-red-100 px-2.5 py-1 text-xs font-bold text-red-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f5f0eb] text-[#a2785d]">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-[#21140b]">Tidak ada data pembayaran</p>
                                            <p class="text-xs text-[#8f7664]">Tidak ditemukan transaksi pembayaran yang sesuai.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($pembayaranList->hasPages())
                    <div class="border-t border-[#ede6df]/60 px-6 py-4 bg-[#faf2eb]/30">
                        {{ $pembayaranList->links() }}
                    </div>
                @endif
            </div>
        </div>

    @else
        {{-- ========================================== --}}
        {{-- TAB: METODE PEMBAYARAN                     --}}
        {{-- ========================================== --}}

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-[#21140b]">Metode Pembayaran Aktif</h3>
                    <p class="text-xs text-[#8f7664] font-semibold mt-0.5">Kelola bagaimana pelanggan membayar pesanan mereka.</p>
                </div>
            </div>

            <div class="space-y-6">

                {{-- QRIS DYNAMIC CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 p-6 shadow-sm">
                    <form action="{{ route('admin.pembayaran.settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="method_type" value="qris">
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                            {{-- QR Image Column (Span 4) --}}
                            <div class="md:col-span-4 flex flex-col items-center justify-center p-4 bg-[#faf7f5] rounded-2xl border border-[#ede6df]/80">
                                <p class="text-[10px] font-bold text-[#8f7664] uppercase mb-3">Tampilan QR Code</p>
                                <div class="rounded-xl overflow-hidden bg-white p-2.5 border border-[#edd8cf] shadow-sm mb-4">
                                    <img src="{{ asset($paymentSettings['qris']['qr_image'] ?? 'images/QR Pembayaran.jpeg') }}" class="w-36 h-36 object-contain" alt="QRIS Code">
                                </div>
                                <label class="cursor-pointer bg-white border border-[#ede6df] hover:bg-[#faf7f2] text-xs font-bold px-3 py-2 rounded-xl transition-all shadow-sm">
                                    Unggah QR Baru
                                    <input type="file" name="qr_image" accept="image/*" class="hidden" onchange="this.form.submit()">
                                </label>
                            </div>

                            {{-- Form Fields Column (Span 8) --}}
                            <div class="md:col-span-8 space-y-4">
                                <div class="flex items-center justify-between border-b border-[#f5f0eb] pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-[#fbf1e8] text-[#a2785d]">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <rect x="3" y="3" width="7" height="7" />
                                                <rect x="14" y="3" width="7" height="7" />
                                                <rect x="14" y="14" width="7" height="7" />
                                                <rect x="3" y="14" width="7" height="7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-[#21140b]">QRIS Dynamic</h4>
                                            <p class="text-[10px] text-[#8f7664] font-semibold">Verifikasi otomatis melalui scan kode QR.</p>
                                        </div>
                                    </div>
                                    
                                    {{-- Status Toggle --}}
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-[#8f7664]">Status:</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="status" value="1" {{ ($paymentSettings['qris']['status'] ?? false) ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                            <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5 text-left">
                                        <label class="text-xs font-bold text-[#8f7664]">MERCHANT ID</label>
                                        <input type="text" name="merchant_id" value="{{ $paymentSettings['qris']['merchant_id'] ?? '' }}" placeholder="KOTE-SHOP-001" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl px-4 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10" />
                                    </div>
                                    <div class="space-y-1.5 text-left">
                                        <label class="text-xs font-bold text-[#8f7664]">MERCHANT NAME</label>
                                        <input type="text" name="merchant_name" value="{{ $paymentSettings['qris']['merchant_name'] ?? '' }}" placeholder="KOTE SCHOOL SHOP" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl px-4 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10" />
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-5 py-3 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TRANSFER BANK CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 p-6 shadow-sm">
                    <form action="{{ route('admin.pembayaran.settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method_type" value="transfer_bank">

                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-[#f5f0eb] pb-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-[#fbf1e8] text-[#a2785d]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#21140b]">Transfer Bank</h4>
                                        <p class="text-[10px] text-[#8f7664] font-semibold">Pelanggan melakukan transfer manual ke rekening toko.</p>
                                    </div>
                                </div>

                                {{-- Status Toggle --}}
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-[#8f7664]">Status:</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="status" value="1" {{ ($paymentSettings['transfer_bank']['status'] ?? false) ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                        <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1.5 text-left">
                                    <label class="text-xs font-bold text-[#8f7664]">PILIH BANK</label>
                                    <select name="bank_name" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl px-4 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 cursor-pointer">
                                        <option value="BCA" {{ ($paymentSettings['transfer_bank']['bank_name'] ?? '') === 'BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                        <option value="Mandiri" {{ ($paymentSettings['transfer_bank']['bank_name'] ?? '') === 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                        <option value="BNI" {{ ($paymentSettings['transfer_bank']['bank_name'] ?? '') === 'BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                                        <option value="BRI" {{ ($paymentSettings['transfer_bank']['bank_name'] ?? '') === 'BRI' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5 text-left">
                                    <label class="text-xs font-bold text-[#8f7664]">NO. REKENING</label>
                                    <input type="text" name="no_rekening" value="{{ $paymentSettings['transfer_bank']['no_rekening'] ?? '' }}" placeholder="8291002345" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl px-4 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10" />
                                </div>
                                <div class="space-y-1.5 text-left">
                                    <label class="text-xs font-bold text-[#8f7664]">NAMA PEMILIK REKENING</label>
                                    <input type="text" name="nama_pemilik" value="{{ $paymentSettings['transfer_bank']['nama_pemilik'] ?? '' }}" placeholder="KOTE SCHOOL SHOP" class="w-full bg-[#fdfbf9] border border-[#e8ded5] text-xs rounded-xl px-4 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10" />
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-5 py-3 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TUNAI (CASH ON DELIVERY) CARD --}}
                <div class="rounded-2xl bg-white border border-[#ede6df]/60 p-6 shadow-sm">
                    <form action="{{ route('admin.pembayaran.settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method_type" value="tunai">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-[#fbf1e8] text-[#a2785d]">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-[#21140b]">Tunai (Cash on Delivery)</h4>
                                    <p class="text-[10px] text-[#8f7664] font-semibold">Pelanggan membayar secara tunai langsung di kasir atau di tempat.</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 self-end sm:self-auto">
                                {{-- Status Toggle --}}
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-[#8f7664]">Status:</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="status" value="1" {{ ($paymentSettings['tunai']['status'] ?? false) ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                        <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>

                                <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endif
</div>

{{-- MODAL DETAIL & VERIFIKASI PEMBAYARAN --}}
<div id="modal-verifikasi-pembayaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
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

            <div class="border-t border-[#f0ebe5] pt-4">
                <p class="text-xs font-semibold text-[#8f7664] mb-2">Bukti Pembayaran / Transfer</p>
                <div class="rounded-xl border border-[#ede6df] overflow-hidden bg-stone-50 max-h-80 flex items-center justify-center">
                    <img id="modal-receipt-img" src="" alt="Bukti Transfer" class="max-h-80 w-auto object-contain">
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
<div id="modal-konfirmasi-tolak" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
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
        
        // Handle public asset paths correctly
        if (receiptImgSrc) {
            document.getElementById('modal-receipt-img').src = receiptImgSrc.startsWith('http') || receiptImgSrc.startsWith('/') 
                ? receiptImgSrc 
                : '/' + receiptImgSrc;
        } else {
            document.getElementById('modal-receipt-img').src = 'https://placehold.co/400';
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
