@extends('frontend.layouts.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonical)
@section('og_url', $canonical)
@section('main_class', '')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
            <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Kategori Produk</h1>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @forelse($categories as $category)
            <a href="{{ route('kategori.detail', $category->slug) }}" class="group relative bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-300 flex flex-col items-center text-center gap-4 overflow-hidden">
                <div class="w-16 h-16 md:w-20 md:h-20 flex-none rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center border border-blue-100 dark:border-blue-800 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fas {{ $category->icon ?: 'fa-layer-group' }} text-2xl md:text-3xl text-blue-600"></i>
                </div>
                
                <h3 class="font-black text-gray-900 dark:text-white text-sm md:text-lg leading-tight group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $category->name }}</h3>
                <p class="text-[9px] md:text-xs font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-full uppercase tracking-widest">{{ number_format($category->active_products_count) }} Produk</p>
                
                <div class="absolute bottom-0 translate-y-full group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300 inset-x-0 pb-3 md:pb-4 flex justify-center w-full">
                    <span class="inline-flex items-center gap-1.5 text-[9px] md:text-xs font-bold text-white bg-blue-600 px-3 md:px-4 py-1.5 rounded-full shadow-md">
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
