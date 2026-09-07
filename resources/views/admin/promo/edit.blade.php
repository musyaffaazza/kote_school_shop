@extends('layouts.admin')

@section('page-title', 'Manajemen Promo')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs font-semibold text-[#8f7664]">
        <a href="{{ route('admin.promo.index') }}" class="hover:text-[#21140b] transition-colors">Manajemen Promo</a>
        <span class="text-[#d5ccc3]">›</span>
        <span class="text-[#21140b] font-bold">Edit Promo</span>
    </nav>

    {{-- Title --}}
    <h1 class="text-3xl font-extrabold text-[#21140b] tracking-tight">Edit Promo</h1>

    {{-- Form --}}
    <form action="{{ route('admin.promo.update', $promo) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-white border border-[#e0d5ca]/60 shadow-sm p-6 md:p-8 space-y-6">

            {{-- Two-Column Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column fields --}}
                <div class="space-y-5">
                    {{-- Nama Promo --}}
                    <div>
                        <label for="nama_promo" class="block text-sm font-bold text-[#21140b] mb-1.5">Nama Promo</label>
                        <input type="text" name="nama_promo" id="nama_promo" value="{{ old('nama_promo', $promo->nama_promo) }}" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all" required />
                        @error('nama_promo') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nilai Promo --}}
                    <div>
                        <label for="nilai_promo" class="block text-sm font-bold text-[#21140b] mb-1.5">Nilai Promo</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="nilai_promo" id="nilai_promo" value="{{ old('nilai_promo', $promo->nilai_promo) }}" min="0" class="flex-1 bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all" required />
                            <div class="relative shrink-0">
                                <select name="satuan_nilai" class="bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl pl-3 pr-8 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all cursor-pointer appearance-none">
                                    <option value="persen" {{ old('satuan_nilai', $promo->satuan_nilai) === 'persen' ? 'selected' : '' }}>%</option>
                                    <option value="rupiah" {{ old('satuan_nilai', $promo->satuan_nilai) === 'rupiah' ? 'selected' : '' }}>Rp</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#8f7664]">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                        @error('nilai_promo') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Periode Berlaku --}}
                    <div>
                        <label class="block text-sm font-bold text-[#21140b] mb-1.5">Periode Berlaku</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </span>
                                <input type="date" name="periode_mulai" value="{{ old('periode_mulai', $promo->periode_mulai?->format('Y-m-d')) }}" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl pl-10 pr-4 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all" />
                                @error('periode_mulai') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="date" name="periode_selesai" value="{{ old('periode_selesai', $promo->periode_selesai?->format('Y-m-d')) }}" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all" />
                                @error('periode_selesai') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column fields --}}
                <div class="space-y-5">
                    {{-- Jenis Promo --}}
                    <div>
                        <label for="jenis_promo" class="block text-sm font-bold text-[#21140b] mb-1.5">Jenis Promo</label>
                        <div class="relative">
                            <select name="jenis_promo" id="jenis_promo" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all appearance-none cursor-pointer pr-10" required>
                                <option value="diskon" {{ old('jenis_promo', $promo->jenis_promo) === 'diskon' ? 'selected' : '' }}>DISKON</option>
                                <option value="paket" {{ old('jenis_promo', $promo->jenis_promo) === 'paket' ? 'selected' : '' }}>PAKET</option>
                                <option value="voucher" {{ old('jenis_promo', $promo->jenis_promo) === 'voucher' ? 'selected' : '' }}>VOUCHER</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        @error('jenis_promo') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kode Voucher --}}
                    <div>
                        <label for="kode_voucher" class="block text-sm font-bold text-[#21140b] mb-1.5">Kode Voucher</label>
                        <input type="text" name="kode_voucher" id="kode_voucher" value="{{ old('kode_voucher', $promo->kode_voucher) }}" placeholder="CONTOH: MERDEKA45" class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] placeholder-[#b09a87] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all uppercase" />
                        @error('kode_voucher') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi Promo --}}
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-[#21140b] mb-1.5">Deskripsi Promo</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Jelaskan detail dan syarat ketentuan promo..." class="w-full bg-[#faf7f2]/40 border border-[#d5ccc3] rounded-xl px-4 py-3 text-sm text-[#21140b] placeholder-[#b09a87] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/15 focus:border-[#8b5a2b] transition-all resize-none h-[116px]">{{ old('deskripsi', $promo->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Banner Promo --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-[#21140b]">Banner Promo</label>
                
                <div class="flex items-center gap-3">
                    {{-- File Input Button --}}
                    <label class="cursor-pointer bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-3 rounded-xl transition-all shadow-sm shrink-0 whitespace-nowrap">
                        <span>Pilih Gambar</span>
                        <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" />
                    </label>
                    
                    {{-- File Name Display & Click to View --}}
                    <div id="file-info-container" class="flex items-center gap-2 border border-[#d5ccc3] rounded-xl px-4 py-2.5 bg-[#faf7f2]/40 w-full overflow-hidden {{ $promo->gambar ? '' : 'hidden' }}">
                        <svg class="h-4 w-4 text-[#8f7664] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z" />
                        </svg>
                        <a href="{{ $promo->gambar ? asset($promo->gambar) : '#' }}" id="file-link" target="_blank" class="text-xs font-bold text-[#8b5a2b] hover:text-[#3d2a1f] underline truncate select-none">
                            {{ $promo->gambar ? basename($promo->gambar) : 'Belum ada file dipilih' }}
                        </a>
                        <span class="text-[10px] text-[#8f7664] font-medium shrink-0">(Klik untuk melihat)</span>
                    </div>

                    {{-- No file selected state --}}
                    <span id="no-file-text" class="text-xs text-[#8f7664] font-medium {{ $promo->gambar ? 'hidden' : '' }}">Belum ada file dipilih</span>
                </div>
                @error('gambar') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Status Promo --}}
            <div class="flex items-center justify-between bg-[#faf7f2] border border-[#e0d5ca]/60 rounded-xl px-5 py-4">
                <div>
                    <p class="text-sm font-bold text-[#21140b]">Status Promo</p>
                    <p class="text-xs text-[#8f7664]">Tentukan apakah promo ini sedang aktif</p>
                </div>
                <input type="hidden" name="is_active" value="0">
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $promo->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-[#d5ccc3] peer-checked:bg-[#3d2a1f] rounded-full relative peer-focus:outline-none after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:shadow-md after:transition-all duration-300 peer-checked:after:translate-x-full"></div>
                </label>
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('admin.promo.index') }}" class="px-6 py-2.5 text-sm font-bold text-[#6b5b4f] bg-white border border-[#d5ccc3] rounded-xl hover:bg-[#faf7f2] transition-all active:scale-95">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-[#8b5a2b] hover:bg-[#6d4722] rounded-xl transition-all cursor-pointer shadow-md hover:shadow-lg active:scale-95">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

<script>
    // Live filename indicator and preview link
    document.getElementById('gambar')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const container = document.getElementById('file-info-container');
        const link = document.getElementById('file-link');
        const noFileText = document.getElementById('no-file-text');

        if (file && container && link && noFileText) {
            link.href = URL.createObjectURL(file);
            link.textContent = file.name;
            container.classList.remove('hidden');
            noFileText.classList.add('hidden');
        }
    });
</script>
@endsection
