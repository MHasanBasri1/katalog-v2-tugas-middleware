<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public array $favoritedProductIds = [];

    public function render(): View
    {
        $heroBanners = Cache::remember(
            'public.home.hero_banners',
            now()->addMinutes(10),
            fn () => Banner::query()
                ->select('id', 'title', 'subtitle', 'image_url', 'cta_label', 'cta_url')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest('id')
                ->limit(6)
                ->get()
        );

        $popularCategories = Cache::remember(
            'public.home.categories_all',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug', 'icon', 'color')
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('status', true),
                ])
                ->orderByDesc('active_products_count')
                ->get()
        );

        $bestSellerProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $flashSaleProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->where('show_in_promo', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $newProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->latest('id')
            ->limit(10)
            ->get();

        $displayedProductIds = $bestSellerProducts
            ->pluck('id')
            ->merge($flashSaleProducts->pluck('id'))
            ->merge($newProducts->pluck('id'))
            ->unique()
            ->values();

        $this->syncFavoriteState($displayedProductIds);

        return view('livewire.public.home', [
            'heroBanners' => $heroBanners,
            'popularCategories' => $popularCategories,
            'bestSellerProducts' => $bestSellerProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'newProducts' => $newProducts,
        ]);
    }

    public function toggleFavorite(int $productId): void
    {
        if (! Auth::check()) {
            session()->flash('status', 'Silakan daftar atau masuk terlebih dahulu untuk menambah atribut favorit.');
            $this->redirectRoute('user.login', navigate: true);

            return;
        }

        $productExists = Product::query()
            ->whereKey($productId)
            ->where('status', true)
            ->exists();

        if (! $productExists) {
            return;
        }

        $userId = Auth::id();

        $existing = Favorite::query()
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();

            $this->favoritedProductIds = array_values(array_filter(
                $this->favoritedProductIds,
                fn ($id) => (int) $id !== $productId
            ));

            return;
        }

        Favorite::query()->create([
            'product_id' => $productId,
            'user_id' => $userId,
        ]);

        if (! in_array($productId, $this->favoritedProductIds, true)) {
            $this->favoritedProductIds[] = $productId;
        }
    }

    private function syncFavoriteState(Collection $productIds): void
    {
        $userId = Auth::id();

        if (! $userId || $productIds->isEmpty()) {
            $this->favoritedProductIds = [];

            return;
        }

        $this->favoritedProductIds = Favorite::query()
            ->whereIn('product_id', $productIds)
            ->where('user_id', $userId)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}
