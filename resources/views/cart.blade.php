@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">Keranjang Belanja</span>
        </nav>
    </div>

    {{-- TITLE --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <h1 class="text-3xl font-extrabold text-[#21140b] tracking-tight text-left">
            Keranjang Belanja
        </h1>
    </div>

    {{-- EMPTY STATE --}}
    <div id="cart-empty-state" class="hidden mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-16 text-center">
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#fbf1e8] text-[#a2785d] mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.057-.803 2.228-1.91l1.246-8.098H5.112M7.5 14.25a3 3 0 0 1-3 3M7.5 14.25a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3M7.5 21a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm9 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-[#21140b]">Keranjang Belanja Kosong</h3>
        <p class="text-sm text-[#7b6558] mt-1">Silakan pilih menu favorit Anda terlebih dahulu.</p>
        <div class="mt-6">
            <a href="{{ route('menu') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#21140b] px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#3d2a1f] transition-all">
                Kembali ke Menu
            </a>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div id="cart-grid-wrapper" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left column: Cart Items (Span 8) --}}
            <div class="lg:col-span-8 space-y-4" id="cart-items-container">
                <!-- Items will be loaded dynamically from localStorage -->
            </div>

            {{-- Right column: Order Summary (Span 4) --}}
            <div class="lg:col-span-4">
                <div class="rounded-3xl bg-[#fbf1e8]/60 border border-[#edd8cf]/80 p-6 space-y-6 shadow-sm">
                    <h2 class="text-base font-extrabold text-[#21140b] text-left">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 text-xs font-bold text-[#21140b]">
                        <!-- Estimasi Waktu -->
                        <div class="flex items-center justify-between pb-4 border-b border-[#edd8cf]/80">
                            <span class="text-[#8f7664] uppercase tracking-wider">ESTIMASI WAKTU</span>
                            <span class="text-[#21140b]">15-30 menit</span>
                        </div>
                        
                        <!-- Total -->
                        <div class="flex items-center justify-between">
                            <span class="text-[#21140b]">Total</span>
                            <span id="cart-total-price" class="text-base font-extrabold text-[#a2785d]">Rp 25.000</span>
                        </div>
                    </div>
                    
                    <!-- Checkout Button -->
                    <a href="{{ route('checkout') }}" class="w-full min-h-[50px] rounded-full bg-[#21140b] text-white text-sm font-bold flex items-center justify-center gap-2 hover:bg-[#3d2a1f] active:scale-95 transition-all shadow-md cursor-pointer tracking-wider">
                        <span>Checkout</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    
                    <p class="text-[10px] font-semibold text-[#8f7664] text-center">
                        Harga sudah termasuk pajak & biaya layanan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalEl = document.getElementById('cart-total-price');

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function recalculateTotal() {
        const cart = getCart();
        const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        if (totalEl) {
            totalEl.textContent = formatRupiah(total);
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const grid = document.getElementById('cart-grid-wrapper');
        const emptyState = document.getElementById('cart-empty-state');
        const cart = getCart();

        if (!container) return;

        if (cart.length === 0) {
            if (grid) grid.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (grid) grid.classList.remove('hidden');
        if (emptyState) emptyState.classList.add('hidden');

        container.innerHTML = '';

        cart.forEach((item, index) => {
            let optionsList = [];
            if (item.sugar) optionsList.push("Gula " + item.sugar);
            if (item.ice) optionsList.push(item.ice + " Ice");
            if (item.toppings && item.toppings.length > 0) {
                optionsList.push("Toppings: " + item.toppings.join(", "));
            }
            if (item.notes) {
                optionsList.push(`Catatan: "${item.notes}"`);
            }
            const optionsText = optionsList.join(" | ") || "Tanpa Kustomisasi";

            const itemEl = document.createElement('div');
            itemEl.className = 'relative flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 bg-white border border-[#edd8cf]/50 rounded-[1.75rem] shadow-[0_8px_30px_rgba(33,20,11,0.02)] transition-all duration-300 hover:shadow-[0_15px_35px_rgba(33,20,11,0.06)] hover:-translate-y-0.5';
            
            itemEl.innerHTML = `
                <!-- Top section: Image & Product info -->
                <div class="flex items-start sm:items-center gap-4 min-w-0 flex-1">
                    <!-- Image -->
                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-[#fbf1e8] border border-[#edd8cf]/30">
                        <img src="${item.image}" alt="${item.name}" class="h-full w-full object-cover" />
                    </div>
                    <!-- Details -->
                    <div class="min-w-0 text-left flex-1 pr-8 sm:pr-0">
                        <h3 class="text-sm font-extrabold text-[#21140b] break-words sm:truncate tracking-tight leading-tight">${item.name}</h3>
                        <p class="text-[11px] text-[#8f7664] font-medium mt-1 leading-relaxed">${optionsText}</p>
                        <p class="text-xs font-bold text-[#a2785d] mt-2">${formatRupiah(item.price)}</p>
                    </div>
                </div>

                <!-- Remove Button (Absolute top-right on mobile, static on desktop) -->
                <button type="button" class="remove-btn absolute top-5 right-5 sm:static text-[#8f7664] hover:text-red-500 transition-colors p-1.5 rounded-xl hover:bg-red-50 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Bottom section: Qty Selector & Subtotal -->
                <div class="flex items-center justify-between sm:justify-end gap-6 pt-4 sm:pt-0 border-t border-[#edd8cf]/40 sm:border-none">
                    <!-- Quantity Selector -->
                    <div class="flex items-center rounded-full border border-[#edd8cf] bg-[#fbf1e8]/20 px-2 py-0.5">
                        <button type="button" class="minus-btn flex h-8 w-8 items-center justify-center text-sm font-bold text-[#4a3d35] hover:bg-[#fbf1e8] rounded-full transition-colors cursor-pointer select-none">
                            &minus;
                        </button>
                        <span class="qty-val flex h-8 w-8 items-center justify-center text-xs font-bold text-[#21140b] select-none">
                            ${item.qty}
                        </span>
                        <button type="button" class="plus-btn flex h-8 w-8 items-center justify-center text-sm font-bold text-[#4a3d35] hover:bg-[#fbf1e8] rounded-full transition-colors cursor-pointer select-none">
                            +
                        </button>
                    </div>
                    <!-- Item Total Price -->
                    <span class="total-price-val text-sm font-extrabold text-[#21140b] min-w-[80px] text-right">
                        ${formatRupiah(item.price * item.qty)}
                    </span>
                </div>
            `;

            // Event listeners
            const minusBtn = itemEl.querySelector('.minus-btn');
            const plusBtn = itemEl.querySelector('.plus-btn');
            const qtyVal = itemEl.querySelector('.qty-val');
            const totalPriceVal = itemEl.querySelector('.total-price-val');
            const removeBtn = itemEl.querySelector('.remove-btn');

            minusBtn.addEventListener('click', () => {
                if (item.qty > 1) {
                    item.qty--;
                    qtyVal.textContent = item.qty;
                    totalPriceVal.textContent = formatRupiah(item.price * item.qty);
                    const currentCart = getCart();
                    currentCart[index].qty = item.qty;
                    saveCart(currentCart);
                    recalculateTotal();
                }
            });

            plusBtn.addEventListener('click', () => {
                const maxStock = item.maxStock ? parseInt(item.maxStock) : 99;
                if (item.qty < maxStock) {
                    item.qty++;
                    qtyVal.textContent = item.qty;
                    totalPriceVal.textContent = formatRupiah(item.price * item.qty);
                    const currentCart = getCart();
                    currentCart[index].qty = item.qty;
                    saveCart(currentCart);
                    recalculateTotal();
                } else {
                    if (typeof showToast === 'function') {
                        showToast(`Jumlah pesanan mencapai batas stok tersedia (${maxStock} porsi).`, 'warning');
                    }
                }
            });

            removeBtn.addEventListener('click', () => {
                const currentCart = getCart();
                currentCart.splice(index, 1);
                saveCart(currentCart);
                renderCart();
                if (typeof showToast === 'function') {
                    showToast('Item berhasil dihapus dari keranjang.');
                }
            });

            container.appendChild(itemEl);
        });

        recalculateTotal();
    }

    renderCart();

    window.addEventListener('koteshop-cart-updated', function () {
        renderCart();
    });
});
</script>
@endsection
