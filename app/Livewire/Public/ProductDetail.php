<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Favorite;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ProductDetail extends Component
{
    public string $slug;

    public Product $product;

    public array $marketplaceLinks = [];

    public array $galleryImages = [];

    public $relatedProducts;

    public array $favoritedProductIds = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;

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

        $activeMarketplaces = Setting::query()->first()?->marketplaces ?? ['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop'];
        $activeLower = array_map(fn($m) => Str::lower($m), $activeMarketplaces);

        $this->marketplaceLinks = $this->product->marketplaceLinks
            ->filter(fn ($item) => in_array(Str::lower($item->marketplace), $activeLower, true))
            ->mapWithKeys(fn ($item) => [Str::lower($item->marketplace) => $item->url])
            ->toArray();

        $this->galleryImages = $this->product->images
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $this->relatedProducts = Cache::remember(
            "public.product.related.{$this->product->id}",
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->with(['primaryImage:id,product_id,image'])
                ->orderByDesc('sold_count')
                ->limit(4)
                ->get()
        );

        $this->syncFavoriteState(
            collect([$this->product->id])->merge($this->relatedProducts->pluck('id'))->unique()->values()
        );
    }

    public function render(): View
    {
        return view('livewire.public.product-detail');
    }

    public function toggleFavorite(int $productId): void
    {
        if (! Auth::check()) {
            session()->flash('status', 'Silakan daftar atau masuk terlebih dahulu untuk menambah favorit.');
            $this->redirectRoute('user.login', navigate: true);

            return;
        }

        try {
            $productExists = Product::query()
                ->whereKey($productId)
                ->where('status', true)
                ->exists();

            if (! $productExists) {
                $this->dispatch('alert', type: 'error', message: 'Produk tidak ditemukan atau tidak aktif.');
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
                
                $this->dispatch('alert', type: 'success', message: 'Produk dihapus dari favorit.');
                return;
            }

            Favorite::query()->create([
                'product_id' => $productId,
                'user_id' => $userId,
            ]);

            if (! in_array($productId, $this->favoritedProductIds)) {
                $this->favoritedProductIds[] = $productId;
            }

            $this->dispatch('alert', type: 'success', message: 'Produk berhasil ditambahkan ke favorit.');
            
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
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
