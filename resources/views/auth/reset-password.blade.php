@extends('frontend.layouts.app')

@php
    $isAdmin = $isAdmin ?? false;
    $storeRoute = $isAdmin ? 'admin.password.store' : 'password.store';
    $accountLabel = $isAdmin ? 'Akun Admin' : 'Akun Pengguna';
    $title = $isAdmin ? 'Reset Password Admin - Kataloque' : 'Reset Password - Kataloque';
    $description = $isAdmin
        ? 'Atur ulang password akun admin Kataloque Anda.'
        : 'Atur ulang password akun Kataloque Anda.';

    $gradientColors = $isAdmin 
        ? 'from-indigo-100 via-blue-100 to-cyan-100'
        : 'from-blue-100 via-cyan-100 to-indigo-100';
@endphp

@section('title', $title)
@section('meta_description', $description)
@section('canonical', url()->current())
@section('og_url', url()->current())

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <!-- Gradient Blur Background -->
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r {{ $gradientColors }} blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">Reset Password</h1>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                Silakan masukkan password baru Anda untuk mengamankan kembali akun {{ $isAdmin ? 'admin' : '' }}.
            </p>
        </div>

        <div class="bg-white/40 backdrop-blur-xl rounded-3xl border border-white/40 shadow-2xl shadow-gray-200/40 p-6 sm:p-10 relative z-10">
            <form method="POST" action="{{ route($storeRoute) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-envelope text-sm text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                            style="padding: 0.75rem 0.75rem 0.75rem 44px;">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div x-data="{ showPassword: false }">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password Baru</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-lock text-sm text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                            style="padding: 0.75rem 44px 0.75rem 44px;"
                            placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-blue-600 transition"
                            style="width: 44px; justify-content: center;"
                            :aria-label="showPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'">
                            <i class="fas text-[13px]" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div x-data="{ showPasswordConfirmation: false }">
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-check-double text-sm text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                            style="padding: 0.75rem 44px 0.75rem 44px;"
                            placeholder="Ulangi password baru">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-blue-600 transition"
                            style="width: 44px; justify-content: center;"
                            :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password baru' : 'Tampilkan konfirmasi password baru'">
                            <i class="fas text-[13px]" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 hover:-translate-y-0.5">
                    <i class="fas fa-rotate-right text-[13px]"></i>
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
