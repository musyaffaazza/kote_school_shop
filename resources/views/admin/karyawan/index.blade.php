@extends('layouts.admin')

@section('page-title', 'Data Karyawan')

@section('content')
<div class="space-y-6">
    {{-- Title and Add Button --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1 text-left">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b]">
                Data Karyawan
            </h1>
            <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
                Kelola informasi staff, role, dan akses sistem.
            </p>
        </div>
        <a href="{{ route('admin.karyawan.create') }}" class="inline-flex items-center gap-2 bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-3 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap uppercase tracking-wider">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- Filter & Search Row --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-2xl border border-[#ede6df] shadow-sm">
        {{-- Role / Jabatan Filter --}}
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <span class="text-xs font-bold text-[#8f7664] whitespace-nowrap">Filter Role:</span>
            <div class="flex bg-[#faf5f0] p-1 rounded-xl gap-1 border border-[#ede6df]/45">
                <a href="{{ route('admin.karyawan.index', ['jabatan' => 'all', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filterJabatan === 'all' ? 'bg-white text-[#21140b] shadow-sm border border-[#ede6df]/55 font-extrabold' : 'text-[#7b6558] hover:text-[#21140b]' }}">
                    Semua
                </a>
                <a href="{{ route('admin.karyawan.index', ['jabatan' => 'Barista', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filterJabatan === 'Barista' ? 'bg-white text-[#21140b] shadow-sm border border-[#ede6df]/55 font-extrabold' : 'text-[#7b6558] hover:text-[#21140b]' }}">
                    Barista
                </a>
                <a href="{{ route('admin.karyawan.index', ['jabatan' => 'Kasir', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filterJabatan === 'Kasir' ? 'bg-white text-[#21140b] shadow-sm border border-[#ede6df]/55 font-extrabold' : 'text-[#7b6558] hover:text-[#21140b]' }}">
                    Kasir
                </a>
            </div>
        </div>

        {{-- Search Input --}}
        <form action="{{ route('admin.karyawan.index') }}" method="GET" class="flex w-full sm:max-w-md items-center gap-2">
            @if($filterJabatan !== 'all')
                <input type="hidden" name="jabatan" value="{{ $filterJabatan }}">
            @endif
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-9 pr-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all" />
            </div>
            <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-sm active:scale-95 whitespace-nowrap">
                Cari
            </button>
        </form>
    </div>

    {{-- Karyawan List Table Card --}}
    <div class="rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="bg-[#faf7f2] border-b border-[#ede6df]/45 text-[#8f7664] font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 w-16">No</th>
                        <th class="px-6 py-4 w-20">Foto</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ede6df]/45">
                    @forelse($karyawans as $index => $karyawan)
                        <tr class="hover:bg-[#faf7f2]/40 transition-colors">
                            <td class="px-6 py-4 font-semibold text-[#8f7664]">
                                {{ ($karyawans->currentPage() - 1) * $karyawans->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                @if($karyawan->foto_identitas)
                                    <img src="{{ str_starts_with($karyawan->foto_identitas, 'http') ? $karyawan->foto_identitas : asset($karyawan->foto_identitas) }}" 
                                         alt="{{ $karyawan->nama }}" 
                                         class="h-10 w-10 rounded-full object-cover border border-[#ede6df]/50 bg-[#faf7f2] shrink-0" />
                                @else
                                    <div class="h-10 w-10 rounded-full bg-[#3d2a1f] flex items-center justify-center text-xs font-bold text-[#d5c6b8] shrink-0 uppercase">
                                        {{ substr($karyawan->nama, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-extrabold text-sm text-[#21140b]">
                                {{ $karyawan->nama }}
                            </td>
                            <td class="px-6 py-4">
                                @if($karyawan->jabatan === 'Barista')
                                    <span class="inline-block rounded-full bg-[#fbf1e8] px-2.5 py-0.5 text-[10px] font-bold text-[#a2785d] border border-[#edd8cf]/40">
                                        Barista
                                    </span>
                                @elseif($karyawan->jabatan === 'Kasir')
                                    <span class="inline-block rounded-full bg-[#f1f1f1] px-2.5 py-0.5 text-[10px] font-bold text-[#6b6b6b] border border-[#ede6df]/55">
                                        Kasir
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-bold text-gray-500 border border-gray-200">
                                        {{ $karyawan->jabatan ?? '-' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[#7b6558] font-medium">
                                {{ $karyawan->email }}
                            </td>
                            <td class="px-6 py-4 text-[#7b6558] font-medium">
                                {{ $karyawan->no_hp }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.karyawan.edit', $karyawan) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#faf5f0] text-[#8b5a2b] hover:bg-[#3d2a1f] hover:text-white transition-colors cursor-pointer border border-[#e8dfd5]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" onclick="openDeleteModal('{{ $karyawan->id_user }}', '{{ addslashes($karyawan->nama) }}')" class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors cursor-pointer border border-red-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#8f7664] font-medium bg-[#faf7f2]/20">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#faf5f0] text-[#a2785d] mb-3 border border-[#ede6df]/50">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="font-extrabold text-[#21140b] text-sm">Tidak ada karyawan ditemukan</h4>
                                <p class="text-[11px] text-[#8f7664] mt-1">Coba sesuaikan kata kunci pencarian atau filter role Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Footer --}}
        <div class="px-6 py-4 bg-[#faf7f2]/40 border-t border-[#ede6df]/45 flex items-center justify-between text-xs text-[#8f7664] font-medium">
            <div>
                Menampilkan {{ $karyawans->firstItem() ?? 0 }}-{{ $karyawans->lastItem() ?? 0 }} dari {{ $karyawans->total() }} karyawan
            </div>
            @if($karyawans->hasPages())
                <div class="flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($karyawans->onFirstPage())
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#faf7f2] text-gray-300 cursor-not-allowed border border-[#ede6df]/60">
                            &lsaquo;
                        </span>
                    @else
                        <a href="{{ $karyawans->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#7b6558] hover:bg-[#3d2a1f] hover:text-white transition-colors cursor-pointer border border-[#ede6df]">
                            &lsaquo;
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($karyawans->getUrlRange(1, $karyawans->lastPage()) as $page => $url)
                        @if ($page == $karyawans->currentPage())
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d2a1f] text-white font-extrabold shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#7b6558] hover:bg-[#faf7f2] transition-colors cursor-pointer border border-[#ede6df]">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($karyawans->hasMorePages())
                        <a href="{{ $karyawans->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#7b6558] hover:bg-[#3d2a1f] hover:text-white transition-colors cursor-pointer border border-[#ede6df]">
                            &rsaquo;
                        </a>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#faf7f2] text-gray-300 cursor-not-allowed border border-[#ede6df]/60">
                            &rsaquo;
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-karyawan-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-[0_25px_60px_rgba(33,20,11,0.25)] border border-[#ede6df] transform transition-all space-y-5">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="space-y-2">
            <h3 class="text-base font-extrabold text-[#21140b]">Hapus Karyawan</h3>
            <p class="text-xs text-[#8a7b6e] font-semibold leading-relaxed">
                Apakah Anda yakin ingin menghapus karyawan <span id="delete-karyawan-name" class="font-extrabold text-[#21140b]"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-xs font-extrabold text-[#7b6558] py-3.5 rounded-full cursor-pointer transition-colors active:scale-[0.98]">
                Batal
            </button>
            <form id="delete-karyawan-form" action="" method="POST" class="flex-1">
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
    function openDeleteModal(id, name) {
        const modal = document.getElementById('delete-karyawan-modal');
        const form = document.getElementById('delete-karyawan-form');
        const nameSpan = document.getElementById('delete-karyawan-name');
        
        if (modal && form && nameSpan) {
            form.action = '/admin/karyawan/' + id;
            nameSpan.textContent = name;
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-karyawan-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
