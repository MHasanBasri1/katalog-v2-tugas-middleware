@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Detail Blog - VISTORA')
@section('meta_description', $seoDescription ?? 'Artikel VISTORA.')
@section('canonical', $canonical ?? route('blog.index'))
@section('og_url', $canonical ?? route('blog.index'))
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/vistora-blog/1200/630')
@section('og_type', 'article')
@section('main_class', '')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <nav class="text-sm">
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
                <div class="flex flex-wrap items-center gap-2 text-gray-500">
                    <a href="{{ route('home') }}" class="font-semibold text-gray-600 hover:text-primary transition">Beranda</a>
                    <span class="text-gray-300">/</span>
                    <a href="{{ route('blog.index') }}" class="font-semibold text-gray-600 hover:text-primary transition">Blog</a>
                    <span class="text-gray-300">/</span>
                    <span class="font-semibold text-primary">{{ $post->title }}</span>
                </div>
            </div>
        </nav>

        <article class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full aspect-[16/8] object-cover">
            <div class="p-5 md:p-8">
                <div class="flex flex-wrap items-center gap-2 text-xs md:text-sm text-gray-500">
                    <span>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                    <span>&bull;</span>
                    <span>{{ $post->author_name }}</span>
                    @if ($post->category)
                        <span>&bull;</span>
                        <span class="font-semibold text-blue-700">{{ $post->category->name }}</span>
                    @endif
                </div>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 mt-2 leading-tight">{{ $post->title }}</h1>

                @if ($post->tags->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span class="text-[11px] font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full px-2.5 py-1">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <p class="text-base text-gray-700 leading-relaxed mt-5">{{ $post->content }}</p>
            </div>
        </article>

        <section class="space-y-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Artikel Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($relatedPosts as $item)
                    <a href="{{ route('blog.detail', $item->slug) }}" class="bg-white border border-gray-100 rounded-2xl p-4 hover:border-blue-200 hover:shadow-sm transition">
                        <p class="text-xs text-gray-500 mb-2">{{ optional($item->published_at)->translatedFormat('d M Y') }}</p>
                        <h3 class="text-sm font-bold text-gray-800 leading-snug">{{ $item->title }}</h3>
                        @if ($item->category)
                            <p class="text-[11px] font-semibold text-blue-700 mt-2">{{ $item->category->name }}</p>
                        @endif
                    </a>
                @empty
                    <div class="text-sm text-gray-500">Belum ada artikel lain.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
