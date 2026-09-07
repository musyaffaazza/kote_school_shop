@extends('layouts.karyawan')

@section('page-title', 'Manajemen Menu')

@section('header-search')
    {{-- Empty: search is in the content area --}}
@endsection

@section('content')
<div class="space-y-6">

    {{-- SEARCH & CATEGORY FILTER --}}
    <div class="rounded-2xl bg-white p-5 shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('karyawan.menu') }}" class="relative flex-1 max-w-md w-full" id="searchForm">
                <input type="hidden" name="kategori" value="{{ $filterCategory }}">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari produk..."
                    class="w-full bg-[#faf7f4] border border-[#e8ded5] text-sm rounded-xl pl-10 pr-4 py-2.5 text-[#21140b] placeholder-[#b09a87] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all"
                />
            </form>

            {{-- Category Tabs --}}
            <div class="flex gap-2 shrink-0 flex-wrap">
                <a href="{{ route('karyawan.menu', ['search' => $search, 'kategori' => 'semua']) }}"
                   class="px-5 py-2 text-xs font-bold rounded-full transition-all {{ $filterCategory === 'semua' ? 'bg-[#21140b] text-white shadow-sm' : 'bg-[#f5f0eb] text-[#5a4d42] hover:bg-[#ede6df]' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('karyawan.menu', ['search' => $search, 'kategori' => $cat]) }}"
                       class="px-5 py-2 text-xs font-bold rounded-full transition-all capitalize {{ $filterCategory === $cat ? 'bg-[#21140b] text-white shadow-sm' : 'bg-[#f5f0eb] text-[#5a4d42] hover:bg-[#ede6df]' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MENU TABLE --}}
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(33,20,11,0.04)] border border-[#ede6df]/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="border-b border-[#ede6df] bg-[#faf7f4]">
                        <th class="py-3.5 px-5 text-left text-[11px] font-bold text-[#8f7664] uppercase tracking-wider w-12">No</th>
                        <th class="py-3.5 px-5 text-left text-[11px] font-bold text-[#8f7664] uppercase tracking-wider">Produk</th>
                        <th class="py-3.5 px-5 text-left text-[11px] font-bold text-[#8f7664] uppercase tracking-wider">Harga</th>
                        <th class="py-3.5 px-5 text-right text-[11px] font-bold text-[#8f7664] uppercase tracking-wider">Aksi & Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f0eb]">
                    @forelse($menus as $index => $menu)
                        <tr class="group hover:bg-[#fdfaf7] transition-colors" data-menu-id="{{ $menu->id_menu }}">
                            {{-- No --}}
                            <td class="py-5 px-5 text-sm font-bold text-[#8f7664]">
                                {{ $menus->firstItem() + $index }}
                            </td>

                            {{-- Produk: Image + Name + Category Badge --}}
                            <td class="py-5 px-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-[#f5f0eb] shadow-sm">
                                        @if($menu->gambar)
                                            <img src="{{ $menu->gambar }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover" />
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[#b09a87]">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.41a2.25 2.25 0 013.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-[#21140b]">{{ $menu->nama_menu }}</p>
                                        <span class="mt-1 inline-block rounded-md bg-[#3d2a1f] px-2.5 py-0.5 text-[10px] font-bold text-white capitalize">
                                            {{ $menu->kategori }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Harga --}}
                            <td class="py-5 px-5">
                                <span class="text-sm font-extrabold text-[#21140b]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </td>

                            {{-- Aksi & Status --}}
                            <td class="py-5 px-5">
                                <div class="flex items-center justify-end gap-4">
                                    {{-- Stock Controls --}}
                                    <div class="flex items-center gap-1.5">
                                        <button
                                            type="button"
                                            onclick="adjustStock({{ $menu->id_menu }}, -1)"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e8ded5] bg-[#faf7f4] text-[#5a4d42] hover:bg-[#ede6df] transition-all cursor-pointer active:scale-90 text-sm font-bold"
                                        >−</button>
                                        <input
                                            type="number"
                                            id="stock-{{ $menu->id_menu }}"
                                            value="{{ $menu->stok }}"
                                            min="0"
                                            oninput="syncStatusWithStock({{ $menu->id_menu }})"
                                            onchange="syncStatusWithStock({{ $menu->id_menu }})"
                                            class="w-12 text-center rounded-lg border border-[#e8ded5] bg-[#faf7f4] py-1.5 text-sm font-bold text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        />
                                        <button
                                            type="button"
                                            onclick="adjustStock({{ $menu->id_menu }}, 1)"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e8ded5] bg-[#faf7f4] text-[#5a4d42] hover:bg-[#ede6df] transition-all cursor-pointer active:scale-90 text-sm font-bold"
                                        >+</button>
                                    </div>

                                    {{-- Simpan Button --}}
                                    <button
                                        type="button"
                                        onclick="saveStock({{ $menu->id_menu }}, event)"
                                        class="rounded-xl bg-[#21140b] px-4 py-2 text-[11px] font-bold text-white hover:bg-[#3d2a1f] transition-all cursor-pointer active:scale-95 shadow-sm"
                                    >Simpan</button>

                                    {{-- Toggle Tersedia/Habis --}}
                                    <div class="flex flex-col items-center gap-1">
                                        <label class="relative inline-flex cursor-pointer">
                                            <input
                                                type="checkbox"
                                                id="toggle-{{ $menu->id_menu }}"
                                                class="sr-only peer"
                                                {{ $menu->status === 'tersedia' ? 'checked' : '' }}
                                                onchange="toggleStatus({{ $menu->id_menu }})"
                                            />
                                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </label>
                                        <span id="status-label-{{ $menu->id_menu }}" class="text-[10px] font-bold {{ $menu->status === 'tersedia' ? 'text-emerald-600' : 'text-gray-400' }}">
                                            {{ $menu->status === 'tersedia' ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="h-12 w-12 text-[#d5c6b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm font-bold text-[#8f7664]">Tidak ada menu ditemukan</p>
                                    <p class="text-xs text-[#b09a87]">Coba ubah kata kunci atau filter kategori</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($menus->hasPages())
            <div class="flex items-center justify-between border-t border-[#ede6df] px-5 py-4">
                <p class="text-xs font-semibold text-[#8f7664]">
                    Menampilkan {{ $menus->firstItem() }}-{{ $menus->lastItem() }} dari {{ $menus->total() }} produk
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if($menus->onFirstPage())
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-[#d5c6b8] cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </span>
                    @else
                        <a href="{{ $menus->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#5a4d42] hover:bg-[#f5f0eb] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($menus->getUrlRange(1, $menus->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-bold transition-all {{ $page == $menus->currentPage() ? 'bg-[#21140b] text-white shadow-sm' : 'text-[#5a4d42] hover:bg-[#f5f0eb]' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if($menus->hasMorePages())
                        <a href="{{ $menus->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#5a4d42] hover:bg-[#f5f0eb] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-[#d5c6b8] cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>

<script>
    /**
     * Sync availability status based on stock value.
     * Auto-set status to Habis when stock is 0.
     */
    function syncStatusWithStock(menuId) {
        const input = document.getElementById('stock-' + menuId);
        if (!input) return;
        let current = parseInt(input.value);
        if (isNaN(current) || current < 0) {
            current = 0;
            input.value = 0;
        }

        const toggle = document.getElementById('toggle-' + menuId);
        const label = document.getElementById('status-label-' + menuId);

        if (current === 0) {
            if (toggle) toggle.checked = false;
            if (label) {
                label.textContent = 'Habis';
                label.className = 'text-[10px] font-bold text-gray-400';
            }
        } else {
            if (toggle) toggle.checked = true;
            if (label) {
                label.textContent = 'Tersedia';
                label.className = 'text-[10px] font-bold text-emerald-600';
            }
        }
    }

    /**
     * Adjust stock value by increment (can be negative).
     */
    function adjustStock(menuId, delta) {
        const input = document.getElementById('stock-' + menuId);
        if (!input) return;
        let current = parseInt(input.value) || 0;
        current = Math.max(0, current + delta);
        input.value = current;

        syncStatusWithStock(menuId);
    }

    /**
     * Toggle the status between tersedia and habis.
     */
    function toggleStatus(menuId) {
        const toggle = document.getElementById('toggle-' + menuId);
        const label = document.getElementById('status-label-' + menuId);
        if (!toggle || !label) return;

        if (toggle.checked) {
            label.textContent = 'Tersedia';
            label.className = 'text-[10px] font-bold text-emerald-600';
        } else {
            label.textContent = 'Habis';
            label.className = 'text-[10px] font-bold text-gray-400';
        }

        // Auto-save on toggle
        saveStock(menuId);
    }

    /**
     * Save stock and status via AJAX POST.
     */
    function saveStock(menuId, event = null) {
        const stockInput = document.getElementById('stock-' + menuId);
        const toggle = document.getElementById('toggle-' + menuId);
        if (!stockInput || !toggle) return;

        const stok = parseInt(stockInput.value) || 0;
        const status = toggle.checked ? 'tersedia' : 'habis';

        const btn = event ? event.target.closest('button') : null;
        if (btn) {
            btn.disabled = true;
            btn.textContent = '...';
        }

        fetch(`{{ url('/karyawan/menu') }}/${menuId}/update-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ stok, status }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);

                // Sync UI with server response
                const toggle = document.getElementById('toggle-' + menuId);
                const label = document.getElementById('status-label-' + menuId);
                if (toggle) toggle.checked = (data.status === 'tersedia');
                if (label) {
                    label.textContent = data.status === 'tersedia' ? 'Tersedia' : 'Habis';
                    label.className = 'text-[10px] font-bold ' + (data.status === 'tersedia' ? 'text-emerald-600' : 'text-gray-400');
                }
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal menyimpan perubahan!');
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });
    }
</script>
@endsection
