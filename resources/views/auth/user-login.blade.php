@extends('frontend.layouts.app')

@section('title', 'Masuk - VISTORA')
@section('meta_description', 'Masuk ke akun VISTORA untuk mengelola profil dan wishlist produk.')
@section('canonical', route('user.login'))
@section('og_url', route('user.login'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-blue-100 via-cyan-100 to-indigo-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative grid gap-5 md:grid-cols-[1.05fr_1.2fr] items-stretch">
        <div class="rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 p-6 sm:p-7 text-white shadow-lg shadow-blue-200/70">
            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-blue-100/90">Akun Pengguna</p>
            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Masuk ke Akun Anda</h1>
            <p class="mt-3 text-sm text-blue-50/95 leading-relaxed">
                Login untuk mengelola profil, cek wishlist, dan lanjutkan eksplorasi produk favorit Anda.
            </p>
            <div class="mt-6 space-y-3 text-sm">
                <div class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2 backdrop-blur-sm">
                    <i class="fas fa-shield-halved text-xs"></i>
                    Data akun tetap aman
                </div>
                <div class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2 backdrop-blur-sm">
                    <i class="fas fa-heart text-xs"></i>
                    Wishlist tersimpan otomatis
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/60 p-6 sm:p-8">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route('user.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope text-xs"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="yourname@example.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="relative" x-data="{ showPassword: false }">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock text-xs"></i>
                    </span>
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan password">
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                        <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Ingat sesi saya
            </label>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                <i class="fas fa-right-to-bracket text-xs"></i>
                Masuk
            </button>
            </form>

            <p class="mt-5 text-sm text-center text-gray-500">
                Belum punya akun?
                <a href="{{ route('user.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Daftar di sini</a>
            </p>
        </div>
    </div>
</section>
@endsection
