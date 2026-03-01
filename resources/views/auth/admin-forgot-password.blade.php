@extends('layouts.auth')

@section('title', 'Lupa Password Admin - VISTORA')

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
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Lupa Password Admin</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Masukkan email admin untuk menerima link reset password.</p>
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

                <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Email Admin</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="admin@contoh.com"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full h-12 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-4 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition"
                        >
                        @error('email')
                            <p class="mt-1 text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-button type="submit" variant="primary" class="w-full shadow-indigo-500/20">
                        Kirim Link Reset
                    </x-button>
                </form>

                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    Sudah ingat password?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Kembali ke login admin</a>
                </p>
            </div>
        </x-card>
    </div>
</div>
@endsection
