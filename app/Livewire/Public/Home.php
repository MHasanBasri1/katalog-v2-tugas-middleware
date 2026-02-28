<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public array $wishlistedProductIds = [];

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
            'public.home.popular_categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('status', true),
                ])
                ->orderByDesc('active_products_count')
                ->limit(12)
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

        $this->syncWishlistState($displayedProductIds);

        return view('livewire.public.home', [
            'heroBanners' => $heroBanners,
            'popularCategories' => $popularCategories,
            'bestSellerProducts' => $bestSellerProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'newProducts' => $newProducts,
        ]);
    }

    public function toggleWishlist(int $productId): void
    {
        if (! Auth::check()) {
            session()->flash('status', 'Silakan daftar atau masuk terlebih dahulu untuk menggunakan wishlist.');
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

        $existing = Wishlist::query()
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();

            $this->wishlistedProductIds = array_values(array_filter(
                $this->wishlistedProductIds,
                fn ($id) => (int) $id !== $productId
            ));

            return;
        }

        Wishlist::query()->create([
            'product_id' => $productId,
            'user_id' => $userId,
        ]);

        if (! in_array($productId, $this->wishlistedProductIds, true)) {
            $this->wishlistedProductIds[] = $productId;
        }
    }

    private function syncWishlistState(Collection $productIds): void
    {
        $userId = Auth::id();

        if (! $userId || $productIds->isEmpty()) {
            $this->wishlistedProductIds = [];

            return;
        }

        $this->wishlistedProductIds = Wishlist::query()
            ->whereIn('product_id', $productIds)
            ->where('user_id', $userId)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}
