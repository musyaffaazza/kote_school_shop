@extends('layouts.karyawan')

@section('page-title', 'Riwayat Transaksi')

@section('header-search')
<div class="hidden md:block flex-1 max-w-xs mx-4 relative">
    <form action="{{ route('karyawan.riwayat') }}" method="GET">
        <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search Order ID..." class="w-full bg-[#ebdcd0]/75 border-transparent text-xs rounded-xl pl-10 pr-4 py-2 text-[#21140b] placeholder-[#8f7664]/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 transition-all" />
        @if($status !== 'semua')
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        @if($startDate)
            <input type="hidden" name="start_date" value="{{ $startDate }}">
        @endif
        @if($endDate)
            <input type="hidden" name="end_date" value="{{ $endDate }}">
        @endif
    </form>
</div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- FILTER ROW --}}
    <form action="{{ route('karyawan.riwayat') }}" method="GET" id="filter-form" class="flex flex-wrap items-end gap-4 bg-white rounded-2xl border border-[#ede6df]/60 p-5 shadow-[0_2px_12px_rgba(33,20,11,0.02)]">
        @if($search)
            <input type="hidden" name="search" value="{{ $search }}">
        @endif

        <!-- Rentang Waktu -->
        <div class="space-y-1.5 flex-1 min-w-[280px]">
            <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Rentang Waktu</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl pl-9 pr-3 py-2.5 text-xs text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#3d2a1f] transition-all" />
                </div>
                <span class="text-[#8f7664] text-xs font-semibold shrink-0">s/d</span>
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl pl-9 pr-3 py-2.5 text-xs text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#3d2a1f] transition-all" />
                </div>
            </div>
        </div>
        
        <!-- Status -->
        <div class="space-y-1.5 shrink-0 w-full sm:w-auto">
            <label class="text-[10px] font-extrabold text-[#8f7664] uppercase tracking-wider block">Status</label>
            <div class="flex gap-2">
                <button type="button" onclick="setStatus('semua')" class="status-btn px-4 py-2.5 text-xs font-bold rounded-xl border transition-all cursor-pointer {{ $status === 'semua' ? 'bg-[#3d2a1f] text-white border-[#3d2a1f] shadow-sm' : 'bg-[#faf7f2]/40 text-[#8f7664] border-[#e8ded5] hover:bg-[#ebdcd0]/45' }}">Semua</button>
                <button type="button" onclick="setStatus('berhasil')" class="status-btn px-4 py-2.5 text-xs font-bold rounded-xl border transition-all cursor-pointer {{ $status === 'berhasil' ? 'bg-[#3d2a1f] text-white border-[#3d2a1f] shadow-sm' : 'bg-[#faf7f2]/40 text-[#8f7664] border-[#e8ded5] hover:bg-[#ebdcd0]/45' }}">Berhasil</button>
                <button type="button" onclick="setStatus('dibatalkan')" class="status-btn px-4 py-2.5 text-xs font-bold rounded-xl border transition-all cursor-pointer {{ $status === 'dibatalkan' ? 'bg-[#3d2a1f] text-white border-[#3d2a1f] shadow-sm' : 'bg-[#faf7f2]/40 text-[#8f7664] border-[#e8ded5] hover:bg-[#ebdcd0]/45' }}">Dibatalkan</button>
            </div>
            <input type="hidden" name="status" id="input-status" value="{{ $status }}">
        </div>
        
        <!-- Submit Button -->
        <div class="shrink-0 w-full sm:w-auto">
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#8b5a2b] hover:bg-[#6d4722] text-white text-xs font-bold px-5 py-3.5 rounded-xl transition-all cursor-pointer shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Terapkan Filter</span>
            </button>
        </div>
    </form>

    {{-- TABLE CARD --}}
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">
                <thead>
                    <tr class="border-b border-[#ede6df] bg-[#faf2eb]">
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider rounded-l-2xl">No. Order</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider">Waktu</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider">Metode</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider">Total</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-[#8f7664] uppercase tracking-wider rounded-r-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f0eb]">
                    @forelse($transactions as $transaction)
                        @php
                            $isSuccess = in_array($transaction->status_pesanan, ['selesai', 'diproses', 'sedang_dibuat', 'siap_diambil']);
                        @endphp
                        <tr class="hover:bg-[#faf7f2]/50 transition-colors">
                            {{-- No. Order --}}
                            <td class="py-4 px-6 text-sm font-extrabold text-[#21140b]">
                                <div>{{ $transaction->order_number }}</div>
                                <div class="mt-1">
                                    @if($transaction->tipe_pesanan === 'diantar')
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-1.5 py-0.5 text-[9px] font-bold text-blue-700">🛵 Diantar</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-[#faf2eb] px-1.5 py-0.5 text-[9px] font-bold text-[#8c5a3c]">🏬 Ambil di Toko</span>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Waktu --}}
                            <td class="py-4 px-6 text-xs text-[#8f7664] font-semibold">
                                {{ $transaction->tanggal_pesan->locale('id')->translatedFormat('d M Y, H:i') }}
                            </td>
                            
                            {{-- Pelanggan --}}
                            <td class="py-4 px-6 text-sm font-bold text-[#21140b]">
                                {{ $transaction->user ? $transaction->user->nama : 'Guest' }}
                            </td>

                            {{-- Metode --}}
                            <td class="py-4 px-6">
                                @if(strtolower($transaction->metode_pembayaran) === 'qris')
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/40 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7" />
                                            <rect x="14" y="3" width="7" height="7" />
                                            <rect x="14" y="14" width="7" height="7" />
                                            <rect x="3" y="14" width="7" height="7" />
                                        </svg>
                                        <span>QRIS</span>
                                    </span>
                                @elseif(strtolower($transaction->metode_pembayaran) === 'tunai')
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/40 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Tunai</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#ebdcd0]/40 px-2.5 py-1.5 text-xs font-bold text-[#5a4d42]">
                                        <svg class="h-3.5 w-3.5 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <span>Transfer Bank</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Total --}}
                            <td class="py-4 px-6 text-sm font-extrabold text-[#8b5a2b]">
                                Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-4 px-6">
                                @if($transaction->status_pesanan === 'selesai')
                                    <span class="inline-block rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700">
                                        Selesai
                                    </span>
                                @elseif($transaction->status_pesanan === 'siap_diambil')
                                    <span class="inline-block rounded-xl border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-bold text-blue-700">
                                        Siap Diambil
                                    </span>
                                @elseif($transaction->status_pesanan === 'sedang_dibuat')
                                    <span class="inline-block rounded-xl border border-amber-100 bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700">
                                        Sedang Dibuat
                                    </span>
                                @elseif($transaction->status_pesanan === 'diproses')
                                    <span class="inline-block rounded-xl border border-orange-100 bg-orange-50 px-3 py-1 text-[11px] font-bold text-orange-700">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-block rounded-xl border border-red-100 bg-red-50 px-3 py-1 text-[11px] font-bold text-red-700">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6">
                                @if($isSuccess)
                                    <button type="button" onclick="openStrukModal('{{ $transaction->id_pesanan }}')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#8b5a2b] hover:text-[#3d2a1f] transition-colors cursor-pointer border-none bg-transparent">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Lihat Struk</span>
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-300 cursor-not-allowed select-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Lihat Struk</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f5f0eb] text-[#a2785d]">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-[#21140b]">Tidak ada transaksi ditemukan</p>
                                    <p class="text-xs text-[#8f7664]">Coba ubah filter atau lakukan pencarian lain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION ROW --}}
        @if($transactions->hasPages())
            <div class="border-t border-[#ede6df] px-6 py-4 bg-[#faf9f6] flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-[#8f7664] font-semibold">
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </p>
                <div class="flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if($transactions->onFirstPage())
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-gray-100 text-gray-400 text-xs cursor-not-allowed select-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white border border-[#e8ded5] text-[#21140b] hover:bg-[#faf7f2] hover:border-[#8b5a2b] transition-all text-xs font-bold active:scale-95 shadow-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if($page === $transactions->currentPage())
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#3d2a1f] text-white text-xs font-bold shadow-md shadow-[#3d2a1f]/10 select-none">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white border border-[#e8ded5] text-[#8f7664] hover:bg-[#faf7f2] hover:text-[#21140b] transition-all text-xs font-bold active:scale-95 shadow-sm">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white border border-[#e8ded5] text-[#21140b] hover:bg-[#faf7f2] hover:border-[#8b5a2b] transition-all text-xs font-bold active:scale-95 shadow-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-gray-100 text-gray-400 text-xs cursor-not-allowed select-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- STATS CARDS ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
        <!-- Card 1: Total Penjualan Hari Ini -->
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-[0_2px_12px_rgba(33,20,11,0.02)] flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#fbf2eb] text-[#8b5a2b]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Total Penjualan Hari Ini</p>
                <p class="text-lg font-extrabold text-[#21140b] mt-0.5">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 2: Total Transaksi Berhasil -->
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-[0_2px_12px_rgba(33,20,11,0.02)] flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#fbf2eb] text-[#8b5a2b]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Total Transaksi Berhasil</p>
                <p class="text-lg font-extrabold text-[#21140b] mt-0.5">{{ number_format($totalSuccessCount, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 3: Pelanggan Unik -->
        <div class="rounded-2xl bg-white p-5 border border-[#ede6df]/60 shadow-[0_2px_12px_rgba(33,20,11,0.02)] flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#fbf2eb] text-[#8b5a2b]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Pelanggan Unik</p>
                <p class="text-lg font-extrabold text-[#21140b] mt-0.5">{{ number_format($uniqueCustomersCount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

</div>

{{-- MODAL DETAIL STRUK --}}
<div id="modal-detail-struk" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity no-print" onclick="closeStrukModal()"></div>

    {{-- Panel --}}
    <div id="struk-print-area" class="relative w-full max-w-sm rounded-2xl bg-white shadow-2xl overflow-hidden p-6 flex flex-col space-y-4 border border-[#ede6df] text-left">
        {{-- Close button top right --}}
        <button type="button" onclick="closeStrukModal()" class="no-print absolute top-4 right-4 flex h-7 w-7 items-center justify-center rounded-lg bg-[#faf7f2] text-[#8f7664] hover:text-[#21140b] hover:bg-[#ebdcd0]/60 transition-colors cursor-pointer" title="Tutup">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Receipt Header --}}
        <div class="text-center space-y-1 pt-1">
            <h2 class="text-base font-black text-[#21140b] tracking-wider uppercase">KOTE SCHOOL SHOP</h2>
            <p class="text-[10px] text-[#8f7664] font-bold tracking-wide">Premium Artisanal Coffee</p>
        </div>

        {{-- Receipt Metadata --}}
        <div class="border-y border-dashed border-[#ede6df] py-3 space-y-1.5 text-xs text-[#5a4d42]">
            <div class="flex justify-between">
                <span>Order ID:</span>
                <span id="struk-order-id" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span id="struk-tanggal" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu:</span>
                <span id="struk-waktu" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span id="struk-kasir" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Pelanggan:</span>
                <span id="struk-pelanggan" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Metode:</span>
                <span id="struk-metode" class="font-bold text-[#21140b]">-</span>
            </div>
            <div class="flex justify-between">
                <span>Layanan:</span>
                <span id="struk-layanan" class="font-bold text-[#21140b]">-</span>
            </div>
            <div id="struk-alamat-row" class="flex justify-between hidden">
                <span>Lokasi Antar:</span>
                <span id="struk-alamat" class="font-bold text-[#21140b] text-right max-w-[180px]">-</span>
            </div>
        </div>

        {{-- Receipt Items --}}
        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1" id="struk-items-container">
            <!-- Dynamic receipt items go here -->
        </div>

        {{-- Receipt Totals --}}
        <div class="border-t border-dashed border-[#ede6df] pt-3 space-y-1.5 text-xs">
            <div class="flex justify-between text-[#5a4d42]">
                <span>Subtotal:</span>
                <span id="struk-subtotal" class="font-semibold">-</span>
            </div>
            <div class="flex justify-between text-[#5a4d42]" id="struk-diskon-row">
                <span>Diskon:</span>
                <span id="struk-diskon" class="font-semibold text-emerald-600">Rp 0</span>
            </div>
            <div class="flex justify-between text-[#5a4d42]">
                <span>Biaya Layanan:</span>
                <span id="struk-biaya-layanan" class="font-semibold">-</span>
            </div>
            <div class="flex justify-between text-[#21140b] font-extrabold text-sm border-t border-[#f5f0eb] pt-2">
                <span>TOTAL:</span>
                <span id="struk-total" class="text-base text-[#8b5a2b] font-black">-</span>
            </div>
        </div>

        {{-- Receipt Footer --}}
        <div class="text-center pt-2 text-[10px] text-[#8f7664] border-t border-dashed border-[#ede6df] space-y-0.5">
            <p class="font-bold text-[#21140b]">Terima kasih atas kunjungan Anda!</p>
            <p class="text-[9px] text-[#b09a87]">Simpan struk ini sebagai bukti pembayaran</p>
        </div>

        {{-- Modal Actions --}}
        <div class="space-y-2 pt-2 modal-actions no-print">
            <button onclick="printReceipt()" class="w-full bg-[#21140b] hover:bg-[#3d2a1f] text-white py-3 rounded-xl text-xs font-bold transition-all active:scale-[0.98] cursor-pointer text-center shadow-sm flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Struk</span>
            </button>
            <button onclick="closeStrukModal()" class="w-full bg-white border border-[#ede6df] text-[#8f7664] hover:bg-[#faf7f2] py-2.5 rounded-xl text-xs font-bold transition-all active:scale-[0.98] cursor-pointer text-center">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    @page {
        size: 80mm auto;
        margin: 0;
    }
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    body * {
        visibility: hidden !important;
    }
    #modal-detail-struk,
    #modal-detail-struk #struk-print-area,
    #modal-detail-struk #struk-print-area * {
        visibility: visible !important;
    }
    #modal-detail-struk {
        position: fixed !important;
        inset: 0 !important;
        background: transparent !important;
        display: flex !important;
        justify-content: center !important;
        align-items: flex-start !important;
        padding: 0 !important;
        margin: 0 !important;
        z-index: 99999 !important;
    }
    #struk-print-area {
        position: relative !important;
        width: 78mm !important;
        max-width: 78mm !important;
        border: none !important;
        box-shadow: none !important;
        padding: 10px !important;
        margin: 0 auto !important;
        background: #ffffff !important;
        overflow: visible !important;
        max-height: none !important;
    }
    #struk-items-container {
        max-height: none !important;
        overflow: visible !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<script>
    function setStatus(statusVal) {
        document.getElementById('input-status').value = statusVal;

        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.className = "status-btn px-4 py-2.5 text-xs font-bold rounded-xl border transition-all cursor-pointer bg-[#faf7f2]/40 text-[#8f7664] border-[#e8ded5] hover:bg-[#ebdcd0]/45";
        });

        const targetBtn = document.querySelector(`button[onclick*="setStatus('${statusVal}')"]`);
        if (targetBtn) {
            targetBtn.className = "status-btn px-4 py-2.5 text-xs font-bold rounded-xl border transition-all cursor-pointer bg-[#3d2a1f] text-white border-[#3d2a1f] shadow-sm";
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function openStrukModal(orderId) {
        const modal = document.getElementById('modal-detail-struk');
        if (!modal) return;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Reset display to loading state
        document.getElementById('struk-order-id').innerText = 'Memuat...';
        document.getElementById('struk-tanggal').innerText = '-';
        document.getElementById('struk-waktu').innerText = '-';
        document.getElementById('struk-kasir').innerText = '-';
        document.getElementById('struk-pelanggan').innerText = '-';
        document.getElementById('struk-metode').innerText = '-';
        document.getElementById('struk-layanan').innerText = '-';
        const alamatRow = document.getElementById('struk-alamat-row');
        if (alamatRow) alamatRow.classList.add('hidden');
        document.getElementById('struk-items-container').innerHTML = '<div class="text-center text-xs text-[#8f7664] py-4">Memuat data...</div>';
        document.getElementById('struk-subtotal').innerText = '-';
        document.getElementById('struk-diskon').innerText = '-';
        document.getElementById('struk-biaya-layanan').innerText = '-';
        document.getElementById('struk-total').innerText = '-';

        const url = '/karyawan/riwayat/' + orderId + '/struk';

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
        .then(function(res) {
            if (!res.ok) {
                return res.text().then(function(text) {
                    throw new Error('Server error ' + res.status);
                });
            }
            return res.json();
        })
        .then(function(data) {
            if (!data.success) {
                document.getElementById('struk-order-id').innerText = 'Error';
                document.getElementById('struk-items-container').innerHTML = '<div class="text-center text-xs text-red-500 py-4">Data transaksi tidak valid.</div>';
                return;
            }

            document.getElementById('struk-order-id').innerText = data.order_number || '-';
            document.getElementById('struk-tanggal').innerText = data.tanggal || '-';
            document.getElementById('struk-waktu').innerText = data.waktu || '-';
            document.getElementById('struk-kasir').innerText = data.kasir || '-';
            document.getElementById('struk-pelanggan').innerText = data.pelanggan || 'Guest';
            document.getElementById('struk-metode').innerText = data.metode_pembayaran || '-';
            document.getElementById('struk-layanan').innerText = data.tipe_pesanan_label || 'Ambil di Toko';

            if (data.tipe_pesanan === 'diantar' && data.alamat_pengiriman) {
                if (alamatRow) {
                    document.getElementById('struk-alamat').innerText = data.alamat_pengiriman;
                    alamatRow.classList.remove('hidden');
                }
            } else {
                if (alamatRow) alamatRow.classList.add('hidden');
            }

            // Items
            const container = document.getElementById('struk-items-container');
            container.innerHTML = '';

            (data.items || []).forEach(function(item) {
                const row = document.createElement('div');
                row.className = 'py-1.5 border-b border-dashed border-[#ede6df]/60 last:border-0';
                
                let notesHtml = '';
                if (item.catatan) {
                    notesHtml = '<div class="text-[10px] text-[#b09a87] italic mt-0.5">Catatan: ' + escapeHtml(item.catatan) + '</div>';
                }

                row.innerHTML = 
                    '<div class="flex justify-between text-xs font-semibold text-[#21140b]">' +
                        '<span>' + escapeHtml(item.nama) + '</span>' +
                        '<span class="font-bold">Rp ' + item.subtotal + '</span>' +
                    '</div>' +
                    '<div class="flex justify-between text-[11px] text-[#8f7664] mt-0.5">' +
                        '<span>' + item.jumlah + 'x @ Rp ' + item.harga + '</span>' +
                    '</div>' +
                    notesHtml;
                
                container.appendChild(row);
            });

            // Totals
            document.getElementById('struk-subtotal').innerText = 'Rp ' + (data.subtotal || '0');
            
            const diskonEl = document.getElementById('struk-diskon');
            const diskonVal = data.diskon || '0';
            if (diskonVal !== '0' && diskonVal !== '0,00' && diskonVal !== '') {
                diskonEl.innerText = '-Rp ' + diskonVal;
            } else {
                diskonEl.innerText = 'Rp 0';
            }

            document.getElementById('struk-biaya-layanan').innerText = 'Rp ' + (data.biaya_layanan || '0');
            document.getElementById('struk-total').innerText = 'Rp ' + (data.total || '0');
        })
        .catch(function(err) {
            console.error('Error fetching struk:', err);
            document.getElementById('struk-order-id').innerText = 'Error';
            document.getElementById('struk-items-container').innerHTML = '<div class="text-center text-xs text-red-500 py-4">Gagal memuat data struk.<br>Silakan coba lagi.</div>';
        });
    }

    function closeStrukModal() {
        document.getElementById('modal-detail-struk').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function printReceipt() {
        window.print();
    }
</script>
@endsection
