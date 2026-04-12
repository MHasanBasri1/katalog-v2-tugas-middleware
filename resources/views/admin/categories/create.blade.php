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
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Elektronik, Audio, dll" x-ref="nameInput"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="elektronik-audio"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div x-data="{ 
                    icon: '{{ old('icon', 'fa-layer-group') }}',
                    textColor: '{{ old('text_color', 'text-blue-600') }}',
                    bgColor: '{{ old('color', 'bg-blue-50') }}',
                    search: '',
                    icons: [
                        'fa-layer-group', 'fa-laptop', 'fa-mobile-screen', 'fa-tablet-screen-button', 'fa-desktop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-video', 'fa-gamepad', 'fa-mouse', 'fa-keyboard', 'fa-microchip', 'fa-plug', 'fa-bolt', 'fa-battery-full',
                        'fa-shirt', 'fa-socks', 'fa-glasses', 'fa-hat-cowboy', 'fa-shoe-prints', 'fa-bag-shopping', 'fa-suitcase', 'fa-gem', 'fa-watch', 'fa-tags', 'fa-tag',
                        'fa-utensils', 'fa-bowl-food', 'fa-burger', 'fa-pizza-slice', 'fa-mug-hot', 'fa-wine-glass', 'fa-ice-cream', 'fa-apple-whole', 'fa-carrot', 'fa-bread-slice', 'fa-egg', 'fa-fish',
                        'fa-house', 'fa-couch', 'fa-bed', 'fa-bath', 'fa-toilet', 'fa-shower', 'fa-lightbulb', 'fa-door-open', 'fa-key', 'fa-lock', 'fa-broom', 'fa-bucket', 'fa-screwdriver-wrench', 'fa-hammer', 'fa-paint-roller',
                        'fa-car', 'fa-motorcycle', 'fa-bicycle', 'fa-truck', 'fa-bus', 'fa-plane', 'fa-ship', 'fa-train', 'fa-gas-pump', 'fa-map-pin', 'fa-compass',
                        'fa-heart', 'fa-star', 'fa-gift', 'fa-balloon', 'fa-cake-candles', 'fa-crown', 'fa-medal', 'fa-trophy', 'fa-basketball', 'fa-football', 'fa-volleyball', 'fa-table-tennis-paddle-ball',
                        'fa-briefcase-medical', 'fa-stethoscope', 'fa-pills', 'fa-hospital', 'fa-user-doctor', 'fa-mask-face', 'fa-bandage',
                        'fa-book', 'fa-graduation-cap', 'fa-pencil', 'fa-pen-nib', 'fa-marker', 'fa-compass-drafting', 'fa-microscope', 'fa-flask', 'fa-brain',
                        'fa-music', 'fa-guitar', 'fa-microphone', 'fa-drum', 'fa-trumpet', 'fa-compact-disc', 'fa-film', 'fa-clapperboard',
                        'fa-paw', 'fa-dog', 'fa-cat', 'fa-fish-fins', 'fa-cow', 'fa-horse', 'fa-leaf', 'fa-tree', 'fa-seedling', 'fa-cloud-sun', 'fa-snowflake', 'fa-umbrella',
                        'fa-user', 'fa-users', 'fa-address-card', 'fa-id-card', 'fa-building', 'fa-store', 'fa-shop', 'fa-cart-shopping', 'fa-money-bill-wave', 'fa-credit-card', 'fa-wallet',
                        'fa-phone', 'fa-envelope', 'fa-comment', 'fa-paper-plane', 'fa-share-nodes', 'fa-magnifying-glass', 'fa-gear', 'fa-trash', 'fa-check', 'fa-xmark'
                    ],
                    get filteredIcons() {
                        if (!this.search) return this.icons;
                        return this.icons.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
                    }
                }" class="space-y-6">
                    <!-- Visual Picker Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Left: Selection -->
                        <div class="space-y-6">
                            <!-- Icon Selection -->
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-4 bg-blue-600 rounded-full"></div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Pilih Icon Kategori</label>
                                    </div>
                                    <div class="relative w-full sm:w-56">
                                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="search" placeholder="Cari icon (misal: laptop, car)..." 
                                            class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl pl-9 pr-3 py-2 text-[11px] font-bold outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-600 transition-all duration-300">
                                    </div>
                                </div>

                                <div class="grid grid-cols-6 sm:grid-cols-8 gap-2.5 p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 max-h-64 overflow-y-auto custom-scrollbar shadow-inner">
                                    <template x-for="i in filteredIcons" :key="i">
                                        <button type="button" @click="icon = i" 
                                            :title="i.replace('fa-', '').replace('-', ' ')"
                                            class="w-full aspect-square rounded-xl flex items-center justify-center text-sm transition-all duration-300 border-2 group relative"
                                            :class="icon === i ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200 scale-95' : 'bg-gray-50 dark:bg-gray-800 border-transparent text-gray-400 hover:border-blue-600/30 hover:text-blue-600 hover:bg-blue-50/50'">
                                            <i class="fas" :class="i"></i>
                                            <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-[9px] font-bold rounded shadow-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50" x-text="i.replace('fa-', '').replace('-', ' ')"></span>
                                        </button>
                                    </template>
                                    <template x-if="filteredIcons.length === 0">
                                        <div class="col-span-full py-10 text-center">
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 text-gray-400 mb-3">
                                                <i class="ti ti-search-off text-2xl"></i>
                                            </div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Icon tidak ditemukan<br><span class="opacity-50 font-medium">Coba kata kunci lain</span></p>
                                        </div>
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
