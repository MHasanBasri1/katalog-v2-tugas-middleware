<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class Header extends Component
{
    public string $search = '';

    public int $notificationCount = 1;

    public array $categories = [];

    public array $menus = [
        ['label' => 'Beranda', 'icon' => 'fa-home', 'url' => '/', 'route' => 'home'],
        ['label' => 'Blog', 'icon' => 'fa-newspaper', 'url' => '/blog', 'route' => 'blog.*'],
        ['label' => 'Flash Deals', 'icon' => 'fa-bolt', 'url' => '#', 'route' => null],
        ['label' => 'Track Order', 'icon' => 'fa-box', 'url' => '#', 'route' => null],
        ['label' => 'Return & Refund', 'icon' => 'fa-undo', 'url' => '#', 'route' => null],
        ['label' => 'Shipping Info', 'icon' => 'fa-truck', 'url' => '#', 'route' => null],
    ];

    public function mount(): void
    {
        $this->categories = Cache::remember(
            'public.header.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->limit(12)
                ->get()
                ->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])
                ->all()
        );
    }

    public function clearNotifications(): void
    {
        $this->notificationCount = 0;
    }

    public function goToSearch(): void
    {
        $query = trim($this->search);
        $this->search = $query;

        if ($query === '') {
            $this->redirectRoute('katalog');

            return;
        }

        $this->redirectRoute('katalog', ['q' => $query]);
    }

    public function getSearchResultsProperty(): array
    {
        $query = trim($this->search);

        if ($query === '') {
            return [];
        }

        return Product::query()
            ->select('name', 'slug')
            ->where('status', true)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->orderByDesc('sold_count')
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'name' => $product->name,
                'url' => route('produk.detail', $product->slug),
            ])
            ->all();
    }

    public function getFilteredCategoriesProperty(): array
    {
        if ($this->search === '') {
            return $this->categories;
        }

        return array_values(array_filter(
            $this->categories,
            fn (array $category) => Str::contains(Str::lower($category['name']), Str::lower($this->search))
        ));
    }

    public function render()
    {
        $this->menus = array_map(function (array $menu): array {
            $menu['active'] = $menu['route'] ? request()->routeIs($menu['route']) : false;

            return $menu;
        }, $this->menus);

        return view('livewire.public.header');
    }
}
