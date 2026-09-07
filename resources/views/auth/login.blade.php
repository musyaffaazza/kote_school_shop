@extends('layouts.guest')

@section('content')
    <div class="flex min-h-[calc(100vh-12rem)] items-center justify-center bg-[#fbf1e8] px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-[420px]">
            <div class="mx-auto w-full rounded-[2rem] border border-[#edd8cf]/60 bg-white p-8 sm:p-10 shadow-[0_20px_50px_rgba(33,20,11,0.06)]">
                <div class="space-y-1.5 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-[#21140b] sm:text-3xl">Masuk ke Akun Anda</h1>
                    <p class="text-[11px] font-bold tracking-widest text-[#a2785d] uppercase">AKSES LAYANAN KOTE SCHOOL</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <x-ui.input name="email" type="email" label="Email" placeholder="nama@email.com" :error="$errors->first('email')">
                            <x-slot name="icon">
                                <x-icons.envelope class="w-5 h-5 text-[#8f7664]" />
                            </x-slot>
                        </x-ui.input>
                    </div>

                    <div>
                        <x-ui.input name="password" type="password" label="Password" placeholder="••••••••" :error="$errors->first('password')">
                            <x-slot name="icon">
                                <x-icons.lock class="w-5 h-5 text-[#8f7664]" />
                            </x-slot>
                            <x-slot name="rightIcon">
                                <x-icons.eye class="w-5 h-5 text-[#8f7664]" />
                                <x-icons.eye-off class="w-5 h-5 text-[#8f7664]" />
                            </x-slot>
                        </x-ui.input>
                    </div>

                    <div class="flex items-center justify-between text-sm pt-1">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#d5c6b8] text-[#21140b] focus:ring-[#8f7664]/30 bg-[#fbf1e8] accent-[#21140b]" />
                            <span class="text-sm font-medium text-[#5b4f45]">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-full px-6 py-3.5 text-base font-semibold bg-[#21140b] hover:bg-[#3d2a1f] active:scale-[0.99] text-white shadow-[0_12px_24px_rgba(33,20,11,0.18)] transition-all cursor-pointer">
                        Masuk
                    </button>

                    <p class="text-center text-sm text-[#7b6558] pt-1">
                        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#ab7a55] hover:text-[#21140b] hover:underline transition-colors">Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
