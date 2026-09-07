@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- HERO SECTION (Full-bleed) --}}
    <section class="relative min-h-[380px] md:min-h-[480px] flex items-center bg-cover bg-center" style="background-image: url('/images/about_hero.jpg');">
        <!-- Warm dark brown overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#21140b]/95 via-[#21140b]/80 to-[#21140b]/30"></div>
        
        <!-- Aligned Content -->
        <div class="relative z-10 mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-10">
            <div class="max-w-2xl py-12 md:py-20 space-y-4 md:space-y-6 text-white">
                <span class="inline-block rounded-full bg-white/10 border border-white/20 px-3.5 py-1.5 text-xs font-bold tracking-widest uppercase text-white/95 backdrop-blur-sm">
                    EST. 2026
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-[1.15] text-white">
                    Dedikasi dalam Setiap Tetes Kopi
                </h1>
                <p class="text-sm md:text-base text-[#d5c6b8] leading-relaxed max-w-lg">
                    Menghubungkan cita rasa artisanal dengan semangat akademik yang hangat di lingkungan sekolah.
                </p>
            </div>
        </div>
    </section>

    {{-- SECTION 1: CERITA KAMI (Cream Background) --}}
    <div class="py-16 md:py-24 bg-[#fbf1e8]">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 grid grid-cols-1 md:grid-cols-2 items-center gap-12 lg:gap-16">
            <!-- Left: Text -->
            <div class="space-y-6 text-left">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#21140b]">
                        Cerita Kami
                    </h2>
                    <span class="block h-1 w-16 bg-[#a2785d] mt-2 rounded-full"></span>
                </div>
                <div class="space-y-4 text-sm sm:text-base text-[#7b6558] leading-relaxed">
                    <p>
                        <span class="font-bold text-[#21140b]">KOTE SCHOOL SHOP</span> lahir dari visi sederhana menghadirkan pengalaman kopi berkualitas tinggi di jantung lingkungan pendidikan. Kami believe secangkir kopi yang diracik dengan presisi artisanal dapat menjadi katalisator inspirasi, diskusi, dan ketenangan di tengah kesibukan akademik.
                    </p>
                    <p>
                        Bermula dari kecintaan terhadap biji kopi nusantara, kami mengurasi setiap proses—mulai dari pemilihan biji terbaik hingga teknik brewing yang tepat—untuk memastikan setiap kunjungan Anda menjadi momen yang berkesan. Di sini, KOTE bukan sekadar toko; ia adalah rumah kedua bagi para pencari rasa dan pencinta suasana.
                    </p>
                </div>
            </div>

            <!-- Right: Real Image Collage -->
            <div class="relative max-w-md mx-auto w-full md:max-w-none md:pl-6">
                <div class="relative flex flex-col gap-4">
                    <!-- Top Image -->
                    <div class="relative w-full aspect-[16/10] overflow-hidden rounded-[2rem] border border-[#edd8cf]/80 bg-white shadow-[0_15px_45px_rgba(33,20,11,0.06)] hover:shadow-[0_20px_55px_rgba(33,20,11,0.12)] transition-shadow duration-300">
                        <img src="/images/about_students.jpg" alt="Barista brewing coffee" class="h-full w-full object-cover object-[center_30%]" />
                    </div>
                    <!-- Bottom Image -->
                    <div class="relative w-full aspect-[16/10] overflow-hidden rounded-[2rem] border border-[#edd8cf]/80 bg-white shadow-[0_15px_45px_rgba(33,20,11,0.06)] hover:shadow-[0_20px_55px_rgba(33,20,11,0.12)] transition-shadow duration-300">
                        <img src="/images/about_students_group.jpg" alt="Students in cafe" class="h-full w-full object-cover" />
                    </div>
                    <!-- Overlapping badge -->
                    <div class="absolute bottom-6 -left-4 bg-[#21140b] text-white px-6 py-5 rounded-2xl shadow-[0_15px_35px_rgba(33,20,11,0.32)] z-10 text-center border border-white/10 min-w-[130px]">
                        <span class="block text-3xl font-extrabold leading-none text-[#ab7a55]">4+</span>
                        <span class="block mt-1.5 text-[10px] font-bold uppercase tracking-widest text-[#d5c6b8] leading-tight">Varian Coffee</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- SECTION 2: VISION & MISSION (Warmer Beige Background with Soft Inset Shadow & Dividers) --}}
    <div class="bg-[#f5eade]/50 border-y border-[#edd8cf]/60 shadow-[inset_0_12px_24px_rgba(33,20,11,0.03),inset_0_-12px_24px_rgba(33,20,11,0.03)] py-20 md:py-28">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <!-- Visi Card -->
            <div class="rounded-[2rem] border border-[#edd8cf]/60 bg-white p-8 sm:p-10 shadow-[0_15px_40px_rgba(33,20,11,0.04)] hover:shadow-[0_25px_65px_rgba(33,20,11,0.09)] transition-all duration-300 space-y-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#fbf1e8] text-[#a2785d] shadow-[0_6px_15px_rgba(162,120,93,0.06)] border border-[#edd8cf]/40">
                    <x-icons.eye class="h-6 w-6" />
                </div>
                <h3 class="text-xl font-bold text-[#21140b]">Visi Kami</h3>
                <p class="text-sm sm:text-base text-[#7b6558] leading-relaxed">
                    Menjadi destinasi kopi artisanal terkemuka di lingkungan sekolah yang menginspirasi kreativitas dan mempererat komunitas melalui kualitas tanpa kompromi.
                </p>
            </div>

            <!-- Misi Card (Dark Theme) -->
            <div class="rounded-[2rem] bg-[#21140b] p-8 sm:p-10 shadow-[0_20px_50px_rgba(33,20,11,0.18)] hover:shadow-[0_30px_70px_rgba(33,20,11,0.26)] transition-all duration-300 space-y-5 text-white border border-white/5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-[#ab7a55] shadow-[0_6px_15px_rgba(0,0,0,0.15)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-5.64-1.655 9 9 0 0 0-6.208-.682L3 15Z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Misi Kami</h3>
                <ul class="space-y-3.5 text-xs sm:text-sm text-[#d5c6b8]">
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#ab7a55] text-xs font-bold shadow-sm">✓</span>
                        <span class="leading-relaxed">Menyajikan produk kopi dan kudapan berkualitas tinggi secara konsisten.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#ab7a55] text-xs font-bold shadow-sm">✓</span>
                        <span class="leading-relaxed">Menciptakan ruang yang nyaman dan inklusif bagi seluruh warga sekolah.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#ab7a55] text-xs font-bold shadow-sm">✓</span>
                        <span class="leading-relaxed">Mengedukasi komunitas tentang apresiasi kopi artisanal.</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    {{-- SECTION 3: WHY CHOOSE US (Cream Background) --}}
    <div class="py-20 md:py-28 bg-[#fbf1e8]">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 space-y-12">
            <!-- Section Header -->
            <div class="text-center space-y-3">
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#21140b]">Kenapa Memilih Kami?</h2>
                <p class="text-sm text-[#7b6558] max-w-md mx-auto leading-relaxed">
                    Setiap detail kami perhatikan untuk kepuasan Anda.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <!-- Feature 1 -->
                <div class="flex flex-col items-center space-y-3 group">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-[0_6px_15px_rgba(162,120,93,0.06)] transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_10px_25px_rgba(162,120,93,0.18)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-[#21140b]">Biji Premium</h4>
                    <p class="text-xs text-[#7b6558] leading-relaxed max-w-[180px] mx-auto">
                        Hanya menggunakan biji kopi pilihan dari perkebunan terbaik nusantara.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col items-center space-y-3 group">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-[0_6px_15px_rgba(162,120,93,0.06)] transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_10px_25px_rgba(162,120,93,0.18)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m0-12.728.707.707m11.314 11.314-.707.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-[#21140b]">Artisan Roast</h4>
                    <p class="text-xs text-[#7b6558] leading-relaxed max-w-[180px] mx-auto">
                        Dipanggang secara presisi untuk mengeluarkan karakter rasa yang unik.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col items-center space-y-3 group">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-[0_6px_15px_rgba(162,120,93,0.06)] transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_10px_25px_rgba(162,120,93,0.18)]">
                        <x-icons.graduation-cap class="h-6 w-6" />
                    </div>
                    <h4 class="text-base font-bold text-[#21140b]">Student Spot</h4>
                    <p class="text-xs text-[#7b6558] leading-relaxed max-w-[180px] mx-auto">
                        Atmosfer yang tenang, sangat cocok untuk belajar atau diskusi kelompok.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="flex flex-col items-center space-y-3 group">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#f4e6da] text-[#a2785d] shadow-[0_6px_15px_rgba(162,120,93,0.06)] transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_10px_25px_rgba(162,120,93,0.18)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-[#21140b]">Pelayanan Hangat</h4>
                    <p class="text-xs text-[#7b6558] leading-relaxed max-w-[180px] mx-auto">
                        Barista kami siap melayani dengan ramah dan penuh keahlian.
                    </p>
                </div>
            </div>
        </section>
    </div>

    {{-- SECTION 4: CALL TO ACTION (CTA) --}}
    <div class="pb-16 md:pb-24 bg-[#fbf1e8]">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="rounded-[2.5rem] bg-[#21140b] p-10 sm:p-16 text-center space-y-6 shadow-[0_30px_70px_rgba(33,20,11,0.22)] relative overflow-hidden border border-white/5">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(171,122,85,0.15),transparent)]"></div>
                <div class="relative z-10 space-y-4">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Rasakan Pengalaman Kopi yang Berbeda Hari Ini
                    </h2>
                    <p class="text-sm sm:text-base text-[#d5c6b8] max-w-xl mx-auto leading-relaxed">
                        Kunjungi kedai kami dan temukan rasa favorit Anda dalam suasana yang inspiratif.
                    </p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
