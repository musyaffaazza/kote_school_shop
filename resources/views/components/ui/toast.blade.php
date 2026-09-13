{{-- ================================================================= --}}
{{-- GLOBAL CENTERED POPUP MODAL NOTIFICATION SYSTEM (SWEETALERT / IOS STYLE) --}}
{{-- ================================================================= --}}

<style>
    #koteshop-toast-overlay {
        position: fixed !important;
        inset: 0 !important;
        top: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        z-index: 9999999 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right)) max(1rem, env(safe-area-inset-bottom)) max(1rem, env(safe-area-inset-left)) !important;
        box-sizing: border-box !important;
        background-color: rgba(33, 20, 11, 0.45) !important;
        backdrop-filter: blur(5px) !important;
        -webkit-backdrop-filter: blur(5px) !important;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease-out !important;
    }

    #koteshop-toast-overlay.active {
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    .koteshop-dialog-card {
        position: relative !important;
        width: 100% !important;
        max-width: 380px !important;
        margin: 0 auto !important;
        box-sizing: border-box !important;
        background-color: #ffffff !important;
        border-radius: 2rem !important;
        border: 1px solid #edd8cf !important;
        box-shadow: 0 25px 70px rgba(33, 20, 11, 0.3) !important;
        padding: 2rem 1.75rem 1.75rem 1.75rem !important;
        text-align: center !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        transform: scale(0.9) translateY(12px) !important;
        opacity: 0 !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease-out !important;
        user-select: none;
    }

    #koteshop-toast-overlay.active .koteshop-dialog-card {
        transform: scale(1) translateY(0) !important;
        opacity: 1 !important;
    }

    .koteshop-dialog-card.closing {
        transform: scale(0.9) translateY(12px) !important;
        opacity: 0 !important;
    }
</style>

<div id="koteshop-toast-overlay" aria-live="polite" aria-atomic="true"></div>

