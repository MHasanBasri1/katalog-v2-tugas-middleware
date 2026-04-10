@extends('admin.layouts.app')

@section('title', 'Profil Admin')
@section('header', 'Profil Admin')

@section('content')
    <div class="space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif
        @if (session('status_password'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status_password') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <section class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 md:p-6">
                <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">Informasi Profil</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui nama dan email akun admin.</p>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('name', 'profileUpdate')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('email', 'profileUpdate')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        Simpan Profil
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 md:p-6" x-data="{ showCurrentPassword: false, showNewPassword: false, showConfirmPassword: false }">
                <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">Ubah Password</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gunakan password minimal 8 karakter.</p>

                <form method="POST" action="{{ route('admin.profile.password.update') }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password Saat Ini</label>
                        <div class="relative">
                            <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" required class="w-full rounded-xl border-gray-300 pr-11 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-blue-600" :aria-label="showCurrentPassword ? 'Sembunyikan password' : 'Tampilkan password'" :title="showCurrentPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i class="ti" :class="showCurrentPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                        @error('current_password', 'passwordUpdate')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                        <div class="relative">
                            <input :type="showNewPassword ? 'text' : 'password'" name="password" required class="w-full rounded-xl border-gray-300 pr-11 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-blue-600" :aria-label="showNewPassword ? 'Sembunyikan password' : 'Tampilkan password'" :title="showNewPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i class="ti" :class="showNewPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                        @error('password', 'passwordUpdate')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required class="w-full rounded-xl border-gray-300 pr-11 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-blue-600" :aria-label="showConfirmPassword ? 'Sembunyikan password' : 'Tampilkan password'" :title="showConfirmPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i class="ti" :class="showConfirmPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        Simpan Password
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection
