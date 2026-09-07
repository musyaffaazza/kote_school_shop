@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-8 pb-16 space-y-12">
    {{-- HEADER PAGE --}}
    <div class="space-y-3 text-left">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-[#21140b]">
            Hubungi Kami
        </h1>
        <p class="text-sm md:text-base text-[#7b6558] max-w-3xl leading-relaxed">
            Kami siap mendengar masukan, pertanyaan, atau pesanan spesial Anda. Mari jalin silaturahmi sambil menikmati aroma kopi artisanal terbaik.
        </p>
    </div>

    {{-- TWO COLUMN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        {{-- LEFT COLUMN: CONTACT INFO & BARISTA CARD (SPAN 5) --}}
        <div class="md:col-span-5 space-y-6">
            {{-- Contact Info Card --}}
            <div class="rounded-3xl border border-[#edd8cf]/60 bg-[#fdfaf7] p-6 sm:p-8 shadow-[0_10px_35px_rgba(33,20,11,0.02)] space-y-6">
                <h3 class="text-lg font-bold text-[#21140b] border-b border-[#edd8cf]/40 pb-3">
                    Informasi Kontak
                </h3>
                
                <div class="space-y-5">
                    {{-- Alamat --}}
                    <div class="flex items-start gap-4 text-left">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Alamat</h5>
                            <p class="text-xs text-[#21140b] leading-relaxed">
                                Jalan Barokah Nomor 06, Desa Wanaherang, Kecamatan Gunung Putri, Kabupaten Bogor, Jawa Barat
                            </p>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="flex items-start gap-4 text-left">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.502-5.123-3.802-6.625-6.626l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">WhatsApp</h5>
                            <p class="text-xs text-[#21140b] font-bold leading-relaxed">
                                +62 821 1377 8035
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4 text-left">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Email</h5>
                            <p class="text-xs text-[#21140b] font-semibold leading-relaxed">
                                koteschool@gmail.com
                            </p>
                        </div>
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="flex items-start gap-4 text-left">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Jam Operasional</h5>
                            <p class="text-xs text-[#21140b] leading-relaxed">
                                Senin - Jumat: 08.00 - 15.30
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quality Brewed for You Image Card --}}
            <div class="relative w-full aspect-[16/10] overflow-hidden rounded-3xl border border-[#edd8cf]/80 shadow-[0_15px_45px_rgba(33,20,11,0.06)] group">
                <img src="/images/contact_card.jpg" alt="Student brewing coffee" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                <div class="absolute inset-0 bg-[#21140b]/35 flex items-center justify-center">
                    <span class="text-white font-extrabold text-base sm:text-lg tracking-wide drop-shadow-md">
                        Quality Brewed for You
                    </span>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: CONTACT FORM (SPAN 7) --}}
        <div class="md:col-span-7">
            <div class="rounded-3xl border border-[#edd8cf]/60 bg-white p-6 sm:p-8 shadow-[0_15px_45px_rgba(33,20,11,0.04)]">
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    {{-- Row: Nama & Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <div class="space-y-1 text-left">
                            <label for="nama" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
                        </div>
                        
                        {{-- Email --}}
                        <div class="space-y-1 text-left">
                            <label for="email" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Email</label>
                            <input type="email" id="email" name="email" placeholder="email@contoh.com" class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all" required />
                        </div>
                    </div>

                    {{-- Subjek --}}
                    <div class="space-y-1 text-left">
                        <label for="subjek" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Subjek</label>
                        <div class="relative">
                            <select id="subjek" name="subjek" class="w-full appearance-none bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all cursor-pointer" required>
                                <option value="Saran / Masukan">Saran / Masukan</option>
                                <option value="Kerja Sama">Kerja Sama</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#8f7664]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Pesan Anda --}}
                    <div class="space-y-1 text-left">
                        <label for="pesan" class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider">Pesan Anda</label>
                        <textarea id="pesan" name="pesan" rows="5" placeholder="Ceritakan kebutuhan Anda..." class="w-full bg-[#fdfaf7] border border-[#e8dfd5] text-xs font-bold rounded-xl px-4 py-3.5 text-[#21140b] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] transition-all resize-none" required></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full bg-[#21140b] hover:bg-[#3d2a1f] text-white py-4 rounded-full text-xs font-bold flex items-center justify-center gap-2 cursor-pointer shadow-md hover:shadow-lg transition-all active:scale-[0.98] uppercase tracking-widest">
                        <span>Kirim Pesan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- INTERACTIVE MAP SECTION --}}
    <div class="rounded-3xl border border-[#edd8cf]/60 bg-[#fdfaf7] p-5 sm:p-6 shadow-[0_15px_45px_rgba(33,20,11,0.02)] space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-[#21140b]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-[#a2785d]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.446 5.25-2.25a.75.75 0 0 0 .447-.682V4.82a.75.75 0 0 0-.972-.713L14.25 6.25l-5.503-2.358a.75.75 0 0 0-.693.036L2.75 6.25a.75.75 0 0 0-.447.682v11.834a.75.75 0 0 0 .972.713L9 17.75l5.503 2.358a.75.75 0 0 0 .693-.036Z" />
                </svg>
                <h3 class="text-base font-extrabold">Temukan Kami</h3>
            </div>
            <a href="https://maps.google.com/?q=SMKN+1+Gunung+Putri+Bogor" target="_blank" class="text-xs font-bold text-[#ab7a55] hover:text-[#21140b] transition-colors">Open in Google Maps</a>
        </div>
        
        {{-- Panning Map Container --}}
        <div class="w-full h-80 sm:h-[400px] rounded-2xl overflow-hidden border border-[#edd8cf]/80 bg-white shadow-inner relative">
            <iframe 
                src="https://maps.google.com/maps?q=SMKN+1+Gunung+Putri+Bogor&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                class="absolute inset-0 w-full h-full border-0" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
@endsection
