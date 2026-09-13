<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <title>{{ config('app.name', 'KOTE SCHOOL SHOP') }} - Beranda</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#fbf1e8] text-[#21140b] antialiased font-sans">
        <x-layout.splash-screen />
        <x-ui.toast />
        <x-logout-modal />

        <div class="min-h-screen flex flex-col">
            <x-layout.header />

            <main class="flex-1">
                @yield('content')
            </main>

            <x-layout.footer />
        </div>

        {{-- GLOBAL CART HELPERS & TOAST SYSTEM --}}
        <script>
            var __koteshopIsAuth = @json(auth()->check());
            var __koteshopJustLoggedIn = @json(session('just_logged_in', false));
            var __koteshopCsrfToken = @json(csrf_token());
            var __koteshopSyncUrl = @json(route('cart.sync-login'));
            var __koteshopUpdateUrl = @json(route('cart.update'));

            /**
             * Debounce timer for auto-saving cart to server.
             */
            var __koteshopSaveTimer = null;

            function getCart() {
                try {
                    const cart = localStorage.getItem('koteshop_cart');
                    return cart ? JSON.parse(cart) : [];
                } catch (e) {
                    console.error('Error reading cart from localStorage:', e);
                    return [];
                }
            }

            function saveCart(cart) {
                try {
                    localStorage.setItem('koteshop_cart', JSON.stringify(cart));
                    updateCartBadge();
                    // Auto-save to server for authenticated users (debounced)
                    if (__koteshopIsAuth) {
                        clearTimeout(__koteshopSaveTimer);
                        __koteshopSaveTimer = setTimeout(function () {
                            _persistCartToServer(cart);
                        }, 500);
                    }
                } catch (e) {
                    console.error('Error saving cart to localStorage:', e);
                }
            }

            /**
             * Persist current cart to the server.
             */
            function _persistCartToServer(cart) {
                fetch(__koteshopUpdateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': __koteshopCsrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ cart: cart }),
                }).catch(function (err) {
                    console.error('Error persisting cart to server:', err);
                });
            }

            function updateCartBadge() {
                const cart = getCart();
                const totalQty = cart.reduce((sum, item) => sum + parseInt(item.qty || 1), 0);
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.textContent = totalQty;
                    if (totalQty > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }

            function addToCart(item) {
                const cart = getCart();
                // Create unique key for item options to allow grouping the same options or treating different options as separate items
                const itemToppings = Array.isArray(item.toppings) ? item.toppings : [];
                const key = item.id + '_' + (item.sugar || '') + '_' + (item.ice || '') + '_' + itemToppings.slice().sort().join(',') + '_' + (item.notes || '');
                
                const existingIndex = cart.findIndex(i => {
                    const iToppings = Array.isArray(i.toppings) ? i.toppings : [];
                    const k = i.id + '_' + (i.sugar || '') + '_' + (i.ice || '') + '_' + iToppings.slice().sort().join(',') + '_' + (i.notes || '');
                    return k === key;
                });

                const addQty = parseInt(item.qty || 1);
                const maxStock = item.maxStock !== undefined ? parseInt(item.maxStock) : null;

                if (existingIndex > -1) {
                    const newQty = cart[existingIndex].qty + addQty;
                    if (maxStock !== null && newQty > maxStock) {
                        cart[existingIndex].qty = maxStock;
                        saveCart(cart);
                        showToast(`Jumlah disesuaikan dengan sisa stok maksimal (${maxStock} porsi).`);
                        return;
                    }
                    cart[existingIndex].qty = newQty;
                    if (maxStock !== null) {
                        cart[existingIndex].maxStock = maxStock;
                    }
                } else {
                    if (maxStock !== null && addQty > maxStock) {
                        item.qty = maxStock;
                    }
                    cart.push(item);
                }
                
                saveCart(cart);
                showToast(`Berhasil menambahkan ${item.name} ke keranjang!`);
            }

            document.addEventListener('DOMContentLoaded', function () {
                updateCartBadge();

                // On login: merge guest localStorage cart with server DB cart
                if (__koteshopIsAuth && __koteshopJustLoggedIn) {
                    var guestCart = getCart();
                    fetch(__koteshopSyncUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': __koteshopCsrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ cart: guestCart }),
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.status === 'success' && Array.isArray(data.cart)) {
                            localStorage.setItem('koteshop_cart', JSON.stringify(data.cart));
                            updateCartBadge();
                            window.dispatchEvent(new Event('koteshop-cart-updated'));
                        }
                    })
                    .catch(function (err) {
                        console.error('Error syncing cart on login:', err);
                    });
                }

                // On logout: intercept all logout forms to clear localStorage first
                document.querySelectorAll('form[action*="logout"]').forEach(function (form) {
                    form.addEventListener('submit', function () {
                        localStorage.removeItem('koteshop_cart');
                    });
                });
            });
        </script>
    </body>
</html>
