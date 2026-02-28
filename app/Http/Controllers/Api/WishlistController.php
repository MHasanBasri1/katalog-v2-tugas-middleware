<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Wishlist;
use App\Support\Api\ProductTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $wishlists = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'product' => fn ($query) => $query
                    ->where('status', true)
                    ->with(['category:id,name,slug', 'primaryImage:id,product_id,image', 'images:id,product_id,image,is_primary', 'marketplaceLinks:id,product_id,marketplace,url']),
            ])
            ->latest('id')
            ->get();

        $products = $wishlists
            ->pluck('product')
            ->filter()
            ->unique('id')
            ->values()
            ->map(fn ($product) => ProductTransformer::transform($product));

        return $this->success($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $product = Product::query()
            ->where('id', $validated['product_id'])
            ->where('status', true)
            ->first();

        if (! $product) {
            return $this->error('Produk tidak tersedia.', 404);
        }

        Wishlist::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return $this->success(null, 'Produk ditambahkan ke wishlist.', 201);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return $this->success(null, 'Produk dihapus dari wishlist.');
    }
}
