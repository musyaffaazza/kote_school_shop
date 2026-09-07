@extends('layouts.app')

@section('content')
<div class="w-full pb-16 md:pb-24">
    @php
        $hasOrder = isset($pesanan);
        $pembayaran = $hasOrder ? $pesanan->pembayaran : null;
        $status = $pembayaran ? $pembayaran->status : 'menunggu';
        $hasUploadedBukti = $pembayaran && $pembayaran->bukti_transfer;
        $isTunai = ($hasOrder && strtolower($pesanan->metode_pembayaran) === 'tunai') || request()->query('metode') === 'tunai';
    @endphp

    {{-- BREADCRUMBS --}}
    @if(!$isTunai)
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 pt-6">
        <nav class="flex text-xs font-bold text-[#8f7664] tracking-wider uppercase gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#21140b] transition-colors">Beranda</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <a href="{{ route('checkout') }}" class="hover:text-[#21140b] transition-colors">Checkout</a>
            <span class="text-[#edd8cf]">&rsaquo;</span>
            <span class="text-[#21140b]">Pembayaran</span>
        </nav>
    </div>
    @endif

    {{-- TITLE & SUBTITLE --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 {{ $isTunai ? 'pt-8 sm:pt-10' : 'pt-6' }} text-left">
        @if($isTunai)
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#21140b] tracking-tight">
                Detail Pembayaran Tunai
            </h1>
            <p class="text-xs sm:text-sm font-medium text-[#8f7664] mt-1.5">
                Selesaikan pesanan Anda di kasir.
            </p>
        @else
            <h1 class="text-3xl font-extrabold text-[#21140b] tracking-tight">
                Pembayaran
            </h1>
            <p class="text-xs font-medium text-[#8f7664] mt-1.5">
                Selesaikan pesanan Anda dengan memindai kode QRIS atau melakukan transfer bank di bawah ini.
            </p>
        @endif
    </div>

    {{-- MAIN GRID --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: ORDER SUMMARY (Span 5) --}}
            <div class="lg:col-span-5 space-y-6">
                <!-- Order Summary Card -->
                <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-6 shadow-[0_10px_30px_rgba(33,20,11,0.02)] space-y-6">
                    <h2 class="text-base font-extrabold text-[#21140b] text-left">Ringkasan Pesanan</h2>

                    <!-- Items List -->
                    <div id="payment-items-list" class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                        @if($hasOrder)
                            @foreach($pesanan->detailPesanan as $detail)
                                @php
                                    $optionsText = $detail->opsi ?? '';
                                    if ($detail->catatan) {
                                        $optionsText = $optionsText ? $optionsText . ' • Catatan: "' . $detail->catatan . '"' : 'Catatan: "' . $detail->catatan . '"';
                                    }
                                @endphp
                                <div class="flex items-center justify-between gap-4 py-3.5 border-b border-[#edd8cf]/30 last:border-none">
                                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                        <img src="{{ asset($detail->menu->gambar) }}" alt="{{ $detail->menu->nama_menu }}" class="h-12 w-12 rounded-xl object-cover shrink-0 bg-[#fbf1e8]" />
                                        <div class="min-w-0 text-left">
                                            <h4 class="text-xs font-extrabold text-[#21140b] truncate">{{ $detail->menu->nama_menu }}</h4>
                                            @if($optionsText)
                                                <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5 leading-none">{{ $optionsText }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs font-extrabold text-[#a2785d]">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5">x{{ $detail->jumlah }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback for legacy JS cart loading -->
                        @endif
                    </div>

                    @php
                        $subtotal = $hasOrder ? $pesanan->detailPesanan->sum('subtotal') : 0;
                        $serviceFee = 2000;
                        $total = $hasOrder ? $pesanan->total_harga : 0;
                        $discount = $hasOrder ? max(0, ($subtotal + $serviceFee) - $total) : 0;
                    @endphp

                    <div class="border-t border-[#edd8cf]/60 pt-4 space-y-3.5 text-xs font-bold text-[#21140b]">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between">
                            <span class="text-[#8f7664] font-semibold">Subtotal</span>
                            <span id="payment-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- Biaya Layanan -->
                        <div class="flex items-center justify-between">
                            <span class="text-[#8f7664] font-semibold">Biaya Layanan</span>
                            <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex items-center justify-between text-emerald-600">
                                <span class="font-semibold">Diskon</span>
                                <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-[#edd8cf]/60 pt-4 flex items-center justify-between text-xs font-bold text-[#21140b]">
                        <span class="text-sm font-extrabold">Total Pembayaran</span>
                        <span id="payment-total" class="text-base font-extrabold text-[#a2785d]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    @if($hasOrder)
                        <div class="border-t border-[#edd8cf]/60 pt-3.5 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[#8f7664] font-semibold">Layanan</span>
                                <span class="font-extrabold text-[#21140b]">
                                    @if($pesanan->tipe_pesanan === 'diantar')
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">🛵 Diantar</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-[#faf2eb] px-2 py-0.5 text-[10px] font-bold text-[#8c5a3c]">🏬 Ambil di Toko</span>
                                    @endif
                                </span>
                            </div>
                            @if($pesanan->tipe_pesanan === 'diantar' && $pesanan->alamat_pengiriman)
                                <div class="rounded-xl bg-[#faf2eb] p-2.5 text-left border border-[#eeded3]">
                                    <p class="text-[9px] font-bold text-[#8c5a3c] uppercase">Lokasi Pengantaran:</p>
                                    <p class="text-[11px] font-semibold text-[#21140b] mt-0.5">{{ $pesanan->alamat_pengiriman }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($isTunai)
                {{-- RIGHT COLUMN: CASH PAYMENT VERSION (Span 7) --}}
                <div class="lg:col-span-7">
                    <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-8 sm:p-12 shadow-[0_10px_30px_rgba(33,20,11,0.02)] text-center flex flex-col items-center justify-center space-y-7 min-h-[440px]">
                        
                        {{-- STATUS BADGE --}}
                        <div class="flex items-center justify-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            </span>
                            <span class="text-xs font-bold text-amber-600 tracking-wide">Menunggu Pembayaran & Verifikasi Kasir...</span>
                        </div>

                        {{-- CASH / BANKNOTE ICON --}}
                        <div class="text-[#8b5a2b]">
                            <svg class="w-12 h-12 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm0 2v12h16V6H4zm8 2.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7zm0 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM6 8h2v2H6V8zm10 0h2v2h-2V8zm-10 6h2v2H6v-2zm10 0h2v2h-2v-2z"/>
                            </svg>
                        </div>

                        {{-- TITLE & INSTRUCTIONS --}}
                        <div class="space-y-2">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#21140b] tracking-tight">Pembayaran Tunai</h2>
                            <p class="text-xs sm:text-sm font-semibold text-[#8f7664] max-w-sm mx-auto leading-relaxed">
                                Silakan lakukan pembayaran di kasir dengan menyebutkan nomor pesanan Anda.
                            </p>
                        </div>

                        {{-- ORDER NUMBER --}}
                        <div class="space-y-1.5">
                            <p class="text-[11px] font-extrabold text-[#8f7664] tracking-[0.18em] uppercase">NOMOR PESANAN</p>
                            <p id="cash-order-number" class="text-3xl sm:text-4xl md:text-5xl font-black text-[#21140b] tracking-wider">
                                {{ $hasOrder ? $pesanan->order_number : '#ORD-20240520-001' }}
                            </p>
                        </div>

                        {{-- CONFIRM ORDER BUTTON --}}
                        <div class="pt-2">
                            <button type="button" id="btn-konfirmasi-tunai" onclick="handleKonfirmasiTunai()" class="inline-flex items-center justify-center gap-2.5 rounded-full border border-[#ede6df] bg-white px-8 py-3.5 text-xs font-extrabold text-[#21140b] hover:bg-[#faf7f5] shadow-[0_2px_8px_rgba(33,20,11,0.04)] transition-all active:scale-[0.98] cursor-pointer">
                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#21140b] text-white">
                                    <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span>Konfirmasi Pesanan</span>
                            </button>
                        </div>

                    </div>
                </div>
            @else
                {{-- RIGHT COLUMN: QRIS OR BANK TRANSFER (Span 7) --}}
                <div class="lg:col-span-7">
                    <div class="rounded-3xl bg-white border border-[#edd8cf]/60 p-6 sm:p-8 shadow-[0_10px_30px_rgba(33,20,11,0.02)] text-center space-y-6">
                        
                        {{-- DYNAMIC STATUS BADGE --}}
                        <div class="flex items-center justify-center gap-2">
                            @if($hasUploadedBukti)
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                </span>
                                <span class="text-xs font-bold text-blue-600 tracking-wide">Menunggu Verifikasi Karyawan...</span>
                            @else
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                </span>
                                <span class="text-xs font-bold text-amber-600 tracking-wide">Menunggu Pembayaran...</span>
                            @endif
                        </div>

                        @if($hasUploadedBukti)
                            {{-- STATUS BUKTI TERKIRIM --}}
                            <div class="py-8 space-y-5">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-500 animate-pulse">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="space-y-2">
                                    <h2 class="text-lg font-extrabold text-[#21140b]">Bukti Pembayaran Terkirim</h2>
                                    <p class="text-xs font-semibold text-[#8f7664] max-w-sm mx-auto leading-relaxed">
                                        Mohon tetap berada di halaman ini. Karyawan kami sedang memeriksa pembayaran Anda secara real-time. Sistem akan otomatis beralih setelah disetujui.
                                    </p>
                                </div>

                                <div class="inline-block p-2 bg-[#faf7f5] rounded-2xl border border-[#ede6df] mt-4">
                                    <p class="text-[9px] font-bold text-[#8f7664] mb-1.5 text-center">Bukti Pembayaran Anda:</p>
                                    <img src="{{ $pembayaran->bukti_transfer }}" class="max-h-48 rounded-xl object-contain mx-auto" alt="Bukti Transfer" />
                                </div>
                            </div>
                        @else
                            {{-- SCAN QRIS OR BANK TRANSFER --}}
                            <div>
                                @if($hasOrder && $pesanan->metode_pembayaran === 'Transfer Bank')
                                    <h2 class="text-lg font-extrabold text-[#21140b]">Transfer Bank</h2>
                                    <p class="text-[11px] font-semibold text-[#8f7664] mt-1.5 max-w-xs mx-auto leading-relaxed">
                                        Lakukan transfer ke rekening di bawah ini lalu unggah bukti transfer.
                                    </p>
                                @else
                                    <h2 class="text-lg font-extrabold text-[#21140b]">Scan QRIS</h2>
                                    <p class="text-[11px] font-semibold text-[#8f7664] mt-1.5 max-w-xs mx-auto leading-relaxed">
                                        Scan QR di bawah menggunakan aplikasi pembayaran favorit Anda (OVO, GoPay, Dana, ShopeePay, atau Mobile Banking).
                                    </p>
                                @endif
                            </div>

                            {{-- DYNAMIC DETAILS (QR OR BANK DETAILS) --}}
                            @if($hasOrder && $pesanan->metode_pembayaran === 'Transfer Bank')
                                <div class="bg-[#faf7f5] border border-[#ede6df] rounded-2xl p-5 max-w-md mx-auto text-left space-y-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#8f7664] uppercase">Nama Bank</p>
                                        <p class="text-sm font-extrabold text-[#21140b]">{{ $paymentSettings['transfer_bank']['bank_name'] ?? 'BCA' }}</p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] font-bold text-[#8f7664] uppercase">Nomor Rekening</p>
                                            <p class="text-sm font-extrabold text-[#21140b] tracking-wider">{{ $paymentSettings['transfer_bank']['no_rekening'] ?? '7735 0918 22' }}</p>
                                        </div>
                                        <button onclick="navigator.clipboard.writeText('{{ str_replace(' ', '', $paymentSettings['transfer_bank']['no_rekening'] ?? '7735091822') }}'); showToast('Nomor rekening disalin!', 'success');" class="text-xs font-bold text-[#8b5a2b] hover:text-[#21140b] transition-colors bg-white border border-[#ede6df] px-3 py-1.5 rounded-xl cursor-pointer">
                                            Salin
                                        </button>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-[#8f7664] uppercase">Nama Penerima</p>
                                        <p class="text-sm font-extrabold text-[#21140b]">
                                            {{ $paymentSettings['transfer_bank']['nama_pemilik'] ?? 'Kote School Shop' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                {{-- QR Code Image --}}
                                <div class="flex justify-center py-1">
                                    <div class="rounded-2xl bg-white p-2.5 shadow-[0_4px_16px_rgba(33,20,11,0.06)] border border-[#edd8cf]/40">
                                        <img
                                            src="{{ asset($paymentSettings['qris']['qr_image'] ?? 'images/QR Pembayaran.jpeg') }}"
                                            alt="QRIS QR Code Pembayaran"
                                            class="w-44 h-44 sm:w-48 sm:h-48 object-contain rounded-xl"
                                        />
                                    </div>
                                </div>
                            @endif
                                {{-- Powered By --}}
                                <div class="flex items-center justify-center gap-2 pt-1">
                                    <span class="text-[10px] font-semibold text-[#b5a49a] tracking-wide">Powered by</span>
                                    <div class="flex gap-1.5">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#fbf1e8] text-[9px] font-extrabold text-[#a2785d] border border-[#edd8cf]/50">O</span>
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#fbf1e8] text-[9px] font-extrabold text-[#a2785d] border border-[#edd8cf]/50">G</span>
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#fbf1e8] text-[9px] font-extrabold text-[#a2785d] border border-[#edd8cf]/50">D</span>
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#fbf1e8] text-[9px] font-extrabold text-[#a2785d] border border-[#edd8cf]/50">S</span>
                                    </div>
                                </div>

                            {{-- UPLOAD RECEIPT FORM --}}
                            @if($hasOrder)
                                <form action="{{ route('orders.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-6 border-t border-[#ede6df] text-left max-w-md mx-auto">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-[#21140b]">Unggah Bukti Transaksi</label>
                                        <label for="bukti-pembayaran-input" class="block cursor-pointer">
                                            <div id="upload-zone" class="border border-[#ede6df] rounded-2xl bg-[#faf7f5] hover:bg-[#fbf1e8]/40 p-4 flex items-center justify-between transition-all">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <svg class="h-5 w-5 text-[#8f7664] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span id="upload-file-label" class="text-xs text-[#8f7664] font-semibold truncate">Pilih struk atau foto bukti...</span>
                                                </div>
                                                <span class="rounded-xl bg-[#21140b] text-white px-3 py-1.5 text-[10px] font-bold shrink-0">Pilih File</span>
                                            </div>
                                            <input type="file" id="bukti-pembayaran-input" name="bukti_transfer" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" required />
                                        </label>
                                        @error('bukti_transfer')
                                            <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" id="submit-receipt-btn" class="w-full bg-[#21140b] hover:bg-[#3d2a1f] text-white py-3.5 rounded-2xl text-xs font-bold transition-all active:scale-[0.99] shadow-sm disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer text-center" disabled>
                                        Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('bukti-pembayaran-input');
    const fileLabel = document.getElementById('upload-file-label');
    const submitBtn = document.getElementById('submit-receipt-btn');
    const uploadZone = document.getElementById('upload-zone');

    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('Ukuran file maksimal 2MB', 'warning');
                    this.value = '';
                    fileLabel.textContent = 'Pilih struk atau foto bukti...';
                    submitBtn.disabled = true;
                    return;
                }
                fileLabel.textContent = file.name;
                fileLabel.className = 'text-xs text-[#21140b] font-bold truncate';
                uploadZone.className = 'border border-[#a2785d] bg-[#fbf1e8]/30 rounded-2xl p-4 flex items-center justify-between transition-all';
                submitBtn.disabled = false;
            }
        });
    }

    // Dynamic cart summary loading for legacy fallback
    const itemsList = document.getElementById('payment-items-list');
    const subtotalEl = document.getElementById('payment-subtotal');
    const totalEl = document.getElementById('payment-total');

    if (itemsList && itemsList.children.length === 0) {
        const cart = getCart();
        if (cart.length === 0) {
            window.location.href = "{{ route('menu') }}";
            return;
        }

        let subtotal = 0;
        const serviceFee = 2000;

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;

            let optionsList = [];
            if (item.sugar) optionsList.push(item.sugar);
            if (item.ice) optionsList.push(item.ice);
            if (item.toppings && item.toppings.length > 0) {
                optionsList.push(item.toppings.join(", "));
            }
            const optionsText = optionsList.join(", ");

            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex items-center justify-between gap-4 py-3.5 border-b border-[#edd8cf]/30 last:border-none';
            itemDiv.innerHTML = `
                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                    <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-xl object-cover shrink-0 bg-[#fbf1e8]" />
                    <div class="min-w-0 text-left">
                        <h4 class="text-xs font-extrabold text-[#21140b] truncate">${item.name}</h4>
                        <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5 leading-none">${optionsText || ''}</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-xs font-extrabold text-[#a2785d]">${formatRupiah(itemTotal)}</span>
                    <p class="text-[10px] text-[#8f7664] font-semibold mt-0.5">x${item.qty}</p>
                </div>
            `;
            itemsList.appendChild(itemDiv);
        });

        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);
        if (totalEl) totalEl.textContent = formatRupiah(subtotal + serviceFee);
    }

    // REAL-TIME STATUS POLLING
    @if($hasOrder)
        const orderId = @json($pesanan->id_pesanan);
        
        function pollPaymentStatus() {
            fetch(`/orders/${orderId}/status`)
                .then(res => res.json())
                .then(data => {
                    if (data.status_pembayaran === 'berhasil') {
                        if (typeof showToast === 'function') {
                            showToast('Pembayaran berhasil diverifikasi! Mengalihkan...');
                        }
                        
                        setTimeout(() => {
                            window.location.href = `/orders/${orderId}`;
                        }, 1500);
                    } else if (data.status_pembayaran === 'gagal') {
                        if (typeof showToast === 'function') {
                            showToast('Pembayaran ditolak! Silakan periksa kembali atau kirim bukti baru.', 'error');
                        }
                        // Reload to update the upload view state
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                })
                .catch(err => console.error("Error polling payment status:", err));
        }

        // Poll every 3 seconds
        setInterval(pollPaymentStatus, 3000);
    @endif
});

function handleKonfirmasiTunai() {
    @if($hasOrder)
        const orderId = @json($pesanan->id_pesanan);
        const confirmBtn = document.getElementById('btn-konfirmasi-tunai');

        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = `
                <svg class="animate-spin h-3.5 w-3.5 text-[#21140b]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memeriksa Verifikasi...</span>
            `;
        }

        fetch(`/orders/${orderId}/status`)
            .then(res => res.json())
            .then(data => {
                if (data.status_pembayaran === 'berhasil') {
                    if (typeof showToast === 'function') {
                        showToast('Pembayaran berhasil diverifikasi karyawan! Mengalihkan...');
                    }
                    setTimeout(() => {
                        window.location.href = `/orders/${orderId}`;
                    }, 1000);
                } else {
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = `
                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#21140b] text-white">
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span>Konfirmasi Pesanan</span>
                        `;
                    }
                    showToast('Pembayaran belum diverifikasi kasir. Silakan bayar di kasir terlebih dahulu.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = `
                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#21140b] text-white">
                            <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Konfirmasi Pesanan</span>
                    `;
                }
            });
    @else
        showToast('Pembayaran belum diverifikasi kasir. Silakan bayar di kasir terlebih dahulu.', 'error');
    @endif
}
</script>
@endsection
