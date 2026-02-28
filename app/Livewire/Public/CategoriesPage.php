<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesPage extends Component
{
    use WithPagination;

    public function render(): View
    {
        $categories = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->withCount([
                'products as products_count' => fn ($query) => $query->where('status', true),
            ])
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.public.categories-page', [
            'categories' => $categories,
        ]);
    }
}
