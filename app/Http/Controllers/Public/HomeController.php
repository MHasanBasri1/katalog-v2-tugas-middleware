<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $heroBanners = Banner::query()
            ->select('id', 'title', 'subtitle', 'image_url', 'cta_label', 'cta_url')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest('id')
            ->limit(6)
            ->get();

        $popularCategories = Cache::remember(
            'public.home.categories_all',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug', 'icon', 'color')
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('status', true),
                ])
                ->orderByDesc('active_products_count')
                ->get()
        );

        $bestSellerProducts = Cache::remember(
            'public.home.bestseller_products',
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->with([
                    'category:id,name,slug,icon',
                    'primaryImage:id,product_id,image',
                ])
                ->orderByDesc('sold_count')
                ->latest('id')
                ->limit(10)
                ->get()
        );

        $flashSaleProducts = Cache::remember(
            'public.home.flashsale_products',
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->where('show_in_promo', true)
                ->with([
                    'category:id,name,slug,icon',
                    'primaryImage:id,product_id,image',
                ])
                ->orderByDesc('sold_count')
                ->latest('id')
                ->limit(10)
                ->get()
        );

        $newProducts = Cache::remember(
            'public.home.new_products',
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->with([
                    'category:id,name,slug,icon',
                    'primaryImage:id,product_id,image',
                ])
                ->latest('id')
                ->limit(10)
                ->get()
        );

        return view('frontend.home', [
            'heroBanners' => $heroBanners,
            'popularCategories' => $popularCategories,
            'bestSellerProducts' => $bestSellerProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'newProducts' => $newProducts,
        ]);
    }
}
