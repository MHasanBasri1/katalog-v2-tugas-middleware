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
                    'image_url' => 'https://picsum.photos/seed/kataloque-banner-fallback-1/1600/700',
                    'cta_label' => 'Belanja Sekarang',
                    'cta_url' => route('katalog'),
                ],
            ]);
    @endphp
    <div x-data="bannerSlider({{ $bannerItems->count() }})" x-init="startAutoSlide()" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()" class="relative rounded-[2rem] overflow-hidden w-full h-[200px] sm:h-[300px] md:h-[450px] shadow-2xl shadow-blue-900/10 group">
        <div class="flex h-full w-full slide-transition transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
            @foreach($bannerItems as $banner)
                <div class="w-full h-full flex-shrink-0 relative">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover scale-105 transform group-hover:scale-100 transition-transform duration-1000 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/50 to-transparent mix-blend-multiply"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute inset-0 px-5 sm:px-10 md:px-16 py-6 sm:py-8 md:py-16 flex items-center">
                        <div class="max-w-2xl text-white transform transition-all duration-700 translate-y-0 opacity-100">
                            <!-- Emblem -->
                            <div class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white/20 backdrop-blur-md mb-2 sm:mb-4 border border-white/20">
                                <i class="fas fa-star text-amber-400 text-[7px] sm:text-[10px]"></i>
                                <span class="text-[8px] sm:text-xs font-bold tracking-wider text-white uppercase">Pilihan Utama</span>
                            </div>
                            
                            <h2 class="text-lg sm:text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight mb-1 md:mb-4 drop-shadow-md">{{ str($banner->title)->limit(40) }}</h2>
                            @if($banner->subtitle)
                                <p class="text-[9px] sm:text-base md:text-lg lg:text-xl text-blue-50/90 font-medium max-w-lg leading-tight md:leading-relaxed drop-shadow line-clamp-2 md:line-clamp-none">{{ $banner->subtitle }}</p>
                            @endif

                            @if($banner->cta_url)
                                <a href="{{ $banner->cta_url }}" class="inline-flex items-center gap-1.5 mt-3 sm:mt-6 bg-white text-gray-900 px-3 py-1.5 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-bold hover:bg-gray-50 transition-colors shadow-lg text-[9px] sm:text-sm">
                                    {{ $banner->cta_label ?? 'Beli Sekarang' }} <i class="fas fa-arrow-right text-[7px] sm:text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Controls -->
        <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 flex items-center gap-2 z-10 hidden sm:flex">
            <button type="button" @click.stop="prevSlide()" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/30 hover:bg-black/50 backdrop-blur-md text-white flex items-center justify-center transition-all shadow-sm">
                <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
            </button>
            <button type="button" @click.stop="nextSlide()" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/30 hover:bg-black/50 backdrop-blur-md text-white flex items-center justify-center transition-all shadow-sm">
                <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
            </button>
        </div>

        <!-- Slider Dots Indicator -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center justify-center gap-1.5 sm:gap-2 z-10 bg-black/20 px-3 py-1.5 rounded-full backdrop-blur-md">
            <template x-for="(slide, index) in slides" :key="index">
                <button type="button" @click.stop="goToSlide(index)" 
                    :class="currentSlide === index ? 'w-4 sm:w-6 bg-white' : 'w-1.5 sm:w-2 bg-white/50 hover:bg-white'" 
                    class="h-1.5 sm:h-2 rounded-full transition-all duration-300 shadow-sm"></button>
            </template>
        </div>
    </div>

    <section class="scroll-mt-40 pt-4" x-data="categoryCarousel()">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-50 text-blue-500">
                    <i class="fas fa-layer-group text-sm md:text-base"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2 md:gap-3">
                        Kategori
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1 hidden sm:block">Eksplorasi produk berdasarkan kategori favorit</p>
                </div>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button
                    type="button"
                    @click="scrollLeft()"
                    class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"
                    aria-label="Geser ke kiri"
                >
                    <i class="fas fa-chevron-left text-xs md:text-sm"></i>
                </button>
                <button
                    type="button"
                    @click="scrollRight()"
                    class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"
                    aria-label="Geser ke kanan"
                >
                    <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                </button>
            </div>
        </div>
        @if($popularCategories->isNotEmpty())
            <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen px-4 sm:px-6 lg:px-8">
                <div
                    x-ref="categoryTrack"
                    class="grid grid-flow-col grid-rows-1 auto-cols-[minmax(10.5rem,10.5rem)] md:auto-cols-[minmax(12rem,12rem)] gap-4 md:gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-6 pt-2 hide-scrollbar px-1"
                >
                    @foreach($popularCategories->take(7) as $category)
                        <a href="{{ route('kategori.detail', $category->slug) }}" class="snap-start group flex flex-col items-center justify-center p-6 bg-white/60 backdrop-blur-xl border border-white/50 rounded-3xl hover:border-primary/30 hover:shadow-[0_20px_40px_rgb(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 gap-3 text-center min-h-[140px] relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-white/40 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                            
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-1 {{ $category->color ?: 'bg-blue-50' }} group-hover:scale-110 transition-transform duration-300 shadow-inner">
                                <i class="fas {{ $category->icon ?: 'fa-layer-group' }} text-2xl {{ $category->text_color ?: 'text-blue-500' }}"></i>
                            </div>
                            
                            <div>
                                <span class="block text-[15px] font-bold text-gray-800 group-hover:text-primary transition-colors tracking-tight">{{ $category->name }}</span>
                                <span class="block text-xs font-medium text-gray-400 mt-1 opacity-80">{{ $category->active_products_count }} Products</span>
                            </div>
                        </a>
                    @endforeach

                    <!-- See All Category Card -->
                    <a href="{{ route('kategori') }}" class="snap-start group flex flex-col items-center justify-center p-6 bg-primary text-white border border-primary/20 rounded-3xl hover:bg-primary-dark hover:shadow-xl hover:shadow-primary/20 hover:-translate-y-1 transition-all duration-300 gap-3 text-center min-h-[140px] relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50"></div>
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                        
                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-arrow-right text-2xl text-white"></i>
                        </div>
                        
                        <div>
                            <span class="block text-[15px] font-bold tracking-tight">Lihat Semua</span>
                            <span class="block text-[10px] font-medium opacity-80 mt-1 uppercase tracking-widest">Kategori</span>
                        </div>

                        <div class="absolute bottom-3 right-4 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right text-[8px]"></i>
                            <i class="fas fa-chevron-right text-[8px] opacity-60"></i>
                        </div>
                    </a>
                </div>
            </div>


        @else
            <div class="p-8 bg-gray-50 rounded-2xl text-center border border-gray-100 text-sm text-gray-500">Belum ada kategori aktif.</div>
        @endif
    </section>

    <section id="flash-sale" class="scroll-mt-40 pt-4" x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-rose-50 text-rose-600 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <i class="fas fa-bolt text-sm md:text-base"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        Flash Sale
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1 hidden sm:block">Penawaran waktu terbatas, beli sekarang!</p>
                </div>
            </div>
            
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" @click="scrollLeft()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm">
                    <i class="fas fa-chevron-left text-xs md:text-sm"></i>
                </button>
                <button type="button" @click="scrollRight()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm">
                    <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                </button>
            </div>
        </div>
        <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen px-4 sm:px-6 lg:px-8">
            <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(11rem,11rem)] md:auto-cols-[minmax(14rem,14rem)] gap-4 md:gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-8 pt-2 hide-scrollbar px-1">
                @forelse($flashSaleProducts as $product)
                    @php
                        $flashSaleDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                            ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                            : 0;
                    @endphp
                    <div class="snap-start bg-white/60 backdrop-blur-xl rounded-3xl p-3 border border-white/50 shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] hover:shadow-[0_15px_45px_0_rgba(31,38,135,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative">
                        <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-4 relative">
                            @if($product->primaryImage?->image)
                                <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                            @if($flashSaleDiscount > 0)
                                <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded-xl shadow-md shadow-rose-500/20 uppercase tracking-wide">
                                    -{{ $flashSaleDiscount }}%
                                </span>
                            @endif

                            <!-- Favorite Button (Hover Content) -->
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                <button type="button" 
                                    @auth wire:click.prevent="toggleFavorite({{ $product->id }})" @else onclick="window.location.href='{{ route('user.login') }}'" @endauth
                                    class="w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors duration-300 text-gray-600 {{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'text-rose-500' : '' }}"
                                >
                                    <i class="{{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'fas' : 'far' }} fa-heart text-[13px] md:text-sm"></i>
                                </button>
                            </div>
                        </a>
                        
                        <div class="px-1 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-2 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors">
                                <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            
                            <div class="mt-auto">
                                <p class="text-primary font-black text-lg md:text-xl leading-tight">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                                @if($product->original_price)
                                    <p class="text-gray-400 line-through text-xs md:text-sm mt-0.5 decoration-gray-300">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
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
                <div class="col-span-full text-sm text-gray-500">Belum ada produk promo.</div>
            @endforelse
        </div>
    </section>

    <section class="scroll-mt-40 pt-4" x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-amber-50 text-amber-500">
                    <i class="fas fa-fire text-sm md:text-base"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        Produk Terlaris
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1 hidden sm:block">Paling banyak dicari pelanggan</p>
                </div>
            </div>
            
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" @click="scrollLeft()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm">
                    <i class="fas fa-chevron-left text-xs md:text-sm"></i>
                </button>
                <button type="button" @click="scrollRight()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm">
                    <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                </button>
            </div>
        </div>
        <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen px-4 sm:px-6 lg:px-8">
            <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(11rem,11rem)] md:auto-cols-[minmax(14rem,14rem)] gap-4 md:gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-8 pt-2 hide-scrollbar px-1">
                @forelse($bestSellerProducts as $product)
                    @php
                        $bestSellerDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                            ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                            : 0;
                    @endphp
                    <div class="snap-start bg-white/60 backdrop-blur-xl rounded-3xl p-3 border border-white/50 shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] hover:shadow-[0_15px_45px_0_rgba(31,38,135,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                        <!-- Top Selling Badge -->
                        @if($loop->index < 3)
                            <div class="absolute top-0 right-3 bg-gradient-to-b from-amber-400 to-amber-500 text-white w-8 px-2 pb-3 pt-3 text-center text-xs font-black shadow-lg shadow-amber-500/30 z-20 clip-path-ribbon">
                                #{{ $loop->iteration }}
                            </div>
                        @endif

                        <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-4 relative">
                            @if($product->primaryImage?->image)
                                <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                            
                            @if($bestSellerDiscount > 0)
                                <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded-xl shadow-md shadow-rose-500/20 uppercase tracking-wide">
                                    -{{ $bestSellerDiscount }}%
                                </span>
                            @endif

                            <!-- Favorite Button (Hover Content) -->
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                <button type="button" 
                                    @auth wire:click.prevent="toggleFavorite({{ $product->id }})" @else onclick="window.location.href='{{ route('user.login') }}'" @endauth
                                    class="w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors duration-300 text-gray-600 {{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'text-rose-500' : '' }}"
                                >
                                    <i class="{{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'fas' : 'far' }} fa-heart text-[13px] md:text-sm"></i>
                                </button>
                            </div>
                        </a>
                        
                        <div class="px-1 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-2 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors pr-6">
                                <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            
                            <div class="mt-auto">
                                <p class="text-primary font-black text-lg md:text-xl leading-tight">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                                @if($product->original_price)
                                    <p class="text-gray-400 line-through text-xs md:text-sm mt-0.5 decoration-gray-300">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
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
                    <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border border-gray-100">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-50 text-gray-300 text-2xl">
                            <i class="fas fa-chart-line text-gray-200"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada produk terlaris.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <style>
        .clip-path-ribbon {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 85%, 0 100%);
        }
    </style>

    <section class="scroll-mt-40 pt-6">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-purple-50 text-purple-500">
                    <i class="fas fa-clock text-sm md:text-base"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        Produk Terbaru
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1 hidden sm:block">Koleksi baru khusus untuk Anda</p>
                </div>
            </div>
            <a href="{{ route('katalog') }}" class="text-xs md:text-sm font-semibold text-primary hover:text-primary-dark transition-colors hidden sm:inline-flex items-center gap-1">
                Semua Katalog <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5 mb-12">
            @forelse($newProducts as $product)
                @php
                    $newProductDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                        ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                        : 0;
                @endphp
                <div class="bg-white/60 backdrop-blur-xl rounded-3xl p-3 border border-white/50 shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] hover:shadow-[0_15px_45px_0_rgba(31,38,135,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col group relative">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-4 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                        
                        @if($newProductDiscount > 0)
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded-xl shadow-md shadow-rose-500/20 uppercase tracking-wide">
                                -{{ $newProductDiscount }}%
                            </span>
                        @endif

                            <!-- Favorite Button (Hover Content) -->
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                <button type="button" 
                                    @auth wire:click.prevent="toggleFavorite({{ $product->id }})" @else onclick="window.location.href='{{ route('user.login') }}'" @endauth
                                    class="w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors duration-300 text-gray-600 {{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'text-rose-500' : '' }}"
                                >
                                    <i class="{{ in_array($product->id, $favoritedProductIds ?? [], true) ? 'fas' : 'far' }} fa-heart text-[13px] md:text-sm"></i>
                                </button>
                            </div>
                    </a>
                    
                    <div class="px-1 flex flex-col flex-1">
                        <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-2 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        
                        <div class="mt-auto">
                            <p class="text-primary font-black text-lg md:text-xl leading-tight">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                            @if($product->original_price)
                                <p class="text-gray-400 line-through text-xs md:text-sm mt-0.5 decoration-gray-300">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
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
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border border-gray-100">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-50 text-gray-300 text-2xl">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada produk terbaru.</p>
                </div>
            @endforelse
        </div>
        
        <div class="sm:hidden text-center mt-6">
            <a href="{{ route('katalog') }}" class="inline-flex items-center justify-center gap-2 bg-gray-50 border border-gray-200 text-gray-700 font-bold px-8 py-3 rounded-full text-sm hover:bg-gray-100 transition-colors inline-block">
                Lihat Semua Katalog <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        </div>
    </section>
    
    <div class="mt-6 mb-4 border-t border-gray-100 pt-8">
        <div class="w-full bg-gradient-to-br from-blue-600 to-indigo-800 rounded-3xl p-6 sm:p-10 shadow-xl shadow-blue-900/20 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-blue-400/20 blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left max-w-2xl">
                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2 tracking-tight">Belanja Lebih Mudah & Cepat</h3>
                    <p class="text-blue-100 text-base md:text-lg font-medium opacity-90">Temukan ribuan produk pilihan dengan harga terbaik hanya di Kataloque.</p>
                </div>
                <div>
                    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 bg-white text-blue-700 font-bold px-6 py-3 rounded-full text-sm hover:bg-gray-50 hover:-translate-y-1 hover:shadow-xl hover:shadow-white/20 transition-all duration-300 group">
                        Mulai Belanja Sekarang
                        <span class="bg-blue-50 text-blue-600 rounded-full w-7 h-7 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
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
                this.nextSlide();
            }, 5000);
        },
        stopAutoSlide() {
            clearInterval(this.intervalId);
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        },
        goToSlide(index) {
            this.currentSlide = index;
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
