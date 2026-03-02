<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use App\Support\Api\ProductTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->listProducts($request);
    }

    public function topRated(Request $request): JsonResponse
    {
        $request->merge(['sort' => 'rating']);

        return $this->listProducts($request, null, 'Daftar produk rating tertinggi.');
    }

    public function bestSold(Request $request): JsonResponse
    {
        $request->merge(['sort' => 'sold']);

        return $this->listProducts($request, null, 'Daftar produk terjual terbanyak.');
    }

    public function search(Request $request): JsonResponse
    {
        return $this->listProducts($request, null, 'Hasil pencarian produk.');
    }

    public function byCategory(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()
            ->select('id', 'name', 'slug')
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->listProducts(
            $request,
            fn (Builder $query) => $query->where('category_id', $category->id),
            'Daftar produk berdasarkan kategori.',
            ['category' => $category]
        );
    }

    public function detail(string $slug): JsonResponse
    {
        return $this->show($slug);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with($this->withRelations())
            ->firstOrFail();

        $related = Product::query()
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with($this->withRelations())
            ->orderByDesc('sold_count')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return $this->success([
            'product' => ProductTransformer::transform($product),
            'related_products' => $related->map(fn ($item) => ProductTransformer::transform($item))->values(),
        ]);
    }

    private function listProducts(
        Request $request,
        ?callable $extraConstraint = null,
        string $message = 'OK',
        array $extraData = []
    ): JsonResponse {
        $perPage = (int) $request->integer('per_page', 12);
        $perPage = max(1, min(50, $perPage));

        $query = $this->buildListQuery($request);

        if ($extraConstraint) {
            $extraConstraint($query);
        }

        $this->applySorting($query, (string) $request->query('sort', 'newest'));

        $products = $query->paginate($perPage)->withQueryString();

        $data = array_merge($extraData, [
            'products' => collect($products->items())->map(fn ($product) => ProductTransformer::transform($product))->values(),
        ]);

        return $this->success(
            $data,
            $message,
            200,
            [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        );
    }

    private function buildListQuery(Request $request): Builder
    {
        return Product::query()
            ->where('status', true)
            ->with($this->withRelations())
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
    }

    private function withRelations(): array
    {
        return ['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url'];
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
