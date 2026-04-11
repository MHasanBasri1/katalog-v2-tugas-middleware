@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Detail Blog - Kataloque')
@section('meta_description', $seoDescription ?? 'Artikel Kataloque.')
@section('canonical', $canonical ?? route('blog.index'))
@section('og_url', $canonical ?? route('blog.index'))
@section('og_image', $ogImage ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=1200&h=630&auto=format&fit=crop')
@section('og_type', 'article')
@section('main_class', '')

@section('content')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": ["{{ $post->cover_image }}"],
  "author": {
    "@@type": "Person",
    "name": "{{ $post->author_name }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Kataloque",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ url('/logo.png') }}"
    }
  },
  "datePublished": "{{ optional($post->published_at)->toIso8601String() }}",
  "description": "{{ $seoDescription }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url()->current() }}"
  }
}
</script>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 md:pt-5 pb-8 space-y-6">
        <nav class="text-sm">
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl px-5 py-3 overflow-hidden">
                <div class="flex items-center gap-2 text-gray-500 font-medium whitespace-nowrap overflow-hidden">
                    <a href="{{ route('home') }}" class="flex-shrink-0 text-gray-500 hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-home text-xs"></i> Beranda</a>
                    <span class="flex-shrink-0 text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                    <a href="{{ route('blog.index') }}" class="flex-shrink-0 text-gray-500 hover:text-primary transition-colors">Blog</a>
                    <span class="flex-shrink-0 text-gray-300"><i class="fas fa-chevron-right text-[10px]"></i></span>
                    <span class="font-bold text-primary truncate">{{ $post->title }}</span>
                </div>
            </div>
        </nav>

        <article class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
            <!-- Blog Header -->
            <div class="p-6 md:p-12 pb-8">
                @if ($post->category)
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-gray-50 text-primary text-[10px] md:text-xs font-black px-4 py-2 rounded-xl border border-gray-200 uppercase tracking-widest">
                            <i class="fas fa-folder-open text-primary/60"></i> {{ $post->category->name }}
                        </span>
                    </div>
                @endif
                
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 leading-[1.15] tracking-tight mb-8">{{ $post->title }}</h1>
                
                <!-- Metadata Section -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 py-6 border-y border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center text-primary">
                            <i class="fas fa-user-edit text-sm"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase text-gray-400 font-black tracking-widest leading-none mb-1">Dibuat Oleh</span>
                            <span class="text-sm font-bold text-gray-800">{{ $post->author_name }}</span>
                        </div>
                    </div>
                    
                    <div class="hidden sm:block w-px h-8 bg-gray-100 mx-2"></div>
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                            <i class="far fa-calendar-alt text-sm"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase text-gray-400 font-black tracking-widest leading-none mb-1">Dipublikasi</span>
                            <span class="text-sm font-bold text-gray-800">{{ optional($post->published_at)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="px-4 md:px-12">
                <div class="rounded-2xl overflow-hidden border border-gray-200 aspect-[16/9] md:aspect-[21/9]">
                    <x-optimized-image 
                        :src="$post->cover_image" 
                        :alt="$post->title" 
                        class="w-full h-full object-cover" 
                        width="1200" 
                        height="514" 
                        :lazy="false" 
                        fetchpriority="high"
                        sizes="(max-width: 1024px) 100vw, 1200px"
                    />
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 md:p-12">
                <div class="prose prose-sm md:prose-base lg:prose-lg max-w-none text-gray-700 leading-relaxed font-medium prose-p:mb-6 prose-headings:font-black prose-headings:text-gray-900 prose-img:rounded-3xl">
                    {!! $post->content !!}
                </div>
                
                <!-- Bottom Tags & Share -->
                <div class="mt-16 pt-8 border-t border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    @if ($post->tags->isNotEmpty())
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-2">Tags:</span>
                            @foreach ($post->tags as $tag)
                                <span class="text-xs font-bold text-primary bg-primary/5 hover:bg-primary hover:text-white transition-all rounded-xl px-4 py-2 cursor-pointer border border-primary/10">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 lg:ml-auto">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Bagikan:</span>
                        <div class="flex gap-2">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.detail', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1 shadow-sm" aria-label="Bagikan ke Twitter"><i class="fab fa-twitter text-base" aria-hidden="true"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.detail', $post->slug)) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1 shadow-sm" aria-label="Bagikan ke Facebook"><i class="fab fa-facebook-f text-base" aria-hidden="true"></i></a>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.detail', $post->slug)) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1 shadow-sm" aria-label="Bagikan via WhatsApp"><i class="fab fa-whatsapp text-base" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <section class="space-y-6 mt-12 mb-8">
            <div class="flex items-center gap-3">
                <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase">Artikel Terkait</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse($relatedPosts as $item)
                    <a href="{{ route('blog.detail', $item->slug) }}" class="group bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                        <div class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">
                            <span class="inline-flex items-center gap-1.5"><i class="far fa-calendar-alt text-gray-300"></i> {{ optional($item->published_at)->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        <h3 class="text-base font-black text-gray-800 leading-snug group-hover:text-primary transition-colors line-clamp-2 mb-4">{{ $item->title }}</h3>
                        
                        @if ($item->category)
                            <div class="mt-auto pt-4 border-t border-gray-50">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-primary bg-primary/10 rounded-lg px-2.5 py-1">
                                    <i class="fas fa-folder text-primary/70"></i> {{ $item->category->name }}
                                </span>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border border-gray-100">
                        <p class="text-gray-500 font-medium">Belum ada artikel terkait.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
