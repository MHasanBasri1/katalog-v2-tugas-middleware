<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-bold text-gray-900">Favorit Saya</h2>
        <span class="text-xs font-semibold text-gray-500">{{ $favorites->count() }} produk</span>
    </div>
    <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-4 gap-3">
        @forelse($favorites as $favorite)
            @php $product = $favorite->product; @endphp
            @if($product)
                <div class="rounded-2xl border border-gray-100 p-3 hover:border-blue-200 transition bg-white" wire:key="fav-{{ $favorite->id }}">
                    <div class="aspect-square w-full bg-gray-100 rounded-xl overflow-hidden">
                        <a href="{{ route('produk.detail', $product->slug) }}">
                            @if($product->primaryImage?->image)
                                <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-box text-2xl"></i>
                                </div>
                            @endif
                        </a>
                    </div>
                    <p class="mt-2 text-sm font-semibold text-gray-800 leading-snug min-h-[2.5rem] break-words">
                        <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                    </p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-sm font-bold text-primary">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <button 
                        type="button"
                        wire:click.stop.prevent="removeFavorite({{ $favorite->id }})"
                        wire:key="btn-fav-{{ $favorite->id }}"
                        wire:loading.attr="disabled"
                        class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 transition disabled:opacity-50"
                    >
                        <i class="fas fa-trash text-[10px]" wire:loading.remove wire:target="removeFavorite"></i>
                        <i class="fas fa-circle-notch fa-spin text-[10px]" wire:loading wire:target="removeFavorite"></i>
                        <span wire:loading.remove wire:target="removeFavorite">Hapus Favorit</span>
                        <span wire:loading wire:target="removeFavorite">Menghapus...</span>
                    </button>
                </div>
            @endif
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-200 p-8 text-center bg-gray-50">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                    <i class="fas fa-heart text-gray-200 text-xl"></i>
                </div>
                <p class="text-gray-500 font-medium text-sm">Belum ada produk di favorit.</p>
                <a href="{{ route('katalog') }}" class="inline-block mt-4 text-xs font-bold text-primary hover:underline">Jelajahi Produk</a>
            </div>
        @endforelse
    </div>
</div>
