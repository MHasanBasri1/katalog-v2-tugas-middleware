<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public int $productId;
    public bool $isFavorited = false;
    public string $class = '';

    public function mount(int $productId, string $class = ''): void
    {
        $this->productId = $productId;
        $this->class = $class;
        $this->syncState();
    }

    public function toggleFavorite(): void
    {
        if (!Auth::check()) {
            $this->dispatch('alert', type: 'info', message: 'Silakan masuk terlebih dahulu untuk menambah favorit.');
            $this->redirectRoute('user.login', navigate: true);
            return;
        }

        $userId = Auth::id();
        $favorite = Favorite::query()
            ->where('product_id', $this->productId)
            ->where('user_id', $userId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $this->isFavorited = false;
            $this->dispatch('alert', type: 'success', message: 'Dihapus dari favorit.');
        } else {
            Favorite::query()->create([
                'product_id' => $this->productId,
                'user_id' => $userId,
            ]);
            $this->isFavorited = true;
            $this->dispatch('alert', type: 'success', message: 'Berhasil ditambah ke favorit.');
        }

        $this->dispatch('favorite-updated', productId: $this->productId, isFavorited: $this->isFavorited);
    }

    public function syncState(): void
    {
        if (!Auth::check()) {
            $this->isFavorited = false;
            return;
        }

        $this->isFavorited = Favorite::query()
            ->where('product_id', $this->productId)
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function render()
    {
        return view('livewire.public.favorite-button');
    }
}
