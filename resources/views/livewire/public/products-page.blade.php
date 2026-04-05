@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">


    @if($showFilters)
    <div class="relative bg-white rounded-2xl border border-gray-200 shadow-sm p-5 md:p-8 overflow-hidden z-10">
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl -z-10"></div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pencarian Produk</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-hover:text-primary transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ketik nama produk untuk mencari..." class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl py-3 pl-11 pr-4 outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-400">
                </div>
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-layer-group text-xs"></i>
                    </div>
                    <select wire:model.live="categorySlug" class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl py-3 pl-11 pr-10 outline-none transition-all duration-300 text-sm font-medium appearance-none relative z-0">
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
                <button wire:click="$refresh" class="w-full bg-primary text-white font-black py-3 px-6 rounded-xl hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-sliders-h text-xs"></i>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @forelse($products as $product)
            @php
                $productDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                    ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                    : 0;
            @endphp
            <div class="bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative">
                <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-3 relative">
                    @if($product->primaryImage?->image)
                        <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                    @endif

                    @if($productDiscount > 0)
                        <div class="absolute top-2 left-2 bg-rose-500 text-white text-[10px] font-black px-2 py-1 rounded-lg">
                            -{{ $productDiscount }}%
                        </div>
                    @endif

                    <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-[#00A859] text-white px-2 py-1 rounded-lg shadow-sm border border-white/20">
                        <i class="fas fa-truck-fast text-[10px]"></i>
                        <span class="text-[8px] font-black uppercase leading-none mt-0.5">Gratis Ongkir</span>
                    </div>
                </a>

                <div class="flex flex-col flex-1 px-1">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 hover:text-primary transition-colors mb-2 min-h-[2.5rem]">
                        {{ $product->name }}
                    </a>

                    <div class="flex items-center gap-1.5 mb-2 overflow-hidden flex-wrap">
                        <span class="text-sm md:text-base font-bold text-primary shrink-0"><span class="text-[10px] font-medium mr-0.5">Rp</span>{{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="text-[10px] text-gray-400 line-through truncate">{{ number_format($product->original_price, 0, ',', '.') }}</span>
                            <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded shrink-0">{{ $productDiscount }}%</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 text-[10px] font-medium mb-3">
                        <span class="flex items-center gap-1"><i class="fas fa-star text-amber-400"></i> <span class="text-gray-700">{{ number_format((float) $product->rating_avg, 1) }}</span></span>
                        <span class="text-gray-300">|</span>
                        <span class="text-gray-500">Terjual {{ $compactViews($product->sold_count) }}</span>
                    </div>

                    <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-1.5">
                        <div class="w-4 h-4 rounded bg-blue-500 flex items-center justify-center text-white text-[8px]">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 truncate">{{ $product->category?->name ?? 'Kataloque Official' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fas fa-box-open text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Kami tidak dapat menemukan produk yang sesuai dengan pencarian atau filter Anda.</p>
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
