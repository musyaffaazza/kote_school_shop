@extends('layouts.admin')

@section('page-title', 'Edit Karyawan')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-2 text-xs font-bold text-[#8f7664] uppercase tracking-wider text-left">
        <a href="{{ route('admin.karyawan.index') }}" class="hover:text-[#21140b] transition-colors">Data Karyawan</a>
        <span>&rsaquo;</span>
        <span class="text-[#21140b]">Edit Karyawan</span>
    </div>

    {{-- Title --}}
    <div class="space-y-1 text-left">
        <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b]">
            Edit Karyawan
        </h1>
        <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
            Perbarui detail informasi karyawan <span class="font-extrabold text-[#21140b]">"{{ $karyawan->nama }}"</span>.
        </p>
    </div>

    {{-- Form Card --}}
    <form action="{{ route('admin.karyawan.update', $karyawan) }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-[#ede6df] bg-white shadow-sm overflow-hidden flex flex-col">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT COLUMN: Upload Foto (Span 4) --}}
                <div class="md:col-span-4 space-y-4 flex flex-col items-center">
                    <label class="text-xs font-bold text-[#21140b] uppercase tracking-wider text-center w-full">Foto Profil</label>
                    
                    {{-- Input file (Hidden) --}}
                    <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewImage(this)" />

                    {{-- Circular Foto Box --}}
                    <div onclick="document.getElementById('foto').click();" class="w-40 h-40 border-2 border-dashed border-[#ede6df] rounded-full bg-[#fdfbf9] flex flex-col items-center justify-center cursor-pointer hover:bg-[#faf7f2]/50 transition-colors p-2 relative group overflow-hidden shadow-inner">
                        <div class="flex flex-col items-center justify-center text-center space-y-2 {{ $karyawan->foto_identitas ? 'hidden' : '' }}" id="placeholder-foto">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#fbf1e8]/80 text-[#b5a49a]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#21140b] tracking-wide">Pilih Foto</p>
                                <p class="text-[9px] text-[#8f7664] font-medium mt-0.5">JPG/PNG/WEBP</p>
                            </div>
                        </div>
                        
                        <img id="preview-foto" 
                             src="{{ $karyawan->foto_identitas ? (str_starts_with($karyawan->foto_identitas, 'http') ? $karyawan->foto_identitas : asset($karyawan->foto_identitas)) : '#' }}" 
                             alt="Preview" 
                             class="absolute inset-0 w-full h-full object-cover {{ $karyawan->foto_identitas ? '' : 'hidden' }}" />
                        
                        {{-- Hover overlay to change image --}}
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity {{ $karyawan->foto_identitas ? '' : 'hidden' }}" id="hover-foto">
                            <span class="text-white text-xs font-bold uppercase tracking-wider">Ubah Foto</span>
                        </div>
                    </div>

                    {{-- Keterangan format --}}
                    <p class="text-[10px] text-[#8f7664] font-semibold text-center leading-relaxed">Maksimal ukuran file: 2MB.</p>

                    @error('foto')
                        <p class="text-xs text-red-500 font-semibold text-center mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RIGHT COLUMN: Detail Fields (Span 8) --}}
                <div class="md:col-span-8 space-y-5 text-left">
                    
                    {{-- Nama Lengkap --}}
                    <div class="space-y-2">
                        <label for="nama" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $karyawan->nama) }}" placeholder="Contoh: Budi Santoso" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('nama') border-red-500 @enderror" required />
                        @error('nama')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email & No. HP Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}" placeholder="Contoh: budi@kote.com" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('email') border-red-500 @enderror" required />
                            @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No. HP --}}
                        <div class="space-y-2">
                            <label for="no_hp" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">No. Handphone <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}" placeholder="Contoh: 0812-3456-7890" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('no_hp') border-red-500 @enderror" required />
                            @error('no_hp')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Jabatan, Jenis Kelamin & Password Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        {{-- Jabatan --}}
                        <div class="space-y-2 relative">
                            <label for="jabatan" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Role / Jabatan <span class="text-red-500">*</span></label>
                            <select name="jabatan" id="jabatan" class="w-full appearance-none bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-4 pr-10 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all cursor-pointer @error('jabatan') border-red-500 @enderror" required>
                                <option value="Barista" {{ old('jabatan', $karyawan->jabatan) === 'Barista' ? 'selected' : '' }}>Barista</option>
                                <option value="Kasir" {{ old('jabatan', $karyawan->jabatan) === 'Kasir' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 pt-6 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('jabatan')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="space-y-2 relative">
                            <label for="jenis_kelamin" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="w-full appearance-none bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-4 pr-10 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all cursor-pointer @error('jenis_kelamin') border-red-500 @enderror" required>
                                <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 pt-6 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('jenis_kelamin')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <label for="password" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Password Baru</label>
                            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all @error('password') border-red-500 @enderror" />
                            @error('password')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2">
                        <label for="alamat" class="text-xs font-bold text-[#21140b] uppercase tracking-wider">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat" rows="4" placeholder="Tuliskan alamat lengkap di sini..." class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-2xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all resize-none @error('alamat') border-red-500 @enderror" required>{{ old('alamat', $karyawan->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-end gap-3 px-6 py-5 bg-[#faf7f2]/40 border-t border-[#ede6df]/60">
            <a href="{{ route('admin.karyawan.index') }}" class="px-7 py-3 rounded-full bg-white border border-[#ede6df] text-xs font-extrabold text-[#7b6558] hover:bg-[#faf7f2] cursor-pointer transition-colors active:scale-95 text-center">
                Batal
            </a>
            <button type="submit" class="px-7 py-3 rounded-full bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-extrabold transition-all shadow-sm active:scale-95 cursor-pointer text-center">
                Perbarui Karyawan
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview-foto');
        const placeholder = document.getElementById('placeholder-foto');
        const hover = document.getElementById('hover-foto');
        
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
