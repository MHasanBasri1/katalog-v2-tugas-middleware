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

    public int $favoriteCount = 0;

    public array $categories = [];

    protected $listeners = [
        'favorite-updated' => '$refresh',
        'notifications-updated' => '$refresh'
    ];

    public array $menus = [
        ['label' => 'Beranda', 'icon' => 'fa-home', 'url' => '/', 'route' => 'home'],
        ['label' => 'Blog', 'icon' => 'fa-newspaper', 'url' => '/blog', 'route' => 'blog.*'],
        ['label' => 'Tentang Kami', 'icon' => 'fa-store', 'url' => '/tentang-kami', 'route' => null],
        ['label' => 'Cara Pesan', 'icon' => 'fa-cart-arrow-down', 'url' => '/cara-pesan', 'route' => null],
        ['label' => 'Pembayaran', 'icon' => 'fa-credit-card', 'url' => '/pembayaran', 'route' => null],
        ['label' => 'Lokasi Toko', 'icon' => 'fa-map-marker-alt', 'url' => '/lokasi-toko', 'route' => null],
    ];

    public function mount(): void
    {
        $this->categories = Cache::remember(
            'public.header.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug', 'icon', 'color', 'text_color')
                ->orderBy('name')
                ->limit(12)
                ->get()
                ->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon ?? 'fa-tag',
                    'color' => $category->color ?? 'primary',
                    'text_color' => $category->text_color ?? 'text-primary',
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
        if (auth()->check()) {
            $this->favoriteCount = auth()->user()->favorites()->count();
        }

        $this->menus = array_map(function (array $menu): array {
            $menu['active'] = $menu['route'] ? request()->routeIs($menu['route']) : false;

            return $menu;
        }, $this->menus);

        return view('livewire.public.header');
    }
}
