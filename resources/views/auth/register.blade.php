@extends('layouts.guest')

@section('content')
    <div class="flex min-h-[calc(100vh-12rem)] items-center justify-center bg-[#fbf1e8] px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-[460px] sm:max-w-[500px] md:max-w-4xl transition-all duration-300">
            <div class="mx-auto w-full rounded-[2rem] border border-[#edd8cf]/60 bg-white p-6 sm:p-10 shadow-[0_20px_50px_rgba(33,20,11,0.06)]">
                <!-- Header -->
                <div class="flex flex-col items-center space-y-2 text-center">
                    <div class="flex items-center gap-2">
                        <x-ui.logo class="h-7 w-auto" />
                        <span class="text-xs font-bold uppercase tracking-widest text-[#21140b]">KOTE SCHOOL SHOP</span>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-[#21140b] sm:text-3xl">Buat Akun Baru</h1>
                    <p class="text-xs text-[#8f7664]">Bergabunglah dengan komunitas kopi sekolah kami.</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" novalidate class="mt-8 space-y-6">
                    @csrf

                    <!-- Grid Layout: 1 kolom di Mobile, 2 kolom di Desktop (md+) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            {{-- NAMA LENGKAP → kolom: nama --}}
                            <div>
                                <x-ui.input name="nama" label="NAMA LENGKAP" placeholder="Masukkan nama lengkap Anda"
                                    :value="old('nama')" :error="$errors->first('nama')">
                                    <x-slot name="icon">
                                        <x-icons.user class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>

                            {{-- EMAIL → kolom: email --}}
                            <div>
                                <x-ui.input name="email" type="email" label="EMAIL" placeholder="contoh@email.com"
                                    :value="old('email')" :error="$errors->first('email')">
                                    <x-slot name="icon">
                                        <x-icons.envelope class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>

                            {{-- NO. HP → kolom: no_hp --}}
                            <div>
                                <x-ui.input name="no_hp" type="tel" label="NO. HP" placeholder="0812xxxx"
                                    :value="old('no_hp')" :error="$errors->first('no_hp')">
                                    <x-slot name="icon">
                                        <x-icons.phone class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>

                            {{-- NIS --}}
                            <div>
                                <x-ui.input name="nis" label="NIS" placeholder="Masukkan NIS Anda"
                                    :value="old('nis')" :error="$errors->first('nis')" maxlength="16" inputmode="numeric">
                                    <x-slot name="icon">
                                        <x-icons.id-card class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>

                            {{-- KELAS → kolom: kelas --}}
                            <div>
                                <x-ui.input name="kelas" label="KELAS" placeholder="Misal: XI RPL 1"
                                    :value="old('kelas')" :error="$errors->first('kelas')">
                                    <x-slot name="icon">
                                        <x-icons.graduation-cap class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            {{-- JENIS KELAMIN → kolom: jenis_kelamin (L/P) --}}
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-[#21140b]">JENIS KELAMIN</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="L" class="peer sr-only"
                                            {{ old('jenis_kelamin', 'L') === 'L' ? 'checked' : '' }} />
                                        <div class="flex min-h-[46px] items-center justify-center rounded-xl border border-[#e7d7ce] bg-[#fbf1e8] py-2.5 px-4 text-sm font-semibold text-[#5b4f45] transition peer-checked:bg-[#21140b] peer-checked:border-[#21140b] peer-checked:text-white">
                                            Laki-laki
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="P" class="peer sr-only"
                                            {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }} />
                                        <div class="flex min-h-[46px] items-center justify-center rounded-xl border border-[#e7d7ce] bg-[#fbf1e8] py-2.5 px-4 text-sm font-semibold text-[#5b4f45] transition peer-checked:bg-[#21140b] peer-checked:border-[#21140b] peer-checked:text-white">
                                            Perempuan
                                        </div>
                                    </label>
                                </div>
                                @error('jenis_kelamin')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ALAMAT → kolom: alamat --}}
                            <div>
                                <label for="alamat" class="mb-2 block text-xs font-bold uppercase tracking-wider text-[#21140b]">ALAMAT</label>
                                <textarea id="alamat" name="alamat" rows="2"
                                    placeholder="Masukkan alamat lengkap Anda"
                                    class="w-full rounded-xl border border-[#e7d7ce] bg-[#fbf1e8] px-4 py-3 text-sm text-[#21140b] placeholder:text-[#b79f8d] shadow-sm transition focus:border-[#c5ab98] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/20">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- PASSWORD --}}
                            <div>
                                <x-ui.input name="password" type="password" label="PASSWORD" placeholder="••••••••"
                                    :error="$errors->first('password')">
                                    <x-slot name="icon">
                                        <x-icons.lock class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>

                            {{-- KONFIRMASI PASSWORD --}}
                            <div>
                                <x-ui.input name="password_confirmation" type="password" label="KONFIRMASI PASSWORD" placeholder="••••••••"
                                    :error="$errors->first('password_confirmation')">
                                    <x-slot name="icon">
                                        <x-icons.lock class="w-5 h-5 text-[#8f7664]" />
                                    </x-slot>
                                </x-ui.input>
                            </div>
                        </div>
                    </div>

                    {{-- UNGGAH IDENTITAS (full width, paling bawah) --}}
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-[#21140b]">
                            UNGGAH IDENTITAS (KTP/KIA/KARTU PELAJAR)
                        </label>
                        <label id="foto-dropzone" class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#e7d7ce] bg-[#fbf1e8]/50 py-6 px-4 text-center cursor-pointer transition hover:border-[#c5ab98] hover:bg-[#fbf1e8]">
                            <input type="file" id="foto_identitas" name="foto_identitas" accept="image/*" class="sr-only" />

                            <!-- State Awal -->
                            <div id="foto-placeholder" class="flex flex-col items-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#8f7664] shadow-sm mb-2">
                                    <x-icons.cloud-upload class="w-5 h-5" />
                                </div>
                                <p class="text-xs font-semibold text-[#21140b]">Klik untuk unggah foto identitas</p>
                                <p class="mt-0.5 text-[10px] font-bold tracking-wider text-[#a2785d] uppercase">MAKSIMAL 2MB (.JPG, .PNG)</p>
                            </div>

                            <!-- State Setelah Foto Dipilih -->
                            <div id="foto-preview-container" class="hidden flex-col items-center space-y-2 w-full">
                                <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 border border-emerald-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Foto Berhasil Dipilih</span>
                                </div>
                                <div class="relative h-28 w-auto max-w-[220px] overflow-hidden rounded-xl border border-[#edd8cf] bg-white shadow-sm my-1">
                                    <img id="foto-preview-img" src="#" alt="Preview Identitas" class="h-full w-full object-cover" />
                                </div>
                                <p id="foto-name" class="text-xs font-bold text-[#21140b] truncate max-w-[240px]"></p>
                                <p id="foto-size" class="text-[10px] font-semibold text-[#8f7664]"></p>
                                <span class="text-[11px] font-semibold text-[#ab7a55] underline hover:text-[#21140b] pt-1">Klik untuk mengganti foto</span>
                            </div>
                        </label>
                        @error('foto_identitas')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bottom Actions (Syarat, Submit, Login Link) -->
                    <div class="space-y-4 pt-2">
                        {{-- SYARAT & KETENTUAN --}}
                        <div class="pt-1">
                            <label class="inline-flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="terms"
                                    class="mt-0.5 w-4 h-4 rounded border-[#d5c6b8] text-[#21140b] focus:ring-[#8f7664]/30 bg-[#fbf1e8] accent-[#21140b]" />
                                <span class="text-xs text-[#5b4f45] leading-snug">
                                    Saya setuju dengan
                                    <a href="{{ route('terms') }}" target="_blank" class="font-semibold text-[#ab7a55] hover:text-[#21140b] underline">Syarat &amp; Ketentuan</a>
                                    serta
                                    <a href="{{ route('privacy') }}" target="_blank" class="font-semibold text-[#ab7a55] hover:text-[#21140b] underline">Kebijakan Privasi</a>.
                                </span>
                            </label>
                            @error('terms')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-full px-6 py-3.5 text-base font-semibold bg-[#21140b] hover:bg-[#3d2a1f] active:scale-[0.99] text-white shadow-[0_12px_24px_rgba(33,20,11,0.18)] transition-all cursor-pointer uppercase tracking-wider">
                            <span>DAFTAR SEKARANG</span>
                            <x-icons.arrow-right class="w-4 h-4 text-white" />
                        </button>

                        <p class="text-center text-sm text-[#7b6558] pt-1">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-semibold text-[#ab7a55] hover:text-[#21140b] hover:underline transition-colors">Masuk di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('foto_identitas');
            const placeholder = document.getElementById('foto-placeholder');
            const previewContainer = document.getElementById('foto-preview-container');
            const previewImg = document.getElementById('foto-preview-img');
            const fileName = document.getElementById('foto-name');
            const fileSize = document.getElementById('foto-size');
            const dropzone = document.getElementById('foto-dropzone');

            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function (evt) {
                                previewImg.src = evt.target.result;
                                fileName.textContent = file.name;

                                // Format size
                                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                                fileSize.textContent = `${sizeInMB} MB`;

                                placeholder.classList.add('hidden');
                                previewContainer.classList.remove('hidden');
                                previewContainer.classList.add('flex');

                                if (dropzone) {
                                    dropzone.classList.remove('border-dashed', 'border-[#e7d7ce]', 'bg-[#fbf1e8]/50');
                                    dropzone.classList.add('border-solid', 'border-emerald-400', 'bg-emerald-50/40');
                                }
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                });
            }
        });
    </script>
@endsection

