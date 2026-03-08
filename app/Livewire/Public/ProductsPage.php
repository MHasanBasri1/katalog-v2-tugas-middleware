<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
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

    public array $favoritedProductIds = [];

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

        $this->syncFavoriteState(collect($products->items())->pluck('id')->values());

        return view('livewire.public.products-page', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function toggleFavorite(int $productId): void
    {
        if (! Auth::check()) {
            $this->dispatch('alert', type: 'info', message: 'Silakan masuk terlebih dahulu untuk menambah favorit.');
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

                $this->dispatch('alert', type: 'success', message: 'Dihapus dari favorit.');
                return;
            }

            Favorite::query()->create([
                'product_id' => $productId,
                'user_id' => $userId,
            ]);

            if (! in_array($productId, $this->favoritedProductIds)) {
                $this->favoritedProductIds[] = $productId;
            }

            $this->dispatch('alert', type: 'success', message: 'Berhasil ditambah ke favorit.');

        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Gagal memproses favorit: ' . $e->getMessage());
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
