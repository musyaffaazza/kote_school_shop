<style>
    /* Search popup (muncul di bawah tombol search) */
    #search-popup {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 340px;
        background: white;
        border: 1px solid #edd8cf;
        border-radius: 1rem;
        box-shadow: 0 8px 30px rgba(33,20,11,0.12);
        z-index: 200;
        overflow: hidden;
    }
    #search-popup.open {
        display: block;
    }
    #search-popup input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.8rem;
        font-weight: 500;
        color: #21140b;
        width: 100%;
    }
    #search-popup input::placeholder {
        color: #b5a49a;
    }
</style>

<header class="sticky top-0 z-40 bg-[#fcfaf7]/95 backdrop-blur-md border-b border-[#f3ece4] shadow-[0_4px_20px_rgba(33,20,11,0.03)] transition-all">


    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-10">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
            <x-ui.logo class="h-8 w-auto" />
            <span class="hidden sm:inline text-lg font-bold tracking-tight text-[#21140b]">KOTE SCHOOL SHOP</span>
            <span class="sm:hidden text-lg font-bold tracking-tight text-[#21140b]">KSS</span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-sm pb-1 border-b-2 transition-colors {{ request()->routeIs('home') ? 'font-semibold text-[#21140b] border-[#a2785d]' : 'font-normal text-[#4a3d35] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">Beranda</a>
            <a href="{{ route('menu') }}" class="text-sm pb-1 border-b-2 transition-colors {{ request()->routeIs('menu', 'menu.show') ? 'font-semibold text-[#21140b] border-[#a2785d]' : 'font-normal text-[#4a3d35] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">Menu</a>
            <a href="{{ route('promo') }}" class="text-sm pb-1 border-b-2 transition-colors {{ request()->routeIs('promo') ? 'font-semibold text-[#21140b] border-[#a2785d]' : 'font-normal text-[#4a3d35] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">Promo</a>
            <a href="{{ route('about') }}" class="text-sm pb-1 border-b-2 transition-colors {{ request()->routeIs('about') ? 'font-semibold text-[#21140b] border-[#a2785d]' : 'font-normal text-[#4a3d35] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="text-sm pb-1 border-b-2 transition-colors {{ request()->routeIs('contact') ? 'font-semibold text-[#21140b] border-[#a2785d]' : 'font-normal text-[#4a3d35] border-transparent hover:text-[#21140b] hover:border-[#c5ab98]' }}">Kontak</a>
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-1">

            <!-- Search toggle + popup -->
            <div style="position:relative;">
                <button id="search-toggle" type="button" aria-label="Cari" class="p-2 rounded-lg text-[#21140b] hover:bg-[#f4e6da]/60 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>

                <!-- Search popup -->
                <div id="search-popup">
                    <!-- Input row -->
                    <div style="display:flex;align-items:center;gap:0.5rem;padding:0.6rem 0.75rem;border-bottom:1px solid #f4e6da;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" style="width:0.9rem;height:0.9rem;color:#a2785d;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input id="search-input" type="text" placeholder="Cari menu..." autocomplete="off" spellcheck="false" />
                        <button id="search-close" type="button" style="padding:0.2rem;cursor:pointer;color:#8f7664;background:none;border:none;display:flex;align-items:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" style="width:0.85rem;height:0.85rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Results -->
                    <div id="search-results" style="max-height:13rem;overflow-y:auto;padding:0.3rem;">
                        <p style="text-align:center;padding:1.2rem 1rem;font-size:0.72rem;color:#8f7664;">Ketik nama menu yang ingin dicari...</p>
                    </div>
                </div>
            </div>

            <!-- Cart -->
            <a href="{{ route('cart') }}" aria-label="Keranjang" class="relative p-2 rounded-lg text-[#21140b] hover:bg-[#f4e6da]/60 transition-colors cursor-pointer flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.057-.803 2.228-1.91l1.246-8.098H5.112M7.5 14.25a3 3 0 0 1-3 3M7.5 14.25a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3M7.5 21a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm9 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                </svg>
                <span id="cart-badge" class="hidden absolute top-0.5 right-0.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-red-600 text-[9px] font-extrabold text-white shadow-sm">0</span>
            </a>

            @guest
                <a id="masuk-btn" href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-[#21140b] px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#3d2a1f] transition-all uppercase tracking-wider ml-1">
                    Masuk
                </a>
            @endguest

            @auth
                <div class="relative ml-1 pl-3 border-l border-[#e7d7ce]">
                    <button type="button" id="user-menu-button" class="flex items-center gap-2 rounded-full py-1 px-2.5 hover:bg-[#f4e6da]/70 transition-all cursor-pointer focus:outline-none">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#21140b] text-xs font-bold text-white uppercase shadow-sm">
                            {{ substr(Auth::user()->nama, 0, 1) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-[#21140b] leading-tight">{{ Auth::user()->nama }}</span>
                            <span class="text-[10px] font-semibold text-[#8f7664] capitalize">{{ Auth::user()->role }}</span>
                        </div>
                        <svg id="user-menu-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-[#8f7664] transition-transform duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-2xl border border-[#edd8cf] bg-white p-2 shadow-xl ring-1 ring-black/5 z-50">
                        <div class="px-3 py-2.5 border-b border-[#f4e6da]">
                            <p class="text-xs font-bold text-[#21140b] truncate">{{ Auth::user()->nama }}</p>
                            <p class="text-[11px] text-[#8f7664] truncate">{{ Auth::user()->email }}</p>
                            <span class="mt-1.5 inline-block rounded-full bg-[#fbf1e8] px-2.5 py-0.5 text-[10px] font-bold text-[#a2785d] capitalize">
                                Peran: {{ Auth::user()->role }}
                            </span>
                        </div>
                        <div class="py-1">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-[#ab7a55] hover:bg-[#fbf1e8] hover:text-[#21140b] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-[#ab7a55]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                    </svg>
                                    Dashboard Admin
                                </a>
                            @elseif(auth()->user()->role === 'karyawan')
                                <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-[#ab7a55] hover:bg-[#fbf1e8] hover:text-[#21140b] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-[#ab7a55]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                    </svg>
                                    Dashboard Karyawan
                                </a>
                            @endif
                            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-[#4a3d35] hover:bg-[#fbf1e8] hover:text-[#21140b] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-[#8f7664]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-[#4a3d35] hover:bg-[#fbf1e8] hover:text-[#21140b] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-[#8f7664]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Riwayat Pesanan
                            </a>
                        </div>
                        <div class="border-t border-[#f4e6da] pt-1">
                            <button type="button" onclick="openLogoutModal()" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                Keluar / Logout
                            </button>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Hamburger (mobile only, shown via JS) -->
            <button id="mobile-menu-button" type="button" aria-label="Menu" class="p-2 rounded-lg text-[#21140b] hover:bg-[#f4e6da]/60 transition-colors cursor-pointer" style="display:none;">
                <svg id="icon-hamburger" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-5 w-5" style="display:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile nav -->
    <nav id="mobile-nav" style="display:none;" class="border-t border-[#f3ece4]/60 bg-[#fcfaf7] px-4 py-3 space-y-1">
        <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('home') ? 'font-semibold text-[#21140b] bg-[#f4e6da]/50' : 'font-normal text-[#4a3d35] hover:bg-[#f4e6da]/50' }}">Beranda</a>
        <a href="{{ route('menu') }}" class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('menu') ? 'font-semibold text-[#21140b] bg-[#f4e6da]/50' : 'font-normal text-[#4a3d35] hover:bg-[#f4e6da]/50' }}">Menu</a>
        <a href="{{ route('promo') }}" class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('promo') ? 'font-semibold text-[#21140b] bg-[#f4e6da]/50' : 'font-normal text-[#4a3d35] hover:bg-[#f4e6da]/50' }}">Promo</a>
        <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('about') ? 'font-semibold text-[#21140b] bg-[#f4e6da]/50' : 'font-normal text-[#4a3d35] hover:bg-[#f4e6da]/50' }}">Tentang Kami</a>
        <a href="{{ route('contact') }}" class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('contact') ? 'font-semibold text-[#21140b] bg-[#f4e6da]/50' : 'font-normal text-[#4a3d35] hover:bg-[#f4e6da]/50' }}">Kontak</a>
        @guest
            <div class="pt-2 border-t border-[#f3ece4]/60">
                <a href="{{ route('login') }}" class="flex items-center justify-center rounded-full bg-[#21140b] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#3d2a1f] transition-all uppercase tracking-wider">
                    Masuk
                </a>
            </div>
        @endguest
        @auth
            <div class="pt-2 border-t border-[#f3ece4]/60 space-y-1">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-[#a2785d] hover:bg-[#f4e6da]/50">Dashboard Admin</a>
                @elseif(auth()->user()->role === 'karyawan')
                    <a href="{{ route('karyawan.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-[#a2785d] hover:bg-[#f4e6da]/50">Dashboard Karyawan</a>
                @endif
                <a href="{{ route('profile') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-[#4a3d35] hover:bg-[#f4e6da]/50">Profil Saya</a>
                <a href="{{ route('orders.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-[#4a3d35] hover:bg-[#f4e6da]/50">Riwayat Pesanan</a>
                <button type="button" onclick="openLogoutModal()" class="w-full text-left flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Keluar / Logout
                </button>
            </div>
        @endauth
</header>

<script>
(function () {
    var LG = 1024;

    /* ── Responsive show/hide ── */
    function applyResponsive() {
        var isMobile = window.innerWidth < LG;
        var masukBtn = document.getElementById('masuk-btn');
        var hamburger = document.getElementById('mobile-menu-button');
        if (masukBtn)  masukBtn.style.display  = isMobile ? 'none' : 'inline-flex';
        if (hamburger) hamburger.style.display = isMobile ? 'inline-flex' : 'none';
        if (!isMobile) {
            var nav = document.getElementById('mobile-nav');
            if (nav) nav.style.display = 'none';
            resetHamburgerIcon();
        }
    }

    function resetHamburgerIcon() {
        var h = document.getElementById('icon-hamburger');
        var c = document.getElementById('icon-close');
        if (h) h.style.display = '';
        if (c) c.style.display = 'none';
    }

    applyResponsive();
    window.addEventListener('resize', applyResponsive);

    document.addEventListener('DOMContentLoaded', function () {

        /* ── Mobile menu ── */
        var mobileBtn = document.getElementById('mobile-menu-button');
        var mobileNav = document.getElementById('mobile-nav');
        var iconH     = document.getElementById('icon-hamburger');
        var iconC     = document.getElementById('icon-close');
        var navOpen   = false;

        if (mobileBtn && mobileNav) {
            mobileBtn.addEventListener('click', function () {
                navOpen = !navOpen;
                mobileNav.style.display = navOpen ? 'block' : 'none';
                if (iconH) iconH.style.display = navOpen ? 'none' : '';
                if (iconC) iconC.style.display = navOpen ? '' : 'none';
            });
        }

        /* ── User dropdown ── */
        var userBtn   = document.getElementById('user-menu-button');
        var ddMenu    = document.getElementById('user-menu-dropdown');
        var chevron   = document.getElementById('user-menu-chevron');

        if (userBtn && ddMenu) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var hidden = ddMenu.classList.contains('hidden');
                ddMenu.classList.toggle('hidden');
                if (chevron) chevron.style.transform = hidden ? 'rotate(180deg)' : '';
            });
            document.addEventListener('click', function (e) {
                if (!ddMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    ddMenu.classList.add('hidden');
                    if (chevron) chevron.style.transform = '';
                }
            });
        }

        /* ── Search ── */
        var searchToggle = document.getElementById('search-toggle');
        var searchPopup  = document.getElementById('search-popup');
        var searchClose  = document.getElementById('search-close');
        var searchInput  = document.getElementById('search-input');
        var searchRes    = document.getElementById('search-results');

        function openSearch(e) {
            e.stopPropagation();
            searchPopup.classList.add('open');
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    if (searchInput) searchInput.focus();
                });
            });
        }

        function closeSearch() {
            searchPopup.classList.remove('open');
            if (searchInput) searchInput.value = '';
            showPlaceholder();
        }

        function showPlaceholder() {
            searchRes.innerHTML = '<p style="text-align:center;padding:1.2rem 1rem;font-size:0.72rem;color:#8f7664;">Ketik nama menu yang ingin dicari...</p>';
        }

        function showNoResult(q) {
            searchRes.innerHTML = '<p style="text-align:center;padding:1.2rem 1rem;font-size:0.72rem;color:#8f7664;">Tidak ada hasil untuk <strong style="color:#21140b;">' + q + '</strong></p>';
        }

        function renderResults(matches) {
            searchRes.innerHTML = matches.slice(0, 6).map(function (el) {
                var name  = el.dataset.menuName  || '';
                var price = el.dataset.menuPrice || '';
                var img   = el.dataset.menuImg   || '';
                return '<a href="#menu" onclick="document.getElementById(\'search-popup\').classList.remove(\'open\')" style="display:flex;align-items:center;gap:0.6rem;padding:0.45rem 0.65rem;border-radius:0.6rem;text-decoration:none;cursor:pointer;transition:background 0.15s;" onmouseover="this.style.background=\'#fbf1e8\'" onmouseout="this.style.background=\'\'">'
                    + (img ? '<img src="' + img + '" style="width:2rem;height:2rem;border-radius:0.4rem;object-fit:cover;flex-shrink:0;" />' : '<div style="width:2rem;height:2rem;border-radius:0.4rem;background:#f4e6da;flex-shrink:0;"></div>')
                    + '<div><p style="font-size:0.8rem;font-weight:700;color:#21140b;margin:0;">' + name + '</p>'
                    + (price ? '<p style="font-size:0.7rem;color:#8f7664;margin:0.1rem 0 0;">' + price + '</p>' : '')
                    + '</div></a>';
            }).join('');
        }

        if (searchToggle) searchToggle.addEventListener('click', openSearch);
        if (searchClose)  searchClose.addEventListener('click', function(e){ e.stopPropagation(); closeSearch(); });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearch();
        });

        /* Click outside closes popup */
        document.addEventListener('click', function (e) {
            if (searchPopup && !searchPopup.contains(e.target) && !searchToggle.contains(e.target)) {
                closeSearch();
            }
        });

        /* Live search */
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                var q = searchInput.value.trim().toLowerCase();
                if (!q) { showPlaceholder(); return; }
                var items   = Array.from(document.querySelectorAll('[data-menu-name]'));
                var matches = items.filter(function (el) {
                    return el.dataset.menuName.toLowerCase().includes(q);
                });
                if (matches.length === 0) { showNoResult(q); return; }
                renderResults(matches);
            });
        }

        showPlaceholder();
    });
})();
</script>
