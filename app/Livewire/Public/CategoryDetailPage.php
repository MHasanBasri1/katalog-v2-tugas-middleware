<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
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

    public function mount(string $slug): void
    {
        $this->slug = $slug;
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
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
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

        return view('livewire.public.category-detail-page', [
            'products' => $products,
        ]);
    }
}
