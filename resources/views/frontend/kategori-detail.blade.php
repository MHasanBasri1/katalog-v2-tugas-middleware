@extends('frontend.layouts.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonical)
@section('og_url', $canonical)
@section('main_class', '')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-6">

    <div class="relative bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 overflow-hidden z-10 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Kategori: {{ $category->name }}</h1>
                    <p class="text-xs text-gray-500 font-medium mt-1">{{ $category->description ?: 'Daftar produk eksklusif berdasarkan kategori.' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kategori') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border border-gray-200 bg-gray-50 text-gray-700 hover:bg-primary hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-layer-group"></i> Semua Kategori
                </a>
            </div>
        </div>
    </div>

    @livewire('public.products-page', ['categorySlug' => $category->slug, 'showFilters' => false])
</div>
@endsection
