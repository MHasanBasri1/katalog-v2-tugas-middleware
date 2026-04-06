@extends('frontend.layouts.app')

@section('title', 'Daftar - Kataloque')
@section('meta_description', 'Daftar akun Kataloque untuk menyimpan favorit dan mengelola profil pengguna.')
@section('canonical', route('user.register'))
@section('og_url', route('user.register'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Daftar Akun</h1>
            <p class="mt-3 text-sm text-gray-500 font-medium">
                Simpan favorit dan akses fitur personal lainnya.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 relative z-10">
            <form method="POST" action="{{ route('user.register.store') }}" class="space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-700">Nama</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-user text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 0.75rem 0.75rem 44px;"
                            placeholder="Nama lengkap">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-envelope text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 0.75rem 0.75rem 44px;"
                            placeholder="yourname@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div x-data="{ showPassword: false }">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-lock text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 44px 0.75rem 44px;"
                            placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-blue-600 transition"
                            style="width: 44px; justify-content: center;"
                            :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                            <i class="fas text-[13px]" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div x-data="{ showPasswordConfirmation: false }">
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-check-double text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 44px 0.75rem 44px;"
                            placeholder="Ulangi password">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-blue-600 transition"
                            style="width: 44px; justify-content: center;"
                            :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password'">
                            <i class="fas text-[13px]" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-primary-dark transition shadow-lg shadow-primary/20 hover:-translate-y-0.5">
                    <i class="fas fa-user-plus text-[13px]"></i>
                    Daftar
                </button>

                {{-- Divider --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">atau</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>

                {{-- Google --}}
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
