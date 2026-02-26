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

    <div class="bg-white rounded-2xl border border-gray-100 p-4 md:p-5 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Produk</label>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ketik nama produk..." class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-xl py-2.5 px-4 outline-none transition text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
            <select wire:model.live="categorySlug" class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-xl py-2.5 px-3 outline-none transition text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-3 flex flex-wrap gap-2 pt-1">
            <button wire:click="clearFilters" class="px-3 py-1.5 text-xs md:text-sm font-semibold rounded-full border {{ $selectedCategory || $search !== '' ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-200 text-blue-700 bg-white' }} hover:bg-blue-600 hover:text-white transition">
                Semua
            </button>
            @foreach($categories->take(6) as $category)
                <button wire:click="$set('categorySlug', '{{ $category->slug }}')" class="px-3 py-1.5 text-xs md:text-sm font-semibold rounded-full border {{ $selectedCategory && $selectedCategory->slug === $category->slug ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-200 text-blue-700 bg-white' }} hover:bg-blue-600 hover:text-white transition">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                    @if($product->primaryImage?->image)
                        <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                    @endif
                    <span class="absolute top-2 right-2 bg-white/95 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-2">
                        <span class="inline-flex items-center gap-1"><i class="far fa-eye"></i> {{ $compactViews($product->view_count) }}</span>
                        <span class="text-gray-400">|</span>
                        <span class="inline-flex items-center gap-1"><i class="fas fa-bag-shopping text-[10px]"></i> {{ number_format($product->sold_count) }}</span>
                    </span>
                </a>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-sm md:text-xs mb-2">
                    <span class="bg-primary-light text-primary-dark text-[10px] md:text-xs px-2 py-1 rounded-full font-semibold truncate max-w-full md:max-w-[55%]">{{ $product->category?->name }}</span>
                    <span class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-full border border-blue-100 w-fit">
                        <i class="fas fa-star text-blue-500"></i> {{ number_format((float) $product->rating_avg, 1) }}
                    </span>
                </div>
                <h3 class="font-bold text-gray-800 text-sm md:text-lg leading-snug mb-1.5 min-h-[2.5rem] break-words">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="hover:text-primary transition">{{ $product->name }}</a>
                </h3>
                <div class="mb-3">
                    @if($product->original_price)
                        <p class="text-gray-400 line-through text-xs md:text-sm">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
                    @endif
                    <p class="text-primary font-black text-base md:text-2xl break-words">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                </div>
                <div class="mt-auto flex flex-col sm:flex-row gap-2">
                    <button
                        type="button"
                        wire:click="toggleWishlist({{ $product->id }})"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-xs md:text-sm font-semibold transition {{ in_array($product->id, $wishlistedProductIds, true) ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-100 bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
                    >
                        <i class="{{ in_array($product->id, $wishlistedProductIds, true) ? 'fas' : 'far' }} fa-heart"></i>
                        Wishlist
                    </button>
                    <a href="{{ route('produk.detail', $product->slug) }}" class="flex-1 text-center bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-xl py-2.5 md:py-3 text-xs md:text-sm hover:from-blue-600 hover:to-blue-800 transition">Detail Produk</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-sm text-gray-500">Produk tidak ditemukan.</div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="flex justify-center items-center gap-2 pt-2">
            <a href="{{ $products->previousPageUrl() ?: '#' }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition {{ $products->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">Prev</a>

            @foreach($products->getUrlRange(max(1, $products->currentPage() - 1), min($products->lastPage(), $products->currentPage() + 1)) as $page => $url)
                <a href="{{ $url }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border text-sm font-semibold transition {{ $page === $products->currentPage() ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-200 text-blue-700 bg-white hover:bg-blue-50' }}">{{ $page }}</a>
            @endforeach

            <a href="{{ $products->nextPageUrl() ?: '#' }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition {{ $products->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">Next</a>
        </div>
    @endif
</div>
