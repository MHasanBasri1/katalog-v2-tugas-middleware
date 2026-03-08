@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div>
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clock"></i>
                </span>
                Produk Terbaru
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                @if($selectedCategory)
                    Menampilkan produk kategori <span class="font-semibold text-gray-700">{{ $selectedCategory->name }}</span>.
                @else
                    Cari dan filter produk berdasarkan kategori.
                @endif
            </p>
        </div>
    </div>

    <div class="relative bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 md:p-8 overflow-hidden z-10">
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl -z-10"></div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pencarian Produk</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-hover:text-primary transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ketik nama produk untuk mencari..." class="w-full bg-gray-50/80 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-2xl py-3 pl-11 pr-4 outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-400">
                </div>
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-layer-group text-xs"></i>
                    </div>
                    <select wire:model.live="categorySlug" class="w-full bg-gray-50/80 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-2xl py-3 pl-10 pr-10 outline-none transition-all duration-300 text-sm font-medium appearance-none relative z-0">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>
            <div class="md:col-span-1">
                <button wire:click="$refresh" class="w-full bg-primary text-white font-black py-3 px-6 rounded-2xl hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-sliders-h text-xs"></i>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @forelse($products as $product)
            @php
                $productDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                    ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                    : 0;
            @endphp
            <div class="bg-white rounded-3xl p-3 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_10px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative">
                <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-4 relative">
                    @if($product->primaryImage?->image)
                        <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                    @endif
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                    @if($productDiscount > 0)
                        <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded-xl shadow-md shadow-rose-500/20 uppercase tracking-wide">
                            -{{ $productDiscount }}%
                        </span>
                    @endif

                    <!-- Favorite Button (Hover Content) -->
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                        <button 
                            type="button"
                            wire:click.stop.prevent="toggleFavorite({{ $product->id }})" 
                            class="w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors duration-300 text-gray-600 {{ in_array($product->id, $favoritedProductIds ?? []) ? 'text-rose-500' : '' }}"
                        >
                            <i class="{{ in_array($product->id, $favoritedProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-[13px] md:text-sm"></i>
                        </button>
                    </div>
                </a>
                
                <div class="px-1 flex flex-col flex-1">
                    @if($product->category)
                        <a href="javascript:void(0)" wire:click.prevent="$set('categorySlug', '{{ $product->category->slug }}')" class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 hover:text-primary transition-colors w-fit relative z-10">
                            {{ $product->category->name }}
                        </a>
                    @else
                        <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Katalog</span>
                    @endif
                    <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-2 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors pr-6 md:pr-0">
                        <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    
                    <div class="mt-auto">
                        <p class="text-primary font-black text-base md:text-xl leading-tight">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                        @if($product->original_price)
                            <p class="text-gray-400 line-through text-[10px] md:text-sm mt-0.5 decoration-gray-300">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    
                    <div class="pt-2 flex items-center justify-between text-[10px] md:text-xs text-gray-500 font-medium mt-1">
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-star text-amber-400"></i>
                            <span class="text-gray-700">{{ number_format((float) $product->rating_avg, 1) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 opacity-80">
                            <i class="fas fa-shopping-bag text-[10px] text-gray-400"></i>
                            {{ $compactViews($product->sold_count) }} terjual
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm mt-4">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fas fa-box-open text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto">Kami tidak dapat menemukan produk yang sesuai dengan kriteria pencarian atau kategori yang Anda pilih.</p>
                <button wire:click="clearFilters" class="mt-6 px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-md shadow-primary/20">
                    Tampilkan Semua Produk
                </button>
            </div>
        @endforelse
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
