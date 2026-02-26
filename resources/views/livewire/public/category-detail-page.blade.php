@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-tag"></i>
                </span>
                Kategori: {{ $category->name }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ $category->description ?: 'Daftar produk berdasarkan kategori terpilih.' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kategori') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border border-blue-100 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white transition">
                Semua Kategori
            </a>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border border-blue-100 bg-white text-blue-700 hover:bg-blue-50 transition">
                Semua Produk
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4 md:p-5">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Produk di Kategori Ini</label>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ketik nama produk..." class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-xl py-2.5 px-4 outline-none transition text-sm">
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
                    <span class="bg-primary-light text-primary-dark text-[10px] md:text-xs px-2 py-1 rounded-full font-semibold truncate max-w-full md:max-w-[55%]">{{ $category->name }}</span>
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
            <div class="col-span-full text-sm text-gray-500">Belum ada produk di kategori ini.</div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="pt-2">
            {{ $products->onEachSide(1)->links() }}
        </div>
    @endif
</div>
