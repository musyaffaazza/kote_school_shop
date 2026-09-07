@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-8 pb-16 space-y-8">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-semibold text-[#8f7664] flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
        <span>&rsaquo;</span>
        <span class="text-[#21140b]">Profil Saya</span>
    </nav>

    {{-- Profile Header --}}
    <div class="flex flex-col items-center text-center space-y-3">
        <div class="relative">
            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-[#3d2a1f] text-3xl font-extrabold text-[#d5c6b8] shadow-md border-4 border-white">
                {{ substr($user->nama ?? 'AM', 0, 2) }}
            </div>
            <div class="absolute bottom-0 right-0 h-7 w-7 rounded-full bg-[#8b5a2b] border-2 border-white flex items-center justify-center text-white shadow-sm cursor-pointer hover:bg-[#3d2a1f] transition-all" title="Ubah Foto Profil">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-[#21140b]">Profil Saya</h2>
            <p class="text-xs text-[#8f7664] font-semibold mt-0.5">Kelola informasi akun dan preferensi kopi Anda.</p>
        </div>
    </div>

    {{-- Form Section --}}
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Informasi Pribadi (Left Columns - Span 2) --}}
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 border border-[#ede6df]/60 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold text-[#21140b]">Informasi Pribadi</h3>
                
                <div class="space-y-4">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1">
                        <label for="nama" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
                    </div>

                    {{-- Alamat Email --}}
                    <div class="space-y-1">
                        <label for="email" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div class="space-y-1">
                        <label for="no_hp" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
                    </div>
                </div>
            </div>

            {{-- Keamanan (Right Column - Span 1) --}}
            <div class="rounded-2xl bg-white p-6 border border-[#ede6df]/60 shadow-sm flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <h3 class="text-base font-extrabold text-[#21140b]">Keamanan</h3>
                    <p class="text-xs text-[#8f7664] leading-relaxed font-medium">
                        Pastikan akun Anda tetap aman dengan memperbarui kata sandi secara berkala.
                    </p>
                </div>
                <button type="button" onclick="openPasswordModal()" class="w-full bg-white border border-[#21140b] hover:bg-[#faf5f0] text-[#21140b] active:scale-[0.98] transition-all py-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-5 9a3 3 0 01-3-3V9a3 3 0 013-3h3a3 3 0 013 3v6a3 3 0 01-3 3h-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v2m-3-2h6" />
                    </svg>
                    Ubah Password
                </button>
            </div>
        </div>

        {{-- Submit Button (Aligend to Right Sesuai Mockup) --}}
        <div class="flex justify-end pt-2">
            <button type="submit" class="bg-[#8b5a2b] hover:bg-[#3d2a1f] text-white font-extrabold text-xs px-8 py-3.5 rounded-full shadow-sm hover:shadow active:scale-[0.98] transition-all cursor-pointer">
                Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- Menu Favorit Section --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-[#ede6df]/60 pb-3">
            <h3 class="text-base font-extrabold text-[#21140b]">Menu Favorit</h3>
            <a href="{{ route('menu') }}" class="text-xs font-bold text-[#8b5a2b] hover:text-[#3d2a1f] transition-colors">Lihat Semua</a>
        </div>

        {{-- Favorite Menu Cards Grid (Sesuai Mockup) --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($favoritMenu as $menu)
            <div class="group relative rounded-2xl bg-white border border-[#ede6df]/60 shadow-sm overflow-hidden hover:shadow-md transition-all">
                {{-- Product Image --}}
                <a href="{{ route('menu.show', $menu) }}" class="block relative h-48 w-full bg-stone-100 overflow-hidden">
                    <img src="{{ $menu->gambar ? asset($menu->gambar) : '/images/logo.png' }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='/images/logo.png'">
                    
                    {{-- Love Icon --}}
                    <span class="absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#8b5a2b] shadow-sm select-none">
                        <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>

                {{-- Product details --}}
                <div class="p-4 space-y-1">
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('menu.show', $menu) }}" class="hover:text-[#8b5a2b] transition-colors">
                            <h4 class="text-sm font-extrabold text-[#21140b] truncate">{{ $menu->nama_menu }}</h4>
                        </a>
                        <span class="text-xs font-extrabold text-[#8b5a2b] shrink-0">Rp {{ number_format($menu->harga / 1000, 0) }}k</span>
                    </div>
                    <p class="text-[11px] text-[#8f7664] font-semibold leading-relaxed truncate">{{ $menu->deskripsi }}</p>
                </div>
            </div>
            @empty
            <p class="text-xs text-[#8f7664] font-semibold py-4">Belum ada menu favorit.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL UBAH PASSWORD --}}
<div id="modal-ubah-password" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closePasswordModal()"></div>

    {{-- Panel --}}
    <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-[0_25px_60px_rgba(33,20,11,0.2)] overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#f0ebe5] px-6 py-4">
            <h3 class="text-sm font-extrabold text-[#21140b]">Ubah Password</h3>
            <button onclick="closePasswordModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#8f7664] hover:bg-[#f5f0eb] hover:text-[#21140b] cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
            @csrf
            {{-- Password Lama --}}
            <div class="space-y-1">
                <label for="password_lama" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Password Lama</label>
                <input type="password" id="password_lama" name="password_lama" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
            </div>

            {{-- Password Baru --}}
            <div class="space-y-1">
                <label for="password" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Password Baru</label>
                <input type="password" id="password" name="password" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div class="space-y-1">
                <label for="password_confirmation" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-2.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closePasswordModal()" class="flex-1 bg-[#faf7f2] border border-[#ede6df] hover:bg-[#ede6df]/50 text-[#8f7664] py-2.5 rounded-xl text-xs font-bold cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-[#21140b] hover:bg-[#3d2a1f] text-white py-2.5 rounded-xl text-xs font-bold cursor-pointer shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPasswordModal() {
        document.getElementById('modal-ubah-password').classList.remove('hidden');
    }

    function closePasswordModal() {
        document.getElementById('modal-ubah-password').classList.add('hidden');
    }
</script>
@endsection
