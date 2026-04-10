@extends('admin.layouts.app')

@section('title', 'Edit Halaman')
@section('header', 'Edit Halaman')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.halaman-statis.index') }}" class="hover:text-blue-600 transition-colors">Halaman Statis</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Edit Halaman</span>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-500 truncate max-w-[200px]">{{ $page->title }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.halaman-statis.update', $page) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden" x-data="{ title: '{{ old('title', $page->title) }}', slug: '{{ old('slug', $page->slug) }}' }">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Konten Halaman</h3>
                        <span class="text-xs font-mono text-gray-400">#{{ $page->id }}</span>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Halaman</label>
                            <input type="text" name="title" x-model="title" required
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            @error('title') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest text-center">Permalink (Slug)</label>
                            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl font-mono text-[10px]">
                                <span class="text-gray-400">{{ url('/') }}/</span>
                                <input type="text" name="slug" x-model="slug" required
                                    class="flex-1 bg-transparent border-none p-0 focus:ring-0 text-blue-600 overflow-hidden text-ellipsis">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Ringkasan (Excerpt)</label>
                            <textarea name="excerpt" rows="2"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('excerpt', $page->excerpt) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Konten Utama</label>
                            <textarea name="content" rows="15" required
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium font-serif leading-relaxed">{{ old('content', $page->content) }}</textarea>
                            @error('content') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Settings -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Publishing</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status</label>
                            <select name="is_published" required 
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                                <option value="1" @selected(old('is_published', $page->is_published) == '1')>Terbitkan (Published)</option>
                                <option value="0" @selected(old('is_published', $page->is_published) == '0')>Simpan Draft</option>
                            </select>
                        </div>

                        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                <span>Terakhir Update</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                                {{ $page->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
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
                <a href="{{ route('admin.halaman-statis.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
