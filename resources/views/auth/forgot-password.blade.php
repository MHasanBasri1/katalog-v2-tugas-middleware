@extends('frontend.layouts.app')

@php
    $isAdmin = $isAdmin ?? false;
    $requestRoute = $isAdmin ? 'admin.password.request' : 'password.request';
    $emailRoute = $isAdmin ? 'admin.password.email' : 'password.email';
    $loginRoute = $isAdmin ? 'login' : 'user.login';
    $accountLabel = $isAdmin ? 'Akun Admin' : 'Akun Pengguna';
    $title = $isAdmin ? 'Lupa Password Admin - Kataloque' : 'Lupa Password - Kataloque';
    $description = $isAdmin
        ? 'Minta link reset password untuk akun admin Kataloque Anda.'
        : 'Minta link reset password untuk akun Kataloque Anda.';
    
    // Choose gradient based on admin status
    $gradientColors = $isAdmin 
        ? 'from-indigo-100 via-blue-100 to-cyan-100'
        : 'from-blue-100 via-cyan-100 to-indigo-100';
@endphp

@section('title', $title)
@section('meta_description', $description)
@section('canonical', route($requestRoute))
@section('og_url', route($requestRoute))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <!-- Gradient Blur Background -->
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r {{ $gradientColors }} blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">Lupa Password?</h1>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                Masukkan email {{ $isAdmin ? 'admin' : 'terdaftar' }} Anda. Kami akan mengirimkan link untuk mengatur ulang password Anda.
            </p>
        </div>

        <div class="bg-white/40 backdrop-blur-xl rounded-3xl border border-white/40 shadow-2xl shadow-gray-200/40 p-6 sm:p-10 relative z-10">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route($emailRoute) }}" class="space-y-6">
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

                <div class="space-y-4">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Kirim Link Reset
                    </button>
                    
                    <a href="{{ route($loginRoute) }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
