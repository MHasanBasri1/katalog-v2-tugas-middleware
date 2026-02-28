@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-12">
    @php
        $bannerItems = $heroBanners->isNotEmpty()
            ? $heroBanners
            : collect([
                (object) [
                    'title' => 'Pilihan Terbaik Hari Ini',
                    'subtitle' => 'Temukan produk favorit dengan harga paling menarik.',
                    'image_url' => 'https://picsum.photos/seed/vistora-banner-fallback-1/1600/700',
                    'cta_label' => 'Belanja Sekarang',
                    'cta_url' => route('katalog'),
                ],
            ]);
    @endphp
    <div x-data="bannerSlider({{ $bannerItems->count() }})" x-init="startAutoSlide()" class="relative rounded-2xl overflow-hidden w-full h-[180px] sm:h-[250px] md:h-[350px] bg-gray-200">
        <div class="flex h-full w-full slide-transition" :style="`transform: translateX(-${currentSlide * 100}%)`">
            @foreach($bannerItems as $banner)
                <div class="w-full h-full flex-shrink-0 relative">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/35 to-black/20"></div>
                    <div class="absolute inset-0 px-6 md:px-12 py-6 md:py-10 flex items-center">
                        <div class="max-w-xl text-white">
                            <h2 class="text-xl sm:text-2xl md:text-4xl font-black leading-tight">{{ $banner->title }}</h2>
                            @if($banner->subtitle)
                                <p class="text-xs sm:text-sm md:text-base mt-2 text-blue-100">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->cta_label && $banner->cta_url)
                                <a href="{{ $banner->cta_url }}" class="mt-4 inline-flex items-center gap-2 bg-white text-blue-700 font-bold px-4 py-2 rounded-full text-xs md:text-sm hover:bg-blue-50 transition">
                                    {{ $banner->cta_label }}
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($bannerItems->count() > 1)
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goToSlide(index)" :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        @endif
    </div>

    <section class="scroll-mt-40" x-data="categoryCarousel()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-layer-group text-sm"></i>
                </span>
                Kategori Produk
            </h2>
            <div class="inline-flex items-center gap-2">
                <button
                    type="button"
                    @click="scrollLeft()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser kategori ke kiri"
                >
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button
                    type="button"
                    @click="scrollRight()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser kategori ke kanan"
                >
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
        @php
            $categoryIcons = ['fa-mobile-screen', 'fa-laptop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-gamepad', 'fa-basket-shopping', 'fa-couch'];
        @endphp

        @if($popularCategories->isNotEmpty())
            <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen px-4 sm:px-6 lg:px-8">
                <div
                    x-ref="categoryTrack"
                    class="grid grid-flow-col grid-rows-1 auto-cols-[minmax(9.5rem,9.5rem)] md:auto-cols-[minmax(11rem,11rem)] gap-3 md:gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 hide-scrollbar"
                >
                    @foreach($popularCategories as $category)
                        <a href="{{ route('kategori.detail', $category->slug) }}" class="snap-start flex flex-col items-center justify-center p-4 bg-white border border-gray-100 rounded-xl hover:border-primary transition gap-2 text-center min-h-[120px]">
                            <i class="fas {{ $categoryIcons[$loop->index % count($categoryIcons)] }} text-2xl text-primary mb-1"></i>
                            <span class="text-sm font-bold text-gray-800">{{ $category->name }}</span>
                            <span class="text-[11px] text-gray-500">{{ $category->active_products_count }} items</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">Belum ada kategori aktif.</div>
        @endif
    </section>

    <section id="flash-sale" class="scroll-mt-40" x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bolt text-sm"></i>
                </span>
                Produk Promo
            </h2>
            <div class="inline-flex items-center gap-2">
                <button
                    type="button"
                    @click="scrollLeft()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser promo ke kiri"
                >
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button
                    type="button"
                    @click="scrollRight()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser promo ke kanan"
                >
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
        <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(9.8rem,9.8rem)] md:auto-cols-[minmax(12rem,12rem)] gap-3 md:gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 hide-scrollbar">
            @forelse($flashSaleProducts as $product)
                @php
                    $flashSaleDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                        ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                        : 0;
                @endphp
                <div class="snap-start bg-white rounded-xl p-2.5 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute top-2 inset-x-2 grid grid-cols-2 items-start gap-1.5">
                            <span class="justify-self-start bg-rose-500 text-white text-[9px] md:text-[10px] font-bold px-2 py-1 rounded-full tracking-wide">{{ $flashSaleDiscount }}%</span>
                        </div>
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
                <div class="col-span-full text-sm text-gray-500">Belum ada produk promo.</div>
            @endforelse
        </div>
    </section>

    <section x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-chart-line text-sm"></i>
                </span>
                Produk Terlaris
            </h2>
            <div class="inline-flex items-center gap-2">
                <button
                    type="button"
                    @click="scrollLeft()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser terlaris ke kiri"
                >
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button
                    type="button"
                    @click="scrollRight()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition"
                    aria-label="Geser terlaris ke kanan"
                >
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
        <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(9.8rem,9.8rem)] md:auto-cols-[minmax(12rem,12rem)] gap-3 md:gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 hide-scrollbar">
            @forelse($bestSellerProducts as $product)
                @php
                    $bestSellerDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                        ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                        : 0;
                @endphp
                <div class="snap-start bg-white rounded-xl p-2.5 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-100 rounded-lg aspect-square w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl md:text-6xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute top-2 inset-x-2 grid grid-cols-2 items-start gap-1.5">
                            <span class="justify-self-start bg-rose-500 text-white text-[9px] md:text-[10px] font-bold px-2 py-1 rounded-full tracking-wide">{{ $bestSellerDiscount }}%</span>
                        </div>
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

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 mb-10">
            @forelse($newProducts as $product)
                @php
                    $newProductDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
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
                        <div class="absolute top-2 inset-x-2 grid grid-cols-2 items-start gap-1.5">
                            <span class="justify-self-start bg-rose-500 text-white text-[9px] md:text-[10px] font-bold px-2 py-1 rounded-full tracking-wide">{{ $newProductDiscount }}%</span>
                        </div>
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
                <div class="col-span-full text-sm text-gray-500">Belum ada produk terbaru.</div>
            @endforelse
        </div>

        <div class="flex justify-center items-center gap-2 mt-6">
            <span class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white opacity-50 cursor-not-allowed select-none">Prev</span>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-600 bg-blue-600 text-white text-sm font-bold">1</a>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-200 text-blue-700 text-sm font-semibold bg-white hover:bg-blue-50 transition">2</a>
            <a href="{{ route('katalog') }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-blue-200 text-blue-700 text-sm font-semibold bg-white hover:bg-blue-50 transition">3</a>
            <a href="{{ route('katalog') }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition">Next</a>
        </div>
    </section>
</div>

@push('scripts')
<script>
function bannerSlider(totalSlides = 1) {
    return {
        currentSlide: 0,
        slides: Array.from({ length: Math.max(1, Number(totalSlides)) }, (_, index) => index),
        intervalId: null,
        startAutoSlide() {
            if (this.slides.length <= 1) return;
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

function categoryCarousel() {
    return {
        scrollLeft() {
            const track = this.$refs.categoryTrack;
            if (!track) return;
            track.scrollBy({ left: -Math.round(track.clientWidth * 0.85), behavior: 'smooth' });
        },
        scrollRight() {
            const track = this.$refs.categoryTrack;
            if (!track) return;
            track.scrollBy({ left: Math.round(track.clientWidth * 0.85), behavior: 'smooth' });
        }
    }
}

function stripCarousel() {
    return {
        scrollLeft() {
            const track = this.$refs.track;
            if (!track) return;
            track.scrollBy({ left: -Math.round(track.clientWidth * 0.85), behavior: 'smooth' });
        },
        scrollRight() {
            const track = this.$refs.track;
            if (!track) return;
            track.scrollBy({ left: Math.round(track.clientWidth * 0.85), behavior: 'smooth' });
        }
    }
}
</script>
@endpush
