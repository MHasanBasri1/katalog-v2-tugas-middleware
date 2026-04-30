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
                'category:id,name,slug,icon',
                'images' => fn ($query) => $query
                    ->select('id', 'product_id', 'image', 'is_primary')
                    ->orderByDesc('is_primary')
                    ->orderBy('id'),
                'marketplaceLinks:id,product_id,marketplace,url',
                'reviews',
            ])
            ->firstOrFail();

        // Increment views count and log analytics
        $product->increment('views_count');
        \App\Models\AnalyticsLog::create([
            'target_id' => $product->id,
            'target_type' => 'product',
            'activity' => 'view',
            'ip_address' => request()->ip(),
        ]);

        $settings = Setting::query()->first();
        $marketplaces = $settings?->marketplaces ?? ['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop'];
        
        $activeLower = [];
        if (!empty($marketplaces)) {
            if (!isset($marketplaces[0])) {
                $activeLower = array_keys($marketplaces);
            } else {
                $activeLower = array_map(function($m) {
                    return Str::lower(is_array($m) ? ($m['platform'] ?? '') : (string) $m);
                }, $marketplaces);
            }
        }
        $activeLower = array_filter($activeLower);

        // Normalize tiktok keys for better matching
        if (in_array('tiktok', $activeLower) && !in_array('tiktok shop', $activeLower)) $activeLower[] = 'tiktok shop';
        if (in_array('tiktok shop', $activeLower) && !in_array('tiktok', $activeLower)) $activeLower[] = 'tiktok';

        $marketplaceLinks = $product->marketplaceLinks
            ->filter(fn ($item) => in_array(Str::lower($item->marketplace), $activeLower, true))
            ->values();

        $galleryImages = $product->images
            ->map(fn($item) => \Illuminate\Support\Facades\Storage::url($item->image))
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

    public function marketplaceRedirect(int $id)
    {
        $link = \App\Models\MarketplaceLink::findOrFail($id);
        $link->increment('click_count');
        
        \App\Models\AnalyticsLog::create([
            'target_id' => $link->id,
            'target_type' => 'marketplace_link',
            'activity' => 'click',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->away($link->url);
    }
}
