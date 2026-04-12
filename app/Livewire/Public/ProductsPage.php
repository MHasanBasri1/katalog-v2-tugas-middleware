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

    public bool $showFilters = true;

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

    public function clearSearch(): void
    {
        $this->search = '';
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

        // Use Scout search if search term is provided, otherwise fallback to standard query
        $productsQuery = $searchTerm !== '' 
            ? Product::search($searchTerm) 
            : Product::query();

        $products = $productsQuery
            ->where('status', true)
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory->id);
            })
            ->query(fn ($query) => $query->with([
                'category:id,name,slug,icon',
                'primaryImage:id,product_id,image',
            ])->orderByDesc('id'))
            ->paginate(8);

        return view('livewire.public.products-page', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