<script>
    (function () {
        let activeDismissTimer = null;

        window.showToast = function (message, type = 'success', title = null, duration = 3500) {
            if (!message) return;

            let overlay = document.getElementById('koteshop-toast-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'koteshop-toast-overlay';
                overlay.setAttribute('aria-live', 'polite');
                overlay.setAttribute('aria-atomic', 'true');
                document.body.appendChild(overlay);
            }

            if (activeDismissTimer) {
                clearTimeout(activeDismissTimer);
                activeDismissTimer = null;
            }

            const typeConfig = {
                success: {
                    defaultTitle: 'Berhasil!',
                    iconBg: '#fbf1e8',
                    iconColor: '#a2785d',
                    ringColor: 'rgba(251, 241, 232, 0.8)',
                    borderColor: '#edd8cf',
                    btnBg: '#21140b',
                    btnHover: '#3d2a1f',
                    btnText: '#ffffff',
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                           </svg>`
                },
                error: {
                    defaultTitle: 'Peringatan!',
                    iconBg: '#fef2f2',
                    iconColor: '#dc2626',
                    ringColor: 'rgba(254, 226, 226, 0.8)',
                    borderColor: '#fecaca',
                    btnBg: '#dc2626',
                    btnHover: '#b91c1c',
                    btnText: '#ffffff',
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                           </svg>`
                },
                danger: {
                    defaultTitle: 'Peringatan!',
                    iconBg: '#fef2f2',
                    iconColor: '#dc2626',
                    ringColor: 'rgba(254, 226, 226, 0.8)',
                    borderColor: '#fecaca',
                    btnBg: '#dc2626',
                    btnHover: '#b91c1c',
                    btnText: '#ffffff',
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                           </svg>`
                },
                warning: {
                    defaultTitle: 'Perhatian!',
                    iconBg: '#fffbeb',
                    iconColor: '#d97706',
                    ringColor: 'rgba(254, 243, 199, 0.8)',
                    borderColor: '#fde68a',
                    btnBg: '#21140b',
                    btnHover: '#3d2a1f',
                    btnText: '#ffffff',
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                           </svg>`
                },
                info: {
                    defaultTitle: 'Informasi',
                    iconBg: '#f0f9ff',
                    iconColor: '#0284c7',
                    ringColor: 'rgba(224, 242, 254, 0.8)',
                    borderColor: '#bae6fd',
                    btnBg: '#21140b',
                    btnHover: '#3d2a1f',
                    btnText: '#ffffff',
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                           </svg>`
                }
            };

            const config = typeConfig[type] || typeConfig.success;
            const finalTitle = title || config.defaultTitle;

            overlay.innerHTML = `
                <div class="koteshop-dialog-card">
                    <!-- Close button -->
                    <button type="button" class="btn-close-toast" style="position: absolute; top: 16px; right: 16px; color: #b5a49a; border: none; background: transparent; padding: 6px; cursor: pointer; border-radius: 9999px; display: flex; align-items: center; justify-content: center; transition: color 0.15s ease;" onmouseover="this.style.color='#21140b'" onmouseout="this.style.color='#b5a49a'" aria-label="Tutup notifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Large Centered Icon -->
                    <div style="display: flex; height: 72px; width: 72px; flex-shrink: 0; align-items: center; justify-content: center; border-radius: 9999px; background-color: ${config.iconBg}; color: ${config.iconColor}; border: 1px solid ${config.borderColor}; box-shadow: 0 0 0 8px ${config.ringColor}; margin-bottom: 20px;">
                        ${config.icon}
                    </div>

                    <!-- Title -->
                    <h3 style="font-size: 1.25rem; line-height: 1.75rem; font-weight: 800; color: #21140b; letter-spacing: -0.025em; margin: 0 0 8px 0;">${finalTitle}</h3>

                    <!-- Message -->
                    <div style="font-size: 0.875rem; line-height: 1.4rem; font-weight: 500; color: #7b6558; margin-bottom: 24px; max-width: 320px; word-break: break-word; user-select: text;">${message}</div>

                    <!-- Action Button -->
                    <button type="button" class="btn-action-ok" style="width: 100%; padding: 12px 24px; border-radius: 9999px; background-color: ${config.btnBg}; color: ${config.btnText}; font-size: 0.875rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(33,20,11,0.15); transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='${config.btnHover}'" onmouseout="this.style.backgroundColor='${config.btnBg}'">
                        Oke, Mengerti
                    </button>
                </div>
            `;

            const card = overlay.querySelector('.koteshop-dialog-card');
            const closeBtn = overlay.querySelector('.btn-close-toast');
            const okBtn = overlay.querySelector('.btn-action-ok');

            const dismissToast = () => {
                if (activeDismissTimer) {
                    clearTimeout(activeDismissTimer);
                    activeDismissTimer = null;
                }
                card?.classList.add('closing');
                overlay.classList.remove('active');
                setTimeout(() => {
                    if (!overlay.classList.contains('active')) {
                        overlay.innerHTML = '';
                    }
                }, 250);
            };

            closeBtn?.addEventListener('click', function (e) {
                e.stopPropagation();
                dismissToast();
            });

            okBtn?.addEventListener('click', function (e) {
                e.stopPropagation();
                dismissToast();
            });

            overlay.onclick = function (e) {
                if (e.target === overlay) {
                    dismissToast();
                }
            };

            // Animate in
            requestAnimationFrame(() => {
                setTimeout(() => {
                    overlay.classList.add('active');
                }, 20);
            });

            // Auto dismiss
            if (duration > 0) {
                activeDismissTimer = setTimeout(dismissToast, duration);
            }
        };

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const overlay = document.getElementById('koteshop-toast-overlay');
                if (overlay && overlay.classList.contains('active')) {
                    overlay.querySelector('.btn-action-ok')?.click();
                }
            }
        });
    })();

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif
        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif
        @if(session('status'))
            showToast(@json(session('status')), 'info');
        @endif
        @if($errors->any())
            @php
                $errList = $errors->all();
                $errText = count($errList) === 1 ? $errList[0] : implode('<br>', array_map('e', $errList));
            @endphp
            showToast({!! json_encode($errText) !!}, 'error', 'Validasi Gagal');
        @endif
    });
</script>
