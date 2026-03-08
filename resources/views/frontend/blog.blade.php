@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Blog - Kataloque')
@section('meta_description', $seoDescription ?? 'Artikel terbaru Kataloque.')
@section('canonical', $canonical ?? route('blog.index'))
@section('og_url', $canonical ?? route('blog.index'))
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/kataloque-blog/1200/630')
@section('og_type', 'article')
@section('main_class', '')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8 relative">
        <section class="relative rounded-[2rem] bg-gradient-to-br from-primary to-primary-dark text-white p-8 md:p-12 overflow-hidden z-10">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/10 blur-3xl -z-10"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 rounded-full bg-black/10 blur-3xl -z-10"></div>
            
            <span class="inline-flex items-center gap-2 bg-white/20 text-white backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-bold tracking-widest uppercase mb-4 shadow-sm border border-white/10">
                <i class="fas fa-newspaper"></i> Kataloque Journal
            </span>
            <h1 class="text-3xl md:text-5xl font-black mt-2 leading-tight tracking-tight">Blog Kataloque</h1>
            <p class="text-base md:text-lg text-primary-light mt-4 max-w-2xl leading-relaxed">Temukan inspirasi, tips belanja, insight produk, dan panduan sederhana sebelum Anda memutuskan untuk checkout.</p>
        </section>

        <section class="bg-white border border-gray-100 rounded-[1.5rem] p-5 md:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20 -mt-6 mx-4 md:mx-8">
            <form method="GET" action="{{ route('blog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 md:items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Kategori</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 z-10">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <select name="kategori" class="w-full bg-gray-50/80 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl py-3 pl-11 pr-10 outline-none transition-all duration-300 text-sm font-medium appearance-none">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Tag</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 z-10">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <select name="tag" class="w-full bg-gray-50/80 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl py-3 pl-11 pr-10 outline-none transition-all duration-300 text-sm font-medium appearance-none">
                            <option value="">Semua Tag</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->slug }}" @selected($selectedTag === $tag->slug)>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary-dark text-white text-sm font-bold px-5 py-3 w-full shadow-md shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fas fa-filter text-xs"></i> Terapkan
                    </button>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:text-rose-500 hover:border-rose-200 text-gray-600 text-sm font-bold px-4 py-3 transition-all duration-300" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mt-8">
            @forelse($posts as $post)
                <article class="group bg-white border border-gray-100 rounded-3xl shadow-[0_2px_15px_rgb(0,0,0,0.03)] hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1">
                    <a href="{{ route('blog.detail', $post->slug) }}" class="block relative overflow-hidden aspect-[16/10] bg-gray-100">
                        <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                        @if ($post->category)
                            <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 bg-white/95 backdrop-blur-sm text-primary-dark text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/50 uppercase tracking-widest">
                                <i class="fas fa-folder-open text-primary"></i> {{ $post->category->name }}
                            </span>
                        @endif
                    </a>
                    <div class="p-6 md:p-7 flex flex-col gap-4 flex-1">
                        <div class="flex items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <span class="inline-flex items-center gap-1.5"><i class="far fa-calendar-alt text-gray-400"></i> {{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                            <span class="text-gray-300">&bull;</span>
                            <span class="inline-flex items-center gap-1.5"><i class="far fa-user text-gray-400"></i> {{ $post->author_name }}</span>
                        </div>
                        
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                            <a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a>
                        </h2>
                        
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed font-medium line-clamp-3 mb-2">{{ $post->excerpt }}</p>
                        
                        <a href="{{ route('blog.detail', $post->slug) }}" class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-dark transition-colors group/link w-fit">
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right text-[10px] group-hover/link:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <i class="fas fa-newspaper text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Kami belum mempublikasikan artikel yang sesuai dengan kriteria pencarian Anda.</p>
                </div>
            @endforelse
        </section>

        @if(method_exists($posts, 'links'))
            <div class="pt-4 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
