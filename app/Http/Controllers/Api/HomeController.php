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
            ->get(['id', 'title', 'subtitle', 'image_url', 'cta_label', 'cta_url', 'sort_order'])
            ->map(function ($banner) {
                if ($banner->image_url && !str_starts_with($banner->image_url, 'http')) {
                    $banner->image_url = url(str_starts_with($banner->image_url, 'storage/') ? $banner->image_url : 'storage/' . $banner->image_url);
                }
                return $banner;
            });

        return $this->success([
            'banners' => $banners,
            'categories' => $categories->map(function ($cat) {
                if ($cat->icon && !str_starts_with($cat->icon, 'http') && (str_contains($cat->icon, '.') || str_contains($cat->icon, '/'))) {
                    $cat->icon = url(str_starts_with($cat->icon, 'storage/') ? $cat->icon : 'storage/' . $cat->icon);
                }
                return $cat;
            }),
            'flashsale_products' => $promoProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            'terlaris_products' => $popularProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            'best_seller_products' => $popularProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            'terbaru_products' => $latestProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            // Keep original keys for compatibility
            'promo_products' => $promoProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            'popular_products' => $popularProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
            'latest_products' => $latestProducts->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function promoProducts(Request $request): JsonResponse
    {
        return $this->flashSale($request);
    }

    public function flashSale(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->promoProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function popularProducts(Request $request): JsonResponse
    {
        return $this->terlaris($request);
    }

    public function terlaris(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->popularProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    public function latestProducts(Request $request): JsonResponse
    {
        return $this->terbaru($request);
    }

    public function terbaru(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $products = $this->latestProductsCollection($limit);

        return $this->success([
            'products' => $products->map(fn (Product $product) => ProductTransformer::transform($product))->values(),
        ]);
    }

    private function withRelations(): array
    {
        return ['category:id,name,slug,icon', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'];
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
