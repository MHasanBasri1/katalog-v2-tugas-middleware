@extends('frontend.layouts.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonical)
@section('og_url', $canonical)
@section('main_class', '')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
            <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Kategori Produk</h1>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @php
            $icons = ['fa-mobile-screen', 'fa-laptop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-gamepad', 'fa-basket-shopping', 'fa-couch', 'fa-shoe-prints', 'fa-shirt'];
        @endphp
        @forelse($categories as $category)
            <a href="{{ route('kategori.detail', $category->slug) }}" class="group relative bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-sm hover:shadow-xl hover:border-primary/20 transition-all duration-300 flex flex-col items-center text-center gap-2 md:gap-3 overflow-hidden">
                <div class="absolute inset-x-0 -bottom-2 h-1/2 bg-gradient-to-t from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                
                <div class="w-12 h-12 md:w-16 md:h-16 flex-none rounded-2xl bg-gray-50 flex items-center justify-center group-hover:bg-primary group-hover:scale-[1.10] transition-transform duration-300 shadow-sm border border-gray-200 group-hover:border-primary">
                    <i class="fas {{ $icons[$loop->index % count($icons)] }} text-lg md:text-2xl text-gray-400 group-hover:text-white transition-colors duration-300"></i>
                </div>
                
                <h3 class="font-black text-gray-800 text-xs md:text-lg leading-snug group-hover:text-primary transition-colors flex-1">{{ $category->name }}</h3>
                <p class="text-[10px] md:text-xs font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full group-hover:bg-primary/10 group-hover:text-primary-dark transition-colors">{{ number_format($category->active_products_count) }} Produk</p>
                
                <div class="absolute bottom-0 translate-y-full group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300 inset-x-0 pb-3 md:pb-4 flex justify-center w-full">
                    <span class="inline-flex items-center gap-1.5 text-[9px] md:text-xs font-bold text-white bg-primary px-3 md:px-4 py-1.5 rounded-full shadow-md">
                        Lihat Produk <i class="fas fa-arrow-right text-[8px] md:text-[10px]"></i>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fas fa-layer-group text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Kategori</h3>
                <p class="text-gray-500 max-w-md mx-auto">Kategori produk belum ditambahkan ke dalam sistem.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
