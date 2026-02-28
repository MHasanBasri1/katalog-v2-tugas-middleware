<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public ?string $categorySlug = null;

    public array $wishlistedProductIds = [];

    public function mount(): void
    {
        $this->search = trim((string) request()->query('q', $this->search));
        $this->categorySlug = request()->query('kategori', $this->categorySlug);
    }

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->categorySlug = null;
        $this->resetPage();
    }

    public function render(): View
    {
        $searchTerm = trim($this->search);

        $categories = Cache::remember(
            'public.products.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get()
        );

        $selectedCategory = $categories->firstWhere('slug', $this->categorySlug);

        $products = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
            ->where('status', true)
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('id')
            ->paginate(8);

        $this->syncWishlistState(collect($products->items())->pluck('id')->values());

        return view('livewire.public.products-page', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
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
