@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-6">


    @if($showFilters)
    <div class="relative bg-white rounded-2xl border border-gray-200 shadow-sm p-5 md:p-8 overflow-hidden z-10">
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl -z-10"></div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pencarian Produk</label>
                <div class="relative group">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-primary transition-colors" style="width: 44px;">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input id="catalogSearchInput" wire:model.live.debounce.300ms="search" type="text" placeholder="Ketik nama produk untuk mencari..."
                        class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500 pr-12"
                        style="padding: 0.75rem 3rem 0.75rem 44px;" aria-label="Cari Produk Lengkap">
                    
                    {{-- Clear Search Button --}}
                    @if($search !== '')
                        <button wire:click="clearSearch"
                            class="absolute right-0 top-0 bottom-0 px-4 text-gray-400 hover:text-rose-500 transition-colors"
                            aria-label="Hapus Pencarian">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    @endif
                </div>
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Kategori</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 z-10" style="width: 44px;">
                        <i class="fas fa-layer-group text-xs"></i>
                    </div>
                    <select id="catalogCategorySelect" wire:model.live="categorySlug"
                        class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                        style="padding: 0.75rem 2.5rem 0.75rem 44px; appearance: none;" aria-label="Pilih Kategori Produk">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center text-gray-400" style="width: 40px;">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>
            <div class="md:col-span-1">
                <button wire:click="$refresh" class="w-full bg-primary text-white font-black py-3 px-6 rounded-xl hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-sliders-h text-xs"></i>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>
    @endif

    <div wire:loading.remove wire:target="search, categorySlug" class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @forelse($products as $product)
            <x-product-card :product="$product" :compactViews="$compactViews" />
        @empty
            <div class="col-span-full py-20 px-4 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-3 font-primary uppercase tracking-tight">Opps! Produk Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8 text-sm md:text-base font-medium leading-relaxed">
                    Kami tidak dapat menemukan produk yang sesuai dengan pencarian atau filter Anda. Silakan coba kata kunci lain.
                </p>
                <button wire:click="clearFilters" class="px-8 py-3 bg-primary text-white font-black rounded-xl hover:bg-primary-dark transition-all duration-300 shadow-lg shadow-primary/20 flex items-center gap-2">
                    <i class="fas fa-undo text-xs"></i>
                    Tampilkan Semua Produk
                </button>
            </div>
        @endforelse
    </div>

    {{-- Skeleton Loading --}}
    <div wire:loading.grid wire:target="search, categorySlug" class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @for($i = 0; $i < 10; $i++)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full animate-pulse transition-all">
                <div class="aspect-square w-full skeleton"></div>
                <div class="p-4 space-y-4 flex-1">
                    <div class="h-3 w-1/3 skeleton rounded-full"></div>
                    <div class="h-5 w-full skeleton rounded-lg"></div>
                    <div class="h-4 w-2/3 skeleton rounded-lg"></div>
                    <div class="pt-4 flex gap-3">
                        <div class="h-10 flex-1 skeleton rounded-xl"></div>
                        <div class="h-10 w-10 skeleton rounded-xl"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    @if($products->hasPages())
        <div class="flex justify-center items-center gap-1.5 md:gap-2 pt-8 flex-wrap">
            <a href="{{ $products->previousPageUrl() ?: '#' }}" class="px-3 md:px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs md:text-sm font-bold bg-white hover:bg-gray-50 hover:text-primary transition-colors shadow-sm {{ $products->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}">
                <i class="fas fa-chevron-left mr-1 text-[10px] md:text-xs"></i> Prev
            </a>

            @foreach($products->getUrlRange(max(1, $products->currentPage() - 1), min($products->lastPage(), $products->currentPage() + 1)) as $page => $url)
                <a href="{{ $url }}" class="w-8 h-8 md:w-10 md:h-10 inline-flex items-center justify-center rounded-xl border text-xs md:text-sm font-bold transition-all shadow-sm {{ $page === $products->currentPage() ? 'border-primary bg-primary text-white shadow-primary/20 scale-105' : 'border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:text-primary hover:-translate-y-0.5' }}">{{ $page }}</a>
            @endforeach

            <a href="{{ $products->nextPageUrl() ?: '#' }}" class="px-3 md:px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs md:text-sm font-bold bg-white hover:bg-gray-50 hover:text-primary transition-colors shadow-sm {{ $products->hasMorePages() ? '' : 'pointer-events-none opacity-40' }}">
                Next <i class="fas fa-chevron-right ml-1 text-[10px] md:text-xs"></i>
            </a>
        </div>
    @endif
</div>
