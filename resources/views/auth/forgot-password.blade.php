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
    
    $gradientColors = $isAdmin 
        ? 'from-indigo-100 via-blue-100 to-cyan-100'
        : 'from-blue-100 via-cyan-100 to-indigo-100';
@endphp

@section('title', $title)
@section('meta_description', $description)
@section('canonical', route($requestRoute))
@section('og_url', route($requestRoute))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Lupa Password?</h1>
            <p class="mt-3 text-sm text-gray-500 font-medium">
                Masukkan email {{ $isAdmin ? 'admin' : 'terdaftar' }} Anda. Kami akan mengirimkan link untuk mengatur ulang password Anda.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 relative z-10">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route($emailRoute) }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-envelope text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-500 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 0.75rem 0.75rem 44px;"
                            placeholder="yourname@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="space-y-4">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-primary-dark transition shadow-lg shadow-primary/20 hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane text-[13px]"></i>
                        Kirim Link Reset
                    </button>
                    
                    <a href="{{ route($loginRoute) }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left text-[13px]"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
