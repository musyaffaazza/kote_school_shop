@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#fbf1e8] py-8 sm:py-12">
    {{-- CENTERED SLIMMER CONTAINER (MAX-W-4XL) --}}
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
                        <span>Kebijakan Privasi Resmi</span>
                    </div>
                </div>

                {{-- Title & Subtitle --}}
                <div class="space-y-3 pt-2">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#21140b]">
                        Kebijakan Privasi
                    </h1>
                    <p class="text-xs sm:text-sm text-[#7b6558] leading-relaxed">
                        Penjelasan lengkap mengenai bagaimana <strong class="text-[#21140b]">Kote School Shop (SMKN 1 Gunung Putri)</strong> mengumpulkan, melindungi, dan mengelola data pribadi Anda saat menggunakan layanan kami.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-[#a9988b] pt-1">
                        <span class="inline-flex items-center gap-1.5 bg-[#fbf1e8] px-3 py-1 rounded-full text-[#7b6558] font-medium">
                            <svg class="w-3.5 h-3.5 text-[#ab7a55]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Pembaruan: September 2026
                        </span>
                        <span>•</span>
                        <span>Estimasi baca: ± 3 menit</span>
                    </div>
                </div>

                {{-- PILLS NAV --}}
                <div class="space-y-2.5 pt-2">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#8f7664]">Lompat ke Bab:</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="#privasi-1" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">1. Data Dikumpulkan</a>
                        <a href="#privasi-2" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">2. Tujuan Penggunaan</a>
                        <a href="#privasi-3" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">3. Keamanan &amp; Enkripsi</a>
                        <a href="#privasi-4" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">4. Perlindungan Pihak Ketiga</a>
                        <a href="#privasi-5" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">5. Hak &amp; Kontrol Data</a>
                        <a href="#privasi-6" class="rounded-full border border-[#edd8cf] bg-[#fbf1e8]/60 px-3.5 py-1.5 text-xs font-semibold text-[#66574c] hover:bg-[#21140b] hover:text-white hover:border-[#21140b] transition-all">6. Kontak Pengelola</a>
                    </div>
                </div>
            </div>

            {{-- NOTICE CALLOUT BANNER --}}
            <div class="rounded-3xl border border-[#edd8cf] bg-[#fdfaf7] p-5 sm:p-6 flex items-start gap-4 shadow-sm">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-[#a2785d]">
                    <x-icons.lock class="w-5 h-5 text-[#a2785d]" />
                </div>
                <div class="space-y-1 text-left">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#21140b]">Komitmen Perlindungan Privasi Siswa &amp; Pengguna</h3>
                    <p class="text-xs sm:text-sm text-[#66574c] leading-relaxed">
                        Kami sangat menghargai privasi civitas sekolah. Seluruh data identitas yang Anda daftarkan hanya digunakan untuk keperluan layanan internal <strong>Kote School Shop</strong> dan tidak akan diperjualbelikan kepada pihak lain.
                    </p>
                </div>
            </div>

            {{-- SECTIONS --}}
            <div class="space-y-12 sm:space-y-14">

                {{-- BAB 1: DATA DIKUMPULKAN --}}
                <section id="privasi-1" class="scroll-mt-24 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            01
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Informasi yang Kami Kumpulkan</h2>
                            <p class="text-xs text-[#8f7664]">Jenis data yang diperlukan untuk operasional kedai</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">👤</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Data Identitas Akun</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Nama lengkap, alamat email aktif, nomor WhatsApp/HP, NIS (Nomor Induk Siswa), kelas, jenis kelamin, dan alamat tempat tinggal.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">🪪</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Foto Kartu Pelajar / Identitas</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Berkas foto kartu pelajar (atau KTP/KIA) yang diunggah untuk verifikasi keanggotaan warga SMKN 1 Gunung Putri.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">💳</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Data Transaksi &amp; Bukti Bayar</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Rincian item pesanan, total harga, catatan racikan menu, metode pembayaran, serta unggahan bukti transfer/QRIS.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">💬</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Ulasan &amp; Pesan Kontak</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Komentar penilaian menu, rating bintang, serta pesan formulir kontak yang Anda kirimkan kepada tim pengelola.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- BAB 2: TUJUAN PENGGUNAAN DATA --}}
                <section id="privasi-2" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            02
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Tujuan Penggunaan Informasi</h2>
                            <p class="text-xs text-[#8f7664]">Bagaimana data Anda kami manfaatkan untuk layanan</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">1</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pemrosesan &amp; Penyiapan Pesanan</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Memastikan pesanan racikan kopi dan makanan disiapkan sesuai preferensi (kadar gula, es, topping) dan dapat diantar atau diambil dengan akurat.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">2</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pemberian Promo Khusus Civitas Sekolah</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Memverifikasi kelayakan pengguna untuk mendapatkan diskon dan voucher promo eksklusif bagi siswa dan staf SMKN 1 Gunung Putri.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 p-4 sm:p-5 rounded-3xl bg-[#fdfaf7] border border-[#edd8cf]/80">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#21140b] text-white text-xs font-bold">3</span>
                            <div class="space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Keamanan &amp; Verifikasi Transaksi</h4>
                                <p class="text-xs text-[#66574c] leading-relaxed">Memvalidasi bukti transfer pembayaran guna menghindari kecurangan serta mempermudah konfirmasi saat terjadi kendala pesanan.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- BAB 3: KEAMANAN & ENKRIPSI --}}
                <section id="privasi-3" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            03
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Penyimpanan &amp; Keamanan Data</h2>
                            <p class="text-xs text-[#8f7664]">Metode perlindungan data dan enkripsi password</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.lock class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Enkripsi Kata Sandi</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Password akun dienkripsi menggunakan hashing standar industri (*Bcrypt*). Tidak ada staf yang dapat melihat password asli Anda.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.shield class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Penyimpanan Aman</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Berkas identitas dan data transaksi disimpan dalam storage terproteksi di server internal sistem Kote School Shop.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white text-[#a2785d] shadow-sm">
                                <x-icons.user class="w-4 h-4 text-[#a2785d]" />
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Akses Terbatas</h4>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Akses data hanya diberikan kepada administrator dan kasir berwenang untuk validasi pesanan dan pembayaran.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- BAB 4: PERLINDUNGAN DARI PIHAK KETIGA --}}
                <section id="privasi-4" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            04
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Perlindungan Dari Pihak Ketiga</h2>
                            <p class="text-xs text-[#8f7664]">Jaminan integritas data pengguna</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 sm:p-6 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 border border-emerald-300 px-3 py-1 text-xs font-bold text-emerald-800">
                                🛡️ Jaminan Bebas Jual-Beli Data
                            </span>
                        </div>
                        <ul class="text-xs sm:text-sm text-[#66574c] space-y-2 leading-relaxed list-disc list-inside">
                            <li><strong>Tidak Ada Iklan Komersial Pihak Ketiga:</strong> Kami tidak pernah menjual, menyewakan, atau memperdagangkan informasi pribadi Anda kepada perusahaan periklanan eksternal.</li>
                            <li><strong>Kebutuhan Internal Sekolah:</strong> Seluruh pertukaran data hanya berlangsung dalam ekosistem layanan Kote School Shop SMKN 1 Gunung Putri.</li>
                            <li><strong>Penyimpanan Browser (Cookies):</strong> Kami hanya menggunakan sesi browser terenkripsi dan <em>localStorage</em> untuk mengingat login dan keranjang belanja Anda.</li>
                        </ul>
                    </div>
                </section>

                {{-- BAB 5: HAK & KONTROL DATA PENGGUNA --}}
                <section id="privasi-5" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            05
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Hak &amp; Kontrol Pengguna Atas Data</h2>
                            <p class="text-xs text-[#8f7664]">Kelola dan perbarui data profil Anda kapan saja</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">⚙️</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pembaruan Data Profil</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Anda dapat melihat dan mengubah informasi nama, nomor WhatsApp, dan alamat secara mandiri melalui menu <strong>Profil Akun</strong>.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] p-5 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-[#a2785d] text-xs shadow-sm">🔑</span>
                                <h4 class="text-xs sm:text-sm font-bold text-[#21140b]">Pergantian Kata Sandi</h4>
                            </div>
                            <p class="text-xs text-[#66574c] leading-relaxed">
                                Anda dapat memperbarui kata sandi akun kapan pun diperlukan demi menjaga keamanan akses akun pribadi Anda.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- BAB 6: KONTAK PENGELOLA DATA --}}
                <section id="privasi-6" class="scroll-mt-24 space-y-5 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-[#f4e6da] text-xs font-extrabold text-[#a2785d]">
                            06
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#21140b]">Kontak Pengelola Data &amp; Privasi</h2>
                            <p class="text-xs text-[#8f7664]">Hubungi kami apabila memiliki pertanyaan mengenai perlindungan data</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <a href="https://whatsapp.com/channel/0029Vb93h6JB4hdOYlT1Yu0e" target="_blank"
                           class="flex items-center gap-3.5 p-4 rounded-3xl border border-[#edd8cf]/80 bg-[#fdfaf7] hover:bg-[#fbf1e8] transition-all group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm">
                                <x-icons.whatsapp class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#8f7664]">WhatsApp Resmi</p>
                                <p class="text-xs font-bold text-[#21140b] group-hover:text-[#ab7a55] transition-colors">Saluran Kote School Shop</p>
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
                                <svg class="w-4 h-4 text-[#8b5a2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <h4 class="text-sm sm:text-base font-bold text-white">Privasi Anda Aman Bersama Kami</h4>
                    <p class="text-xs text-[#d5c6b8]">Nikmati kemudahan memesan kopi favorit dengan nyaman.</p>
                </div>
                <a href="{{ route('menu') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-[#ab7a55] hover:bg-[#8f6241] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white transition-all shadow-md shrink-0">
                    <span>Mulai Memesan</span>
                    <x-icons.arrow-right class="w-3.5 h-3.5 text-white" />
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
