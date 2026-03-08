@extends('layouts.auth')

@section('title', 'Reset Password Admin - Kataloque')

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-blue-100 via-cyan-100 to-indigo-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">Reset Password</h1>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                Buat kata sandi baru yang kuat untuk mengamankan akun administrator Anda.
            </p>
        </div>

        <div class="bg-white/40 backdrop-blur-xl rounded-3xl border border-white/40 shadow-2xl shadow-gray-200/40 p-6 sm:p-10 relative z-10">
            <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email Admin</label>
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
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
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
                        <input id="password_confirmation" name="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required autocomplete="new-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="Ulangi password baru">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi' : 'Tampilkan konfirmasi'">
                            <i class="fas text-xs" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                    <i class="fas fa-rotate-right text-xs"></i>
                    Perbarui Password Admin
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
