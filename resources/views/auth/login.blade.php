@extends('layouts.auth')

@section('title', 'Login - VISTORA Admin')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center p-6 bg-gray-50 dark:bg-gray-950 relative" x-data>
    <div class="mb-8 flex flex-col items-center gap-4">
        <a href="{{ route('home') }}">
            <x-logo :with-text="false" size="md" />
        </a>
        <h1 class="text-xl font-black italic text-blue-600 tracking-tight">
            VISTORA
            <span class="not-italic font-bold text-gray-900 dark:text-white">Admin</span>
        </h1>
    </div>

    <div class="w-full max-w-md">
        <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
            <div class="space-y-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Selamat Datang Kembali</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Silakan masuk untuk mengakses akun Anda.</p>
                    </div>
                    <button
                        type="button"
                        @click="$store.theme.toggle()"
                        class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-all shadow-sm active:scale-95"
                        title="Toggle theme"
                    >
                        <i class="ti text-lg" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
                    </button>
                </div>

                <x-auth-session-status class="mb-2 text-sm text-green-700 dark:text-green-300" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <x-input
                        label="Email"
                        name="email"
                        type="email"
                        icon="mail"
                        placeholder="yourname@example.com"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <div class="space-y-1">
                        <x-input
                            label="Password"
                            name="password"
                            type="password"
                            icon="lock"
                            placeholder="........"
                            required
                            autocomplete="current-password"
                        />
                        @if (Route::has('password.request'))
                            <div class="flex justify-end">
                                <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 uppercase tracking-widest transition-colors">
                                    Lupa Password?
                                </a>
                            </div>
                        @endif
                    </div>

                    <x-checkbox name="remember" label="Ingat Sesi Saya" />

                    <div class="space-y-4 pt-2">
                        <x-button type="submit" variant="primary" class="w-full shadow-indigo-500/20">
                            Log in
                        </x-button>
                    </div>
                </form>
            </div>
        </x-card>

        <p class="text-center mt-8 text-xs text-gray-400 font-semibold uppercase tracking-widest opacity-60">
            &copy; {{ date('Y') }} VISTORA. All rights reserved.
        </p>
    </div>
</div>
@endsection
