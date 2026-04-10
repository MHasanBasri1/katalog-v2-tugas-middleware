@extends('frontend.layouts.app')

@section('title', 'Beranda - Kataloque')
@section('meta_description', 'Beranda Kataloque: temukan produk terbaru, promo hari ini, kategori populer, dan penawaran terbaik setiap hari.')
@section('canonical', route('home'))
@section('og_url', route('home'))
@section('main_class', '')

@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
    $optimizeImg = function($url, $w, $h) {
        if ($url && preg_match('#/id/(\d+)/#', $url, $m)) {
            return "https://picsum.photos/id/{$m[1]}/{$w}/{$h}.webp";
        }
        return $url;
    };
@endphp

@push('styles')
<style>
    .trust-swiper {
        width: 100%;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .trust-swiper .swiper-wrapper {
        transition-timing-function: linear !important;
    }
    .trust-swiper .swiper-slide {
        width: auto !important;
        display: flex;
        align-items: center;
        padding: 0 2.5rem;
    }
    @media (min-width: 1024px) {
        .trust-swiper .swiper-wrapper {
            justify-content: space-around !important;
            width: 100% !important;
            transform: none !important;
            transition: none !important;
        }
        .trust-swiper .swiper-slide {
            padding: 0 !important;
            flex: 1;
            justify-content: center;
        }
    }
    .hero-swiper-btn-prev, .hero-swiper-btn-next,
    .promo-swiper-btn-prev, .promo-swiper-btn-next,
    .best-swiper-btn-prev, .best-swiper-btn-next {
        transition: all 0.3s ease;
    }
    .hero-swiper-btn-prev:hover, .hero-swiper-btn-next:hover {
        background: rgba(0, 0, 0, 0.4) !important;
    }
    .hero-swiper-pagination {
        background: rgba(0, 0, 0, 0.45) !important;
        backdrop-filter: blur(8px);
        padding: 5px 12px !important;
        border-radius: 9999px !important;
        width: auto !important;
        display: flex !important;
        align-items: center;
        gap: 6px !important;
    }
    @media (max-width: 768px) {
        .hero-swiper-pagination {
            padding: 3px 8px !important;
            gap: 4px !important;
        }
        .hero-swiper-pagination .swiper-pagination-bullet {
            width: 5px !important;
            height: 5px !important;
        }
        .hero-swiper-pagination .swiper-pagination-bullet-active {
            width: 18px !important;
        }
    }
    .hero-swiper-pagination .swiper-pagination-bullet {
        background: #9ca3af !important; /* Grey circle */
        width: 7px !important;
        height: 7px !important;
        margin: 0 !important;
        border-radius: 9999px !important;
        transition: all 0.3s ease !important;
        opacity: 1 !important;
    }
    .hero-swiper-pagination .swiper-pagination-bullet-active {
        background: #ffffff !important; /* White pill */
        width: 28px !important;
    }
    /* Category section hover fix */
    .category-section:hover .category-nav-btn {
        opacity: 1 !important;
    }
</style>
@endpush

@section('content')
<h1 class="sr-only">Kataloque - Katalog Produk Modern & Terpercaya</h1>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-3 pb-8 space-y-4 md:space-y-6">
    @php
        $bannerItems = collect([
            (object) [
                'image_url' => 'https://www.static-src.com/siva/asset/04_2026/imagebtrgendesk.jpg?w=1200',
                'cta_url' => route('katalog'),
            ],
            (object) [
                'image_url' => 'https://www.static-src.com/siva/asset/04_2026/CRTV-12373_CAROUSEL_2000x500_Saucony_3_26.jpg?w=1200',
                'cta_url' => route('katalog'),
            ],
            (object) [
                'image_url' => 'https://www.static-src.com/siva/asset/04_2026/Carousel-Pisen-Reg-apr26-2000x500.jpeg?w=1200',
                'cta_url' => route('katalog'),
            ],
            (object) [
                'image_url' => 'https://www.static-src.com/siva/asset/03_2026/ocbcthurday-Desktop-carousel.jpg?w=1200',
                'cta_url' => route('katalog'),
            ],
            (object) [
                'image_url' => 'https://www.static-src.com/siva/asset/04_2026/Home-Desk-Travo-PAW-Expo-26-75rb-1.jpg?w=1200',
                'cta_url' => route('katalog'),
            ],
        ]);
    @endphp
    <div class="mt-2 md:mt-4">
        <!-- Hero Banner Slider -->
        <div class="relative group pb-3">
            <div class="swiper hero-swiper !overflow-visible">
                <div class="swiper-wrapper">
                    @foreach($bannerItems as $banner)
                        <div class="swiper-slide !w-[90%] md:!w-[70%] px-1 sm:px-2 md:px-4">
                            <a href="{{ $banner->cta_url ?? route('katalog') }}" class="block w-full aspect-[4/1] rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <x-optimized-image 
                                    :src="$banner->image_url" 
                                    alt="Promo Banner" 
                                    class="w-full h-full object-cover object-center bg-gray-50" 
                                    width="1200" 
                                    height="300" 
                                    :lazy="!$loop->first"
                                    :fetchpriority="$loop->first ? 'high' : 'auto'"
                                    sizes="100vw"
                                />
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Buttons (Visible on Hover) --}}
                <button type="button" class="hero-swiper-btn-prev absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-20 w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/90 backdrop-blur-sm text-gray-800 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white" aria-label="Sebelumnya">
                    <i class="fas fa-chevron-left text-xs md:text-sm"></i>
                </button>
                <button type="button" class="hero-swiper-btn-next absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-20 w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/90 backdrop-blur-sm text-gray-800 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white" aria-label="Berikutnya">
                    <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                </button>
            </div>

            {{-- Slider Footer Pagination (Right-Aligned) --}}
            <div class="mt-2 flex justify-end px-4 sm:px-6 lg:px-8">
                <div class="hero-swiper-pagination !relative !bottom-0 !left-auto !right-0 !p-0"></div>
            </div>
        </div>
    </div>

    <!-- Category & Trust Section -->
    <section class="scroll-mt-40 pt-0 pb-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4 overflow-hidden">
            <!-- Trust Section (Kelebihan) -->
            <div class="bg-gray-50/50 border-b border-gray-100 overflow-hidden relative">
                
                {{-- DESKTOP VERSION (Static 4 Items) --}}
                <div class="hidden lg:flex max-w-7xl mx-auto px-8 h-[52px] items-center justify-between">
                    <div class="flex items-center gap-3 text-gray-900 font-bold text-[13px] uppercase tracking-tighter">
                        <div class="relative flex items-center justify-center shrink-0">
                            <i class="fas fa-certificate text-blue-600 text-xl"></i>
                            <span class="absolute text-[7px] text-white font-black leading-none mt-[0.5px]">100%</span>
                        </div>
                        <span>Jaminan Ori</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-900 font-bold text-[13px] uppercase tracking-tighter">
                        <i class="fas fa-undo-alt text-amber-500 text-base"></i>
                        <span>Bebas Retur Mudah</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-900 font-bold text-[13px] uppercase tracking-tighter">
                        <i class="fas fa-truck-fast text-rose-500 text-base"></i>
                        <span>Garansi Tepat Waktu</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-900 font-bold text-[13px] uppercase tracking-tighter">
                        <i class="fas fa-headset text-emerald-500 text-base"></i>
                        <span>Layanan Cepat 24/7</span>
                    </div>
                </div>

                {{-- MOBILE VERSION (Swiper Marquee) --}}
                <div class="swiper trust-swiper lg:hidden">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <div class="relative flex items-center justify-center shrink-0">
                                    <i class="fas fa-certificate text-blue-600 text-lg"></i>
                                    <span class="absolute text-[6px] text-white font-black leading-none mt-[0.5px]">100%</span>
                                </div>
                                <span>Jaminan Ori</span>
                            </div>
                        </div>
                        <!-- Slide 2 -->
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <i class="fas fa-undo-alt text-amber-500 text-sm"></i>
                                <span>Bebas Retur Mudah</span>
                            </div>
                        </div>
                        <!-- Slide 3 -->
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <i class="fas fa-truck-fast text-rose-500 text-sm"></i>
                                <span>Garansi Tepat Waktu</span>
                            </div>
                        </div>
                        <!-- Slide 4 -->
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <i class="fas fa-headset text-emerald-500 text-sm"></i>
                                <span>Layanan Cepat 24/7</span>
                            </div>
                        </div>
                        <!-- Repeats for seamless loop -->
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <div class="relative flex items-center justify-center shrink-0">
                                    <i class="fas fa-certificate text-blue-600 text-lg"></i>
                                    <span class="absolute text-[6px] text-white font-black leading-none mt-[0.5px]">100%</span>
                                </div>
                                <span>Jaminan Ori</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="flex items-center gap-3 text-gray-900 font-bold text-[11px] uppercase tracking-tighter whitespace-nowrap">
                                <i class="fas fa-undo-alt text-amber-500 text-sm"></i>
                                <span>Bebas Retur Mudah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 pb-0 md:p-8">
                <!-- Swiper Categories -->
                <div class="relative px-4 md:px-0 category-section">
                    <button type="button" class="category-swiper-btn-prev absolute left-2 md:left-0 top-1/2 -translate-y-1/2 md:-translate-x-1/2 z-20 w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/95 border border-gray-200 shadow-xl flex items-center justify-center opacity-0 category-nav-btn transition-all duration-300 hover:text-primary text-gray-400 hover:scale-110 active:scale-95" aria-label="Geser Kiri Kategori">
                        <i class="fas fa-chevron-left text-xs md:text-sm" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="category-swiper-btn-next absolute right-2 md:right-0 top-1/2 -translate-y-1/2 md:translate-x-1/2 z-20 w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/95 border border-gray-200 shadow-xl flex items-center justify-center opacity-0 category-nav-btn transition-all duration-300 hover:text-primary text-gray-400 hover:scale-110 active:scale-95" aria-label="Geser Kanan Kategori">
                        <i class="fas fa-chevron-right text-xs md:text-sm" aria-hidden="true"></i>
                    </button>

                    <div class="swiper category-swiper pb-4">
                        <div class="swiper-wrapper">
                            @foreach($popularCategories->take(15) as $category)
                                @if($category->slug)
                                    <div class="swiper-slide !w-auto">
                                        <a href="{{ route('kategori.detail', $category->slug) }}" class="group flex flex-col items-center gap-3 text-center w-20 md:w-28">
                                            <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gray-50 flex items-center justify-center border border-gray-200 group-hover:border-primary group-hover:bg-primary/5 transition-all shadow-sm group-hover:shadow-md">
                                                <i class="fas {{ $category->icon ?: 'fa-layer-group' }} text-base md:text-lg text-primary group-hover:scale-110 transition-transform"></i>
                                            </div>
                                            <span class="text-[10px] md:text-sm font-bold text-gray-700 group-hover:text-primary transition-colors line-clamp-1 truncate w-full px-1">{{ $category->name }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="promo" class="scroll-mt-40 pt-4">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Promo Hari Ini</h2>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" class="promo-swiper-btn-prev inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm" aria-label="Geser Kiri Promo"><i class="fas fa-chevron-left text-xs md:text-sm" aria-hidden="true"></i></button>
                <button type="button" class="promo-swiper-btn-next inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm" aria-label="Geser Kanan Promo"><i class="fas fa-chevron-right text-xs md:text-sm" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="relative w-full">
            <div class="swiper promo-swiper !px-1">
                <div class="swiper-wrapper">
                    @foreach($flashSaleProducts as $product)
                        <div class="swiper-slide h-auto">
                            <x-product-card :product="$product" :compactViews="$compactViews" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="scroll-mt-40 pt-4">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Produk Terlaris</h2>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" class="best-swiper-btn-prev inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm" aria-label="Geser Kiri Terlaris"><i class="fas fa-chevron-left text-xs md:text-sm" aria-hidden="true"></i></button>
                <button type="button" class="best-swiper-btn-next inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm" aria-label="Geser Kanan Terlaris"><i class="fas fa-chevron-right text-xs md:text-sm" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="relative w-full">
            <div class="swiper best-swiper !px-1">
                <div class="swiper-wrapper">
                    @foreach($bestSellerProducts as $product)
                        <div class="swiper-slide h-auto">
                            <x-product-card :product="$product" :compactViews="$compactViews" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="scroll-mt-40 pt-6">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Produk Terbaru</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5 mb-12">
            @foreach($newProducts as $product)
                <x-product-card :product="$product" :compactViews="$compactViews" />
            @endforeach
        </div>
        <div class="mt-6 text-center pb-0">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 transition-all duration-300 shadow-xl shadow-blue-200">
                Lihat produk lainnya <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Trust Section Marquee
    new Swiper('.trust-swiper', {
        loop: true,
        autoplay: { delay: 0, disableOnInteraction: false },
        speed: 5000,
        slidesPerView: 'auto',
        allowTouchMove: true,
        freeMode: { enabled: true, momentum: false },
        breakpoints: {
            1024: {
                enabled: false,
                slidesPerView: 4,
                autoplay: false,
                loop: false,
            }
        }
    });

    // 2. Hero Banner Slider
    new Swiper('.hero-swiper', {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        speed: 800,
        centeredSlides: true,
        slidesPerView: 'auto',
        spaceBetween: 12,
        pagination: {
            el: '.hero-swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.hero-swiper-btn-next',
            prevEl: '.hero-swiper-btn-prev',
        },
        breakpoints: {
            768: {
                spaceBetween: 24
            }
        }
    });

    // 3. Category Swiper
    new Swiper('.category-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        navigation: {
            nextEl: '.category-swiper-btn-next',
            prevEl: '.category-swiper-btn-prev',
        },
        breakpoints: {
            768: { spaceBetween: 24 }
        }
    });

    // 4. Promo Swiper
    new Swiper('.promo-swiper', {
        slidesPerView: 2.2,
        spaceBetween: 12,
        navigation: {
            nextEl: '.promo-swiper-btn-next',
            prevEl: '.promo-swiper-btn-prev',
        },
        breakpoints: {
            768: { 
                slidesPerView: 4,
                spaceBetween: 20 
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 20
            }
        }
    });

    // 5. Best Seller Swiper
    new Swiper('.best-swiper', {
        slidesPerView: 2.2,
        spaceBetween: 12,
        navigation: {
            nextEl: '.best-swiper-btn-next',
            prevEl: '.best-swiper-btn-prev',
        },
        breakpoints: {
            768: { 
                slidesPerView: 4,
                spaceBetween: 20 
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 20
            }
        }
    });
});
</script>
@endpush
