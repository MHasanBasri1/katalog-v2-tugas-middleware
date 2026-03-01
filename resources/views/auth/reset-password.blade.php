@extends('frontend.layouts.app')

@php
    $isAdmin = $isAdmin ?? false;
    $storeRoute = $isAdmin ? 'admin.password.store' : 'password.store';
    $accountLabel = $isAdmin ? 'Akun Admin' : 'Akun Pengguna';
    $title = $isAdmin ? 'Reset Password Admin - VISTORA' : 'Reset Password - VISTORA';
    $description = $isAdmin
        ? 'Atur ulang password akun admin VISTORA Anda.'
        : 'Atur ulang password akun VISTORA Anda.';
@endphp

@section('title', $title)
@section('meta_description', $description)
@section('canonical', url()->current())
@section('og_url', url()->current())

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-cyan-100 via-blue-100 to-indigo-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative grid gap-5 md:grid-cols-[1.05fr_1.2fr] items-stretch">
        <div class="rounded-3xl border border-cyan-100 bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 p-6 sm:p-7 text-white shadow-lg shadow-blue-200/70">
            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-cyan-50/90">{{ $accountLabel }}</p>
            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Reset Password</h1>
            <p class="mt-3 text-sm text-blue-50/95 leading-relaxed">
                Buat password baru untuk melanjutkan akses ke akun Anda.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/60 p-6 sm:p-8">
            <form method="POST" action="{{ route($storeRoute) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password Baru</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'">
                            <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ showPasswordConfirmation: false }">
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-check-double text-xs"></i>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required autocomplete="new-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password baru' : 'Tampilkan konfirmasi password baru'">
                            <i class="fas text-xs" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                    <i class="fas fa-rotate-right text-xs"></i>
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
