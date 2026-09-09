<footer class="bg-[#ebe3db] border-t border-[#dfd6ce]/60 text-[#4f4136]">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-10">
        <div class="flex flex-col md:flex-row gap-10 md:gap-12 lg:gap-16 items-start">

            <!-- Brand -->
            <div class="space-y-4 md:w-1/3 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="flex items-center justify-center">
                        <x-ui.logo class="h-8 w-auto" />
                    </div>
                    <span class="text-lg font-bold tracking-tight text-[#21140b] font-sans uppercase">KOTE SCHOOL SHOP</span>
                </a>

                <p class="max-w-xs text-sm leading-relaxed text-[#4f4136]">
                    Menyajikan kopi artisanal dengan sentuhan kehangatan akademik setiap harinya.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-4 text-[#21140b] pt-1">
                    <a href="https://www.instagram.com/kotecoffee.id?utm_source=ig_web_button_share_sheet&igsi=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="text-[#21140b] hover:text-[#8f7664] transition-colors">
                        <x-icons.instagram class="w-5 h-5" />
                    </a>
                    <a href="https://whatsapp.com/channel/0029Vb93h6JB4hdOYlT1Yu0e" target="_blank" rel="noopener noreferrer" aria-label="Saluran WhatsApp" class="text-[#21140b] hover:text-[#8f7664] transition-colors">
                        <x-icons.whatsapp class="w-5 h-5" />
                    </a>
                    <a href="https://www.tiktok.com/@kotteschoolshop" target="_blank" rel="noopener noreferrer" aria-label="TikTok" class="text-[#21140b] hover:text-[#8f7664] transition-colors">
                        <x-icons.tiktok class="w-5 h-5" />
                    </a>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="shrink-0">
                <p class="mb-4 text-xs font-bold uppercase tracking-widest text-[#21140b]">NAVIGASI</p>
                <ul class="space-y-2.5 text-sm text-[#4f4136]">
                    <li><a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors whitespace-nowrap">Beranda</a></li>
                    <li><a href="{{ route('menu') }}" class="hover:text-[#21140b] transition-colors whitespace-nowrap">Menu</a></li>
                    <li><a href="{{ route('promo') }}" class="hover:text-[#21140b] transition-colors whitespace-nowrap">Promo</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#21140b] transition-colors whitespace-nowrap">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#21140b] transition-colors whitespace-nowrap">Kontak</a></li>
                </ul>
            </div>

            <!-- Lokasi Kedai -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#21140b]">LOKASI KEDAI</p>
                    <a href="https://maps.google.com/?q=SMKN+1+Gunung+Putri+Bogor" target="_blank" class="text-xs font-bold text-[#8b5a2b] hover:text-[#21140b] transition-colors flex items-center gap-1">
                        <span>Buka Maps</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </div>

                <div class="w-full h-44 rounded-2xl overflow-hidden border border-[#dfd6ce] bg-white shadow-sm relative">
                    <iframe
                        src="https://maps.google.com/maps?q=SMKN+1+Gunung+Putri+Bogor&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        class="absolute inset-0 w-full h-full border-0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <p class="mt-2.5 text-xs text-[#66574c] font-medium flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#8b5a2b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span>SMKN 1 Gunung Putri, Bogor, Jawa Barat</span>
                </p>
            </div>

        </div>

        <!-- Copyright & Legal -->
        <div class="mt-12 border-t border-[#dfd6ce] pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left text-xs sm:text-sm text-[#66574c]">
            <div>
                &copy; 2026 KOTE SCHOOL SHOP. Modern Artisanal Coffee Experience.
            </div>
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('terms') }}" class="hover:text-[#21140b] underline transition-colors">Syarat &amp; Ketentuan</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-[#21140b] underline transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
