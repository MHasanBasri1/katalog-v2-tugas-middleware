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

    public array $trendingKeywords = [];
    public array $topMenus = [];

    public array $menus = [];

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

        $setting = Cache::remember('global.settings', now()->addDay(), fn() => \App\Models\Setting::first());
        
        // Trending Keywords
        if ($setting && !empty($setting->trending_keywords)) {
            $this->trendingKeywords = $setting->trending_keywords;
        } else {
            $this->trendingKeywords = [
                ['keyword' => 'iPhone 15 Pro', 'url' => null],
                ['keyword' => 'Samsung S24 Ultra', 'url' => null],
                ['keyword' => 'MacBook Pro M3', 'url' => null],
                ['keyword' => 'Sony WH-1000XM5', 'url' => null],
                ['keyword' => 'Logitech G Pro', 'url' => null],
                ['keyword' => 'iPad Pro M2', 'url' => null],
            ];
        }

        // Top Bar Menus
        if ($setting && !empty($setting->header_navigation)) {
            $this->topMenus = $setting->header_navigation;
        } else {
            $this->topMenus = [
                ['label' => 'Tentang Kami', 'url' => '/tentang-kami'],
                ['label' => 'Blog & Edukasi', 'url' => '/blog'],
                ['label' => 'Cara Order', 'url' => '/cara-pesan'],
            ];
        }

        // Sidebar/Mobile Menus (keep current default if not set, or we can use the same as top bar)
        $this->menus = [
            ['label' => 'Beranda', 'icon' => 'fa-home', 'url' => '/', 'route' => 'home'],
            ['label' => 'Blog', 'icon' => 'fa-newspaper', 'url' => '/blog', 'route' => 'blog.*'],
            ['label' => 'Tentang Kami', 'icon' => 'fa-store', 'url' => '/tentang-kami', 'route' => null],
            ['label' => 'Cara Pesan', 'icon' => 'fa-cart-arrow-down', 'url' => '/cara-pesan', 'route' => null],
            ['label' => 'Pembayaran', 'icon' => 'fa-credit-card', 'url' => '/pembayaran', 'route' => null],
        ];
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

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function getSearchResultsProperty(): array
    {
        $query = trim($this->search);

        if ($query === '' || mb_strlen($query) < 2) {
            return [];
        }

        $products = Product::query()
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
                'type' => 'Produk',
            ]);

        $blogs = \App\Models\Blog::query()
            ->select('title', 'slug')
            ->where('is_published', true)
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', '%' . $query . '%')
                    ->orWhere('excerpt', 'like', '%' . $query . '%')
                    ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->latest('published_at')
            ->limit(5)
            ->get()
            ->map(fn ($blog) => [
                'name' => $blog->title,
                'url' => route('blog.detail', $blog->slug),
                'type' => 'Artikel',
            ]);

        return $products->concat($blogs)->all();
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

    public function getFavoriteItemsProperty(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return \App\Models\Favorite::query()
            ->where('user_id', auth()->id())
            ->with(['product.primaryImage'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn($fav) => [
                'id' => $fav->product->id,
                'name' => $fav->product->name,
                'price' => $fav->product->price,
                'image' => $fav->product->primaryImage?->image_url ?? 'https://via.placeholder.com/50x50?text=No+Image',
                'url' => route('produk.detail', $fav->product->slug),
            ])
            ->all();
    }

    public function removeFromFavorite(int $productId): void
    {
        if (!auth()->check()) {
            return;
        }

        \App\Models\Favorite::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        $this->dispatch('favorite-updated');
    }

    public function getNotificationItemsProperty(): array
    {
        return \App\Models\Product::query()
            ->where('status', true)
            ->latest('updated_at')
            ->limit(3)
            ->get()
            ->map(fn($p) => [
                'name' => $p->name,
                'price' => $p->price,
                'url' => route('produk.detail', $p->slug),
                'time' => $p->updated_at->diffForHumans(),
            ])
            ->all();
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
