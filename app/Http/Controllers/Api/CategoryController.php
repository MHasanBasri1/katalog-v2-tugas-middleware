<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use App\Support\Api\ProductTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->integer('per_page', 20);
        $perPage = max(1, min(50, $perPage));

        $categories = Category::query()
            ->when(
                $request->filled('q'),
                fn (Builder $query) => $query->where('name', 'like', '%' . trim((string) $request->query('q')) . '%')
            )
            ->withCount(['products as products_count' => fn (Builder $query) => $query->where('status', true)])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return $this->success($categories->items(), 'OK', 200, [
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
        ]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->where('slug', $slug)
            ->firstOrFail();

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min(50, $perPage));

        $query = Product::query()
            ->where('status', true)
            ->where('category_id', $category->id)
            ->with(['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->when(
                $request->filled('q'),
                fn (Builder $builder) => $builder
                    ->where('name', 'like', '%' . trim((string) $request->query('q')) . '%')
            );

        $this->applySorting($query, (string) $request->query('sort', 'newest'));

        $products = $query->paginate($perPage)->withQueryString();

        return $this->success([
            'category' => $category,
            'products' => collect($products->items())->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ], 'OK', 200, [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ]);
    }

    private function applySorting(Builder $query, string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'sold' => $query->orderByDesc('sold_count')->orderByDesc('id'),
            'rating' => $query->orderByDesc('rating_avg')->orderByDesc('id'),
            default => $query->latest('id'),
        };
    }
}
