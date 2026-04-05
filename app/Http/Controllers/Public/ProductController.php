<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::query()
            ->select([
                'id',
                'category_id',
                'name',
                'slug',
                'description',
                'price',
                'original_price',
                'sold_count',
                'rating_avg',
                'rating_count',
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->with([
                'category:id,name',
                'images' => fn ($query) => $query
                    ->select('id', 'product_id', 'image', 'is_primary')
                    ->orderByDesc('is_primary')
                    ->orderBy('id'),
                'marketplaceLinks:id,product_id,marketplace,url',
            ])
            ->firstOrFail();

        $settings = Setting::query()->first();
        $marketplaces = $settings?->marketplaces ?? ['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop'];
        $activeLower = array_map(fn($m) => Str::lower($m), $marketplaces);

        $marketplaceLinks = $product->marketplaceLinks
            ->filter(fn ($item) => in_array(Str::lower($item->marketplace), $activeLower, true))
            ->mapWithKeys(fn ($item) => [Str::lower($item->marketplace) => $item->url])
            ->toArray();

        $galleryImages = $product->images
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $relatedProducts = Cache::remember(
            "public.product.related.{$product->id}",
            now()->addMinutes(10),
            fn () => Product::query()
                ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'rating_avg', 'rating_count')
                ->where('status', true)
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->with(['primaryImage:id,product_id,image'])
                ->orderByDesc('sold_count')
                ->limit(4)
                ->get()
        );

        $seoTitle = "{$product->name} - Kataloque";
        $seoDescription = $product->description ? Str::limit(strip_tags($product->description), 160) : "Beli {$product->name} harga terbaik di Kataloque.";
        $canonical = route('produk.detail', $product->slug);
        $ogImage = $galleryImages[0] ?? '';

        return view('frontend.detail', compact(
            'product', 
            'marketplaceLinks', 
            'galleryImages', 
            'relatedProducts',
            'seoTitle',
            'seoDescription',
            'canonical',
            'ogImage'
        ));
    }
}
