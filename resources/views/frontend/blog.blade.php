@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Blog - Kataloque')
@section('meta_description', $seoDescription ?? 'Artikel terbaru Kataloque.')
@section('canonical', $canonical ?? route('blog.index'))
@section('og_url', $canonical ?? route('blog.index'))
@section('og_image', $ogImage ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=1200&h=630&auto=format&fit=crop')
@section('og_type', 'article')
@section('main_class', '')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-8 relative">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Blog Kataloque</h1>
                    <p class="text-xs text-gray-500 font-medium mt-1">Temukan inspirasi, tips belanja, dan panduan produk.</p>
                </div>
            </div>
        </div>

        <section class="bg-white border border-gray-200 rounded-2xl p-5 md:p-8 shadow-sm relative z-20 mx-0 overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl -z-10"></div>
            
            <form method="GET" action="{{ route('blog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Search Article --}}
                <div class="md:col-span-2" x-data="{ search: '{{ request('search') }}' }">
                    <label for="blogSearchInput" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pencarian Artikel</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-primary transition-colors z-10" style="width: 44px;">
                            <i class="fas fa-search text-xs"></i>
                        </div>
                        <input id="blogSearchInput" name="search" x-model="search" type="text" placeholder="Ketik judul artikel untuk mencari..."
                            class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500 pr-10"
                            style="padding: 0.75rem 2.5rem 0.75rem 44px;">
                        
                        {{-- Clear Button --}}
                        <template x-if="search.length > 0">
                            <button type="button" @click="search = ''; $nextTick(() => $el.closest('form').submit())" class="absolute right-0 top-0 bottom-0 px-4 text-gray-400 hover:text-rose-500 transition-colors z-20">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Category Select --}}
                <div class="md:col-span-1">
                    <label for="blogCategorySelect" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Kategori</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 z-10" style="width: 44px;">
                            <i class="fas fa-folder-open text-xs"></i>
                        </div>
                        <select id="blogCategorySelect" name="kategori" 
                            class="w-full bg-gray-50/80 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-xl outline-none transition-all duration-300 text-sm font-medium appearance-none"
                            style="padding: 0.75rem 2.5rem 0.75rem 44px;">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center text-gray-400" style="width: 40px;">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="md:col-span-1 flex items-center gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white font-black py-3 px-6 rounded-xl hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-filter text-xs"></i>
                        Terapkan
                    </button>
                    <a href="{{ route('blog.index') }}" class="w-12 h-12 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-rose-500 transition-all shadow-sm" title="Reset Filter">
                        <i class="fas fa-rotate-left text-sm"></i>
                    </a>
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mt-8">
            @forelse($posts as $post)
                <article class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1">
                    <a href="{{ route('blog.detail', $post->slug) }}" class="block relative overflow-hidden aspect-[16/10] bg-gray-100">
                        <x-optimized-image 
                            :src="$post->cover_image" 
                            :alt="$post->title" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                            width="600" 
                            height="375" 
                            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                        @if ($post->category)
                            <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 bg-white text-primary-dark text-[10px] font-black px-3 py-1.5 rounded-lg shadow-sm border border-gray-200 uppercase tracking-widest">
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
                <div class="col-span-full py-20 px-4 text-center bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                    <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-3 font-primary uppercase tracking-tight">Oops! Artikel Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-8 text-sm md:text-base font-medium leading-relaxed">
                        Kami tidak dapat menemukan artikel yang sesuai dengan pencarian atau filter Anda. Silakan coba kata kunci lain.
                    </p>
                    <a href="{{ route('blog.index') }}" class="px-8 py-3 bg-primary text-white font-black rounded-xl hover:bg-primary-dark transition-all duration-300 shadow-lg shadow-primary/20 flex items-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i>
                        Tampilkan Semua Artikel
                    </a>
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
