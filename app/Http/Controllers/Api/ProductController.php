<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Support\Api\ProductTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->integer('per_page', 12);
        $perPage = max(1, min(50, $perPage));

        $query = Product::query()
            ->where('status', true)
            ->with(['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->when(
                $request->filled('q'),
                fn (Builder $builder) => $builder->where(function (Builder $q) use ($request) {
                    $keyword = trim((string) $request->query('q'));
                    $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                })
            )
            ->when(
                $request->filled('category_id'),
                fn (Builder $builder) => $builder->where('category_id', (int) $request->query('category_id'))
            )
            ->when(
                $request->filled('category_slug'),
                fn (Builder $builder) => $builder->whereHas('category', fn (Builder $q) => $q->where('slug', (string) $request->query('category_slug')))
            )
            ->when(
                $request->filled('min_price'),
                fn (Builder $builder) => $builder->where('price', '>=', (float) $request->query('min_price'))
            )
            ->when(
                $request->filled('max_price'),
                fn (Builder $builder) => $builder->where('price', '<=', (float) $request->query('max_price'))
            )
            ->when(
                $request->has('promo'),
                fn (Builder $builder) => $builder->where('show_in_promo', filter_var($request->query('promo'), FILTER_VALIDATE_BOOLEAN))
            )
            ->when(
                $request->has('featured'),
                fn (Builder $builder) => $builder->where('is_featured', filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN))
            );

        $this->applySorting($query, (string) $request->query('sort', 'newest'));

        $products = $query->paginate($perPage)->withQueryString();

        return $this->success(
            collect($products->items())->map(fn ($product) => ProductTransformer::transform($product))->values(),
            'OK',
            200,
            [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        );
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with(['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->firstOrFail();

        $related = Product::query()
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return $this->success([
            'product' => ProductTransformer::transform($product),
            'related_products' => $related->map(fn ($item) => ProductTransformer::transform($item))->values(),
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
