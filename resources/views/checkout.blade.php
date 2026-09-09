@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    {{-- BREADCRUMBS --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <a href="{{ route('cart') }}" class="hover:text-[#21140b] transition-colors">Keranjang Belanja</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">Checkout</span>
        </nav>
    </div>

    {{-- TITLE & SUBTITLE --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6 text-left">
        <h1 class="text-3xl font-extrabold text-[#21140b] tracking-tight">
            Selesaikan Pembayaran
        </h1>
        <p class="text-xs font-medium text-[#8f7664] mt-1.5">
            Silakan pilih metode pembayaran dan konfirmasi pesanan Anda.
        </p>
    </div>

    {{-- MAIN GRID --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LEFT COLUMN: ORDER SUMMARY (Span 5) --}}
            <div class="lg:col-span-5 space-y-6">
                <!-- Order Summary Card -->
                <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-6 shadow-[0_10px_30px_rgba(33,20,11,0.02)] space-y-6">
                    <h2 class="text-base font-extrabold text-[#21140b] text-left">Ringkasan Pesanan</h2>
                    
                    <!-- Dynamic Cart Items List -->
                    <div id="checkout-items-list" class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                        <!-- Loaded dynamically -->
                    </div>

                    <div class="border-t border-[#edd8cf]/60 pt-4 space-y-3.5 text-xs font-bold text-[#21140b]">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between">
                            <span class="text-[#8f7664] font-semibold">Subtotal</span>
                            <span id="checkout-subtotal">Rp 0</span>
                        </div>
                        
                        <!-- Biaya Layanan -->
                        <div class="flex items-center justify-between">
                            <span class="text-[#8f7664] font-semibold">Biaya Layanan</span>
                            <span>Rp 2.000</span>
                        </div>

                        <!-- Promo Code Apply -->
                        <div class="flex gap-2 pt-1.5">
                            <input type="text" id="promo-input" placeholder="Masukkan kode promo" class="flex-1 min-h-[42px] px-4 rounded-xl border border-[#edd8cf] text-xs text-[#21140b] uppercase font-mono placeholder:font-sans placeholder:normal-case placeholder:text-[#b5a49a] focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 transition-all" />
                            <button type="button" id="apply-promo-btn" class="min-h-[42px] px-5 rounded-xl bg-[#21140b] text-white text-xs font-bold hover:bg-[#3d2a1f] active:scale-95 transition-all shadow-sm cursor-pointer whitespace-nowrap uppercase tracking-wider">
                                Terapkan
                            </button>
                        </div>
                        <p id="promo-message" class="text-[10px] text-green-600 font-bold hidden text-left mt-1"></p>

                        <!-- Discount (hidden initially) -->
                        <div id="discount-row" class="hidden flex items-center justify-between text-green-600">
                            <span id="discount-label">Diskon Promo</span>
                            <div class="flex items-center gap-1.5">
                                <span id="checkout-discount">-Rp 0</span>
                                <button type="button" id="remove-promo-btn" class="text-[10px] font-bold text-red-500 hover:text-red-700 underline cursor-pointer ml-1">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-[#edd8cf]/60 pt-4 flex items-center justify-between text-xs font-bold text-[#21140b]">
                        <span class="text-sm font-extrabold">Total</span>
                        <span id="checkout-total" class="text-base font-extrabold text-[#a2785d]">Rp 2.000</span>
                    </div>

                    <!-- Catatan Pesanan -->
                    <div class="border-t border-[#edd8cf]/60 pt-4 space-y-2 text-left">
                        <label for="order-note-input" class="text-xs font-bold text-[#21140b] flex items-center justify-between">
                            <span>Catatan Pesanan (Opsional)</span>
                        </label>
                        <textarea id="order-note-input" rows="2" placeholder="Tambahkan catatan untuk pesanan Anda (contoh: Tolong sedotan dipisah)..." class="w-full px-4 py-2.5 rounded-2xl border border-[#edd8cf] bg-[#fbf1e8]/20 text-xs text-[#21140b] placeholder:text-[#b5a49a] focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 transition-all resize-none"></textarea>
                    </div>
                </div>

                <!-- Warning Card -->
                <div class="rounded-2xl bg-[#fff7f2] border border-[#ffeedd] p-4 flex gap-3 text-left">
                    <div class="text-[#c06014] shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.085 1.086L13.065 14.96a.75.75 0 01-1.085-1.085l.041-.021zM21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <p class="text-[11px] font-semibold text-[#a0522d] leading-relaxed">
                        Harap periksa kembali pesanan Anda sebelum melakukan pembayaran. Pesanan yang sudah dibayar tidak dapat dibatalkan secara otomatis.
                    </p>
                </div>
            </div>

            {{-- RIGHT COLUMN: DELIVERY & PAYMENT METHOD (Span 7) --}}
            <div class="lg:col-span-7 space-y-6">
                <!-- Fulfillment / Delivery Method Selection Card -->
                <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-6 shadow-[0_10px_30px_rgba(33,20,11,0.02)] space-y-5 text-left">
                    <div>
                        <h2 class="text-base font-extrabold text-[#21140b]">Pilihan Layanan</h2>
                        <p class="text-xs font-medium text-[#8f7664] mt-0.5">Pilih apakah pesanan diambil di tempat atau diantar ke lokasi Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ambil di Toko -->
                        <label class="fulfillment-method-card relative flex items-center justify-between p-4 bg-[#fbf1e8]/30 border-2 border-[#a2785d] rounded-2xl cursor-pointer transition-all duration-200">
                            <input type="radio" name="tipe_pesanan" value="ambil_di_toko" checked class="sr-only peer" />
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fbf1e8] text-[#a2785d]">
                                    <!-- Store / Shop icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-[#21140b]">Ambil di Toko</p>
                                    <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">Ambil di kasir / counter</p>
                                </div>
                            </div>
                            <div class="flex h-4 w-4 items-center justify-center rounded-full border-2 border-[#a2785d] p-0.5">
                                <div class="fulfillment-radio-dot h-2 w-2 rounded-full bg-[#a2785d]"></div>
                            </div>
                        </label>

                        <!-- Diantar -->
                        <label class="fulfillment-method-card relative flex items-center justify-between p-4 bg-white border border-[#edd8cf] rounded-2xl cursor-pointer hover:border-[#c5ab98] transition-all duration-200">
                            <input type="radio" name="tipe_pesanan" value="diantar" class="sr-only peer" />
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fbf1e8]/40 text-[#a2785d]">
                                    <!-- Delivery Truck icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-[#21140b]">Di Antar</p>
                                    <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">Diantar ke kelas / lokasi Anda</p>
                                </div>
                            </div>
                            <div class="flex h-4 w-4 items-center justify-center rounded-full border border-[#edd8cf] p-0.5">
                                <div class="fulfillment-radio-dot h-2 w-2 rounded-full bg-transparent"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Alamat / Lokasi Pengantaran (Hidden initially when ambil_di_toko) -->
                    @php
                        $prefillLocation = '';
                        if (auth()->check()) {
                            $u = auth()->user();
                            if ($u->kelas) {
                                $prefillLocation = 'Kelas ' . $u->kelas . ($u->alamat ? ' - ' . $u->alamat : '');
                            } elseif ($u->alamat) {
                                $prefillLocation = $u->alamat;
                            }
                        }
                    @endphp
                    <div id="delivery-address-container" class="hidden pt-2 space-y-2 text-left">
                        <label for="delivery-address-input" class="text-xs font-bold text-[#21140b] flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-[#a2785d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                Lokasi / Alamat Pengantaran <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <textarea id="delivery-address-input" rows="2" placeholder="Tuliskan gedung, lantai, kelas, atau nomor ruangan (contoh: Gedung B Lantai 2, Ruang XII RPL 1)" class="w-full px-4 py-2.5 rounded-2xl border border-[#edd8cf] bg-[#fbf1e8]/20 text-xs text-[#21140b] placeholder:text-[#b5a49a] focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/30 transition-all resize-none">{{ $prefillLocation }}</textarea>
                        <p id="delivery-address-error" class="text-[10px] text-red-500 font-bold hidden text-left"></p>
                    </div>
                </div>

                <!-- Payment Method Selection Card -->
                <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-6 shadow-[0_10px_30px_rgba(33,20,11,0.02)] space-y-6">
                    <h2 class="text-base font-extrabold text-[#21140b] text-left">Metode Pembayaran</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $activeMethods = [];
                            if ($paymentSettings['tunai']['status'] ?? false) $activeMethods[] = 'Tunai';
                            if ($paymentSettings['qris']['status'] ?? false) $activeMethods[] = 'QRIS';
                            if ($paymentSettings['transfer_bank']['status'] ?? false) $activeMethods[] = 'Transfer Bank';
                            $firstActive = reset($activeMethods);
                        @endphp

                        <!-- Tunai -->
                        @if($paymentSettings['tunai']['status'] ?? false)
                        <label class="payment-method-card relative flex items-center justify-between p-4 {{ $firstActive === 'Tunai' ? 'bg-[#fbf1e8]/30 border-2 border-[#a2785d]' : 'bg-white border border-[#edd8cf]' }} rounded-2xl cursor-pointer transition-all duration-200">
                            <input type="radio" name="payment_method" value="Tunai" {{ $firstActive === 'Tunai' ? 'checked' : '' }} class="sr-only peer" />
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fbf1e8] text-[#a2785d]">
                                    <!-- Wallet icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125H3.75M3.75 4.5A1.125 1.125 0 002.625 5.625v12.75c0 .621.504 1.125 1.125 1.125m0-13.5A1.125 1.125 0 002.625 5.625v12.75c0 .621.504 1.125 1.125 1.125m0-13.5h16.5M5.25 9h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H18m-9 3H18m-9 3H18" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-[#21140b]">Tunai</p>
                                    <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">Bayar di tempat</p>
                                </div>
                            </div>
                            <div class="flex h-4 w-4 items-center justify-center rounded-full {{ $firstActive === 'Tunai' ? 'border-2 border-[#a2785d]' : 'border border-[#edd8cf]' }} p-0.5">
                                <div class="payment-radio-dot h-2 w-2 rounded-full {{ $firstActive === 'Tunai' ? 'bg-[#a2785d]' : 'bg-transparent' }}"></div>
                            </div>
                        </label>
                        @endif

                        <!-- QRIS -->
                        @if($paymentSettings['qris']['status'] ?? false)
                        <label class="payment-method-card relative flex items-center justify-between p-4 {{ $firstActive === 'QRIS' ? 'bg-[#fbf1e8]/30 border-2 border-[#a2785d]' : 'bg-white border border-[#edd8cf]' }} rounded-2xl cursor-pointer hover:border-[#c5ab98] transition-all duration-200">
                            <input type="radio" name="payment_method" value="QRIS" {{ $firstActive === 'QRIS' ? 'checked' : '' }} class="sr-only peer" />
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fbf1e8]/40 text-[#a2785d]">
                                    <!-- QR Code icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM16.875 15v1.875m0 0H15m1.875 0H18.75m0-1.875h-1.875m0 0v-1.875m0 1.875h1.875m-1.875 1.875v1.875m-.75-7.125h.008v.008h-.008v-.008zm1.5-.75h.008v.008h-.008v-.008zm-1.5 3h.008v.008h-.008v-.008zm2.25-.75h.008v.008h-.008v-.008zm-3 1.5h.008v.008h-.008v-.008zm1.5-.75h.008v.008h-.008v-.008zm-1.5 3h.008v.008h-.008v-.008zm1.5-.75h.008v.008h-.008v-.008zm-3-3h.008v.008h-.008v-.008zm1.5-.75h.008v.008h-.008v-.008zm-1.5 3h.008v.008h-.008v-.008z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-[#21140b]">QRIS</p>
                                    <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">Scan & Bayar</p>
                                </div>
                            </div>
                            <div class="flex h-4 w-4 items-center justify-center rounded-full {{ $firstActive === 'QRIS' ? 'border-2 border-[#a2785d]' : 'border border-[#edd8cf]' }} p-0.5">
                                <div class="payment-radio-dot h-2 w-2 rounded-full {{ $firstActive === 'QRIS' ? 'bg-[#a2785d]' : 'bg-transparent' }}"></div>
                            </div>
                        </label>
                        @endif

                        <!-- Transfer Bank -->
                        @if($paymentSettings['transfer_bank']['status'] ?? false)
                        <label class="payment-method-card relative flex items-center justify-between p-4 {{ $firstActive === 'Transfer Bank' ? 'bg-[#fbf1e8]/30 border-2 border-[#a2785d]' : 'bg-white border border-[#edd8cf]' }} rounded-2xl cursor-pointer hover:border-[#c5ab98] transition-all duration-200">
                            <input type="radio" name="payment_method" value="Transfer Bank" {{ $firstActive === 'Transfer Bank' ? 'checked' : '' }} class="sr-only peer" />
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fbf1e8]/40 text-[#a2785d]">
                                    <!-- Bank Building icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33m4.5 10.67V10.33M21 21H3" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-[#21140b]">Transfer Bank</p>
                                    <p class="text-[10px] font-semibold text-[#8f7664] mt-0.5">{{ $paymentSettings['transfer_bank']['bank_name'] }}</p>
                                </div>
                            </div>
                            <div class="flex h-4 w-4 items-center justify-center rounded-full {{ $firstActive === 'Transfer Bank' ? 'border-2 border-[#a2785d]' : 'border border-[#edd8cf]' }} p-0.5">
                                <div class="payment-radio-dot h-2 w-2 rounded-full {{ $firstActive === 'Transfer Bank' ? 'bg-[#a2785d]' : 'bg-transparent' }}"></div>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="space-y-4">
                    <button type="button" id="pay-now-btn" class="w-full min-h-[56px] rounded-full bg-[#312217] text-white text-sm font-bold flex items-center justify-center gap-2 hover:bg-[#4a3525] active:scale-[0.98] transition-all shadow-md shadow-[#312217]/15 cursor-pointer tracking-wider">
                        <span>Bayar Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                    <p class="text-[11px] font-medium text-[#8f7664] text-center">
                        Dengan membayar, Anda menyetujui <a href="{{ route('terms') }}" target="_blank" class="underline hover:text-[#21140b] transition-colors">Syarat &amp; Ketentuan</a> kami.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsList = document.getElementById('checkout-items-list');
    const subtotalEl = document.getElementById('checkout-subtotal');
    const totalEl = document.getElementById('checkout-total');
    const discountRow = document.getElementById('discount-row');
    const discountEl = document.getElementById('checkout-discount');
    const promoInput = document.getElementById('promo-input');
    const applyPromoBtn = document.getElementById('apply-promo-btn');
    const promoMessage = document.getElementById('promo-message');
    const payNowBtn = document.getElementById('pay-now-btn');
    
    let subtotal = 0;
    const serviceFee = 2000;
    let discount = 0;
    
    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function renderCheckoutItems() {
        const cart = getCart();
        
        if (cart.length === 0) {
            // Redirect to menu if cart is empty
            window.location.href = "{{ route('menu') }}";
            return;
        }

        if (!itemsList) return;
        itemsList.innerHTML = '';
        subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;

            let optionsList = [];
            if (item.sugar) optionsList.push(item.sugar + " Gula");
            if (item.ice) optionsList.push(item.ice + " Es");
            if (item.toppings && item.toppings.length > 0) {
                optionsList.push(item.toppings.join(", "));
            }
            if (item.notes) {
                optionsList.push(`Catatan: "${item.notes}"`);
            }
            const optionsText = optionsList.join(" • ");

            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex items-center justify-between gap-4 py-3.5 border-b border-[#edd8cf]/30 last:border-none';
            
            itemDiv.innerHTML = `
                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                    <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-xl object-cover shrink-0 bg-[#fbf1e8]" />
                    <div class="min-w-0 text-left">
                        <h4 class="text-xs font-extrabold text-[#21140b] truncate">${item.name}</h4>
                        <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5 leading-tight">Qty: ${item.qty} ${optionsText ? '• ' + optionsText : ''}</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-[#21140b] shrink-0">${formatRupiah(itemTotal)}</span>
            `;
            
            itemsList.appendChild(itemDiv);
        });

        updateTotals();
    }

    function updateTotals() {
        const total = Math.max(0, subtotal + serviceFee - discount);
        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);
        if (totalEl) totalEl.textContent = formatRupiah(total);
    }

    let appliedPromoCode = null;

    const removePromoBtn = document.getElementById('remove-promo-btn');
    if (removePromoBtn) {
        removePromoBtn.addEventListener('click', function () {
            discount = 0;
            appliedPromoCode = null;
            if (promoInput) promoInput.value = '';
            localStorage.removeItem('koteshop_applied_promo');
            if (discountRow) discountRow.classList.add('hidden');
            if (promoMessage) {
                promoMessage.textContent = 'Promo berhasil dilepas.';
                promoMessage.className = 'text-[10px] text-[#8f7664] font-bold text-left mt-1';
                promoMessage.classList.remove('hidden');
            }
            updateTotals();
            if (typeof showToast === 'function') {
                showToast('Promo berhasil dilepas.');
            }
        });
    }

    // Promo Code Logic
    if (applyPromoBtn && promoInput) {
        applyPromoBtn.addEventListener('click', function () {
            const code = promoInput.value.trim().toUpperCase();
            if (!code) {
                promoMessage.textContent = 'Silakan masukkan kode promo.';
                promoMessage.className = 'text-[10px] text-red-500 font-bold text-left mt-1';
                promoMessage.classList.remove('hidden');
                return;
            }

            applyPromoBtn.disabled = true;
            applyPromoBtn.textContent = '...';

            fetch("{{ route('promo.check') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    kode: code,
                    subtotal: subtotal
                })
            })
            .then(res => res.json())
            .then(data => {
                applyPromoBtn.disabled = false;
                applyPromoBtn.textContent = 'Terapkan';

                if (data.success) {
                    discount = data.discount;
                    appliedPromoCode = code;
                    localStorage.setItem('koteshop_applied_promo', code);
                    
                    if (discountRow) discountRow.classList.remove('hidden');
                    const discountLabel = document.getElementById('discount-label');
                    if (discountLabel) {
                        discountLabel.textContent = `Promo "${code}" (${data.promo.formatted})`;
                    }
                    if (discountEl) discountEl.textContent = '-' + formatRupiah(discount);

                    promoMessage.textContent = data.message;
                    promoMessage.className = 'text-[10px] text-emerald-600 font-bold text-left mt-1';
                    promoMessage.classList.remove('hidden');

                    updateTotals();
                } else {
                    promoMessage.textContent = data.message || 'Kode promo tidak valid.';
                    promoMessage.className = 'text-[10px] text-red-500 font-bold text-left mt-1';
                    promoMessage.classList.remove('hidden');

                    discount = 0;
                    appliedPromoCode = null;
                    if (discountRow) discountRow.classList.add('hidden');
                    updateTotals();
                }
            })
            .catch(err => {
                applyPromoBtn.disabled = false;
                applyPromoBtn.textContent = 'Terapkan';
                promoMessage.textContent = 'Gagal memeriksa kode promo.';
                promoMessage.className = 'text-[10px] text-red-500 font-bold text-left mt-1';
                promoMessage.classList.remove('hidden');
            });
        });

        // Check initial promo code from URL param or localStorage
        const urlParams = new URLSearchParams(window.location.search);
        const codeParam = urlParams.get('code') || localStorage.getItem('koteshop_applied_promo');
        if (codeParam) {
            promoInput.value = codeParam;
            setTimeout(() => {
                applyPromoBtn.click();
            }, 300);
        }
    }

    // Fulfillment Method Selection Style toggle
    const fulfillmentRadios = document.querySelectorAll('input[name="tipe_pesanan"]');
    const fulfillmentCards = document.querySelectorAll('.fulfillment-method-card');
    const deliveryAddressContainer = document.getElementById('delivery-address-container');
    const deliveryAddressInput = document.getElementById('delivery-address-input');
    const deliveryAddressError = document.getElementById('delivery-address-error');

    fulfillmentRadios.forEach((radio, index) => {
        radio.addEventListener('change', function () {
            // Reset all fulfillment cards
            fulfillmentCards.forEach(card => {
                card.className = 'fulfillment-method-card relative flex items-center justify-between p-4 bg-white border border-[#edd8cf] rounded-2xl cursor-pointer hover:border-[#c5ab98] transition-all duration-200';
                const dot = card.querySelector('.fulfillment-radio-dot');
                if (dot) dot.className = 'fulfillment-radio-dot h-2 w-2 rounded-full bg-transparent';
                const borderDiv = card.querySelector('.rounded-full.border-2, .rounded-full.border');
                if (borderDiv) borderDiv.className = 'flex h-4 w-4 items-center justify-center rounded-full border border-[#edd8cf] p-0.5';
            });

            // Set active class to selected fulfillment card
            const activeCard = fulfillmentCards[index];
            activeCard.className = 'fulfillment-method-card relative flex items-center justify-between p-4 bg-[#fbf1e8]/30 border-2 border-[#a2785d] rounded-2xl cursor-pointer transition-all duration-200';
            const dot = activeCard.querySelector('.fulfillment-radio-dot');
            if (dot) dot.className = 'fulfillment-radio-dot h-2 w-2 rounded-full bg-[#a2785d]';
            const borderDiv = activeCard.querySelector('.rounded-full');
            if (borderDiv) borderDiv.className = 'flex h-4 w-4 items-center justify-center rounded-full border-2 border-[#a2785d] p-0.5';

            // Toggle delivery address container
            if (this.value === 'diantar') {
                if (deliveryAddressContainer) deliveryAddressContainer.classList.remove('hidden');
                if (deliveryAddressInput) deliveryAddressInput.focus();
            } else {
                if (deliveryAddressContainer) deliveryAddressContainer.classList.add('hidden');
                if (deliveryAddressError) deliveryAddressError.classList.add('hidden');
            }
        });
    });

    // Payment Method Selection Style toggle
    const radioButtons = document.querySelectorAll('input[name="payment_method"]');
    const paymentCards = document.querySelectorAll('.payment-method-card');

    radioButtons.forEach((radio, index) => {
        radio.addEventListener('change', function () {
            // Reset all cards
            paymentCards.forEach(card => {
                card.className = 'payment-method-card relative flex items-center justify-between p-4 bg-white border border-[#edd8cf] rounded-2xl cursor-pointer hover:border-[#c5ab98] transition-all duration-200';
                const dot = card.querySelector('.payment-radio-dot');
                if (dot) dot.className = 'payment-radio-dot h-2 w-2 rounded-full bg-transparent';
                const borderDiv = card.querySelector('.rounded-full.border-2, .rounded-full.border');
                if (borderDiv) borderDiv.className = 'flex h-4 w-4 items-center justify-center rounded-full border border-[#edd8cf] p-0.5';
            });

            // Set active class to selected card
            const activeCard = paymentCards[index];
            activeCard.className = 'payment-method-card relative flex items-center justify-between p-4 bg-[#fbf1e8]/30 border-2 border-[#a2785d] rounded-2xl cursor-pointer transition-all duration-200';
            const dot = activeCard.querySelector('.payment-radio-dot');
            if (dot) dot.className = 'payment-radio-dot h-2 w-2 rounded-full bg-[#a2785d]';
            const borderDiv = activeCard.querySelector('.rounded-full');
            if (borderDiv) borderDiv.className = 'flex h-4 w-4 items-center justify-center rounded-full border-2 border-[#a2785d] p-0.5';
        });
    });

    // Pay Now Submit
    if (payNowBtn) {
        payNowBtn.addEventListener('click', function () {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
            const selectedFulfillment = document.querySelector('input[name="tipe_pesanan"]:checked')?.value || 'ambil_di_toko';
            const cart = getCart();

            if (!selectedMethod) {
                showToast("Silakan pilih metode pembayaran!", "error");
                return;
            }

            if (cart.length === 0) {
                showToast("Keranjang kosong!", "error");
                return;
            }

            let deliveryAddress = '';
            if (selectedFulfillment === 'diantar') {
                deliveryAddress = deliveryAddressInput ? deliveryAddressInput.value.trim() : '';
                if (!deliveryAddress) {
                    if (deliveryAddressError) {
                        deliveryAddressError.textContent = 'Silakan tuliskan lokasi atau ruangan pengantaran.';
                        deliveryAddressError.classList.remove('hidden');
                    }
                    if (deliveryAddressInput) {
                        deliveryAddressInput.focus();
                        deliveryAddressInput.classList.add('border-red-500');
                    }
                    showToast("Silakan isi lokasi atau alamat pengantaran!", "error");
                    return;
                }
            }

            if (deliveryAddressError) {
                deliveryAddressError.classList.add('hidden');
            }
            if (deliveryAddressInput) {
                deliveryAddressInput.classList.remove('border-red-500');
            }

            const orderNoteInput = document.getElementById('order-note-input');
            const orderNote = orderNoteInput ? orderNoteInput.value.trim() : '';

            payNowBtn.disabled = true;
            payNowBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses Pesanan...</span>
            `;

            // Post to backend to save the order
            fetch("{{ route('orders.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    payment_method: selectedMethod,
                    tipe_pesanan: selectedFulfillment,
                    alamat_pengiriman: selectedFulfillment === 'diantar' ? deliveryAddress : null,
                    catatan: orderNote,
                    cart: cart.map(item => ({
                        id: item.id,
                        qty: item.qty,
                        price: item.price,
                        sugar: item.sugar || null,
                        ice: item.ice || null,
                        toppings: Array.isArray(item.toppings) ? item.toppings : [],
                        notes: item.notes || null
                    })),
                    discount: discount,
                    promo_code: appliedPromoCode
                })
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message || ("Gagal memproses pesanan (HTTP " + res.status + ")"));
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    // Clear cart & promo locally
                    localStorage.removeItem('koteshop_cart');
                    localStorage.removeItem('koteshop_applied_promo');
                    if (typeof updateCartBadge === 'function') {
                        updateCartBadge();
                    }

                    showToast(`Pesanan berhasil dibuat! Mengalihkan ke pembayaran...`);
                    setTimeout(() => {
                        window.location.href = "/pembayaran/" + data.order.id_pesanan;
                    }, 1500);
                } else {
                    showToast("Gagal memproses pesanan: " + (data.message || "Terjadi kesalahan"), "error");
                    payNowBtn.disabled = false;
                    payNowBtn.innerHTML = `<span>Bayar Sekarang</span>`;
                }
            })
            .catch(err => {
                console.error("Error creating order:", err);
                showToast(err.message || "Terjadi kesalahan saat memproses pesanan.", "error");
                payNowBtn.disabled = false;
                payNowBtn.innerHTML = `<span>Bayar Sekarang</span>`;
            });
        });
    }

    renderCheckoutItems();
});
</script>
@endsection
