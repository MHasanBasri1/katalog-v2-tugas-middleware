<?php

namespace App\Support\Api;

use App\Models\Product;

class ProductTransformer
{
    public static function transform(Product $product): array
    {
        $primaryImage = $product->primaryImage?->image;
        $firstImage = $product->images->first()?->image;
        $image = $primaryImage ?: $firstImage;

        $price = (float) $product->price;
        $originalPrice = $product->original_price !== null ? (float) $product->original_price : null;

        $discountPercent = null;
        if ($originalPrice !== null && $originalPrice > 0 && $originalPrice > $price) {
            $discountPercent = (int) round((($originalPrice - $price) / $originalPrice) * 100);
        }

        return [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $price,
            'original_price' => $originalPrice,
            'discount_percent' => $discountPercent,
            'status' => (bool) $product->status,
            'sold_count' => (int) $product->sold_count,
            'likes_count' => (int) $product->likes_count,
            'rating_avg' => (float) $product->rating_avg,
            'rating_count' => (int) $product->rating_count,
            'is_featured' => (bool) $product->is_featured,
            'show_in_promo' => (bool) $product->show_in_promo,
            'image' => $image,
            'images' => $product->images->map(fn ($img) => [
                'id' => $img->id,
                'url' => $img->image,
                'is_primary' => (bool) $img->is_primary,
            ])->values()->all(),
            'marketplace_links' => $product->marketplaceLinks->map(fn ($link) => [
                'id' => $link->id,
                'marketplace' => $link->marketplace,
                'url' => $link->url,
            ])->values()->all(),
            'created_at' => optional($product->created_at)->toISOString(),
            'updated_at' => optional($product->updated_at)->toISOString(),
        ];
    }
}
