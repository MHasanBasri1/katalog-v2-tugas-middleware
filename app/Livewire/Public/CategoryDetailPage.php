<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class CategoryDetailPage extends Component
{
    use WithPagination;

    public string $slug;

    public Category $category;

    #[Url(as: 'q')]
    public string $search = '';

    public string $visitorToken;

    public array $wishlistedProductIds = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->visitorToken = session('visitor_token', (string) Str::uuid());
        session(['visitor_token' => $this->visitorToken]);
        $this->search = trim((string) request()->query('q', $this->search));
        $this->category = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->where('slug', $this->slug)
            ->firstOrFail();
    }

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
        $this->resetPage();
    }

    public function render(): View
    {
        $searchTerm = trim($this->search);

        $products = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->where('category_id', $this->category->id)
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            })
            ->with(['primaryImage:id,product_id,image'])
            ->orderByDesc('id')
            ->paginate(8);

        $this->syncWishlistState(collect($products->items())->pluck('id')->values());

        return view('livewire.public.category-detail-page', [
            'products' => $products,
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
