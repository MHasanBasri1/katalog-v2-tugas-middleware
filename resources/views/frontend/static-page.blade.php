@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Halaman - VISTORA')
@section('meta_description', $seoDescription ?? 'Halaman informasi VISTORA.')
@section('canonical', $canonical ?? url()->current())
@section('og_url', $canonical ?? url()->current())
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/vistora-static-page/1200/630')
@section('main_class', '')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <nav class="text-sm">
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
                <div class="flex flex-wrap items-center gap-2 text-gray-500">
                    <a href="{{ route('home') }}" class="font-semibold text-gray-600 hover:text-primary transition">Beranda</a>
                    <span class="text-gray-300">/</span>
                    <span class="font-semibold text-primary">{{ $page->title }}</span>
                </div>
            </div>
        </nav>

        <article class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-8">
            <h1 class="text-2xl md:text-4xl font-black text-gray-900 leading-tight">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="text-sm md:text-base text-gray-600 mt-3">{{ $page->excerpt }}</p>
            @endif
            <div class="mt-6 text-gray-700 leading-relaxed whitespace-pre-line">{{ $page->content }}</div>
        </article>
    </div>
@endsection
