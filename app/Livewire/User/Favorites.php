<?php

namespace App\Livewire\User;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Favorites extends Component
{
    public function render()
    {
        return view('livewire.user.favorites', [
            'favorites' => Favorite::where('user_id', Auth::id())
                ->with(['product.primaryImage'])
                ->latest('id')
                ->get()
        ]);
    }

    public function removeFavorite($favoriteId): void
    {
        \Log::info('Attempting to remove favorite', ['id' => $favoriteId]);
        try {
            $id = (int)$favoriteId;
            $favorite = Favorite::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if ($favorite) {
                $favorite->delete();
                $this->loadFavorites();
                \Log::info('Favorite deleted successfully');
                $this->dispatch('alert', type: 'success', message: 'Produk berhasil dihapus dari favorit.');
            } else {
                \Log::warning('Favorite not found for deletion', ['favoriteId' => $favoriteId]);
                $this->dispatch('alert', type: 'warning', message: 'Data tidak ditemukan atau sudah terhapus.');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting favorite', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', message: 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
