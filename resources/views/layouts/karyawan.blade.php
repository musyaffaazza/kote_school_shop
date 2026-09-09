<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name', 'KOTE SCHOOL SHOP') }} - Staff Portal</title>
        <meta name="description" content="KOTE SCHOOL SHOP Staff Portal - Dashboard Karyawan" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="min-h-screen bg-[#f5f0eb] text-[#21140b] antialiased font-sans">
        <x-ui.toast />
        <x-logout-modal />
        <div class="flex min-h-screen">
            {{-- SIDEBAR --}}
            <aside id="staff-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[230px] flex-col bg-[#1a1210] text-white transition-transform duration-300 lg:translate-x-0 -translate-x-full">
                {{-- Brand --}}
                <div class="px-6 pt-7 pb-6">
                    <h1 class="text-lg font-extrabold tracking-tight leading-tight uppercase">KOTE SCHOOL<br>SHOP</h1>
                    <p class="mt-1 text-[10px] font-bold tracking-[0.25em] text-[#b09a87] uppercase">Staff Portal</p>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 space-y-1 px-4">
                    <x-karyawan.nav-link href="{{ route('karyawan.dashboard') }}" icon="dashboard" :active="request()->routeIs('karyawan.dashboard')">
                        Dashboard
                    </x-karyawan.nav-link>
                    <x-karyawan.nav-link href="{{ route('karyawan.pesanan') }}" icon="list" :active="request()->routeIs('karyawan.pesanan')">
                        Daftar Pesanan
                    </x-karyawan.nav-link>
                    <x-karyawan.nav-link href="{{ route('karyawan.verifikasi') }}" icon="verify" :active="request()->routeIs('karyawan.verifikasi')">
                        Verifikasi Pembayaran
                    </x-karyawan.nav-link>
                    <x-karyawan.nav-link href="{{ route('karyawan.menu') }}" icon="menu" :active="request()->routeIs('karyawan.menu')">
                        Menu
                    </x-karyawan.nav-link>
                    <x-karyawan.nav-link href="{{ route('karyawan.riwayat') }}" icon="history" :active="request()->routeIs('karyawan.riwayat')">
                        Riwayat Transaksi
                    </x-karyawan.nav-link>
                    <x-karyawan.nav-link href="{{ route('karyawan.laporan-keuangan.index') }}" icon="finance" :active="request()->routeIs('karyawan.laporan-keuangan.*')">
                        Laporan Keuangan
                    </x-karyawan.nav-link>
                </nav>

                {{-- Portal & Logout Footer --}}
                <div class="mt-auto px-4 py-4 space-y-2 border-t border-white/5">
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
            <div class="flex-1 lg:ml-[230px]">
                {{-- TOP BAR --}}
                <header class="sticky top-0 z-20 flex items-center justify-between bg-[#f5f0eb]/80 backdrop-blur-md px-6 py-4 lg:px-8">
                    {{-- Mobile Menu Button --}}
                    <button id="btn-mobile-menu" onclick="toggleSidebar()" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#21140b] shadow-sm cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Page Title --}}
                    <div class="hidden lg:block">
                        <h2 class="text-xl font-extrabold text-[#21140b]">@yield('page-title', 'Dashboard Karyawan')</h2>
                        <p class="text-xs text-[#8f7664] font-medium">{{ (request('date') ? \Carbon\Carbon::parse(request('date')) : \Carbon\Carbon::now())->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>

                    @yield('header-search')

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3 sm:gap-4 relative">
                        {{-- Barista Profile Container --}}
                        <div class="relative">
                            <button id="btn-profile" onclick="openModal('modal-profile', 'edit')" class="flex items-center gap-3 rounded-xl bg-white px-3.5 py-2 shadow-sm hover:shadow-md hover:bg-[#fcfaf8] transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#21140b]/20" title="Edit Profil Barista">
                                <div class="relative">
                                    <div id="topbar-user-avatar" class="flex h-8 w-8 items-center justify-center rounded-full bg-[#3d2a1f] text-xs font-bold text-[#d5c6b8]">
                                        {{ substr(auth()->user()->nama ?? 'BS', 0, 2) }}
                                    </div>
                                    <span id="barista-status-dot" class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p id="barista-display-name" class="text-xs font-bold text-[#21140b] leading-tight truncate max-w-[120px]">{{ auth()->user()->nama ?? 'Budi Santoso' }}</p>
                                    <p id="barista-status-text" class="text-[10px] font-medium text-emerald-600">On Duty 🟢</p>
                                </div>
                                <svg class="h-4 w-4 text-[#8f7664] hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="px-6 py-6 lg:px-8">
                    @yield('content')
                </main>

                {{-- Footer --}}
                <footer class="px-6 py-6 text-center text-xs text-[#8f7664] lg:px-8">
                    © {{ date('Y') }} KOTE SCHOOL SHOP System • Modern Artisanal Design
                </footer>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL: PROFILE CARD & EDIT PROFIL            --}}
        {{-- ============================================ --}}
        <div id="modal-profile" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-profile-title">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0" data-modal-backdrop onclick="closeModal('modal-profile')"></div>

            {{-- Panel --}}
            <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-[0_25px_60px_rgba(33,20,11,0.2)] transition-all duration-300 scale-95 opacity-0 overflow-hidden" data-modal-panel>
                {{-- Cover Header --}}
                <div class="relative h-28 bg-gradient-to-br from-[#3d2a1f] via-[#5c3d2a] to-[#1a1210] overflow-hidden">
                    {{-- Decorative pattern --}}
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute -top-6 -right-6 h-24 w-24 rounded-full border-2 border-white/30"></div>
                        <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full border-2 border-white/20"></div>
                    </div>
                    {{-- Close button --}}
                    <button onclick="closeModal('modal-profile')" class="absolute top-3 right-3 flex h-7 w-7 items-center justify-center rounded-lg bg-white/15 text-white/80 hover:bg-white/25 hover:text-white transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    {{-- Role badge --}}
                    <div class="absolute top-3 left-4">
                        <span class="rounded-full bg-white/15 px-3 py-1 text-[10px] font-bold text-white/90 uppercase tracking-wider backdrop-blur-sm">Staff Barista</span>
                    </div>
                </div>

                {{-- Avatar (overlapping cover) --}}
                <div class="flex justify-center -mt-10 relative z-10">
                    <div id="modal-profile-avatar" class="flex h-20 w-20 items-center justify-center rounded-full bg-[#21140b] text-2xl font-extrabold text-white ring-4 ring-white shadow-lg">
                        {{ substr(auth()->user()->nama ?? 'BS', 0, 2) }}
                    </div>
                </div>

                {{-- Name & Role --}}
                <div class="text-center px-6 pt-3 pb-1">
                    <h3 id="modal-profile-title" class="text-lg font-extrabold text-[#21140b]">{{ auth()->user()->nama ?? 'Budi Santoso' }}</h3>
                    <p class="text-xs text-[#a2785d] font-semibold">Senior Barista • NIK: BST-2024-089</p>
                </div>

                {{-- Tab Switcher --}}
                <div class="flex border-b border-[#f0ebe5] px-6 mt-2 gap-4 text-xs font-bold">
                    <button onclick="switchProfileTab('info')" id="tab-btn-info" class="pb-2 text-[#21140b] border-b-2 border-[#21140b]">Informasi</button>
                    <button onclick="switchProfileTab('edit')" id="tab-btn-edit" class="pb-2 text-[#8f7664] hover:text-[#21140b]">Edit Profil</button>
                </div>

                {{-- Tab Content 1: Info Grid --}}
                <div id="profile-tab-info" class="px-6 py-4 space-y-3">
                    {{-- Email --}}
                    <div class="flex items-center gap-3 rounded-xl bg-[#faf7f4] p-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#f0ebe5] text-[#a2785d]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold text-[#8f7664] uppercase tracking-wider">Email</p>
                            <p id="modal-profile-email" class="text-xs font-bold text-[#21140b] truncate">{{ auth()->user()->email ?? 'budi@koteshop.com' }}</p>
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div class="flex items-center gap-3 rounded-xl bg-[#faf7f4] p-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#f0ebe5] text-[#a2785d]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold text-[#8f7664] uppercase tracking-wider">No. Telepon</p>
                            <p id="modal-profile-phone" class="text-xs font-bold text-[#21140b]">{{ auth()->user()->no_hp ?? '0812-3456-7890' }}</p>
                        </div>
                    </div>

                    {{-- Status Shift --}}
                    <div class="rounded-xl bg-[#faf7f4] p-3 text-center">
                        <p class="text-[10px] font-semibold text-[#8f7664] uppercase tracking-wider">Status Shift</p>
                        <div class="mt-1 inline-flex items-center gap-1.5">
                            <span id="modal-status-indicator" class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span id="modal-status-text" class="text-xs font-bold text-emerald-600">On Duty</span>
                        </div>
                    </div>

                    <button onclick="switchProfileTab('edit')" class="w-full rounded-xl bg-[#21140b] py-2.5 text-xs font-bold text-white transition-all hover:bg-[#3d2a1f] active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2 mt-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Profil Barista
                    </button>
                </div>

                {{-- Tab Content 2: Edit Form --}}
                <form id="profile-tab-edit" onsubmit="saveProfileData(event)" class="px-6 py-4 space-y-3 hidden">
                    <div>
                        <label class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider mb-1">Nama Lengkap</label>
                        <input type="text" id="edit-input-nama" value="{{ auth()->user()->nama ?? 'Budi Santoso' }}" class="w-full rounded-xl border border-[#e8ded5] bg-[#faf7f4] px-3 py-2 text-xs font-bold text-[#21140b] focus:border-[#21140b] focus:bg-white focus:outline-none" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider mb-1">Email Barista</label>
                        <input type="email" id="edit-input-email" value="{{ auth()->user()->email ?? 'budi@koteshop.com' }}" class="w-full rounded-xl border border-[#e8ded5] bg-[#faf7f4] px-3 py-2 text-xs font-bold text-[#21140b] focus:border-[#21140b] focus:bg-white focus:outline-none" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[#8f7664] uppercase tracking-wider mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" id="edit-input-phone" value="{{ auth()->user()->no_hp ?? '0812-3456-7890' }}" class="w-full rounded-xl border border-[#e8ded5] bg-[#faf7f4] px-3 py-2 text-xs font-bold text-[#21140b] focus:border-[#21140b] focus:bg-white focus:outline-none" required />
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[#21140b] py-2.5 text-xs font-bold text-white transition-all hover:bg-[#3d2a1f] active:scale-[0.98] cursor-pointer mt-2">
                        Simpan Perubahan
                    </button>
                </form>

                {{-- Footer Actions --}}
                <div class="border-t border-[#f0ebe5] px-6 py-3.5 flex items-center justify-between bg-[#faf7f5]">
                    <span class="text-[10px] font-semibold text-[#8f7664]">KOTE Staff Portal v2.0</span>
                    <button type="button" onclick="openLogoutModal()" class="text-xs font-bold text-red-600 hover:text-red-700 cursor-pointer">
                        Keluar Akun
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Sidebar Toggle
            function toggleSidebar() {
                const sidebar = document.getElementById('staff-sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            // Popover Handlers
            function togglePopover(popoverId) {
                const target = document.getElementById(popoverId);
                const allPopovers = ['popover-profile'];

                allPopovers.forEach(id => {
                    if (id !== popoverId) {
                        document.getElementById(id)?.classList.add('hidden');
                    }
                });

                if (target) {
                    target.classList.toggle('hidden');
                }
            }

            function closePopovers() {
                document.getElementById('popover-profile')?.classList.add('hidden');
            }

            // Close Popovers on Click Outside
            document.addEventListener('click', function(e) {
                const isProfile = e.target.closest('#btn-profile') || e.target.closest('#popover-profile');

                if (!isProfile) {
                    closePopovers();
                }
            });

            // Modal Handlers
            function openModal(id, defaultTab = 'info') {
                closePopovers();
                const modal = document.getElementById(id);
                if (!modal) return;

                if (id === 'modal-profile') {
                    switchProfileTab(defaultTab);
                }

                const backdrop = modal.querySelector('[data-modal-backdrop]');
                const panel = modal.querySelector('[data-modal-panel]');

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                requestAnimationFrame(() => {
                    backdrop?.classList.remove('opacity-0');
                    backdrop?.classList.add('opacity-100');
                    panel?.classList.remove('scale-95', 'opacity-0');
                    panel?.classList.add('scale-100', 'opacity-100');
                });
            }

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                const backdrop = modal.querySelector('[data-modal-backdrop]');
                const panel = modal.querySelector('[data-modal-panel]');

                backdrop?.classList.remove('opacity-100');
                backdrop?.classList.add('opacity-0');
                panel?.classList.remove('scale-100', 'opacity-100');
                panel?.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }

            // Status Switcher Handler
            function setBaristaStatus(status) {
                const statusDot = document.getElementById('barista-status-dot');
                const statusText = document.getElementById('barista-status-text');
                const modalIndicator = document.getElementById('modal-status-indicator');
                const modalText = document.getElementById('modal-status-text');

                const btnOn = document.getElementById('status-btn-onduty');
                const btnBreak = document.getElementById('status-btn-break');
                const btnOff = document.getElementById('status-btn-offduty');

                // Reset button styles
                [btnOn, btnBreak, btnOff].forEach(btn => {
                    if (btn) btn.className = "status-option-btn rounded-xl py-1.5 px-2 font-semibold bg-white text-[#5a4d42] border border-[#e8ded5] hover:bg-[#f5f0eb] cursor-pointer text-center";
                });

                if (status === 'onduty') {
                    if (statusDot) statusDot.className = "absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-white";
                    if (statusText) { statusText.innerText = "On Duty 🟢"; statusText.className = "text-[10px] font-medium text-emerald-600"; }
                    if (modalIndicator) modalIndicator.className = "h-2 w-2 rounded-full bg-emerald-500 animate-pulse";
                    if (modalText) { modalText.innerText = "On Duty"; modalText.className = "text-xs font-bold text-emerald-600"; }
                    if (btnOn) btnOn.className = "status-option-btn rounded-xl py-1.5 px-2 font-bold bg-emerald-500 text-white shadow-sm cursor-pointer text-center";
                    showToast("Status Barista: On Duty 🟢 (Aktif Melayani)");
                } else if (status === 'break') {
                    if (statusDot) statusDot.className = "absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-amber-500 ring-2 ring-white";
                    if (statusText) { statusText.innerText = "Istirahat 🟡"; statusText.className = "text-[10px] font-medium text-amber-600"; }
                    if (modalIndicator) modalIndicator.className = "h-2 w-2 rounded-full bg-amber-500 animate-pulse";
                    if (modalText) { modalText.innerText = "Istirahat"; modalText.className = "text-xs font-bold text-amber-600"; }
                    if (btnBreak) btnBreak.className = "status-option-btn rounded-xl py-1.5 px-2 font-bold bg-amber-500 text-white shadow-sm cursor-pointer text-center";
                    showToast("Status Barista: Istirahat 🟡 (Break Time)");
                } else if (status === 'offduty') {
                    if (statusDot) statusDot.className = "absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-red-500 ring-2 ring-white";
                    if (statusText) { statusText.innerText = "Off Duty 🔴"; statusText.className = "text-[10px] font-medium text-red-600"; }
                    if (modalIndicator) modalIndicator.className = "h-2 w-2 rounded-full bg-red-500";
                    if (modalText) { modalText.innerText = "Off Duty"; modalText.className = "text-xs font-bold text-red-600"; }
                    if (btnOff) btnOff.className = "status-option-btn rounded-xl py-1.5 px-2 font-bold bg-red-500 text-white shadow-sm cursor-pointer text-center";
                    showToast("Status Barista: Off Duty 🔴 (Selesai Shift)");
                }
            }

            // Profile Tabs & Editing
            function switchProfileTab(tab) {
                const tabInfo = document.getElementById('profile-tab-info');
                const tabEdit = document.getElementById('profile-tab-edit');
                const btnInfo = document.getElementById('tab-btn-info');
                const btnEdit = document.getElementById('tab-btn-edit');

                if (tab === 'info') {
                    tabInfo.classList.remove('hidden');
                    tabEdit.classList.add('hidden');
                    btnInfo.className = "pb-2 text-[#21140b] border-b-2 border-[#21140b]";
                    btnEdit.className = "pb-2 text-[#8f7664] hover:text-[#21140b]";
                } else {
                    tabInfo.classList.add('hidden');
                    tabEdit.classList.remove('hidden');
                    btnEdit.className = "pb-2 text-[#21140b] border-b-2 border-[#21140b]";
                    btnInfo.className = "pb-2 text-[#8f7664] hover:text-[#21140b]";
                }
            }

            function saveProfileData(e) {
                e.preventDefault();
                const nama = document.getElementById('edit-input-nama').value;
                const email = document.getElementById('edit-input-email').value;
                const phone = document.getElementById('edit-input-phone').value;

                // Update UI elements live across header, modal, and sidebar
                const nameDisplay = document.getElementById('barista-display-name');
                const sidebarName = document.getElementById('sidebar-user-name');
                const sidebarAvatar = document.getElementById('sidebar-user-avatar');
                const topbarAvatar = document.getElementById('topbar-user-avatar');
                const popoverName = document.getElementById('popover-barista-name');
                const modalTitle = document.getElementById('modal-profile-title');
                const modalEmail = document.getElementById('modal-profile-email');
                const modalPhone = document.getElementById('modal-profile-phone');
                const avatarEl = document.getElementById('modal-profile-avatar');

                if (nameDisplay) nameDisplay.innerText = nama;
                if (sidebarName) sidebarName.innerText = nama;
                if (popoverName) popoverName.innerText = nama;
                if (modalTitle) modalTitle.innerText = nama;
                if (modalEmail) modalEmail.innerText = email;
                if (modalPhone) modalPhone.innerText = phone;
                if (avatarEl && nama) avatarEl.innerText = nama.substring(0, 2).toUpperCase();
                if (sidebarAvatar && nama) sidebarAvatar.innerText = nama.substring(0, 2).toUpperCase();
                if (topbarAvatar && nama) topbarAvatar.innerText = nama.substring(0, 2).toUpperCase();

                // Send AJAX POST request to save changes in database
                fetch('{{ route("karyawan.profile.update", [], false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nama: nama, email: email, no_hp: phone })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast("Profil berhasil disimpan ke database! ✓");
                    }
                })
                .catch(err => console.error(err));

                switchProfileTab('info');
                showToast("Profil Barista berhasil diperbarui! ✓");
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePopovers();
                    document.querySelectorAll('[id^="modal-"]:not(.hidden)').forEach(modal => {
                        closeModal(modal.id);
                    });
                }
            });
        </script>
    </body>
</html>

