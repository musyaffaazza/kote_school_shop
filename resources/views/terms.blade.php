@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#fbf1e8] py-8 sm:py-12">
    {{-- CENTERED SLIMMER CONTAINER --}}
    <div class="mx-auto max-w-4xl px-4 sm:px-6">

        {{-- MAIN CARD CONTAINER --}}
        <div class="rounded-[2.5rem] border border-[#edd8cf]/80 bg-white p-6 sm:p-10 md:p-12 shadow-[0_20px_50px_rgba(33,20,11,0.03)] space-y-12 sm:space-y-14">

            {{-- HEADER AREA --}}
            <div class="space-y-6">
                {{-- Top Actions --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('home') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#edd8cf] bg-[#fbf1e8] px-4 py-2 text-xs font-bold text-[#7b6558] transition-all hover:bg-[#21140b] hover:text-white hover:border-[#21140b] group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>

                    <div class="inline-flex items-center gap-2 rounded-full bg-[#f4e6da] px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#a2785d]">
                        <x-icons.shield class="w-3.5 h-3.5 text-[#a2785d]" />
                        <span>Dokumen Resmi &amp; Legal</span>
                    </div>
                </div>

                {{-- Title & Subtitle --}}
                <div class="space-y-3 pt-2">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#21140b]">
                        Syarat &amp; Ketentuan Layanan
                    </h1>
                    <p class="text-xs sm:text-sm text-[#7b6558] leading-relaxed">
                        Ketentuan resmi penggunaan platform, pendaftaran akun siswa/umum, pemesanan racikan kopi artisanal, serta tata cara transaksi di <strong class="text-[#21140b]">Kote School Shop (SMKN 1 Gunung Putri)</strong>.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-[#a9988b] pt-1">
                        <span class="inline-flex items-center gap-1.5 bg-[#fbf1e8] px-3 py-1 rounded-full text-[#7b6558] font-medium">
                            <svg class="w-3.5 h-3.5 text-[#ab7a55]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Pembaruan: September 2026
                        </span>
                        <span>•</span>
                        <span>Estimasi baca: ± 4 menit</span>
                    </div>
                </div>

                {{-- PILLS NAV --}}
                <div class="space-y-2.5 pt-2">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#8f7664]">Lompat ke Bab:</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="#bab-1" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">1. Definisi</a>
                        <a href="#bab-2" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">2. Akun &amp; Privasi</a>
                        <a href="#bab-3" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">3. Pemesanan</a>
                        <a href="#bab-4" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">4. Pembayaran &amp; Promo</a>
                        <a href="#bab-5" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">5. Pengambilan</a>
                        <a href="#bab-6" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">6. Komplain &amp; Refund</a>
                        <a href="#bab-7" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">7. Etika Ulasan</a>
                        <a href="#bab-8" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">8. Hak Cipta</a>
                        <a href="#bab-9" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">9. Kontak Resmi</a>
                    </div>
                </div>
            </div>

            {{-- NOTICE CALLOUT BANNER --}}
            <div class="rounded-3xl border border-[#edd8cf] bg-[#fdfaf7] p-5 sm:p-6 flex items-start gap-4 shadow-sm">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-[#a2785d]">
                    <x-icons.shield class="w-5 h-5 text-[#a2785d]" />
                </div>
                <div class="space-y-1 text-left">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#21140b]">Pemberitahuan Persetujuan Pengguna</h3>
                    <p class="text-xs sm:text-sm text-[#66574c] leading-relaxed">
                        Dengan mengakses web, membuat akun, atau bertransaksi di <strong>Kote School Shop</strong>, Anda menyatakan telah menyetujui seluruh ketentuan layanan di bawah ini.
                    </p>
                </div>
            </div>

            {{-- SECTIONS (COMFORTABLE READING SPACING) --}}
            <div class="space-y-12 sm:space-y-14">

                {{-- BAB 1: DEFINISI --}}
                <section id="bab-1" class="scroll-mt-24 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            01
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Definisi &amp; Istilah Utama</h2>
                            <p class="text-xs text-[#8f7664]">Pengertian istilah yang berlaku pada sistem</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">🏪</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Kote School Shop ("Kami")</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Platform pemesanan dan kedai kopi artisanal di SMKN 1 Gunung Putri, dikembangkan dan dikelola oleh <strong>Azza Musyaffa</strong> beserta tim.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">👤</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pengguna ("Anda")</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Seluruh siswa, guru, staf sekolah SMKN 1 Gunung Putri, maupun tamu umum yang mengakses situs atau memesan menu.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">☕</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Produk Menu</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Seluruh menu minuman kopi, non-kopi, makanan ringan, dan item yang terdaftar secara resmi di katalog Kote School Shop.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">📋</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pesanan &amp; Transaksi</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Permintaan pembelian produk yang dikirimkan Pengguna melalui aplikasi dan telah diverifikasi bukti pembayarannya.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- BAB 2: AKUN & PRIVASI --}}
                <section id="bab-2" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            02
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Pendaftaran Akun &amp; Kebijakan Privasi</h2>
                            <p class="text-xs text-[#8f7664]">Ketentuan registrasi, perlindungan identitas, dan keamanan akun</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.user class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Data Diri Akurat</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Anda wajib mengisi formulir pendaftaran dengan data asli (Nama, Email, WhatsApp aktif, NIS, Kelas, dan Alamat).
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.id-card class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Verifikasi Identitas</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Foto kartu identitas digunakan untuk verifikasi status warga sekolah serta promo khusus pelajar.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.lock class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Kerahasiaan Sandi</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Anda bertanggung jawab penuh menjaga kerahasiaan password akun Anda sendiri.
                            </p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-amber-200/80 bg-amber-50/50 p-4 text-xs text-amber-900 flex items-start gap-3">
                        <span class="text-sm">⚠️</span>
                        <div class="space-y-0.5">
                            <strong class="font-bold">Perhatian Penyalahgunaan:</strong>
                            <p class="text-[#7c5b2c] leading-relaxed">Manajemen Kote School Shop berhak memblokir akun yang memalsukan data identitas atau melakukan tindakan mencurigakan.</p>
                        </div>
                    </div>
                </section>

                {{-- BAB 3: PEMESANAN --}}
                <section id="bab-3" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            03
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Pemesanan &amp; Ketersediaan Menu</h2>
                            <p class="text-xs text-[#8f7664]">Tata cara memesan, jam operasional, dan stok racikan</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">1</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Jam Operasional Pemesanan</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Pemesanan diproses selama jam kerja operasional kedai fisik di SMKN 1 Gunung Putri (Senin - Jumat: 08.00 - 15.30 WIB).</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">2</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Ketersediaan Stok Segar</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Semua minuman diracik secara segar (*freshly brewed*). Jika stok habis setelah checkout, staf kedai akan segera menghubungi Anda untuk penyesuaian menu atau refund.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">3</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Kustomisasi Pesanan</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Kustomisasi kadar gula (*sugar level*), takaran es (*ice level*), dan topping wajib dipilih secara teliti sebelum checkout.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- BAB 4: HARGA & PEMBAYARAN --}}
                <section id="bab-4" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            04
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Harga, Pembayaran &amp; Kode Promo</h2>
                            <p class="text-xs text-[#8f7664]">Ketentuan mata uang, verifikasi transfer, dan voucher diskon</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-[#a2785d] shadow-sm">
                                    <x-icons.wallet class="w-4 h-4 text-[#a2785d]" />
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Metode Pembayaran</h4>
                            </div>
                            <ul class="text-xs text-[#66574c] space-y-1.5 list-disc list-inside leading-relaxed">
                                <li>Semua harga tertera dalam mata uang Rupiah (IDR).</li>
                                <li>Pembayaran non-tunai (QRIS / Transfer) wajib mengunggah bukti yang sah.</li>
                                <li>Pesanan diproses setelah pembayaran disetujui staf kasir.</li>
                            </ul>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-[#a2785d] shadow-sm">
                                    <svg class="w-4 h-4 text-[#a2785d]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Ketentuan Promo &amp; Diskon</h4>
                            </div>
                            <ul class="text-xs text-[#66574c] space-y-1.5 list-disc list-inside leading-relaxed">
                                <li>Voucher promo hanya berlaku pada periode aktif resmi.</li>
                                <li>Setiap promo memiliki syarat minimum belanja atau kuota.</li>
                                <li>Kecurangan pemakaian kode promo membatalkan pesanan.</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- BAB 5: PENGAMBILAN --}}
                <section id="bab-5" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            05
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Pengambilan &amp; Pengantaran Pesanan</h2>
                            <p class="text-xs text-[#8f7664]">Metode pickup mandiri dan pengantaran internal sekolah</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-[#a2785d] shadow-sm">
                                    <svg class="w-4 h-4 text-[#a2785d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Ambil Mandiri (*Self-Pickup*)</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Tunjukkan Nomor ID Pesanan Anda di kasir Kote School Shop saat status pesanan sudah <em>"Siap Diambil"</em>.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-[#a2785d] shadow-sm">
                                    <x-icons.truck class="w-4 h-4 text-[#a2785d]" />
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Diantar ke Ruangan / Kelas</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Khusus area sekolah SMKN 1 Gunung Putri. Pengguna wajib menyertakan detail nama ruangan/kelas secara lengkap saat checkout.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- BAB 6: KOMPLAIN & REFUND --}}
                <section id="bab-6" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            06
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Pembatalan &amp; Kebijakan Komplain (Refund)</h2>
                            <p class="text-xs text-[#8f7664]">Ketentuan pembatalan, salah pesanan, dan penggantian menu</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 sm:p-6 space-y-3.5">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 border border-emerald-300 px-3 py-1 text-xs font-bold text-emerald-800">
                            ⏱️ Batas Waktu Komplain: 15 Menit
                        </span>
                        <ul class="text-xs text-[#66574c] space-y-2 leading-relaxed list-disc list-inside">
                            <li><strong>Pesanan Sedang Dibuat:</strong> Pesanan yang telah diproses barista tidak dapat dibatalkan secara sepihak.</li>
                            <li><strong>Klaim Ketidaksesuaian:</strong> Jika pesanan salah varian atau cacat, segera laporkan maksimal 15 menit setelah pesanan diterima dengan membawa bukti fisik produk.</li>
                            <li><strong>Penggantian Menu:</strong> Tim kedai akan segera mengganti dengan menu baru yang sesuai atau memproses pengembalian dana.</li>
                        </ul>
                    </div>
                </section>

                {{-- BAB 7: ULASAN --}}
                <section id="bab-7" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            07
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Etika Ulasan &amp; Rating Pelanggan</h2>
                            <p class="text-xs text-[#8f7664]">Pedoman pemberian penilaian dan komentar yang santun</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs text-[#66574c] leading-relaxed">
                        <p>
                            Pelanggan berhak memberikan penilaian bintang (1–5) serta komentar ulasan pada menu yang telah selesai dipesan.
                        </p>
                        <div class="p-4 rounded-2xl bg-[#fdfaf7] border border-[#edd8cf]/80 text-[#5b4f45] leading-relaxed">
                            <strong class="font-bold text-[#21140b]">Larangan Konten Ulasan:</strong> Ulasan dilarang mengandung ujaran kebencian, unsur SARA, kata-kata kasar, fitnah, maupun spam promosi pihak luar.
                        </div>
                    </div>
                </section>

                {{-- BAB 8: HAKI --}}
                <section id="bab-8" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            08
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Hak Kekayaan Intelektual (HAKI)</h2>
                            <p class="text-xs text-[#8f7664]">Perlindungan hak cipta karya dan aset digital Kote School Shop</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[#edd8cf] bg-[#21140b] p-5 sm:p-6 text-white space-y-2.5 shadow-sm">
                        <span class="inline-block text-[10px] font-bold uppercase tracking-widest text-[#ab7a55]">© Copyright &amp; Ownership</span>
                        <h3 class="text-sm sm:text-base font-bold text-white">© 2026 Azza Musyaffa. All Rights Reserved.</h3>
                        <p class="text-xs text-[#d5c6b8] leading-relaxed">
                            Platform <strong>Kote School Shop</strong> beserta seluruh kode program, desain antarmuka, struktur data, dan logo merupakan karya cipta yang dikembangkan oleh <strong>Azza Musyaffa</strong>. Dilarang menyalin atau menyebarluaskan materi ini tanpa izin tertulis dari pemilik hak cipta.
                        </p>
                    </div>
                </section>

                {{-- BAB 9: KONTAK RESMI --}}
                <section id="bab-9" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            09
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Kontak Resmi &amp; Layanan Bantuan</h2>
                            <p class="text-xs text-[#8f7664]">Hubungi kami untuk pertanyaan seputar ketentuan layanan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <a href="https://whatsapp.com/channel/0029Vb93h6JB4hdOYlT1Yu0e" target="_blank"
                           class="flex items-center gap-3.5 p-4 rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] hover:bg-[#fbf1e8] transition-all group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm">
                                <x-icons.whatsapp class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#8f7664]">Saluran WhatsApp</p>
                                <p class="text-xs font-bold text-[#21140b] group-hover:text-[#ab7a55] transition-colors">Official Kote School Shop</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/kotecoffee.id?utm_source=ig_web_button_share_sheet&igsi=ZDNlZDc0MzIxNw==" target="_blank"
                           class="flex items-center gap-3.5 p-4 rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] hover:bg-[#fbf1e8] transition-all group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-white text-pink-600 shadow-sm">
                                <x-icons.instagram class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#8f7664]">Instagram</p>
                                <p class="text-xs font-bold text-[#21140b] group-hover:text-[#ab7a55] transition-colors">@kotecoffee.id</p>
                            </div>
                        </a>

                        <a href="https://www.tiktok.com/@kotteschoolshop" target="_blank"
                           class="flex items-center gap-3.5 p-4 rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] hover:bg-[#fbf1e8] transition-all group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-white text-black shadow-sm">
                                <x-icons.tiktok class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#8f7664]">TikTok</p>
                                <p class="text-xs font-bold text-[#21140b] group-hover:text-[#ab7a55] transition-colors">@kotteschoolshop</p>
                            </div>
                        </a>

                        <a href="https://maps.google.com/?q=SMKN+1+Gunung+Putri+Bogor" target="_blank"
                           class="flex items-center gap-3.5 p-4 rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] hover:bg-[#fbf1e8] transition-all group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-white text-[#8b5a2b] shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#8f7664]">Lokasi Kedai</p>
                                <p class="text-xs font-bold text-[#21140b] group-hover:text-[#ab7a55] transition-colors">SMKN 1 Gunung Putri, Bogor</p>
                            </div>
                        </a>
                    </div>
                </section>

            </div>

            {{-- BOTTOM ACTION BANNER --}}
            <div class="rounded-3xl bg-[#21140b] p-6 sm:p-7 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="text-center sm:text-left space-y-0.5">
                    <h4 class="text-sm sm:text-base font-bold text-white">Siap Menikmati Kopi Spesial Hari Ini?</h4>
                    <p class="text-xs text-[#d5c6b8]">Pilih menu racikan favorit Anda dan pesan dengan mudah.</p>
                </div>
                <a href="{{ route('menu') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-[#ab7a55] hover:bg-[#8f6241] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white transition-all shadow-md shrink-0">
                    <span>Lihat Menu Kote</span>
                    <x-icons.arrow-right class="w-3.5 h-3.5 text-white" />
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
