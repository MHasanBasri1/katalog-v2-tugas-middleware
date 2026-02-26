<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\ProductView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class Home extends Component
{
    public string $visitorToken;

    public bool $hasTrackedViews = false;

    public array $wishlistedProductIds = [];

    public function mount(): void
    {
        $this->visitorToken = session('visitor_token', (string) Str::uuid());
        session(['visitor_token' => $this->visitorToken]);
    }

    public function render(): View
    {
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
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('sold_count')
            ->limit(4)
            ->get();

        $flashSaleProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->where(function ($query) {
                $query->where('is_featured', true)
                    ->orWhereColumn('original_price', '>', 'price');
            })
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByRaw('(original_price - price) DESC')
            ->orderByDesc('sold_count')
            ->limit(4)
            ->get();

        $newProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->latest('id')
            ->limit(8)
            ->get();

        $displayedProductIds = $bestSellerProducts
            ->pluck('id')
            ->merge($flashSaleProducts->pluck('id'))
            ->merge($newProducts->pluck('id'))
            ->unique()
            ->values();

        $this->syncWishlistState($displayedProductIds);

        if (! $this->hasTrackedViews) {
            $this->trackProductViews($displayedProductIds);
            $this->hasTrackedViews = true;

            $bestSellerProducts->each(function (Product $product) use ($displayedProductIds): void {
                if ($displayedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });

            $flashSaleProducts->each(function (Product $product) use ($displayedProductIds): void {
                if ($displayedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });

            $newProducts->each(function (Product $product) use ($displayedProductIds): void {
                if ($displayedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });
        }

        return view('livewire.public.home', [
            'popularCategories' => $popularCategories,
            'bestSellerProducts' => $bestSellerProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'newProducts' => $newProducts,
        ]);
    }

    public function toggleWishlist(int $productId): void
    {
        $productExists = Product::query()
            ->whereKey($productId)
            ->where('status', true)
            ->exists();

        if (! $productExists) {
            return;
        }

        $userId = Auth::id();

        $existing = ProductLike::query()
            ->where('product_id', $productId)
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('visitor_token', $this->visitorToken);
                } else {
                    $query->where('visitor_token', $this->visitorToken);
                }
            })
            ->first();

        if ($existing) {
            $existing->delete();
            Product::query()->whereKey($productId)->where('likes_count', '>', 0)->decrement('likes_count');

            $this->wishlistedProductIds = array_values(array_filter(
                $this->wishlistedProductIds,
                fn ($id) => (int) $id !== $productId
            ));

            return;
        }

        ProductLike::query()->create([
            'product_id' => $productId,
            'user_id' => $userId,
            'visitor_token' => $this->visitorToken,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
        ]);

        Product::query()->whereKey($productId)->increment('likes_count');

        if (! in_array($productId, $this->wishlistedProductIds, true)) {
            $this->wishlistedProductIds[] = $productId;
        }
    }

    private function trackProductViews(Collection $productIds): void
    {
        if ($productIds->isEmpty()) {
            return;
        }

        $now = now();

        $existingViews = ProductView::query()
            ->where('visitor_token', $this->visitorToken)
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');

        foreach ($productIds as $productId) {
            $existing = $existingViews->get($productId);

            if ($existing) {
                $existing->increment('view_count');
                $existing->update([
                    'last_viewed_at' => $now,
                    'ip_address' => request()->ip(),
                    'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
                ]);

                continue;
            }

            ProductView::query()->create([
                'product_id' => $productId,
                'visitor_token' => $this->visitorToken,
                'view_count' => 1,
                'last_viewed_at' => $now,
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
            ]);
        }

        Product::query()->whereIn('id', $productIds)->increment('view_count');
    }

    private function syncWishlistState(Collection $productIds): void
    {
        if ($productIds->isEmpty()) {
            $this->wishlistedProductIds = [];

            return;
        }

        $userId = Auth::id();

        $this->wishlistedProductIds = ProductLike::query()
            ->whereIn('product_id', $productIds)
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('visitor_token', $this->visitorToken);
                } else {
                    $query->where('visitor_token', $this->visitorToken);
                }
            })
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}
