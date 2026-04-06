@extends('frontend.layouts.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonical)
@section('og_url', $canonical)
@section('og_image', $ogImage)

@php
    $isPromo = (float) $product->original_price > (float) $product->price;
    $discountPercent = $isPromo ? (int) round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100) : 0;
    $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
<nav class="text-sm">
        <div class="bg-white/80 backdrop-blur-md border border-gray-100 shadow-sm rounded-2xl px-5 py-3">
            <div class="flex flex-wrap items-center gap-2 text-gray-500 font-medium whitespace-nowrap">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-home text-xs"></i> Beranda</a>
                <span class="text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <a href="{{ route('katalog') }}" class="text-gray-500 hover:text-primary transition-colors">Produk</a>
                <span class="text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <a href="{{ route('kategori.detail', $product->category?->slug ?? '#') }}" class="text-gray-500 hover:text-primary transition-colors">{{ $product->category?->name }}</a>
                <span class="text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="font-bold text-primary max-w-[200px] truncate">{{ $product->name }}</span>
            </div>
        </div>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden group/gallery" x-data="productGallery(@js($galleryImages))" x-init="startAutoSlide()">
                <template x-if="slides.length">
                    <div class="relative bg-gray-50 aspect-square md:aspect-[4/3] w-full">
                        <img :src="slides[currentSlide]" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-all duration-500 ease-out transform group-hover/gallery:-translate-y-2">
                        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/10 to-transparent opacity-0 group-hover/gallery:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover/gallery:opacity-100 transition-opacity duration-300">
                            <button @click="prev()" class="w-12 h-12 rounded-full bg-white/80 backdrop-blur-md text-gray-800 hover:bg-primary hover:text-white shadow-xl flex items-center justify-center transition-all duration-300 -translate-x-4 group-hover/gallery:translate-x-0 hover:scale-110"><i class="fas fa-arrow-left text-sm"></i></button>
                            <button @click="next()" class="w-12 h-12 rounded-full bg-white/80 backdrop-blur-md text-gray-800 hover:bg-primary hover:text-white shadow-xl flex items-center justify-center transition-all duration-300 translate-x-4 group-hover/gallery:translate-x-0 hover:scale-110"><i class="fas fa-arrow-right text-sm"></i></button>
                        </div>
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2.5 z-10 bg-black/20 backdrop-blur-sm px-4 py-2 rounded-full">
                            <template x-for="(_, index) in slides" :key="index">
                                <button @click="goTo(index)" :class="currentSlide === index ? 'w-8 bg-white shadow-[0_0_10px_rgba(255,255,255,0.8)]' : 'w-2 bg-white/50 hover:bg-white/80'" class="h-2 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>
                    </div>
                </template>
                <template x-if="!slides.length">
                    <div class="w-full aspect-square md:aspect-[4/3] bg-gray-50 flex flex-col items-center justify-center gap-4 text-gray-300"><i class="fas fa-box-open text-6xl md:text-8xl drop-shadow-sm"></i><span class="text-sm font-medium">Image not available</span></div>
                </template>
            </div>

            <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">Deskripsi Produk</h3>
                </div>
                <div class="prose prose-sm md:prose-base max-w-none text-gray-600 font-medium leading-relaxed"><p>{!! nl2br(e($product->description)) ?: 'Belum ada deskripsi spesifik untuk produk ini.' !!}</p></div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -z-10"></div>
                <div class="mb-5 space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div class="inline-flex items-center gap-2 bg-primary/10 text-primary-dark px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide"><i class="fas fa-folder text-primary/70"></i> {{ $product->category?->name }}</div>
                        @livewire('public.favorite-button', ['productId' => $product->id, 'class' => 'ml-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-rose-500 hover:border-rose-200 shadow-sm text-xs font-bold font-primary'], key('fav-'.$product->id))
                    </div>
                    <h1 class="text-2xl lg:text-4xl font-black text-gray-900 leading-tight tracking-tight">{{ $product->name }}</h1>
                </div>

                <div class="mb-8 p-5 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex flex-col">
                        @if($product->original_price)
                            <div class="flex items-center gap-2 mb-1"><span class="bg-rose-100 text-rose-600 text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded">-{{ $discountPercent }}%</span><p class="text-gray-400 line-through text-xs sm:text-sm font-medium">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p></div>
                        @endif
                        <p class="text-primary font-black text-2xl sm:text-3xl md:text-4xl tracking-tight mb-3">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-4 text-sm font-medium text-gray-600">
                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-star text-amber-500"></i><span class="text-gray-900 font-bold">{{ number_format((float) $product->rating_avg, 1) }}</span></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                            <span class="inline-flex items-center gap-1.5"><span class="text-gray-900 font-bold">{{ $compactViews($product->sold_count) }}</span> Terjual</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Cek Marketplace</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @php
                            $marketplaces = [
                                'shopee' => ['name' => 'Shopee', 'icon' => 'fas fa-shopping-bag', 'color' => 'bg-[#EE4D2D]', 'shadow' => 'hover:shadow-[#EE4D2D]/30'],
                                'tokopedia' => ['name' => 'Tokopedia', 'icon' => 'fas fa-bag-shopping', 'color' => 'bg-[#42B549]', 'shadow' => 'hover:shadow-[#42B549]/30'],
                                'lazada' => ['name' => 'Lazada', 'icon' => 'fas fa-heart', 'icon_class' => 'text-pink-500', 'color' => 'bg-[#0f146d]', 'shadow' => 'hover:shadow-[#0f146d]/30'],
                                'blibli' => ['name' => 'Blibli', 'icon' => 'fas fa-shopping-basket', 'color' => 'bg-[#0095DC]', 'shadow' => 'hover:shadow-[#0095DC]/30'],
                                'tiktok shop' => ['name' => 'TikTok Shop', 'icon' => 'fab fa-tiktok', 'color' => 'bg-black', 'shadow' => 'hover:shadow-black/30'],
                            ];
                        @endphp
                        @foreach($marketplaceLinks as $key => $url)
                            @if(isset($marketplaces[$key]))
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="group relative overflow-hidden inline-flex items-center justify-center gap-2.5 rounded-2xl px-4 py-3.5 font-bold text-sm {{ $marketplaces[$key]['color'] }} text-white hover:-translate-y-1 hover:shadow-lg {{ $marketplaces[$key]['shadow'] }} transition-all duration-300"><span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span><i class="{{ $marketplaces[$key]['icon'] }} {{ $marketplaces[$key]['icon_class'] ?? '' }} relative z-10 text-base"></i> <span class="relative z-10">{{ $marketplaces[$key]['name'] }}</span></a>
                            @else
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="group relative overflow-hidden inline-flex items-center justify-center gap-2.5 rounded-2xl px-4 py-3.5 font-bold text-sm bg-gray-600 text-white hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-600/30 transition-all duration-300"><span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span><i class="fas fa-store relative z-10"></i> <span class="relative z-10 font-bold uppercase">{{ $key }}</span></a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm p-5 md:p-7">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider">Share Produk</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('produk.detail', $product->slug) }}').then(() => alert('Link copied!'))" class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-600 text-white hover:bg-blue-700 transition shadow-md shadow-blue-200"><i class="fas fa-link"></i> Copy Link</button>
                </div>
            </div>

            <div class="lg:hidden bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8"><h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-3"><span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-50 text-gray-600 border border-gray-100 shadow-sm"><i class="fas fa-align-left text-sm"></i></span>Deskripsi Lengkap</h3><div class="prose prose-sm md:prose-base max-w-none text-gray-600 font-medium leading-relaxed"><p>{!! nl2br(e($product->description)) !!}</p></div></div>
        </div>
    </section>

    <section class="mt-12">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Produk Terkait</h2>
                </div>
            </div>
            <a href="{{ route('katalog') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors hidden sm:block uppercase tracking-wider">Lihat Semua <i class="fas fa-arrow-right text-[10px] ml-1"></i></a>            
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5 pb-8">
            @foreach($relatedProducts as $item)
                @php
                    $relatedDiscount = ($item->original_price && (float) $item->original_price > (float) $item->price)
                        ? round((((float) $item->original_price - (float) $item->price) / (float) $item->original_price) * 100)
                        : 0;
                @endphp
                <div class="bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full relative">
                    <a href="{{ route('produk.detail', $item->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[4/4] w-full overflow-hidden mb-3 relative">
                        @if($item->primaryImage?->image)
                            <img src="{{ $item->primaryImage->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-5xl text-gray-200"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                        <!-- Gratis Ongkir Badge -->
                        <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-[#00A859] text-white px-2 py-1 rounded-lg shadow-sm border border-white/20">
                            <i class="fas fa-truck-fast text-[10px]"></i>
                            <span class="text-[8px] font-black uppercase leading-none mt-0.5">Gratis Ongkir</span>
                        </div>
                    </a>
                    
                    <div class="px-1 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug mb-1 line-clamp-2 min-h-[40px] group-hover:text-primary transition-colors pr-6 md:pr-0">
                            <a href="{{ route('produk.detail', $item->slug) }}">{{ $item->name }}</a>
                        </h3>
                        
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="text-gray-900 font-bold text-base md:text-lg tracking-tight"><span class="text-[10px] md:text-xs font-medium">Rp</span>{{ number_format((float) $item->price, 0, ',', '.') }}</span>
                            @if($item->original_price && (float) $item->original_price > (float) $item->price)
                                <span class="text-[10px] md:text-xs text-gray-400 line-through truncate opacity-80">{{ number_format((float) $item->original_price, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1 rounded flex-shrink-0">{{ $relatedDiscount }}%</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-[11px] font-medium mb-2">
                            <span class="flex items-center gap-1"><i class="fas fa-star text-amber-400"></i> <span class="text-gray-700">{{ number_format((float) $item->rating_avg, 1) }}</span></span>
                            <span class="text-gray-300">|</span>
                            <span class="text-gray-500">Terjual {{ $compactViews($item->sold_count) }}</span>
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-100 relative h-7">
                            <!-- Group 1: Category -->
                            <div class="animate-fade-cat absolute inset-x-0 bottom-0 py-1 flex items-center gap-2 px-1">
                                <div class="w-4 h-4 rounded bg-blue-600 flex items-center justify-center text-white text-[8px] flex-shrink-0">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <span class="text-[10px] font-semibold text-gray-600 truncate mt-0.5">{{ $item->category?->name ?? 'Kataloque Official' }}</span>
                            </div>
                            <!-- Group 2: Store -->
                            <div class="animate-fade-store absolute inset-x-0 bottom-0 py-1 flex items-center gap-2 px-1">
                                <div class="w-4 h-4 rounded bg-blue-600 flex items-center justify-center text-white text-[8px] flex-shrink-0">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <span class="text-[10px] font-bold text-blue-600 truncate mt-0.5">Kataloque Official</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

@push('scripts')
<script>
function productGallery(images) {
    return {
        slides: Array.isArray(images) ? images : [],
        currentSlide: 0,
        intervalId: null,
        startAutoSlide() { if (this.slides.length <= 1) return; this.intervalId = setInterval(() => { this.next(); }, 4000); },
        stopAutoSlide() { if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; } },
        next() { if (!this.slides.length) return; this.currentSlide = (this.currentSlide + 1) % this.slides.length; },
        prev() { if (!this.slides.length) return; this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length; },
        goTo(index) { this.currentSlide = index; this.stopAutoSlide(); this.startAutoSlide(); }
    }
}
</script>
@endpush
@endsection
