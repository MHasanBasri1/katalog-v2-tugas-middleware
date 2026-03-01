@extends('frontend.layouts.app')

@section('title', 'Daftar - VISTORA')
@section('meta_description', 'Daftar akun VISTORA untuk menyimpan wishlist dan mengelola profil pengguna.')
@section('canonical', route('user.register'))
@section('og_url', route('user.register'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-cyan-100 via-blue-100 to-indigo-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative grid gap-5 md:grid-cols-[1.1fr_1.25fr] items-stretch">
        <div class="rounded-3xl border border-cyan-100 bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 p-6 sm:p-7 text-white shadow-lg shadow-blue-200/70">
            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-cyan-50/90">Akun Pengguna</p>
            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Buat Akun Baru</h1>
            <p class="mt-3 text-sm text-blue-50/95 leading-relaxed">
                Daftar sekali untuk simpan wishlist, kelola profil, dan akses pengalaman belanja yang lebih personal.
            </p>
            <div class="mt-6 space-y-3 text-sm">
                <div class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2 backdrop-blur-sm">
                    <i class="fas fa-bolt text-xs"></i>
                    Proses registrasi cepat
                </div>
                <div class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2 backdrop-blur-sm">
                    <i class="fas fa-user-shield text-xs"></i>
                    Privasi akun terlindungi
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/60 p-6 sm:p-8">
            <form method="POST" action="{{ route('user.register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-700">Nama</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="Nama lengkap">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="yourname@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
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
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-check-double text-xs"></i>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required autocomplete="new-password" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="Ulangi password">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition" :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password'">
                            <i class="fas text-xs" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                    <i class="fas fa-user-plus text-xs"></i>
                    Daftar
                </button>

                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">atau</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>

                <a href="{{ route('user.google.redirect') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                    <i class="fab fa-google text-[13px] text-rose-500"></i>
                    Daftar dengan Google
                </a>
            </form>

            <p class="mt-5 text-sm text-center text-gray-500">
                Sudah punya akun?
                <a href="{{ route('user.login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk di sini</a>
            </p>
        </div>
    </div>
</section>
@endsection
