@extends('layouts.auth')

@section('title', 'Login Admin - Kataloque')

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="relative max-w-lg mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Admin Portal</h1>
            <p class="mt-3 text-sm text-gray-500 font-medium">
                Silakan masuk untuk mengelola katalog produk, kategori, dan konten portal Anda.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 relative z-10">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email Admin</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-envelope text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-500 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 0.75rem 0.75rem 44px;"
                            placeholder="admin@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    </div>
                    <div class="relative group" x-data="{ showPassword: false }">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" style="width: 44px; justify-content: center;">
                            <i class="fas fa-lock text-sm text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder:text-gray-500 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            style="padding: 0.75rem 44px 0.75rem 44px;"
                            placeholder="Masukkan password">
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

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between gap-4">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Ingat sesi saya
                    </label>
                    @if (Route::has('admin.password.request'))
                        <a href="{{ route('admin.password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-primary-dark transition shadow-lg shadow-primary/20 hover:-translate-y-0.5">
                    <i class="fas fa-right-to-bracket text-[13px]"></i>
                    Masuk
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
