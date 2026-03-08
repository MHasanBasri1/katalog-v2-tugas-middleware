@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="relative bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8 overflow-hidden z-10 mb-8">
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl -z-10"></div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-white shadow-lg shadow-primary/30">
                        <i class="fas fa-tag"></i>
                    </span>
                    Kategori: {{ $category->name }}
                </h1>
                <p class="text-base text-gray-500 mt-2 max-w-2xl">{{ $category->description ?: 'Daftar produk eksklusif berdasarkan kategori terpilih.' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kategori') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold border border-primary/20 bg-primary/10 text-primary-dark hover:bg-primary hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-layer-group"></i> Semua Kategori
                </a>
                <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 shadow-sm">
                    <i class="fas fa-box-open"></i> Semua Produk
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-gray-100 p-5 md:p-6 mb-6 shadow-sm">
        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pencarian Fleksibel</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari produk pada kategori ini..." class="w-full bg-gray-50/80 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl py-3 pl-11 pr-4 outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-400">
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @forelse($products as $product)
            @php
                $categoryDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
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

                    @if($categoryDiscount > 0)
                        <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded-xl shadow-md shadow-rose-500/20 uppercase tracking-wide">
                            -{{ $categoryDiscount }}%
                        </span>
                    @endif

                    <!-- Favorite Button (Hover Content) -->
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10" wire:click.prevent="toggleFavorite({{ $product->id }})">
                        <button class="w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors duration-300 text-gray-600 {{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'text-rose-500' : '' }}">
                            <i class="{{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'fas' : 'far' }} fa-heart text-[13px] md:text-sm"></i>
                        </button>
                    </div>
                </a>
                
                <div class="px-1 flex flex-col flex-1">
                    <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $category->name }}</span>
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
                <p class="text-gray-500 max-w-md mx-auto">Kami tidak dapat menemukan produk yang sesuai pada kategori ini.</p>
                @if($search)
                    <button wire:click="$set('search', '')" class="mt-6 px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-md shadow-primary/20">
                        Hapus Filter Pencarian
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="pt-2">
            {{ $products->onEachSide(1)->links() }}
        </div>
    @endif
</div>
