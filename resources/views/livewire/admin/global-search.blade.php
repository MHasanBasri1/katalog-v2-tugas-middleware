<div class="flex-1 max-w-xl ml-2 relative" x-data="{ open: false }" @click.outside="open = false">
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
            <i class="ti ti-search text-base"></i>
        </div>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            @focus="open = true"
            placeholder="Cari menu, produk, atau data..." 
            class="w-full bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-400 shadow-sm pr-11"
            style="padding: 0.65rem 3rem 0.65rem 44px;"
        >

        {{-- Clear Button --}}
        <template x-if="$wire.search.length > 0">
            <button 
                type="button"
                @click="$wire.set('search', ''); open = false"
                class="absolute right-0 top-0 bottom-0 px-4 text-gray-400 hover:text-rose-500 transition-colors"
                aria-label="Hapus Pencarian"
            >
                <i class="ti ti-circle-x text-lg"></i>
            </button>
        </template>
        
        {{-- Search Results Dropdown --}}
        <div 
            x-show="open && $wire.search.length >= 2" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute top-full left-0 right-0 mt-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-[100]"
        >
            <div class="max-h-[450px] overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                
                {{-- Products Section --}}
                @if(count($products) > 0)
                    <div class="bg-gray-50/50 dark:bg-gray-800/20 px-4 py-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</span>
                    </div>
                    @foreach($products as $product)
                        <a href="{{ route('admin.produk.edit', $product) }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                            <p class="font-bold text-gray-900 dark:text-white truncate leading-none text-sm group-hover:text-blue-600 transition-colors">{{ $product->name }}</p>
                            <p class="text-[10px] text-gray-500 mt-1">{{ $product->category?->name ?? 'Tanpa Kategori' }} &bull; Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                @endif

                {{-- Categories Section --}}
                @if(count($categories) > 0)
                    <div class="bg-gray-50/50 dark:bg-gray-800/20 px-4 py-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori</span>
                    </div>
                    @foreach($categories as $category)
                        <a href="{{ route('admin.kategori.edit', $category) }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                            <p class="font-bold text-gray-900 dark:text-white leading-none text-sm group-hover:text-blue-600 transition-colors">{{ $category->name }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 text-xs">Manajemen kategori</p>
                        </a>
                    @endforeach
                @endif

                {{-- Blogs Section --}}
                @if(count($blogs) > 0)
                    <div class="bg-gray-50/50 dark:bg-gray-800/20 px-4 py-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Artikel Blog</span>
                    </div>
                    @foreach($blogs as $blog)
                        <a href="{{ route('admin.blog.edit', $blog) }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                            <p class="font-bold text-gray-900 dark:text-white leading-none text-sm group-hover:text-blue-600 transition-colors">{{ $blog->title }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 text-xs">Artikel blog</p>
                        </a>
                    @endforeach
                @endif

                @if(count($products) == 0 && count($categories) == 0 && count($blogs) == 0)
                    <div class="p-8 text-center text-xs">
                        <p class="text-sm text-gray-500 font-medium italic">Tidak ada hasil ditemukan untuk "<span class="text-blue-600" x-text="$wire.search"></span>"</p>
                    </div>
                @endif
            </div>
            
            {{-- Quick Links when searching --}}
            <div class="p-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hasil Pencarian</span>
                <div class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[9px] font-bold text-gray-400 shadow-sm">ESC</kbd>
                    <span class="text-[9px] text-gray-400 font-medium">untuk tutup</span>
                </div>
            </div>
        </div>
        
        {{-- Default Quick Access Dropdown when input is focused but search is empty --}}
        <div 
            x-show="open && $wire.search.length < 2" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute top-full left-0 right-0 mt-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-[100]"
        >
            <div class="p-4 border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akses Cepat</span>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                <a href="{{ route('admin.produk.index') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                    <p class="font-bold text-gray-900 dark:text-white leading-none text-sm group-hover:text-blue-600 transition-colors">Daftar Produk</p>
                    <p class="text-[10px] text-gray-400 mt-1">Lihat semua katalog</p>
                </a>
                <a href="{{ route('admin.user.index') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                    <p class="font-bold text-gray-900 dark:text-white leading-none text-sm group-hover:text-blue-600 transition-colors">Manajemen User</p>
                    <p class="text-[10px] text-gray-400 mt-1">Kelola akun administrator & tim</p>
                </a>
            </div>
        </div>
    </div>
</div>
