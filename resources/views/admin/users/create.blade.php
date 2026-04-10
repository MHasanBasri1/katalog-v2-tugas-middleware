@extends('admin.layouts.app')

@section('title', 'Tambah User')
@section('header', 'Tambah User')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.user.index') }}" class="hover:text-blue-600 transition-colors">User</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah User Baru</span>
    </nav>

    <form method="POST" action="{{ route('admin.user.store') }}" class="space-y-6">
        @csrf
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Identitas User</h3>
                <p class="text-sm text-gray-500">Informasi autentikasi dan profil dasar.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@example.com"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('email') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2" x-data="{ show: false }">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Password</label>
                        <div class="relative group">
                            <input :type="show ? 'text' : 'password'" name="password" required placeholder="minimal 8 karakter"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium pr-12">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center justify-center w-12 text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="ti" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Role</label>
                        <select name="role" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="user" @selected(old('role') == 'user')>User Standar</option>
                            <option value="admin" @selected(old('role') == 'admin')>Administrator</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Akun</label>
                        <select name="is_frozen" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="0" @selected(old('is_frozen') == '0')>Aktif</option>
                            <option value="1" @selected(old('is_frozen') == '1')>Dibekukan (Simpan sebagai Draft)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Alasan Bekukan (Opsional)</label>
                    <input type="text" name="freeze_reason" value="{{ old('freeze_reason') }}" placeholder="Contoh: Spam, Melanggar aturan, dll"
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
            :class="{
                'xl:left-72': $store.sidebar.isExpanded,
                'xl:left-20': !$store.sidebar.isExpanded,
                'left-0': true
            }">
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 px-4">
                <a href="{{ route('admin.user.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center">
                    Batal
                </a>
                <button type="submit" name="action" value="save_and_another" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-sm font-bold hover:bg-blue-50 transition text-center">
                    Simpan & Buat Lagi
                </button>
                <button type="submit" class="w-full sm:w-auto px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Simpan User
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
