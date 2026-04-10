@props(['product', 'compactViews'])

@php
    $productDiscount = ($product->original_price && (float) $product->original_price > (float) $product->price)
        ? round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100)
        : 0;
@endphp

<div class="product-card bg-white rounded-2xl p-3 border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group h-full relative overflow-hidden active:scale-[0.98]">
    {{-- Product Image Section --}}
    <a href="{{ route('produk.detail', $product->slug) }}" class="block bg-gray-50 rounded-2xl aspect-[1/1] w-full overflow-hidden mb-3 relative">
        @if($product->primaryImage?->image)
            <x-optimized-image 
                :src="$product->primaryImage->image" 
                :alt="$product->name" 
                class="img-hover-scale w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                width="400" 
                height="400" 
                sizes="(max-width: 640px) 200px, 400px"
            />
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <i class="fas fa-box text-5xl text-gray-200"></i>
            </div>
        @endif
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1.5 items-start">
            @if($productDiscount > 0)
                <div class="bg-rose-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-md shadow-sm">
                    -{{ $productDiscount }}%
                </div>
            @endif
        </div>

        {{-- Shipping Badge --}}
        <div class="absolute bottom-2 left-2 flex items-center gap-1 bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md text-[10px] font-bold shadow-sm border border-emerald-100">
            <i class="fas fa-truck-fast text-[10px]"></i>
            <span class="text-[9px] uppercase leading-none">Gratis Ongkir</span>
        </div>
    </a>

    {{-- Product Information --}}
    <div class="px-1 flex flex-col flex-1">
        {{-- Title --}}
        <h3 class="font-bold text-gray-800 text-[13px] md:text-sm leading-snug mb-2 line-clamp-2 min-h-[36px] group-hover:text-primary transition-colors">
            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        
        {{-- Price --}}
        <div class="flex items-center gap-2 mb-2 overflow-hidden">
            <span class="text-gray-900 font-bold text-[13px] md:text-[14px] tracking-tight leading-none truncate min-w-0">
                <span class="text-[10px] font-medium mr-0.5">Rp</span>{{ number_format((float) $product->price, 0, ',', '.') }}
            </span>
            @if($productDiscount > 0)
                <span class="text-[10px] md:text-[11px] text-gray-400 line-through decoration-gray-300 truncate opacity-70 mt-0.5 min-w-0">
                    {{ number_format((float) $product->original_price, 0, ',', '.') }}
                </span>
            @endif
        </div>

        {{-- Ratings & Sold --}}
        <div class="flex items-center gap-1.5 text-[10px] font-medium mb-3">
            <div class="flex items-center gap-1">
                <i class="fas fa-star text-amber-400 text-[9px]"></i> 
                <span class="text-gray-700 font-bold">{{ number_format((float) $product->rating_avg, 1) }}</span>
            </div>
            <span class="text-gray-200">|</span>
            <span class="text-gray-500">Terjual {{ $compactViews($product->sold_count) }}</span>
        </div>

        {{-- Footer (Category) --}}
        <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-2 overflow-hidden">
            <div class="w-5 h-5 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 text-[10px] flex-shrink-0 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <i class="fas {{ $product->category?->icon ?: 'fa-folder' }}"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-500 truncate group-hover:text-gray-700 transition-colors">{{ $product->category?->name ?? 'Uncategorized' }}</span>
        </div>
    </div>
</div>
