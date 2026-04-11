@extends('admin.layouts.app')

@section('title', 'Edit Kategori Blog')
@section('header', 'Edit Kategori Blog')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.blog-kategori.index') }}" class="hover:text-blue-600 transition-colors">Kategori Blog</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Edit Kategori</span>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-500 truncate max-w-[200px]">{{ $blog_kategori->name }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.blog-kategori.update', $blog_kategori) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden" x-data="{ name: '{{ old('name', $blog_kategori->name) }}', slug: '{{ old('slug', $blog_kategori->slug) }}' }">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Detail Kategori</h3>
                <span class="text-xs font-mono text-gray-400">#{{ $blog_kategori->id }}</span>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Kategori</label>
                    <input type="text" name="name" x-model="name" required
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                    <input type="text" name="slug" x-model="slug" placeholder="teknologi"
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-xs font-mono text-blue-600">
                    <p class="mt-1 text-[10px] text-gray-400 italic font-medium">* Kosongkan untuk generate otomatis dari nama jika diubah.</p>
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
                <a href="{{ route('admin.blog-kategori.index') }}" class="flex-1 sm:flex-none px-4 sm:px-8 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none px-8 sm:px-14 py-2.5 rounded-xl bg-blue-600 text-white text-xs sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
