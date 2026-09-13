<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <title>{{ config('app.name', 'KOTE SCHOOL SHOP') }} - Coffee Admin</title>
        <meta name="description" content="KOTE SCHOOL SHOP Coffee Admin Portal" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Chart.js CDN for beautiful line charts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="min-h-screen bg-[#faf7f2] text-[#21140b] antialiased font-sans">
        <x-ui.toast />
        <x-logout-modal />
        <div class="flex min-h-screen">
            {{-- SIDEBAR --}}
            <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[230px] flex-col bg-[#111111] text-white transition-transform duration-300 lg:translate-x-0 -translate-x-full">
                {{-- Brand --}}
                <div class="px-6 pt-7 pb-6">
                    <h1 class="text-base font-extrabold tracking-tight leading-tight uppercase">Kote School<br>Shop</h1>
                    <p class="mt-1 text-[9px] font-bold tracking-[0.25em] text-[#b09a87] uppercase">Coffee Admin</p>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 space-y-1 px-4 overflow-y-auto">
                    <x-admin.nav-link href="{{ route('admin.dashboard') }}" icon="dashboard" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.menu.index') }}" icon="menu" :active="request()->routeIs('admin.menu.*')">
                        Menu
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.promo.index') }}" icon="promo" :active="request()->routeIs('admin.promo.*')">
                        Promo
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.pembayaran.index') }}" icon="payment" :active="request()->routeIs('admin.pembayaran.*')">
                        Pembayaran
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.laporan-keuangan.index') }}" icon="financial" :active="request()->routeIs('admin.laporan-keuangan.*')">
                        Laporan Keuangan
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.messages.index') }}" icon="messages" :active="request()->routeIs('admin.messages.*')">
                        Pesan Masuk
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.ulasan.index') }}" icon="reviews" :active="request()->routeIs('admin.ulasan.*')">
                        Review Management
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.karyawan.index') }}" icon="users-manage" :active="request()->routeIs('admin.karyawan.*')">
                        Karyawan
                    </x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.pengguna.index') }}" icon="users" :active="request()->routeIs('admin.pengguna.*')">
                        Pengguna
                    </x-admin.nav-link>
                </nav>

                {{-- User Profile & Portal Footer --}}
                <div class="mt-auto border-t border-white/5 px-4 py-4 space-y-2">
                    {{-- Portal Ke Halaman User Button (Hanya di atas Logout) --}}
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-semibold text-[#d5c6b8] hover:text-white bg-white/5 hover:bg-white/10 border border-white/5 transition-all">
                        <svg class="h-4 w-4 shrink-0 text-[#a2785d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span class="flex-1">Halaman User</span>
                        <span class="text-[10px] text-[#8f7664]">↗</span>
                    </a>

                    <button type="button" id="btn-logout" onclick="openLogoutModal()" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-red-400 hover:text-red-300 transition-colors cursor-pointer w-full text-left">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </aside>

            {{-- MOBILE SIDEBAR OVERLAY --}}
            <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm hidden lg:hidden" onclick="toggleSidebar()"></div>

            {{-- MAIN CONTENT --}}
            <div class="flex-1 lg:ml-[230px] flex flex-col min-w-0">
                {{-- TOP BAR --}}
                <header class="sticky top-0 z-20 flex items-center justify-between bg-[#faf7f2]/80 backdrop-blur-md px-6 py-4 lg:px-8 border-b border-[#ede6df]/40">
                    {{-- Left section: Mobile Menu Button & Page Title --}}
                    <div class="flex items-center gap-4">
                        <button id="btn-mobile-menu" onclick="toggleSidebar()" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#21140b] shadow-sm border border-[#ede6df] cursor-pointer shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-extrabold text-[#21140b]">@yield('page-title', 'Admin Dashboard')</h2>
                    </div>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                        {{-- Profile Info --}}
                        <div class="flex items-center gap-3">
                            <div class="hidden sm:block text-right">
                                <p class="text-xs font-bold text-[#21140b] leading-tight">{{ auth()->user()->nama ?? 'James Smith' }}</p>
                                <p class="text-[10px] text-[#8f7664] font-medium">{{ auth()->user()->role === 'admin' ? 'Store Manager' : 'Staff' }}</p>
                            </div>
                            <button id="btn-profile" onclick="openModal('modal-profile')" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#3d2a1f] text-xs font-bold text-[#d5c6b8] cursor-pointer">
                                {{ substr(auth()->user()->nama ?? 'James Smith', 0, 2) }}
                            </button>
                        </div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="px-6 py-6 lg:px-8 flex-1">
                    @yield('content')
                </main>

                {{-- Footer --}}
                <footer class="px-6 py-4 text-center text-xs text-[#8f7664] border-t border-[#ede6df]/45">
                    © {{ date('Y') }} KOTE SCHOOL SHOP System • Coffee Admin Dashboard
                </footer>
            </div>
        </div>



        {{-- ============================================ --}}
        {{-- MODAL: PROFILE CARD & EDIT PROFIL            --}}
        {{-- ============================================ --}}
        <div id="modal-profile" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-profile')"></div>

            {{-- Panel --}}
            <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-[0_25px_60px_rgba(33,20,11,0.2)] overflow-hidden">
                {{-- Cover Header --}}
                <div class="h-24 bg-gradient-to-br from-[#3d2a1f] to-[#111] relative">
                    <button onclick="closeModal('modal-profile')" class="absolute top-3 right-3 flex h-7 w-7 items-center justify-center rounded-lg bg-white/15 text-white/80 hover:bg-white/25 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Avatar --}}
                <div class="flex justify-center -mt-8 relative z-10">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#21140b] text-lg font-extrabold text-white ring-4 ring-white shadow">
                        {{ substr(auth()->user()->nama ?? 'James Smith', 0, 2) }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <h3 class="text-base font-extrabold text-[#21140b]">{{ auth()->user()->nama ?? 'James Smith' }}</h3>
                        <p class="text-xs text-[#a2785d] font-semibold">{{ auth()->user()->role === 'admin' ? 'Store Manager' : 'Staff' }}</p>
                    </div>

                    <div class="space-y-2 text-xs text-[#5a4d42] bg-[#faf7f2] p-4 rounded-xl border border-[#ede6df]/50">
                        <div class="flex justify-between">
                            <span class="font-medium">Email:</span>
                            <span class="font-bold text-[#21140b]">{{ auth()->user()->email ?? 'admin@kote.com' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">No. HP:</span>
                            <span class="font-bold text-[#21140b]">{{ auth()->user()->no_hp ?? '-' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full rounded-xl bg-[#21140b] py-2.5 text-xs font-bold text-white transition-all hover:bg-[#3d2a1f] active:scale-[0.98] cursor-pointer">
                        <svg class="h-4 w-4 text-[#d5c6b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Lihat Halaman User ↗</span>
                    </a>
                </div>
            </div>
        </div>

        <script>
            // Sidebar Toggle
            function toggleSidebar() {
                const sidebar = document.getElementById('admin-sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            // Modal Handlers
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    const openModals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
                    if (openModals.length === 0) {
                        document.body.style.overflow = '';
                    }
                }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
                    openModals.forEach(m => m.classList.add('hidden'));
                    document.body.style.overflow = '';
                }
            });
        </script>
    </body>
</html>
