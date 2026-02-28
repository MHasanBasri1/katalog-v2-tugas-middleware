@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Blog - VISTORA')
@section('meta_description', $seoDescription ?? 'Artikel terbaru VISTORA.')
@section('canonical', $canonical ?? route('blog.index'))
@section('og_url', $canonical ?? route('blog.index'))
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/vistora-blog/1200/630')
@section('og_type', 'article')
@section('main_class', '')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
        <section class="rounded-3xl bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-8 md:px-10 md:py-10">
            <p class="text-xs md:text-sm uppercase tracking-wider font-semibold text-blue-100">VISTORA Journal</p>
            <h1 class="text-2xl md:text-4xl font-black mt-2">Blog VISTORA</h1>
            <p class="text-sm md:text-base text-blue-100 mt-3 max-w-2xl">Tips belanja, insight produk, dan panduan sederhana sebelum checkout.</p>
        </section>

        <section class="bg-white border border-gray-100 rounded-2xl p-4 md:p-5 shadow-sm">
            <form method="GET" action="{{ route('blog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 md:items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
                    <select name="kategori" class="w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tag</label>
                    <select name="tag" class="w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Semua Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->slug }}" @selected($selectedTag === $tag->slug)>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 w-full">
                        Filter
                    </button>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 hover:border-gray-300 text-sm font-semibold px-4 py-2.5">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($posts as $post)
                <article class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                    <a href="{{ route('blog.detail', $post->slug) }}" class="block">
                        <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full aspect-[16/10] object-cover">
                    </a>
                    <div class="p-4 md:p-5 flex flex-col gap-3 h-full">
                        <p class="text-xs text-gray-500">{{ optional($post->published_at)->translatedFormat('d M Y') }} &bull; {{ $post->author_name }}</p>
                        @if ($post->category)
                            <p class="text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-100 rounded-full px-2.5 py-1 w-fit">{{ $post->category->name }}</p>
                        @endif
                        <h2 class="text-base md:text-lg font-bold text-gray-900 leading-snug">
                            <a href="{{ route('blog.detail', $post->slug) }}" class="hover:text-primary transition">{{ $post->title }}</a>
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.detail', $post->slug) }}" class="mt-auto inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                            Baca artikel
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-sm text-gray-500">Belum ada artikel blog.</div>
            @endforelse
        </section>

        @if(method_exists($posts, 'links'))
            <div class="pt-2">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
