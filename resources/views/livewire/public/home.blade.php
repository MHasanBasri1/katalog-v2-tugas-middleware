@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-12">
    <div x-data="bannerSlider()" x-init="startAutoSlide()" class="relative rounded-2xl overflow-hidden w-full h-[180px] sm:h-[250px] md:h-[350px] bg-gray-200">
        <div class="flex h-full w-full slide-transition" :style="`transform: translateX(-${currentSlide * 100}%)`">
            <div class="w-full h-full flex-shrink-0 bg-primary flex items-center justify-between px-8 md:px-20 text-white relative">
                <div class="z-10 max-w-md">
                    <h2 class="text-2xl md:text-4xl font-bold mb-2">Pilihan Terbaik Hari Ini</h2>
                    <p class="text-sm md:text-base mb-4 text-blue-100">Temukan produk favorit dengan harga paling menarik</p>
                    <div class="inline-block bg-blue-100 text-blue-900 font-bold px-4 py-2 rounded border-2 border-dashed border-blue-900 tracking-widest">ELEKTRONIK DEAL</div>
                </div>
                <i class="fas fa-plug-circle-bolt text-6xl md:text-9xl text-white/20 absolute right-10 top-1/2 -translate-y-1/2"></i>
            </div>
            <div class="w-full h-full flex-shrink-0 bg-blue-900 flex items-center justify-between px-8 md:px-20 text-white relative">
                <div class="z-10 max-w-md">
                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-mobile-screen text-xl"></i> Smartphone</div>
                    <h2 class="text-2xl md:text-4xl font-bold mb-2">Upgrade ke<br>Flagship Terbaru</h2>
                    <p class="text-sm md:text-base mb-4 text-blue-200">Performa cepat, kamera tajam, baterai tahan lama.</p>
                </div>
                <i class="fas fa-mobile-alt text-6xl md:text-9xl text-white/10 absolute right-10 top-1/2 -translate-y-1/2"></i>
            </div>
            <div class="w-full h-full flex-shrink-0 bg-blue-700 flex items-center justify-between px-8 md:px-20 text-white relative">
                <div class="z-10 max-w-md">
                    <div class="flex items-center gap-2 mb-2"><i class="fas fa-headphones-alt text-xl"></i> Audio Deals</div>
                    <h2 class="text-2xl md:text-4xl font-bold mb-2">Suara Lebih Jernih,<br>Harga Lebih Ringan</h2>
                </div>
                <i class="fas fa-music text-6xl md:text-9xl text-white/10 absolute right-10 top-1/2 -translate-y-1/2"></i>
            </div>
        </div>
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="goToSlide(index)" :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>

    <section class="hidden md:block scroll-mt-40">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-layer-group text-sm"></i>
                </span>
                Kategori Produk
            </h2>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-primary-light text-primary-dark text-sm font-semibold rounded-full border border-primary/20 hover:bg-primary hover:text-white transition">Lihat Semua <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @php
                $categoryIcons = ['fa-mobile-screen', 'fa-laptop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-gamepad', 'fa-basket-shopping', 'fa-couch'];
            @endphp
            @forelse($popularCategories as $category)
                <a href="{{ route('kategori.detail', $category->slug) }}" class="{{ $loop->iteration > 4 ? 'hidden md:flex' : 'flex' }} flex-col items-center justify-center p-4 bg-white border border-gray-100 rounded-xl hover:border-primary transition gap-2 text-center">
                    <i class="fas {{ $categoryIcons[$loop->index % count($categoryIcons)] }} text-2xl text-primary mb-1"></i>
                    <span class="text-sm font-bold text-gray-800">{{ $category->name }}</span>
                    <span class="text-[11px] text-gray-500">{{ $category->active_products_count }} items</span>
                </a>
            @empty
                <div class="col-span-full text-sm text-gray-500">Belum ada kategori aktif.</div>
            @endforelse
        </div>
    </section>

    <section id="flash-sale" class="scroll-mt-40">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bolt text-sm"></i>
                </span>
                Produk Promo
            </h2>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full border border-blue-100 hover:bg-blue-600 hover:text-white transition">Lihat Semua <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            @forelse($flashSaleProducts as $product)
                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute top-2 inset-x-2 flex items-center justify-between gap-2">
                            <span class="bg-blue-600 text-white text-xs md:text-xs font-bold px-2.5 py-1 rounded-md tracking-wide">PROMO</span>
                            <span class="bg-white/95 text-blue-700 text-[10px] md:text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1.5 shadow-sm whitespace-nowrap">
                                <span class="inline-flex items-center gap-1"><i class="far fa-eye"></i> {{ $compactViews($product->view_count) }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="inline-flex items-center gap-1"><i class="fas fa-bag-shopping text-[10px]"></i> {{ number_format($product->sold_count) }}</span>
                            </span>
                        </div>
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
                        <a href="{{ route('produk.detail', $product->slug) }}" class="flex-1 text-center bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-xl py-2.5 md:py-3 text-xs md:text-sm hover:from-blue-600 hover:to-blue-800 transition">Ambil Promo</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-sm text-gray-500">Belum ada produk promo.</div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-chart-line text-sm"></i>
                </span>
                Produk Terlaris
            </h2>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-light text-primary-dark text-sm font-semibold rounded-full border border-primary/20 hover:bg-primary hover:text-white transition">Lihat Semua <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            @forelse($bestSellerProducts as $product)
                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute top-2 inset-x-2 flex items-center justify-between gap-2">
                            <span class="bg-blue-600 text-white text-xs md:text-xs font-bold px-2.5 py-1 rounded-md tracking-wide">POPULAR</span>
                            <span class="bg-white/95 text-blue-700 text-[10px] md:text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1.5 shadow-sm whitespace-nowrap">
                                <span class="inline-flex items-center gap-1"><i class="far fa-eye"></i> {{ $compactViews($product->view_count) }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="inline-flex items-center gap-1"><i class="fas fa-bag-shopping text-[10px]"></i> {{ number_format($product->sold_count) }}</span>
                            </span>
                        </div>
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
                <div class="col-span-full text-sm text-gray-500">Belum ada produk terlaris.</div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clock text-sm"></i>
                </span>
                Produk Terbaru
            </h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-10">
            @forelse($newProducts as $product)
                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute top-2 inset-x-2 flex items-center justify-between gap-2">
                            <span class="bg-blue-600 text-white text-xs md:text-xs font-bold px-2.5 py-1 rounded-md tracking-wide">NEW</span>
                            <span class="bg-white/95 text-blue-700 text-[10px] md:text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1.5 shadow-sm whitespace-nowrap">
                                <span class="inline-flex items-center gap-1"><i class="far fa-eye"></i> {{ $compactViews($product->view_count) }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="inline-flex items-center gap-1"><i class="fas fa-bag-shopping text-[10px]"></i> {{ number_format($product->sold_count) }}</span>
                            </span>
                        </div>
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
                <div class="col-span-full text-sm text-gray-500">Belum ada produk terbaru.</div>
            @endforelse
        </div>

        <div class="flex justify-center items-center gap-2 mt-6">
            <a href="{{ route('katalog') }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition">Prev</a>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-600 bg-blue-600 text-white text-sm font-bold">1</a>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-200 text-blue-700 text-sm font-semibold bg-white hover:bg-blue-50 transition">2</a>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-200 text-blue-700 text-sm font-semibold bg-white hover:bg-blue-50 transition">3</a>
            <a href="{{ route('katalog') }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition">Next</a>
        </div>
    </section>
</div>

@push('scripts')
<script>
function bannerSlider() {
    return {
        currentSlide: 0,
        slides: [0, 1, 2],
        intervalId: null,
        startAutoSlide() {
            this.intervalId = setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            }, 4000);
        },
        goToSlide(index) {
            this.currentSlide = index;
            clearInterval(this.intervalId);
            this.startAutoSlide();
        }
    }
}
</script>
@endpush
