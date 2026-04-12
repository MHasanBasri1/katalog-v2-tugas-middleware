@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.kategori.index') }}" class="hover:text-blue-600 transition-colors">Kategori</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Edit Kategori</span>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-500 truncate max-w-[200px]">{{ $category->name }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.kategori.update', $category) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Kategori</h3>
                    <p class="text-sm text-gray-500">Perbarui identitas pengelompokan produk.</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">ID Kategori</span>
                    <span class="text-sm font-mono text-gray-600 dark:text-gray-400">#{{ $category->id }}</span>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required x-ref="nameInput"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div x-data="{ 
                    icon: '{{ old('icon', $category->icon) }}',
                    textColor: '{{ old('text_color', $category->text_color) }}',
                    bgColor: '{{ old('color', $category->color) }}',
                    icons: [
                        'fa-layer-group', 'fa-laptop', 'fa-mobile-screen', 'fa-shirt', 'fa-utensils', 
                        'fa-bag-shopping', 'fa-couch', 'fa-basketball', 'fa-briefcase-medical',
                        'fa-car', 'fa-camera', 'fa-gamepad', 'fa-gift', 'fa-glasses', 'fa-headphones',
                        'fa-house', 'fa-key', 'fa-lightbulb', 'fa-microchip', 'fa-music', 'fa-paw',
                        'fa-plug', 'fa-print', 'fa-scissors', 'fa-shoe-prints', 'fa-tags', 'fa-tv',
                        'fa-watch', 'fa-wrench', 'fa-book', 'fa-heart', 'fa-star', 'fa-gem'
                    ],
                    colors: [
                        { text: 'text-blue-600', bg: 'bg-blue-50', name: 'Biru' },
                        { text: 'text-emerald-600', bg: 'bg-emerald-50', name: 'Hijau' },
                        { text: 'text-rose-600', bg: 'bg-rose-50', name: 'Merah' },
                        { text: 'text-amber-600', bg: 'bg-amber-50', name: 'Kuning' },
                        { text: 'text-violet-600', bg: 'bg-violet-50', name: 'Ungu' },
                        { text: 'text-slate-600', bg: 'bg-slate-50', name: 'Abu-abu' },
                        { text: 'text-pink-600', bg: 'bg-pink-50', name: 'Pink' },
                        { text: 'text-indigo-600', bg: 'bg-indigo-50', name: 'Indigo' }
                    ]
                }" class="space-y-6">
                    <!-- Visual Picker Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Left: Selection -->
                        <div class="space-y-6">
                            <!-- Icon Selection -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">Pilih Icon Kategori</label>
                                <div class="grid grid-cols-6 sm:grid-cols-8 gap-2 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 max-h-48 overflow-y-auto custom-scrollbar">
                                    <template x-for="i in icons" :key="i">
                                        <button type="button" @click="icon = i" 
                                            class="w-full aspect-square rounded-xl flex items-center justify-center text-sm transition-all duration-200 border-2"
                                            :class="icon === i ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white dark:bg-gray-900 border-transparent text-gray-400 hover:border-gray-200 dark:hover:border-gray-700 hover:text-gray-600'">
                                            <i class="fas" :class="i"></i>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="icon" :value="icon">
                            </div>

                            <!-- Color Palette Selection -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">Tema Warna</label>
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="c in colors" :key="c.name">
                                        <button type="button" @click="textColor = c.text; bgColor = c.bg" 
                                            class="p-3 rounded-xl border-2 transition-all duration-200 flex flex-col items-center gap-1 group"
                                            :class="(textColor === c.text && bgColor === c.bg) ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-900/10' : 'border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700'">
                                            <div class="w-6 h-6 rounded-full shadow-inner" :class="c.bg + ' ' + c.text.replace('text-', 'bg-').replace('-600', '-500')"></div>
                                            <span class="text-[9px] font-bold text-gray-400 group-hover:text-gray-600 transition-colors uppercase" x-text="c.name"></span>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="text_color" :value="textColor">
                                <input type="hidden" name="color" :value="bgColor">
                            </div>
                        </div>

                        <!-- Right: Real-time Preview -->
                        <div class="sticky top-0 space-y-4">
                            <label class="block text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">Pratinjau (Preview)</label>
                            <div class="p-8 bg-gray-50/50 dark:bg-gray-800/30 rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center gap-6 min-h-[200px]">
                                <!-- Large Dynamic Badge -->
                                <div class="p-8 rounded-[2rem] shadow-xl transition-all duration-500 transform hover:scale-110 flex flex-col items-center gap-4"
                                    :class="bgColor">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-inner bg-white/40 backdrop-blur-sm" :class="textColor">
                                        <i class="fas" :class="icon"></i>
                                    </div>
                                    <span class="text-sm font-black uppercase tracking-widest" :class="textColor" x-text="$refs.nameInput.value || 'Nama Kategori'"></span>
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Tampilan di Menu & Filter</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Kategori</label>
                    <textarea name="description" rows="5"
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('description', $category->description) }}</textarea>
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
                <a href="{{ route('admin.kategori.index') }}" class="flex-1 sm:flex-none px-4 sm:px-8 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
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
