@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.kategori.index') }}" class="hover:text-blue-600 transition-colors">Kategori</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Kategori Baru</span>
    </nav>

    <form method="POST" action="{{ route('admin.kategori.store') }}" class="space-y-6">
        @csrf
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Kategori</h3>
                <p class="text-sm text-gray-500">Definisikan pengelompokan produk Anda.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Elektronik, Audio, dll"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Opsional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="elektronik-audio"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Icon (FontAwesome)</label>
                        <input type="text" name="icon" value="{{ old('icon', 'fa-layer-group') }}" placeholder="fa-laptop"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium text-center">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Warna Teks (Tailwind)</label>
                        <input type="text" name="text_color" value="{{ old('text_color', 'text-blue-500') }}" placeholder="text-blue-500"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium text-center">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Warna BG (Tailwind)</label>
                        <input type="text" name="color" value="{{ old('color', 'bg-blue-50') }}" placeholder="bg-blue-50"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium text-center">
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 italic mt-1 text-center bg-gray-50 dark:bg-gray-800/50 py-2 rounded-lg">
                    Preview: <i class="fas fa-info-circle text-blue-500 px-1"></i> Gunakan class dari <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-600 underline">FontAwesome</a> dan <a href="https://tailwindcss.com/docs/customizing-colors" target="_blank" class="text-blue-600 underline">Tailwind Colors</a>.
                </p>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Kategori</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan penggunaan kategori ini..."
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
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
            <div class="flex flex-row items-center justify-end gap-2 sm:gap-3 px-3 sm:px-6">
                <a href="{{ route('admin.kategori.index') }}" class="flex-1 sm:flex-none px-3 sm:px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-[10px] sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" name="action" value="save_and_another" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-[10px] sm:text-sm font-bold hover:bg-blue-50 transition text-center whitespace-nowrap">
                    Simpan & Buat Lagi
                </button>
                <button type="submit" class="flex-1 sm:flex-none px-6 sm:px-10 py-2.5 rounded-xl bg-blue-600 text-white text-[10px] sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
