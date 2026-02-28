<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div>
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-layer-group"></i>
                </span>
                Kategori Populer
            </h1>
            <p class="text-sm text-gray-500 mt-1">Jelajahi kategori produk dan temukan penawaran terbaik.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
        @php
            $icons = ['fa-mobile-screen', 'fa-laptop', 'fa-tv', 'fa-headphones', 'fa-camera', 'fa-gamepad', 'fa-basket-shopping', 'fa-couch'];
        @endphp
        @forelse($categories as $category)
            <a href="{{ route('kategori.detail', $category->slug) }}" class="bg-white rounded-2xl border border-gray-100 p-3 md:p-4 hover:border-primary hover:shadow-md transition flex flex-col items-center text-center gap-2">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600">
                    <i class="fas {{ $icons[$loop->index % count($icons)] }} text-lg"></i>
                </span>
                <h3 class="font-bold text-gray-800 text-xs md:text-base leading-snug">{{ $category->name }}</h3>
                <p class="text-xs text-gray-500">{{ number_format($category->products_count) }} produk aktif</p>
                <span class="mt-1 inline-flex items-center gap-1 text-[10px] md:text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded-full border border-blue-100">
                    Lihat Kategori <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </a>
        @empty
            <div class="col-span-full text-sm text-gray-500">Belum ada kategori.</div>
        @endforelse
    </div>

    @if($categories->hasPages())
        <div class="flex justify-center items-center gap-2 pt-2">
            <a href="{{ $categories->previousPageUrl() ?: '#' }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition {{ $categories->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">Prev</a>

            @foreach($categories->getUrlRange(max(1, $categories->currentPage() - 1), min($categories->lastPage(), $categories->currentPage() + 1)) as $page => $url)
                <a href="{{ $url }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border text-sm font-semibold transition {{ $page === $categories->currentPage() ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-200 text-blue-700 bg-white hover:bg-blue-50' }}">{{ $page }}</a>
            @endforeach

            <a href="{{ $categories->nextPageUrl() ?: '#' }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50 transition {{ $categories->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">Next</a>
        </div>
    @endif
</div>
