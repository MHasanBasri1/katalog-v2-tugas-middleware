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
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": @js($galleryImages),
  "description": "{{ $seoDescription }}",
  "sku": "PROD-{{ $product->id }}",
  "brand": {
    "@@type": "Brand",
    "name": "Kataloque"
  },
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "IDR",
    "price": "{{ $product->price }}",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ number_format((float) $product->rating_avg, 1) }}",
    "reviewCount": "{{ $product->sold_count ?: 1 }}"
  }
}
</script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-6">
<nav class="text-sm">
        <div class="bg-white/80 backdrop-blur-md border border-gray-100 shadow-sm rounded-2xl px-5 py-3 overflow-hidden">
            <div class="flex items-center gap-2 text-gray-500 font-medium whitespace-nowrap overflow-hidden">
                <a href="{{ route('home') }}" class="flex-shrink-0 text-gray-500 hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-home text-xs"></i> Beranda</a>
                <span class="flex-shrink-0 text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <a href="{{ route('katalog') }}" class="flex-shrink-0 text-gray-500 hover:text-primary transition-colors">Produk</a>
                @if($product->category && $product->category->slug)
                <span class="flex-shrink-0 text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <a href="{{ route('kategori.detail', $product->category->slug) }}" class="flex-shrink-0 text-gray-500 hover:text-primary transition-colors max-w-[120px] truncate">{{ $product->category->name }}</a>
                @endif
                <span class="flex-shrink-0 text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="font-bold text-primary truncate">{{ $product->name }}</span>
            </div>
        </div>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-[480px_1fr] gap-8 lg:gap-12">
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden group/gallery" x-data="productGallery(@js($galleryImages))" x-init="startAutoSlide()">
                <template x-if="slides.length">
                    <div class="relative bg-gray-50 aspect-square w-full">
                        <img :src="slides[currentSlide]" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-all duration-500 ease-out transform group-hover/gallery:scale-105" width="800" height="800">
                        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/10 to-transparent opacity-0 group-hover/gallery:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover/gallery:opacity-100 transition-opacity duration-300">
                            <button @click="prev()" class="w-12 h-12 rounded-full bg-white/80 backdrop-blur-md text-gray-800 hover:bg-primary hover:text-white shadow-xl flex items-center justify-center transition-all duration-300 -translate-x-4 group-hover/gallery:translate-x-0 hover:scale-110" aria-label="Foto Sebelumnya"><i class="fas fa-arrow-left text-sm" aria-hidden="true"></i></button>
                            <button @click="next()" class="w-12 h-12 rounded-full bg-white/80 backdrop-blur-md text-gray-800 hover:bg-primary hover:text-white shadow-xl flex items-center justify-center transition-all duration-300 translate-x-4 group-hover/gallery:translate-x-0 hover:scale-110" aria-label="Foto Berikutnya"><i class="fas fa-arrow-right text-sm" aria-hidden="true"></i></button>
                        </div>
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2.5 z-10 bg-black/20 backdrop-blur-sm px-4 py-2 rounded-full">
                            <template x-for="(_, index) in slides" :key="index">
                                <button @click="goTo(index)" :class="currentSlide === index ? 'w-8 bg-white shadow-[0_0_10px_rgba(255,255,255,0.8)]' : 'w-2 bg-white/50 hover:bg-white/80'" class="h-2 rounded-full transition-all duration-300" :aria-label="`Menuju Foto ${index + 1}`"></button>
                            </template>
                        </div>
                    </div>
                </template>
                <template x-if="!slides.length">
                    <div class="w-full aspect-square bg-gray-50 flex flex-col items-center justify-center gap-4 text-gray-300"><i class="fas fa-box-open text-6xl md:text-8xl drop-shadow-sm"></i><span class="text-sm font-medium">Image not available</span></div>
                </template>
            </div>

            <div class="hidden lg:block sticky top-[160px] bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">Share Produk</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition" aria-label="Bagikan via WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition" aria-label="Bagikan via Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook</a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('produk.detail', $product->slug) }}').then(() => alert('Link copied!'))" class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-600 text-white hover:bg-blue-700 transition shadow-md shadow-blue-200" aria-label="Salin Tautan Produk"><i class="fas fa-link" aria-hidden="true"></i> Copy Link</button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -z-10"></div>
                <div class="mb-5 space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        @if($product->category && $product->category->slug)
                            <a href="{{ route('kategori.detail', $product->category->slug) }}" class="inline-flex items-center gap-2 bg-primary/10 text-primary-dark px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide hover:bg-primary/20 transition-colors"><i class="fas {{ $product->category->icon ?: 'fa-folder' }} text-primary/70"></i> {{ $product->category->name }}</a>
                        @else
                            <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide"><i class="fas fa-folder-open opacity-50"></i> Tanpa Kategori</div>
                        @endif
                        @livewire('public.favorite-button', ['productId' => $product->id, 'class' => 'ml-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-rose-500 hover:border-rose-200 shadow-sm text-xs font-bold font-primary'], key('fav-'.$product->id))
                    </div>
                    <h1 class="text-xl lg:text-3xl font-black text-gray-900 leading-tight tracking-tight">{{ $product->name }}</h1>
                </div>

                <div class="mb-6 pt-0">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <h2 class="text-gray-900 font-black text-2xl sm:text-3xl tracking-tight">
                            <span class="text-sm md:text-lg font-medium text-gray-400 mr-0.5">Rp</span>{{ number_format((float) $product->price, 0, ',', '.') }}
                        </h2>
                        @if($product->original_price)
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 line-through text-sm sm:text-base font-medium">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</span>
                                <span class="text-rose-600 text-[10px] font-bold bg-rose-50 px-1.5 py-0.5 rounded">{{ $discountPercent }}% OFF</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 mt-3 text-xs font-semibold text-gray-500">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-amber-400 text-[10px]"></i> 
                            <span class="text-gray-800 font-bold">{{ number_format((float) $product->rating_avg, 1) }}</span>
                        </div>
                        <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                        <span><span class="text-gray-800 font-bold">{{ $compactViews($product->sold_count) }}</span> Terjual</span>
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

            {{-- Unified Tabs for Description & Reviews --}}
            <div class="bg-white rounded-2xl md:rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden" x-data="{ activeTab: 'description' }">
                {{-- Tab Header --}}
                <div class="flex border-b border-gray-100 px-4 md:px-8 bg-gray-50/50">
                    <button @click="activeTab = 'description'" 
                        :class="activeTab === 'description' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                        class="px-6 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-all duration-300">
                        Deskripsi
                    </button>
                    <button @click="activeTab = 'reviews'" 
                        :class="activeTab === 'reviews' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                        class="px-6 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-all duration-300 flex items-center gap-2">
                        Ulasan
                    </button>
                </div>

                {{-- Tab Body --}}
                <div class="p-6 md:p-8">
                    {{-- Description Tab --}}
                    <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">Detail Produk</h3>
                        </div>
                        <div class="prose prose-sm md:prose-base max-w-none text-gray-600 font-medium leading-relaxed">
                            <p>{!! nl2br(e($product->description)) ?: 'Belum ada deskripsi spesifik untuk produk ini.' !!}</p>
                        </div>
                    </div>

                    {{-- Reviews Tab --}}
                    <div x-show="activeTab === 'reviews'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">Ulasan Pembeli</h3>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-8 items-start md:items-center p-6 bg-gray-50 rounded-2xl mb-8 border border-gray-100">
                            <div class="text-center md:px-8 md:border-r border-gray-200">
                                <div class="text-5xl font-black text-gray-900 mb-1">{{ number_format((float) $product->rating_avg, 1) }}</div>
                                <div class="flex items-center justify-center gap-1 text-amber-400 mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star text-gray-200"></i>
                                </div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rating Kepuasan</div>
                            </div>
                            <div class="flex-1 space-y-2 w-full">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 w-8">
                                            <span class="text-xs font-bold text-gray-600">{{ $star }}</span>
                                            <i class="fas fa-star text-[10px] text-amber-400"></i>
                                        </div>
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-amber-400 rounded-full" style="width: {{ $star == 5 ? '85%' : ($star == 4 ? '10%' : '2%') }}"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400 w-8">{{ $star == 5 ? '85' : ($star == 4 ? '10' : '0') }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dummy Review Items --}}
                        <div class="space-y-6">
                            @php
                                $dummies = [
                                    ['name' => 'Andi Wijaya', 'date' => '2 hari yang lalu', 'rating' => 5, 'comment' => 'Barang sangat bagus, pengiriman cepat sekali. Recomended seller!'],
                                    ['name' => 'Siti Aminah', 'date' => '1 minggu yang lalu', 'rating' => 5, 'comment' => 'Kualitas produk sangat baik, produk original. Harga produk sangat baik.'],
                                ];
                            @endphp
                            @foreach($dummies as $rev)
                                <div class="pb-6 border-b border-gray-100 last:border-0">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">{{ substr($rev['name'], 0, 1) }}</div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $rev['name'] }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">{{ $rev['date'] }}</div>
                                        </div>
                                        <div class="ml-auto flex items-center gap-1 text-[10px] text-amber-400">
                                            @for($i=0; $i<$rev['rating']; $i++) <i class="fas fa-star"></i> @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed font-medium">{{ $rev['comment'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Mobile Share --}}
            <div class="lg:hidden mt-6 bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-50 text-gray-600 border border-gray-100 shadow-sm">
                        <i class="fas fa-share-alt text-sm"></i>
                    </span>
                    Share Produk
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('produk.detail', $product->slug) }}').then(() => alert('Link copied!'))" class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-600 text-white shadow-md shadow-blue-200"><i class="fas fa-link"></i> Salin Link</button>
                </div>
            </div>
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
                <x-product-card :product="$item" :compactViews="$compactViews" />
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
