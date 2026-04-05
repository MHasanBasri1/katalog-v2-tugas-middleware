@extends('frontend.layouts.app')

@section('title', 'Beranda - Kataloque')
@section('meta_description', 'Beranda Kataloque: temukan produk terbaru, promo hari ini, kategori populer, dan penawaran terbaik setiap hari.')
@section('canonical', route('home'))
@section('og_url', route('home'))
@section('main_class', '')

@php
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

@push('styles')
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        display: flex;
        width: max-content;
        animation: marquee 20s linear infinite;
    }
    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 pb-10 space-y-6 md:space-y-8">
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
    <div class="space-y-6">
        <!-- Single Wide Banner -->
        <div x-data="bannerSlider({{ $bannerItems->count() }})" x-init="startAutoSlide()" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()" class="relative rounded-xl overflow-hidden w-full aspect-[4/1.2] sm:aspect-[4/1] shadow-sm group">
            <div class="flex h-full w-full slide-transition transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
                @foreach($bannerItems as $banner)
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
                        <div class="absolute inset-0 px-6 sm:px-12 md:px-16 flex items-center">
                            <div class="max-w-xl text-white">
                                <h2 class="text-xl sm:text-3xl md:text-5xl font-black leading-tight mb-2 md:mb-4 drop-shadow-md">{{ str($banner->title)->limit(40) }}</h2>
                                @if($banner->subtitle)
                                    <p class="text-xs sm:text-lg font-medium text-white/90 drop-shadow line-clamp-2">{{ $banner->subtitle }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 flex items-center gap-2 z-10 hidden sm:flex">
                <button type="button" @click.stop="prevSlide()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-md text-white flex items-center justify-center transition-all shadow-sm">
                    <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
                </button>
                <button type="button" @click.stop="nextSlide()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-md text-white flex items-center justify-center transition-all shadow-sm">
                    <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                </button>
            </div>

            <div class="absolute bottom-4 left-6 flex items-center justify-center gap-1.5 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button type="button" @click.stop="goToSlide(index)" 
                        :class="currentSlide === index ? 'w-6 bg-white' : 'w-2 bg-white/40'" 
                        class="h-1.5 rounded-full transition-all duration-300 shadow-sm"></button>
                </template>
            </div>
        </div>
    </div>

    <!-- Category & Trust Section -->
    <section class="scroll-mt-40 pt-4 pb-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <!-- Trust Bar Inside -->
            <!-- Desktop Grid -->
            <div class="hidden sm:grid grid-cols-3 gap-4 py-4 px-6 md:px-8 border-b border-gray-100 uppercase tracking-tighter">
                <div class="flex items-center justify-center gap-2 text-primary font-bold text-[10px] md:text-sm">
                    <i class="fas fa-certificate text-blue-500"></i>
                    <span>Pasti Ori</span>
                </div>
                <div class="flex items-center justify-center gap-2 text-primary font-bold text-[10px] md:text-sm">
                    <i class="fas fa-undo-alt text-amber-500"></i>
                    <span>Retur alasan apa pun</span>
                </div>
                <div class="flex items-center justify-center gap-2 text-primary font-bold text-[10px] md:text-sm">
                    <i class="fas fa-clock text-rose-500"></i>
                    <span>Jaminan tepat waktu</span>
                </div>
            </div>

            <!-- Mobile Marquee -->
            <div class="sm:hidden py-3 border-b border-gray-100 overflow-hidden relative">
                <div class="animate-marquee flex gap-12 items-center px-4">
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-certificate text-blue-500"></i>
                        <span>Pasti Ori</span>
                    </div>
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-undo-alt text-amber-500"></i>
                        <span>Retur alasan apa pun</span>
                    </div>
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-clock text-rose-500"></i>
                        <span>Jaminan tepat waktu</span>
                    </div>
                    <!-- Duplicate for seamless loop -->
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-certificate text-blue-500"></i>
                        <span>Pasti Ori</span>
                    </div>
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-undo-alt text-amber-500"></i>
                        <span>Retur alasan apa pun</span>
                    </div>
                    <div class="flex items-center gap-2 text-primary font-bold text-[11px] whitespace-nowrap">
                        <i class="fas fa-clock text-rose-500"></i>
                        <span>Jaminan tepat waktu</span>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-8" x-data="categoryCarousel()">
                <!-- Mobile Slider / Desktop Grid for Categories -->
                <div class="relative group">
                    <button @click="scrollLeft()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-xl items-center justify-center hidden md:flex hover:text-primary transition-all text-gray-400 hover:scale-110 active:scale-95">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <button @click="scrollRight()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-xl items-center justify-center hidden md:flex hover:text-primary transition-all text-gray-400 hover:scale-110 active:scale-95">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <div x-ref="categoryTrack" class="flex overflow-x-auto gap-6 md:gap-12 hide-scrollbar snap-x snap-mandatory scroll-smooth pb-2">
                        @foreach($popularCategories->take(15) as $category)
                            <a href="{{ route('kategori.detail', $category->slug) }}" class="group flex flex-col items-center gap-3 text-center shrink-0 w-20 md:w-28 snap-center">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gray-50 flex items-center justify-center border border-gray-200 group-hover:border-primary group-hover:bg-primary/5 transition-all shadow-sm group-hover:shadow-md">
                                    <i class="fas {{ $category->icon ?: 'fa-layer-group' }} text-base md:text-lg text-primary group-hover:scale-110 transition-transform"></i>
                                </div>
                                <span class="text-[10px] md:text-sm font-bold text-gray-700 group-hover:text-primary transition-colors line-clamp-1 truncate w-full px-1">{{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="promo" class="scroll-mt-40 pt-4" x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Promo Hari Ini</h2>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" @click="scrollLeft()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"><i class="fas fa-chevron-left text-xs md:text-sm"></i></button>
                <button type="button" @click="scrollRight()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"><i class="fas fa-chevron-right text-xs md:text-sm"></i></button>
            </div>
        </div>
        <div class="relative w-full">
            <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(11rem,11rem)] md:auto-cols-[minmax(14rem,14rem)] gap-4 md:gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-8 pt-2 hide-scrollbar px-1">
                @foreach($flashSaleProducts as $product)
                    @php
                        $flashSaleDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
                            ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
                            : 0;
                    @endphp
                <div class="snap-start bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                        <!-- Gratis Ongkir Badge -->
                        <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-[#00A859] text-white px-2 py-1 rounded-lg shadow-sm border border-white/20">
                            <i class="fas fa-truck-fast text-[10px]"></i>
                            <span class="text-[8px] font-black uppercase leading-none mt-0.5">Gratis Ongkir</span>
                        </div>

                        <!-- Favorite Button (Hover) -->
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                            @livewire('public.favorite-button', ['productId' => $product->id, 'class' => 'w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white'], key('fav-flash-'.$product->id))
                        </div>
                    </a>
                    
                    <div class="px-1 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-1 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="text-gray-900 font-bold text-base md:text-lg tracking-tight"><span class="text-[10px] md:text-xs font-medium">Rp</span>{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                            @if($product->original_price && (float) $product->original_price > (float) $product->price)
                                <span class="text-[10px] md:text-xs text-gray-400 line-through truncate opacity-80">{{ number_format((float) $product->original_price, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1 rounded flex-shrink-0">{{ $flashSaleDiscount }}%</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-[11px] font-medium mb-2">
                            <span class="flex items-center gap-1"><i class="fas fa-star text-amber-400"></i> <span class="text-gray-700">{{ number_format((float) $product->rating_avg, 1) }}</span></span>
                            <span class="text-gray-300">|</span>
                            <span class="text-gray-500">Terjual {{ $compactViews($product->sold_count) }}</span>
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-blue-500 flex items-center justify-center text-white text-[8px]">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500 truncate">{{ $product->category?->name ?? 'Kataloque Official' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="scroll-mt-40 pt-4" x-data="stripCarousel()">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Produk Terlaris</h2>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button type="button" @click="scrollLeft()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"><i class="fas fa-chevron-left text-xs md:text-sm"></i></button>
                <button type="button" @click="scrollRight()" class="inline-flex items-center justify-center w-8 h-8 md:w-11 md:h-11 rounded-full border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-primary transition-all shadow-sm"><i class="fas fa-chevron-right text-xs md:text-sm"></i></button>
            </div>
        </div>
        <div class="relative w-full">
            <div x-ref="track" class="grid grid-flow-col auto-cols-[minmax(11rem,11rem)] md:auto-cols-[minmax(14rem,14rem)] gap-4 md:gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-8 pt-2 hide-scrollbar px-1">
                @foreach($bestSellerProducts as $product)
                    <div class="snap-start bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                        <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-3 relative">
                            @if($product->primaryImage?->image)
                                <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                            <!-- Gratis Ongkir Badge -->
                            <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-[#00A859] text-white px-2 py-1 rounded-lg shadow-sm border border-white/20">
                                <i class="fas fa-truck-fast text-[10px]"></i>
                                <span class="text-[8px] font-black uppercase leading-none mt-0.5">Gratis Ongkir</span>
                            </div>

                            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                @livewire('public.favorite-button', ['productId' => $product->id, 'class' => 'w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white'], key('fav-best-'.$product->id))
                            </div>
                        </a>
                        <div class="px-1 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-1 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors">
                                <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="text-gray-900 font-bold text-base md:text-lg tracking-tight"><span class="text-[10px] md:text-xs font-medium">Rp</span>{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                @if($product->original_price && (float) $product->original_price > (float) $product->price)
                                    <span class="text-[10px] md:text-xs text-gray-400 line-through truncate opacity-80">{{ number_format((float) $product->original_price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1 rounded flex-shrink-0">{{ round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100) }}%</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-[11px] font-medium mb-2">
                                <span class="flex items-center gap-1"><i class="fas fa-star text-amber-400"></i> <span class="text-gray-700">{{ number_format((float) $product->rating_avg, 1) }}</span></span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-500">Terjual {{ $compactViews($product->sold_count) }}</span>
                            </div>

                            <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded bg-blue-500 flex items-center justify-center text-white text-[8px]">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <span class="text-[11px] font-medium text-gray-500 truncate">{{ $product->category?->name ?? 'Kataloque Official' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                <div class="bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group relative h-full">
                    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-3 relative">
                        @if($product->primaryImage?->image)
                            <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                        <!-- Gratis Ongkir Badge -->
                        <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-[#00A859] text-white px-2 py-1 rounded-lg shadow-sm border border-white/20">
                            <i class="fas fa-truck-fast text-[10px]"></i>
                            <span class="text-[8px] font-black uppercase leading-none mt-0.5">Gratis Ongkir</span>
                        </div>

                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                            @livewire('public.favorite-button', ['productId' => $product->id, 'class' => 'w-8 h-8 md:w-9 md:h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white'], key('fav-new-'.$product->id))
                        </div>
                    </a>
                    
                    <div class="px-1 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-1 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="text-gray-900 font-bold text-base md:text-lg tracking-tight"><span class="text-[10px] md:text-xs font-medium">Rp</span>{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                            @if($product->original_price && (float) $product->original_price > (float) $product->price)
                                <span class="text-[10px] md:text-xs text-gray-400 line-through truncate opacity-80">{{ number_format((float) $product->original_price, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1 rounded flex-shrink-0">{{ round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100) }}%</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-[11px] font-medium mb-2">
                            <span class="flex items-center gap-1"><i class="fas fa-star text-amber-400"></i> <span class="text-gray-700">{{ number_format((float) $product->rating_avg, 1) }}</span></span>
                            <span class="text-gray-300">|</span>
                            <span class="text-gray-500">Terjual {{ $compactViews($product->sold_count) }}</span>
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-blue-500 flex items-center justify-center text-white text-[8px]">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500 truncate">{{ $product->category?->name ?? 'Kataloque Official' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6 text-center pb-0">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-blue-200">
                Lihat produk lainnya <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
function bannerSlider(totalSlides = 1) {
    return {
        currentSlide: 0,
        slides: Array.from({ length: Math.max(1, Number(totalSlides)) }, (_, index) => index),
        intervalId: null,
        startAutoSlide() { if (this.slides.length <= 1) return; this.intervalId = setInterval(() => { this.nextSlide(); }, 5000); },
        stopAutoSlide() { clearInterval(this.intervalId); },
        nextSlide() { this.currentSlide = (this.currentSlide + 1) % this.slides.length; },
        prevSlide() { this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length; },
        goToSlide(index) { this.currentSlide = index; }
    }
}
function categoryCarousel() {
    return {
        scrollLeft() { const track = this.$refs.categoryTrack; if (!track) return; track.scrollBy({ left: -Math.round(track.clientWidth * 0.85), behavior: 'smooth' }); },
        scrollRight() { const track = this.$refs.categoryTrack; if (!track) return; track.scrollBy({ left: Math.round(track.clientWidth * 0.85), behavior: 'smooth' }); }
    }
}
function stripCarousel() {
    return {
        scrollLeft() { const track = this.$refs.track; if (!track) return; track.scrollBy({ left: -Math.round(track.clientWidth * 0.85), behavior: 'smooth' }); },
        scrollRight() { const track = this.$refs.track; if (!track) return; track.scrollBy({ left: Math.round(track.clientWidth * 0.85), behavior: 'smooth' }); }
    }
}
</script>
@endpush
