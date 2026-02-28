@extends('frontend.layouts.app')

@section('title', 'Lupa Password - VISTORA')
@section('meta_description', 'Minta link reset password untuk akun VISTORA Anda.')
@section('canonical', route('password.request'))
@section('og_url', route('password.request'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-cyan-100 via-blue-100 to-indigo-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative grid gap-5 md:grid-cols-[1.05fr_1.2fr] items-stretch">
        <div class="rounded-3xl border border-cyan-100 bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 p-6 sm:p-7 text-white shadow-lg shadow-blue-200/70">
            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-cyan-50/90">Akun Pengguna</p>
            <h1 class="mt-3 text-2xl sm:text-3xl font-black leading-tight">Lupa Password</h1>
            <p class="mt-3 text-sm text-blue-50/95 leading-relaxed">
                Masukkan email akun Anda. Kami akan kirim link reset password ke email tersebut.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/60 p-6 sm:p-8">
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-9 pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" placeholder="yourname@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                    <i class="fas fa-paper-plane text-xs"></i>
                    Kirim Link Reset
                </button>
            </form>

            <p class="mt-5 text-sm text-center text-gray-500">
                Sudah ingat password?
                <a href="{{ route('user.login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk di sini</a>
            </p>
        </div>
    </div>
</section>
@endsection
