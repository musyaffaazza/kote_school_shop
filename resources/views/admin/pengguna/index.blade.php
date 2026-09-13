@extends('layouts.admin')

@section('page-title', 'Data Pengguna')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1 text-left">
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#21140b]">
                    Data Pengguna
                </h1>
                <span class="rounded-full bg-[#fbf1e8] text-[#8c5a3c] border border-[#edd8cf] px-3 py-0.5 text-xs font-extrabold">
                    Pelanggan
                </span>
            </div>
            <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
                Kelola informasi akun pelanggan, status keanggotaan, serta riwayat aktivitas pesanan KOTE School Shop.
            </p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0 self-stretch sm:self-auto justify-end">
            {{-- Ekspor CSV --}}
            <a href="{{ route('admin.pengguna.export') }}" class="inline-flex items-center gap-2 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-[#21140b] text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-2xs hover:shadow-xs active:scale-95">
                <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Ekspor CSV</span>
            </a>

            {{-- Tambah Pengguna --}}
            <button type="button" onclick="openModal('modal-tambah-pengguna')" class="inline-flex items-center gap-2 bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>+ Tambah Pengguna</span>
            </button>
        </div>
    </div>

    {{-- KPI SUMMARY STATS CARDS (2 Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Card 1: Total Pelanggan --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/70 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1.5">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#8f7664]">Total Pelanggan</p>
                    <h3 class="text-3xl font-black text-[#21140b] tracking-tight">{{ number_format($totalPengguna, 0, ',', '.') }}</h3>
                    <p class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-extrabold text-[10px]">
                            +{{ $totalPengguna }}
                        </span>
                        <span class="text-[#8f7664] font-medium">terdaftar di sistem</span>
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#faf5f0] text-[#8b5a2b] border border-[#e8dfd5]/70 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Pelanggan Aktif --}}
        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 border border-[#ede6df]/70 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1.5">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#8f7664]">Pelanggan Aktif</p>
                    <h3 class="text-3xl font-black text-[#21140b] tracking-tight">{{ number_format($penggunaAktif, 0, ',', '.') }}</h3>
                    <p class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-extrabold text-[10px]">
                            {{ $persenAktif }}%
                        </span>
                        <span class="text-[#8f7664] font-medium">pernah berbelanja</span>
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH ROW --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-3 rounded-2xl border border-[#ede6df] shadow-xs">
        <form action="{{ route('admin.pengguna.index') }}" method="GET" class="flex w-full sm:max-w-md items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, no. HP, NIS..." class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-9 pr-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all" />
            </div>
            <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-xs active:scale-95 whitespace-nowrap">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.pengguna.index') }}" class="px-3 py-2.5 rounded-xl border border-[#ede6df] bg-white hover:bg-[#faf7f2] text-xs font-bold text-[#8f7664] transition-all" title="Reset Pencarian">
                    ✕
                </a>
            @endif
        </form>
    </div>

    {{-- USERS TABLE CARD (No dividing lines) --}}
    <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="bg-[#faf7f2]/80 text-[#8f7664] font-extrabold uppercase tracking-wider text-[11px]">
                        <th class="px-5 py-4 w-12 text-center">NO</th>
                        <th class="px-5 py-4">PELANGGAN</th>
                        <th class="px-5 py-4">TIPE & KELAS</th>
                        <th class="px-5 py-4">KONTAK</th>
                        <th class="px-5 py-4">TOTAL BELANJA</th>
                        <th class="px-5 py-4">STATUS</th>
                        <th class="px-5 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penggunas as $index => $user)
                        @php
                            $isAktif = $user->pesanan_count > 0;
                            $isSiswa = (!empty($user->kelas) && $user->kelas !== '-');
                        @endphp
                        <tr class="hover:bg-[#faf7f2]/80 transition-colors">
                            {{-- No --}}
                            <td class="px-5 py-4 font-semibold text-[#8f7664] text-center">
                                {{ ($penggunas->currentPage() - 1) * $penggunas->perPage() + $index + 1 }}
                            </td>

                            {{-- Pelanggan (Avatar + Name + ID) --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#3d2a1f] text-xs font-bold text-[#d5c6b8] uppercase shadow-2xs">
                                            {{ substr($user->nama, 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-sm text-[#21140b] leading-tight">{{ $user->nama }}</h4>
                                        <div class="mt-0.5 flex items-center gap-2 text-[10px] text-[#8f7664] font-semibold">
                                            <span>{{ $user->formatted_id }}</span>
                                            @if($user->nis)
                                                <span>• NIS: {{ $user->nis }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Tipe & Kelas --}}
                            <td class="px-5 py-4">
                                @if($isSiswa)
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-[#fbf1e8] text-[#8c5a3c] border border-[#edd8cf] px-2 py-0.5 text-[10px] font-bold">
                                            <span>🎓 Siswa</span>
                                        </span>
                                        <p class="text-[11px] font-bold text-[#21140b]">{{ $user->kelas }}</p>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md bg-[#f5f1ed] text-[#6b584c] border border-[#e5ded7] px-2 py-0.5 text-[10px] font-bold">
                                        <span>👤 Guru / Umum</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Kontak (Email & Phone) --}}
                            <td class="px-5 py-4">
                                <div class="space-y-0.5">
                                    <p class="font-bold text-[#21140b]">{{ $user->email }}</p>
                                    @if($user->no_hp)
                                        @php
                                            $cleanHp = preg_replace('/[^0-9]/', '', $user->no_hp);
                                            $waNumber = str_starts_with($cleanHp, '0') ? '62' . substr($cleanHp, 1) : $cleanHp;
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                                            <svg class="h-3 w-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.288.043.088.072.19.014.305-.058.115-.087.187-.173.289l-.26.309c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.761.458 3.418 1.258 4.864l-1.336 4.887 5.006-1.313c1.396.762 2.991 1.198 4.686 1.198 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/>
                                            </svg>
                                            <span>{{ $user->no_hp }}</span>
                                        </a>
                                    @else
                                        <p class="text-[11px] text-[#8f7664]">-</p>
                                    @endif
                                </div>
                            </td>

                            {{-- Total Belanja --}}
                            <td class="px-5 py-4">
                                <div class="space-y-0.5">
                                    <p class="font-extrabold text-[#21140b]">{{ $user->pesanan_count }} Pesanan</p>
                                    <p class="text-[11px] font-bold text-[#8c5a3c]">
                                        Rp {{ number_format($user->pesanan_sum_total_harga ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if($isAktif)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200/80">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#f5f1ed] px-3 py-1 text-[11px] font-bold text-[#8f7664] border border-[#e5ded7]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#b09e91]"></span>
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    {{-- Lihat Foto Identitas (Fitur Mata) --}}
                                    <button type="button" onclick="loadUserIdentitas({{ $user->id_user }})" class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#faf5f0] hover:bg-[#3d2a1f] text-[#8c5a3c] hover:text-white border border-[#ebdcd0] transition-all cursor-pointer active:scale-95 shadow-2xs" title="Lihat Foto Identitas (KTP / Kartu Pelajar)">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    {{-- Edit Button --}}
                                    <button type="button" onclick="openEditUserModal({{ json_encode($user) }})" class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#faf5f0] hover:bg-[#3d2a1f] text-[#5a4d42] hover:text-white border border-[#ebdcd0] transition-all cursor-pointer active:scale-95 shadow-2xs" title="Edit Data Pengguna">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button type="button" onclick="openDeleteUserModal({{ $user->id_user }}, '{{ addslashes($user->nama) }}')" class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-100 transition-all cursor-pointer active:scale-95 shadow-2xs" title="Hapus Pengguna">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#8f7664]">
                                <div class="flex flex-col items-center justify-center gap-2.5">
                                    <div class="h-12 w-12 rounded-2xl bg-[#faf5f0] text-[#8c5a3c] flex items-center justify-center border border-[#ede6df]">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="font-extrabold text-sm text-[#21140b]">Tidak ada data pengguna ditemukan</p>
                                    <p class="text-xs text-[#8f7664]">Coba ubah kata kunci pencarian atau ganti filter status di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($penggunas->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 bg-[#faf7f2]/30">
                <p class="text-xs font-semibold text-[#8f7664]">
                    Menampilkan {{ $penggunas->firstItem() }}-{{ $penggunas->lastItem() }} dari {{ $penggunas->total() }} pelanggan
                </p>
                {{ $penggunas->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ======================================================== --}}
{{-- MODAL 1: LIHAT FOTO IDENTITAS (KTP / KARTU PELAJAR)     --}}
{{-- ======================================================== --}}
<div id="modal-foto-identitas" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-foto-identitas')"></div>

    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-xl max-h-[90vh] flex flex-col rounded-3xl bg-white shadow-2xl border border-[#ede6df] z-10 overflow-hidden transform transition-all">
        
        {{-- Sticky Header --}}
        <div class="shrink-0 px-6 py-4 sm:px-7 sm:py-5 border-b border-[#ede6df] bg-[#faf7f2] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#3d2a1f] text-[#d5c6b8] font-black text-sm shadow-2xs uppercase" id="identitas-avatar">
                    US
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-[#21140b] leading-tight" id="identitas-nama">Nama Pelanggan</h3>
                    <p class="text-[11px] font-bold text-[#8f7664] mt-0.5" id="identitas-sub-id">ID: #KT-USR-0000 • Tipe: -</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-foto-identitas')" class="flex h-9 w-9 items-center justify-center rounded-xl bg-white hover:bg-[#ede6df] text-[#8f7664] hover:text-[#21140b] border border-[#ede6df] transition-all cursor-pointer shadow-2xs">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <div id="modal-identitas-body" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
            {{-- Quick Summary Meta --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                <div class="rounded-xl bg-[#faf7f4] p-2.5 border border-[#ede6df]/70">
                    <p class="text-[9px] font-bold text-[#8f7664] uppercase">Tipe Akun</p>
                    <p class="font-extrabold text-[#21140b] mt-0.5 truncate" id="identitas-tipe">-</p>
                </div>
                <div class="rounded-xl bg-[#faf7f4] p-2.5 border border-[#ede6df]/70">
                    <p class="text-[9px] font-bold text-[#8f7664] uppercase">NIS / NIP</p>
                    <p class="font-extrabold text-[#21140b] mt-0.5 truncate" id="identitas-nis">-</p>
                </div>
                <div class="col-span-2 sm:col-span-1 rounded-xl bg-[#faf7f4] p-2.5 border border-[#ede6df]/70">
                    <p class="text-[9px] font-bold text-[#8f7664] uppercase">No. WhatsApp</p>
                    <p class="font-extrabold text-emerald-700 mt-0.5 truncate" id="identitas-phone">-</p>
                </div>
            </div>

            {{-- Foto Identitas Preview Card --}}
            <div class="relative rounded-2xl bg-[#faf7f2] border border-[#ede6df] overflow-hidden min-h-[220px] flex items-center justify-center p-3">
                <img id="identitas-img" src="#" alt="Foto Identitas" class="max-h-[55vh] w-auto max-w-full object-contain rounded-xl shadow-xs hidden" />
                <div id="identitas-empty" class="text-center py-10 text-[#8f7664] space-y-2">
                    <svg class="h-12 w-12 mx-auto text-[#d5c6b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                    <p class="text-xs font-bold text-[#5a4d42]">Belum ada foto identitas (KTP / Kartu Pelajar) yang diunggah</p>
                </div>
            </div>
        </div>

        {{-- Sticky Footer --}}
        <div class="shrink-0 px-5 py-3.5 sm:px-6 border-t border-[#ede6df] bg-[#faf7f2] flex items-center justify-end">
            <button type="button" onclick="closeModal('modal-foto-identitas')" class="px-6 py-2 rounded-xl bg-[#21140b] hover:bg-[#3d2a1f] text-white text-xs font-bold transition-all cursor-pointer shadow-xs active:scale-95">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 2: TAMBAH PENGGUNA BARU                           --}}
{{-- ======================================================== --}}
<div id="modal-tambah-pengguna" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-tambah-pengguna')"></div>

    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col rounded-3xl bg-white shadow-2xl border border-[#ede6df] z-10 overflow-hidden transform transition-all">
        
        {{-- Header --}}
        <div class="shrink-0 px-6 py-4 sm:px-8 sm:py-5 border-b border-[#ede6df] bg-[#faf7f2] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#3d2a1f] text-white shadow-2xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-black text-[#21140b]">Tambah Pengguna Baru</h3>
            </div>
            <button type="button" onclick="closeModal('modal-tambah-pengguna')" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white hover:bg-[#ede6df] text-[#8f7664] hover:text-[#21140b] border border-[#ede6df] transition-colors cursor-pointer">
                ✕
            </button>
        </div>

        {{-- Form & Scrollable Body --}}
        <form action="{{ route('admin.pengguna.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
            @csrf
            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-4 text-xs font-bold">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Masukkan nama lengkap" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                </div>

                {{-- Email & Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="contoh@email.com" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                </div>

                {{-- No HP & NIS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" required placeholder="0812xxxx" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">NIS / NIP (Opsional)</label>
                        <input type="text" name="nis" placeholder="Nomor Induk Siswa/Guru" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                </div>

                {{-- Kelas & Jenis Kelamin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Kelas / Tingkat (Opsional)</label>
                        <input type="text" name="kelas" placeholder="Misal: XI RPL 1 atau kosongkan jika umum" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat pelanggan..." class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]"></textarea>
                </div>

                {{-- Foto Identitas --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Upload Foto Identitas (KTP/Kartu Pelajar)</label>
                    <input type="file" name="foto_identitas" accept="image/*" class="w-full text-xs font-semibold text-[#5a4d42] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#21140b] file:text-white hover:file:bg-[#3d2a1f] file:cursor-pointer" />
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="shrink-0 px-6 py-4 sm:px-8 border-t border-[#ede6df] bg-[#faf7f2] flex gap-3">
                <button type="button" onclick="closeModal('modal-tambah-pengguna')" class="flex-1 py-2.5 rounded-xl border border-[#ede6df] bg-white text-[#8f7664] hover:bg-[#ede6df]/50 font-bold cursor-pointer transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#3d2a1f] hover:bg-[#21140b] text-white font-bold cursor-pointer shadow-xs active:scale-95 transition-all">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 3: EDIT DATA PENGGUNA                             --}}
{{-- ======================================================== --}}
<div id="modal-edit-pengguna" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-edit-pengguna')"></div>

    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col rounded-3xl bg-white shadow-2xl border border-[#ede6df] z-10 overflow-hidden transform transition-all">
        
        {{-- Header --}}
        <div class="shrink-0 px-6 py-4 sm:px-8 sm:py-5 border-b border-[#ede6df] bg-[#faf7f2] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#3d2a1f] text-white shadow-2xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-black text-[#21140b]">Edit Data Pengguna</h3>
            </div>
            <button type="button" onclick="closeModal('modal-edit-pengguna')" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white hover:bg-[#ede6df] text-[#8f7664] hover:text-[#21140b] border border-[#ede6df] transition-colors cursor-pointer">
                ✕
            </button>
        </div>

        {{-- Form & Scrollable Body --}}
        <form id="form-edit-pengguna" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
            @csrf
            @method('PUT')
            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-4 text-xs font-bold">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama" required class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                </div>

                {{-- Email & Password Baru (Opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="edit-email" name="email" required class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tak diubah" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                </div>

                {{-- No HP & NIS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-phone" name="no_hp" required class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">NIS / NIP</label>
                        <input type="text" id="edit-nis" name="nis" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                </div>

                {{-- Kelas & Jenis Kelamin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Kelas / Tingkat</label>
                        <input type="text" id="edit-kelas" name="kelas" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]" />
                    </div>
                    <div>
                        <label class="block text-[#8f7664] uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="edit-kelamin" name="jenis_kelamin" required class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2.5 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Alamat Lengkap</label>
                    <textarea id="edit-alamat" name="alamat" rows="2" class="w-full bg-[#fdfbf9] border border-[#ede6df] rounded-xl px-3.5 py-2 text-[#21140b] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f]"></textarea>
                </div>

                {{-- Ganti Foto Identitas --}}
                <div>
                    <label class="block text-[#8f7664] uppercase mb-1">Ganti Foto Identitas (Opsional)</label>
                    <input type="file" name="foto_identitas" accept="image/*" class="w-full text-xs font-semibold text-[#5a4d42] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#21140b] file:text-white hover:file:bg-[#3d2a1f] file:cursor-pointer" />
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="shrink-0 px-6 py-4 sm:px-8 border-t border-[#ede6df] bg-[#faf7f2] flex gap-3">
                <button type="button" onclick="closeModal('modal-edit-pengguna')" class="flex-1 py-2.5 rounded-xl border border-[#ede6df] bg-white text-[#8f7664] hover:bg-[#ede6df]/50 font-bold cursor-pointer transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#3d2a1f] hover:bg-[#21140b] text-white font-bold cursor-pointer shadow-xs active:scale-95 transition-all">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 4: KONFIRMASI HAPUS PENGGUNA                      --}}
{{-- ======================================================== --}}
<div id="modal-delete-user" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="closeModal('modal-delete-user')"></div>

    {{-- Modal Card --}}
    <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-2xl border border-[#ede6df] z-10 space-y-4">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 border border-red-100">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <div>
            <h4 class="text-base font-black text-[#21140b]">Hapus Akun Pengguna?</h4>
            <p class="text-xs text-[#8f7664] mt-1 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun <span id="delete-user-name" class="font-bold text-[#21140b]"></span>? Seluruh data profil akun ini akan dihapus dari sistem.
            </p>
        </div>
        <form id="form-delete-user" method="POST" class="flex gap-2.5 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modal-delete-user')" class="flex-1 py-2.5 rounded-xl border border-[#ede6df] bg-white text-xs font-bold text-[#8f7664] hover:bg-[#faf7f2] cursor-pointer transition-all">
                Batal
            </button>
            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-xs font-bold text-white cursor-pointer shadow-xs active:scale-95 transition-all">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT HANDLERS --}}
<script>
    function loadUserIdentitas(userId) {
        fetch(`/admin/pengguna/${userId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const u = data.user;

                    document.getElementById('identitas-avatar').innerText = u.nama.substring(0, 2).toUpperCase();
                    document.getElementById('identitas-nama').innerText = u.nama;
                    document.getElementById('identitas-sub-id').innerText = `ID: ${u.formatted_id} • ${u.tipe_akun}`;
                    document.getElementById('identitas-tipe').innerText = u.kelas && u.kelas !== '-' ? `${u.tipe_akun} (${u.kelas})` : u.tipe_akun;
                    document.getElementById('identitas-nis').innerText = u.nis || '-';
                    document.getElementById('identitas-phone').innerText = u.no_hp || '-';

                    const imgEl = document.getElementById('identitas-img');
                    const emptyEl = document.getElementById('identitas-empty');

                    if (u.foto_identitas) {
                        imgEl.src = u.foto_identitas;
                        imgEl.classList.remove('hidden');
                        emptyEl.classList.add('hidden');
                    } else {
                        imgEl.classList.add('hidden');
                        emptyEl.classList.remove('hidden');
                    }

                    // Reset scroll
                    const bodyEl = document.getElementById('modal-identitas-body');
                    if (bodyEl) bodyEl.scrollTop = 0;

                    openModal('modal-foto-identitas');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal memuat foto identitas.');
            });
    }

    function openEditUserModal(user) {
        document.getElementById('edit-nama').value = user.nama || '';
        document.getElementById('edit-email').value = user.email || '';
        document.getElementById('edit-phone').value = user.no_hp || '';
        document.getElementById('edit-nis').value = user.nis || '';
        document.getElementById('edit-kelas').value = user.kelas || '';
        document.getElementById('edit-kelamin').value = user.jenis_kelamin || 'L';
        document.getElementById('edit-alamat').value = user.alamat || '';
        document.getElementById('form-edit-pengguna').action = `/admin/pengguna/${user.id_user}`;
        openModal('modal-edit-pengguna');
    }

    function openDeleteUserModal(userId, userName) {
        document.getElementById('delete-user-name').innerText = userName;
        document.getElementById('form-delete-user').action = `/admin/pengguna/${userId}`;
        openModal('modal-delete-user');
    }
</script>
@endsection
