<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Support\Api\ProductTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $promoProducts = $this->promoProductsCollection($limit);
        $popularProducts = $this->popularProductsCollection($limit);
        $latestProducts = $this->latestProductsCollection($limit);

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
            'popular_products' => $popularProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
            'best_seller_products' => $popularProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
            'latest_products' => $latestProducts->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function promoProducts(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->promoProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function popularProducts(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->popularProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function latestProducts(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->latestProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    private function withRelations(): array
    {
        return ['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'];
    }

    private function promoProductsCollection(int $limit)
    {
        return Product::query()
            ->where('status', true)
            ->where('show_in_promo', true)
            ->with($this->withRelations())
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    private function popularProductsCollection(int $limit)
    {
        return Product::query()
            ->where('status', true)
            ->with($this->withRelations())
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    private function latestProductsCollection(int $limit)
    {
        return Product::query()
            ->where('status', true)
            ->with($this->withRelations())
            ->latest('id')
            ->limit($limit)
            ->get();
    }
}
