<div id="splash-screen" class="fixed inset-0 min-h-[100dvh] z-[9999] flex flex-col items-center justify-center bg-[#f8f3ed] px-4 pt-[env(safe-area-inset-top)] pb-[env(safe-area-inset-bottom)] transition-opacity duration-700 ease-in-out select-none">
    <div class="flex flex-col items-center text-center space-y-4 max-w-sm w-full animate-fade-in">
        <!-- Logo KOTE Coffee Stylized -->
        <div class="relative flex flex-col items-center justify-center mb-1">
            <x-ui.logo variant="splash" class="h-28 w-auto" />
        </div>

        <!-- Nama Brand & Tagline -->
        <div class="space-y-1.5">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-wider text-[#21140b] uppercase font-sans">
                KOTE SCHOOL SHOP
            </h1>
            <p class="text-sm sm:text-base font-semibold text-[#a2785d]">
                Pesan Mudah, Cepat, dan Praktis
            </p>
        </div>

        <!-- Progress Loading Indicator Line -->
        <div class="pt-4 w-full flex justify-center">
            <div class="h-1 w-44 overflow-hidden rounded-full bg-[#edd8cf]">
                <div id="splash-progress-bar" class="h-full w-0 bg-[#e0a98b] transition-all duration-[1400ms] ease-out rounded-full"></div>
            </div>
        </div>

        <!-- Versi App -->
        <p class="pt-2 text-[11px] font-medium text-[#b59e8f]">
            v1.0.0
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const splash = document.getElementById('splash-screen');
        const progressBar = document.getElementById('splash-progress-bar');

        if (!splash) return;

        // Cek jika splash screen sudah pernah ditampilkan di sesi browser ini
        const hasSeenSplash = sessionStorage.getItem('kote_splash_seen');

        if (hasSeenSplash) {
            // Jika sudah pernah ditampilkan di sesi ini, langsung sembunyikan tanpa delay
            splash.style.display = 'none';
            return;
        }

        // Animasi progress bar
        setTimeout(function () {
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        }, 100);

        // Fade out splash screen
        setTimeout(function () {
            splash.classList.add('opacity-0', 'pointer-events-none');
            sessionStorage.setItem('kote_splash_seen', 'true');

            // Sembunyikan dari DOM setelah animasi fadeout selesai
            setTimeout(function () {
                splash.style.display = 'none';
            }, 750);
        }, 1800);
    });
</script>
