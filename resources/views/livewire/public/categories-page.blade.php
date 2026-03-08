<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="mb-6 md:mb-8 text-center sm:text-left">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 flex items-center justify-center sm:justify-start gap-2 md:gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-primary to-primary-dark text-white shadow-lg shadow-primary/30">
                <i class="fas fa-layer-group text-lg md:text-xl"></i>
            </span>
            Kategori Produk
        </h1>
        <p class="text-gray-500 mt-2 text-sm md:text-base">Jelajahi berbagai kategori produk dan temukan penawaran terbaik untuk Anda.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
        @php
            $icons = ['fa-mobile-screen', 'fa-laptop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-gamepad', 'fa-basket-shopping', 'fa-couch', 'fa-shoe-prints', 'fa-shirt'];
        @endphp
        @forelse($categories as $category)
            <a href="{{ route('kategori.detail', $category->slug) }}" class="group relative bg-white rounded-2xl md:rounded-3xl border border-gray-100 p-4 md:p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-xl hover:shadow-primary/10 hover:border-primary/20 transition-all duration-300 flex flex-col items-center text-center gap-2 md:gap-3 overflow-hidden">
                <div class="absolute inset-x-0 -bottom-2 h-1/2 bg-gradient-to-t from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                
                <div class="w-12 h-12 md:w-16 md:h-16 flex-none rounded-2xl bg-gray-50 flex items-center justify-center group-hover:bg-primary group-hover:scale-[1.15] transition-transform duration-300 shadow-sm border border-gray-100 group-hover:border-primary">
                    <i class="fas {{ $icons[$loop->index % count($icons)] }} text-lg md:text-2xl text-gray-400 group-hover:text-white transition-colors duration-300"></i>
                </div>
                
                <h3 class="font-black text-gray-800 text-xs md:text-lg leading-snug group-hover:text-primary transition-colors flex-1">{{ $category->name }}</h3>
                <p class="text-[10px] md:text-xs font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full group-hover:bg-primary/10 group-hover:text-primary-dark transition-colors">{{ number_format($category->products_count) }} Produk</p>
                
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

    @if($categories->hasPages())
        <div class="flex justify-center items-center gap-2 pt-8">
            <a href="{{ $categories->previousPageUrl() ?: '#' }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold bg-white hover:bg-gray-50 hover:text-primary transition-colors shadow-sm {{ $categories->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}">
                <i class="fas fa-chevron-left mr-1 text-xs"></i> Prev
            </a>

            @foreach($categories->getUrlRange(max(1, $categories->currentPage() - 1), min($categories->lastPage(), $categories->currentPage() + 1)) as $page => $url)
                <a href="{{ $url }}" class="w-10 h-10 inline-flex items-center justify-center rounded-xl border text-sm font-bold transition-all shadow-sm {{ $page === $categories->currentPage() ? 'border-primary bg-primary text-white shadow-primary/20 scale-105' : 'border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:text-primary hover:-translate-y-0.5' }}">{{ $page }}</a>
            @endforeach

            <a href="{{ $categories->nextPageUrl() ?: '#' }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold bg-white hover:bg-gray-50 hover:text-primary transition-colors shadow-sm {{ $categories->hasMorePages() ? '' : 'pointer-events-none opacity-40' }}">
                Next <i class="fas fa-chevron-right ml-1 text-xs"></i>
            </a>
        </div>
    @endif
</div>
