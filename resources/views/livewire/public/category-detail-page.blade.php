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

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
        @forelse($products as $product)
            @php
                $categoryDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                    ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                    : 0;
            @endphp
            <div class="bg-white rounded-xl p-2.5 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                    @if($product->primaryImage?->image)
                        <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                    @endif
                    <span class="absolute top-2 left-2 bg-rose-500 text-white text-[9px] md:text-[10px] font-bold px-2 py-1 rounded-full tracking-wide">
                        {{ $categoryDiscount }}%
                    </span>
                </a>
                <h3 class="font-medium text-gray-800 text-sm md:text-[15px] leading-snug mb-1.5 break-words">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="hover:text-primary transition">{{ $product->name }}</a>
                </h3>
                <div>
                    <p class="text-primary font-extrabold text-[15px] md:text-lg leading-tight break-words">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    @if($product->original_price)
                        <p class="text-gray-400 line-through text-[10px] md:text-xs mt-0.5">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
                    @endif
                </div>
                <div class="mt-auto pt-1.5 flex items-end justify-between gap-2 text-[11px] md:text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1 text-amber-500 font-semibold">
                        <i class="fas fa-star text-[10px]"></i> {{ number_format((float) $product->rating_avg, 1) }}
                    </span>
                    <span class="inline-flex items-center gap-1 whitespace-nowrap">
                        <i class="fas fa-bag-shopping text-[10px]"></i> {{ $compactViews($product->sold_count) }}+
                    </span>
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
