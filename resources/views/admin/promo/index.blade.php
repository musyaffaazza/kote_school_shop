@extends('layouts.admin')

@section('page-title', 'Manajemen Promo')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
        <h1 class="text-3xl font-extrabold text-[#21140b] tracking-tight">Daftar Promo</h1>

        <div class="flex flex-wrap gap-3 w-full md:w-auto items-center justify-end">
            {{-- Search --}}
            <form action="{{ route('admin.promo.index') }}" method="GET" class="flex items-center gap-2 flex-1 sm:flex-initial">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari promo atau kode..." class="w-full bg-white border border-[#e0d5ca] text-sm rounded-xl pl-10 pr-4 py-2.5 text-[#21140b] placeholder-[#b09a87] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all shadow-sm" />
                </div>
            </form>

            {{-- Tambah Promo --}}
            <a href="{{ route('admin.promo.create') }}" class="inline-flex items-center gap-2 bg-[#8b5a2b] hover:bg-[#6d4722] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all cursor-pointer shadow-md hover:shadow-lg active:scale-95 whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Promo
            </a>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl bg-white border border-[#e0d5ca]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#faf7f2] border-b border-[#e0d5ca]/60">
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Nama Promo</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Jenis</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Nilai</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Kode</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Periode Berlaku</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-[#8f7664] uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $index => $promo)
                        <tr class="border-b border-[#f0ebe5] hover:bg-[#fdfbf9] transition-colors">
                            {{-- No --}}
                            <td class="px-6 py-5 text-sm font-bold text-[#8f7664]">
                                {{ str_pad($promos->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Nama Promo --}}
                            <td class="px-6 py-5">
                                <span class="text-sm font-bold text-[#21140b]">{{ $promo->nama_promo }}</span>
                            </td>

                            {{-- Jenis Badge --}}
                            <td class="px-6 py-5">
                                @php
                                    $jenisColors = [
                                        'diskon' => 'bg-[#3d2a1f] text-white',
                                        'paket'  => 'bg-[#d4a24e] text-white',
                                        'voucher' => 'bg-[#5a8f5a] text-white',
                                    ];
                                    $colorClass = $jenisColors[$promo->jenis_promo] ?? 'bg-gray-400 text-white';
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider {{ $colorClass }}">
                                    {{ $promo->jenis_label }}
                                </span>
                            </td>

                            {{-- Nilai --}}
                            <td class="px-6 py-5 text-sm font-bold text-[#21140b]">
                                {{ $promo->nilai_promo > 0 ? $promo->nilai_formatted : '-' }}
                            </td>

                            {{-- Kode --}}
                            <td class="px-6 py-5 text-sm font-semibold text-[#21140b] tracking-wide font-mono">
                                {{ $promo->kode_voucher ?? '-' }}
                            </td>

                            {{-- Periode --}}
                            <td class="px-6 py-5 text-sm text-[#6b5b4f]">
                                {{ $promo->periode_formatted }}
                            </td>

                            {{-- Status Toggle --}}
                            <td class="px-6 py-5">
                                <form action="{{ route('admin.promo.toggle', $promo) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3d2a1f]/20 {{ $promo->is_active ? 'bg-[#3d2a1f]' : 'bg-[#d5ccc3]' }}" title="{{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-md transition-transform duration-300 {{ $promo->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-1.5">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.promo.edit', $promo) }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#faf5f0] text-[#8f7664] hover:bg-[#3d2a1f] hover:text-white transition-all border border-[#e8dfd5]" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <button type="button" onclick="openDeleteModal('{{ $promo->id }}', '{{ addslashes($promo->nama_promo) }}')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all border border-red-100 cursor-pointer" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#faf5f0] text-[#a2785d] border border-[#ede6df]/50">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-extrabold text-[#21140b]">Belum ada promo</p>
                                    <p class="text-xs text-[#8f7664]">Tambahkan promo untuk menarik pelanggan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($promos->hasPages())
            <div class="border-t border-[#e0d5ca]/60 px-6 py-4 bg-[#fdfbf9] flex items-center justify-between">
                <p class="text-xs text-[#8f7664] font-semibold">
                    Menampilkan {{ $promos->count() }} dari {{ $promos->total() }} promo
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if($promos->onFirstPage())
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-[#d5ccc3] text-xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </span>
                    @else
                        <a href="{{ $promos->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f0ebe5] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @for($i = 1; $i <= $promos->lastPage(); $i++)
                        @if($i === $promos->currentPage())
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d2a1f] text-white text-xs font-bold">{{ $i }}</span>
                        @else
                            <a href="{{ $promos->url($i) }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f0ebe5] text-xs font-semibold transition-colors">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if($promos->hasMorePages())
                        <a href="{{ $promos->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f0ebe5] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-[#d5ccc3] text-xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-promo-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-[0_25px_60px_rgba(33,20,11,0.25)] border border-[#ede6df] transform transition-all space-y-5">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="space-y-2">
            <h3 class="text-base font-extrabold text-[#21140b]">Hapus Promo</h3>
            <p class="text-xs text-[#8a7b6e] font-semibold leading-relaxed">
                Apakah Anda yakin ingin menghapus promo <span id="delete-promo-name" class="font-extrabold text-[#21140b]"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-xs font-extrabold text-[#7b6558] py-3.5 rounded-full cursor-pointer transition-colors active:scale-[0.98]">
                Batal
            </button>
            <form id="delete-promo-form" action="" method="POST" class="flex-1">
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
        const modal = document.getElementById('delete-promo-modal');
        const form = document.getElementById('delete-promo-form');
        const nameSpan = document.getElementById('delete-promo-name');

        if (modal && form && nameSpan) {
            form.action = '/admin/promo/' + id;
            nameSpan.textContent = name;
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-promo-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
