<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Support\Api\ProductTransformer;
use Illuminate\Http\JsonResponse;

class HomeController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $with = ['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'];

        $promoProducts = Product::query()
            ->where('status', true)
            ->where('show_in_promo', true)
            ->with($with)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $bestSellerProducts = Product::query()
            ->where('status', true)
            ->with($with)
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $latestProducts = Product::query()
            ->where('status', true)
            ->with($with)
            ->latest('id')
            ->limit(10)
            ->get();

        $categories = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->orderBy('name')
            ->limit(20)
            ->get();

        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'subtitle', 'image_url', 'cta_label', 'cta_url', 'sort_order']);

        return $this->success([
            'banners' => $banners,
            'categories' => $categories,
            'promo_products' => $promoProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
            'best_seller_products' => $bestSellerProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
            'latest_products' => $latestProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);
    }
}
