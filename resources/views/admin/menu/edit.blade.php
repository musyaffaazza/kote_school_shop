@extends('layouts.admin')

@section('page-title', 'Edit Menu')

@section('content')
@php
    $subImages = [
        'sub_1' => null,
        'sub_2' => null,
        'sub_3' => null,
    ];

    if ($menu->gambar && str_starts_with($menu->gambar, '/storage/menu/')) {
        $folder = 'menu/' . $menu->id_menu;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder)) {
            $files = \Illuminate\Support\Facades\Storage::disk('public')->files($folder);
            foreach ($files as $file) {
                $baseName = pathinfo($file, PATHINFO_FILENAME);
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($baseName, ['sub_1', 'sub_2', 'sub_3']) && in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $subImages[$baseName] = '/storage/' . $file;
                }
            }
        }
    }
@endphp

<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-2 text-xs font-bold text-[#8f7664] uppercase tracking-wider text-left">
        <a href="{{ route('admin.menu.index') }}" class="hover:text-[#21140b] transition-colors">Menu</a>
        <span>&rsaquo;</span>
        <span class="text-[#21140b]">Edit Menu</span>
    </div>

    {{-- Title --}}
    <div class="space-y-1 text-left">
        <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b]">
            Edit Menu
        </h1>
        <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
            Perbarui detail menu <span class="font-extrabold text-[#21140b]">"{{ $menu->nama_menu }}"</span> di bawah ini.
        </p>
    </div>

    {{-- Form Card --}}
    <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-[#ede6df] bg-white shadow-sm overflow-hidden flex flex-col">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT COLUMN: Upload Gambar (Span 5) --}}
                <div class="md:col-span-5 space-y-4">
                    {{-- Input files (Hidden) --}}
                    <input type="file" name="gambar" id="gambar" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewImage(this, 'main')" />
                    <input type="file" name="gambar_sub_1" id="gambar_sub_1" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewImage(this, 'sub_1')" />
                    <input type="file" name="gambar_sub_2" id="gambar_sub_2" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewImage(this, 'sub_2')" />
                    <input type="file" name="gambar_sub_3" id="gambar_sub_3" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewImage(this, 'sub_3')" />

                    {{-- Box Gambar Utama --}}
                    <div onclick="document.getElementById('gambar').click();" class="w-full aspect-square border-2 border-dashed border-[#ede6df] rounded-[1.5rem] bg-[#fdfbf9] flex flex-col items-center justify-center cursor-pointer hover:bg-[#faf7f2]/50 transition-colors p-4 relative group overflow-hidden shadow-inner">
                        <div class="flex flex-col items-center justify-center text-center space-y-2 {{ $menu->gambar ? 'hidden' : '' }}" id="placeholder-main">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#fbf1e8]/80 text-[#b5a49a]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#21140b] tracking-wide">Gambar Utama</p>
                                <p class="text-[10px] text-[#8f7664] font-medium mt-0.5">Tarik & lepas di sini</p>
                            </div>
                            <button type="button" class="mt-1 bg-white border border-[#ede6df] text-[#a2785d] rounded-full px-4 py-1.5 text-[10px] font-extrabold cursor-pointer transition-colors hover:bg-[#faf5f0] shadow-sm select-none">
                                Pilih Foto
                            </button>
                        </div>
                        <img id="preview-main" src="{{ $menu->gambar ?? '#' }}" alt="Main Preview" class="absolute inset-0 w-full h-full object-cover {{ $menu->gambar ? '' : 'hidden' }}" />
                        
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity {{ $menu->gambar ? '' : 'hidden' }}" id="hover-main">
                            <span class="text-white text-xs font-bold uppercase tracking-wider">Ubah Foto</span>
                        </div>
                    </div>

                    {{-- 3 Box Sub Gambar --}}
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['sub_1', 'sub_2', 'sub_3'] as $subKey)
                            @php
                                $subImgUrl = $subImages[$subKey];
                            @endphp
                            <div onclick="document.getElementById('gambar_{{ $subKey }}').click();" class="aspect-square border-2 border-dashed border-[#ede6df] rounded-2xl bg-[#fdfbf9] flex items-center justify-center cursor-pointer hover:bg-[#faf7f2]/50 transition-colors relative overflow-hidden group shadow-inner">
                                <div class="flex flex-col items-center justify-center text-[#b5a49a] {{ $subImgUrl ? 'hidden' : '' }}" id="placeholder-{{ $subKey }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <img id="preview-{{ $subKey }}" src="{{ $subImgUrl ?? '#' }}" alt="{{ $subKey }} Preview" class="absolute inset-0 w-full h-full object-cover {{ $subImgUrl ? '' : 'hidden' }}" />
                                
                                {{-- Hover overlay --}}
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity {{ $subImgUrl ? '' : 'hidden' }}" id="hover-{{ $subKey }}">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Keterangan format --}}
                    <p class="text-[10px] text-[#8f7664] font-semibold text-left">Format: JPG, JPEG, PNG, WEBP</p>

                    @error('gambar')
                        <p class="text-xs text-red-500 font-semibold text-left mt-1">{{ $message }}</p>
                    @enderror
                    @error('gambar_sub_1')
                        <p class="text-xs text-red-500 font-semibold text-left mt-1">{{ $message }}</p>
                    @enderror
                    @error('gambar_sub_2')
                        <p class="text-xs text-red-500 font-semibold text-left mt-1">{{ $message }}</p>
                    @enderror
                    @error('gambar_sub_3')
                        <p class="text-xs text-red-500 font-semibold text-left mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RIGHT COLUMN: Detail Fields (Span 7) --}}
                <div class="md:col-span-7 space-y-5 text-left">
                    
                    {{-- Nama Menu --}}
                    <div class="space-y-2">
                        <label for="nama_menu" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Nama Menu <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" placeholder="Contoh: Kopi Susu Aren" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('nama_menu') border-red-500 @enderror" required />
                        @error('nama_menu')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori & Harga Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Kategori --}}
                        <div class="space-y-2 relative">
                            <label for="kategori" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" id="kategori" class="w-full appearance-none bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-4 pr-10 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all cursor-pointer @error('kategori') border-red-500 @enderror" required>
                                <option value="Coffee" {{ old('kategori', $menu->kategori) === 'Coffee' ? 'selected' : '' }}>Coffee</option>
                                <option value="Non Coffee" {{ old('kategori', $menu->kategori) === 'Non Coffee' ? 'selected' : '' }}>Non Coffee</option>
                                <option value="Tea" {{ old('kategori', $menu->kategori) === 'Tea' ? 'selected' : '' }}>Tea</option>
                                <option value="Snack" {{ old('kategori', $menu->kategori) === 'Snack' ? 'selected' : '' }}>Snack</option>
                                <option value="Dessert" {{ old('kategori', $menu->kategori) === 'Dessert' ? 'selected' : '' }}>Dessert</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 pt-6 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('kategori')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga --}}
                        <div class="space-y-2">
                            <label for="harga" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga', intval($menu->harga)) }}" placeholder="0" min="0" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('harga') border-red-500 @enderror" required />
                            @error('harga')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Stok Awal & Status Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Stok Awal --}}
                        <div class="space-y-2">
                            <label for="stok" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Jumlah Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', $menu->stok) }}" placeholder="0" min="0" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('stok') border-red-500 @enderror" />
                            @error('stok')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Stok (Tersedia/Habis) --}}
                        <div class="space-y-2 relative">
                            <label for="status" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Status Stok <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full appearance-none bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-4 pr-10 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all cursor-pointer @error('status') border-red-500 @enderror" required>
                                <option value="tersedia" {{ old('status', $menu->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="habis" {{ old('status', $menu->status) === 'habis' ? 'selected' : '' }}>Habis</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 pt-6 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('status')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2">
                        <label for="deskripsi" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="6" placeholder="Tuliskan deskripsi menu di sini..." class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-2xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-end gap-3 px-6 py-5 bg-[#faf7f2]/40 border-t border-[#ede6df]/60">
            <a href="{{ route('admin.menu.index') }}" class="px-7 py-3 rounded-full bg-white border border-[#ede6df] text-xs font-extrabold text-[#7b6558] hover:bg-[#faf7f2] cursor-pointer transition-colors active:scale-95 text-center">
                Batal
            </a>
            <button type="submit" class="px-7 py-3 rounded-full bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-extrabold transition-all shadow-sm active:scale-95 cursor-pointer text-center">
                Perbarui Menu
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input, type) {
        const preview = document.getElementById('preview-' + type);
        const placeholder = document.getElementById('placeholder-' + type);
        const hover = document.getElementById('hover-' + type);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (hover) {
                    hover.classList.remove('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
