{{-- Logout Confirmation Modal --}}
<div id="logout-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
    {{-- Backdrop --}}
    <div id="logout-modal-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200" onclick="closeLogoutModal()"></div>

    {{-- Modal Card --}}
    <div id="logout-modal-panel" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl transition-all duration-200 transform scale-95 opacity-0">
        {{-- Icon --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 ring-8 ring-red-50/50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-7 w-7 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
        </div>

        {{-- Text --}}
        <h3 id="logout-modal-title" class="mt-4 text-center text-base font-extrabold text-[#21140b]">Konfirmasi Logout</h3>
        <p class="mt-1.5 text-center text-xs sm:text-sm text-[#8f7664] leading-relaxed">Apakah kamu yakin ingin keluar dari akun? Kamu perlu masuk kembali untuk mengakses akunmu.</p>

        {{-- Buttons --}}
        <div class="mt-6 flex gap-3">
            <button type="button" onclick="closeLogoutModal()" class="flex-1 rounded-xl border border-[#e8ded5] bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-[#4a3d35] transition-colors hover:bg-[#f4e6da]/50 hover:text-[#21140b] cursor-pointer">
                Batal
            </button>
            <form id="logout-modal-form" method="POST" action="{{ route('logout') }}" onsubmit="try { localStorage.removeItem('koteshop_cart'); } catch(e) {}" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white transition-all hover:bg-red-700 active:scale-[0.98] shadow-sm cursor-pointer">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        var modal = document.getElementById('logout-modal');
        var backdrop = document.getElementById('logout-modal-backdrop');
        var panel = document.getElementById('logout-modal-panel');
        if (!modal) return;

        // Close user dropdown if open
        var userDropdown = document.getElementById('user-menu-dropdown');
        if (userDropdown && !userDropdown.classList.contains('hidden')) {
            userDropdown.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function () {
            if (backdrop) {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }
            if (panel) {
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }
        });
    }

    function closeLogoutModal() {
        var modal = document.getElementById('logout-modal');
        var backdrop = document.getElementById('logout-modal-backdrop');
        var panel = document.getElementById('logout-modal-panel');
        if (!modal) return;

        if (backdrop) {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
        }
        if (panel) {
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(function () {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('logout-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeLogoutModal();
            }
        }
    });
</script>
