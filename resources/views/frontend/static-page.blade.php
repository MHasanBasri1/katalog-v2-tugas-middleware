@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Halaman - Kataloque')
@section('meta_description', $seoDescription ?? 'Halaman informasi Kataloque.')
@section('canonical', $canonical ?? url()->current())
@section('og_url', $canonical ?? url()->current())
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/kataloque-static-page/1200/630')
@section('main_class', '')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-8">
        <nav class="text-sm">
            <div class="bg-white/80 backdrop-blur-md border border-gray-100 shadow-sm rounded-2xl px-5 py-3">
                <div class="flex flex-wrap items-center gap-2 text-gray-500 font-medium">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-home text-xs"></i> Beranda</a>
                    <span class="text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                    <span class="font-bold text-primary">{{ $page->title }}</span>
                </div>
            </div>
        </nav>

        <article class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative shape -->
            <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-primary/5 blur-3xl -z-10"></div>
            
            <div class="mb-10 text-center">
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight tracking-tight">{{ $page->title }}</h1>
                <div class="w-16 h-1.5 bg-primary rounded-full mx-auto mt-6"></div>
                
                @if($page->excerpt)
                    <p class="text-base md:text-lg text-gray-500 mt-6 max-w-2xl mx-auto font-medium">{{ $page->excerpt }}</p>
                @endif
            </div>

            <div class="prose prose-sm md:prose-base lg:prose-lg max-w-none text-gray-600 leading-relaxed">
                <div class="mt-6 whitespace-pre-line prose-headings:font-black prose-headings:text-gray-900 prose-a:text-primary hover:prose-a:text-primary-dark prose-p:mb-5 prose-ul:my-5 prose-li:my-2">{{ $page->content }}</div>
            </div>
        </article>
    </div>
@endsection
