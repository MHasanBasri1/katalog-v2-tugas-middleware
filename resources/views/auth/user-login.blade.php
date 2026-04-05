@extends('frontend.layouts.app')

@section('title', 'Masuk - Kataloque')
@section('meta_description', 'Masuk ke akun Kataloque untuk mengelola profil dan favorit produk.')
@section('canonical', route('user.login'))
@section('og_url', route('user.login'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Masuk ke Akun</h1>
            <p class="mt-3 text-sm text-gray-500 font-medium">
                Kelola profil, favorit, dan belanja lebih mudah.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 relative z-10">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route('user.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope text-xs"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" placeholder="yourname@example.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="mb-1.5">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                </div>
                <div class="relative" x-data="{ showPassword: false }">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock text-xs"></i>
                    </span>
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" placeholder="Masukkan password">
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                        <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Ingat sesi saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-primary-dark transition shadow-lg shadow-primary/20 hover:-translate-y-0.5">
                <i class="fas fa-right-to-bracket text-xs"></i>
                Masuk
            </button>

            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gray-200"></div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">atau</span>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>

            <a href="{{ route('user.google.redirect') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                <i class="fab fa-google text-[13px] text-rose-500"></i>
                Masuk dengan Google
            </a>
            </form>

            <p class="mt-5 text-sm text-center text-gray-500">
                Belum punya akun?
                <a href="{{ route('user.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Daftar di sini</a>
            </p>
        </div>
    </div>
</section>
@endsection
