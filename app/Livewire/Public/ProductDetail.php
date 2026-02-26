<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\ProductView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class ProductDetail extends Component
{
    public string $slug;

    public Product $product;

    public string $visitorToken;

    public array $marketplaceLinks = [];

    public array $galleryImages = [];

    public $relatedProducts;

    public array $wishlistedProductIds = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->visitorToken = session('visitor_token', (string) Str::uuid());
        session(['visitor_token' => $this->visitorToken]);

        $this->product = Product::query()
            ->select([
                'id',
                'category_id',
                'name',
                'slug',
                'description',
                'price',
                'original_price',
                'sold_count',
                'view_count',
                'rating_avg',
                'rating_count',
            ])
            ->where('slug', $this->slug)
            ->where('status', true)
            ->with([
                'category:id,name',
                'images' => fn ($query) => $query
                    ->select('id', 'product_id', 'image', 'is_primary')
                    ->orderByDesc('is_primary')
                    ->orderBy('id'),
                'marketplaceLinks:id,product_id,marketplace,url',
            ])
            ->firstOrFail();

        $this->marketplaceLinks = $this->product->marketplaceLinks
            ->mapWithKeys(fn ($item) => [Str::lower($item->marketplace) => $item->url])
            ->toArray();

        $this->galleryImages = $this->product->images
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $this->trackView();

        $this->relatedProducts = Cache::remember(
            "public.product.related.{$this->product->id}",
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->with(['primaryImage:id,product_id,image'])
                ->orderByDesc('sold_count')
                ->limit(4)
                ->get()
        );

        $this->syncWishlistState(
            collect([$this->product->id])->merge($this->relatedProducts->pluck('id'))->unique()->values()
        );
    }

    public function render(): View
    {
        return view('livewire.public.product-detail');
    }

    private function trackView(): void
    {
        $existing = ProductView::query()
            ->where('product_id', $this->product->id)
            ->where('visitor_token', $this->visitorToken)
            ->first();

        if ($existing) {
            $existing->increment('view_count');
            $existing->update([
                'last_viewed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
            ]);
        } else {
            ProductView::query()->create([
                'product_id' => $this->product->id,
                'visitor_token' => $this->visitorToken,
                'view_count' => 1,
                'last_viewed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
            ]);
        }

        Product::query()->whereKey($this->product->id)->increment('view_count');
        $this->product->view_count++;
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
