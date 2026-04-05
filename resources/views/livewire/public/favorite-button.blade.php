<button 
    type="button" 
    wire:click.stop="toggleFavorite"
    class="{{ $class }} {{ $isFavorited ? 'text-rose-500' : 'text-gray-400' }} transition-colors duration-300"
    title="{{ $isFavorited ? 'Hapus dari favorit' : 'Tambah ke favorit' }}"
>
    <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
</button>
