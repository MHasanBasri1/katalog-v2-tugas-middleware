<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Product;
use App\Support\Api\BlogTransformer;
use App\Support\Api\ProductTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends BaseApiController
{
    /**
     * Search for products and blogs.
     * Prevents spam by requiring at least 2 characters.
     */
    public function global(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return $this->success([
                'products' => [],
                'blogs' => [],
            ], 'Query pencarian terlalu pendek. Butuh minimal 2 karakter.');
        }

        $products = Product::query()
            ->where('status', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->with([
                'category:id,name,slug,icon',
                'primaryImage:id,product_id,image',
                'images',
                'marketplaceLinks',
                'reviews'
            ])
            ->orderByDesc('sold_count')
            ->limit(10)
            ->get();

        $blogs = Blog::query()
            ->where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('excerpt', 'like', '%' . $query . '%')
                    ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->with(['category'])
            ->latest('published_at')
            ->limit(5)
            ->get();

        return $this->success([
            'products' => $products->map(fn (Product $item) => ProductTransformer::transform($item))->values(),
            'blogs' => $blogs->map(fn (Blog $item) => BlogTransformer::transform($item))->values(),
        ], 'Hasil pencarian global.');
    }
}
