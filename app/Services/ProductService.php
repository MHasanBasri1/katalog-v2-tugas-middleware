<?php

namespace App\Services;

use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProductService
{
    /**
     * Generate a unique slug for a product.
     */
    public function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'produk';
        $slug = $base;
        $counter = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Synchronize product images with automatic WebP conversion for SEO optimization.
     */
    public function syncProductImages(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        foreach ($images as $image) {
            // Convert to WebP for optimization
            $img = Image::read($image);
            $filename = Str::random(40) . '.webp';
            $path = 'products/' . $filename;
            
            Storage::disk('public')->put($path, (string) $img->toWebp(80));

            $product->images()->create([
                'image' => $path,
                'is_primary' => !$product->images()->exists(),
            ]);
        }
    }

    /**
     * Synchronize marketplace links.
     */
    public function syncMarketplaceLinks(Product $product, array $links): void
    {
        $normalized = collect($links)
            ->map(function ($item) {
                return [
                    'marketplace' => trim((string) ($item['marketplace'] ?? '')),
                    'url' => trim((string) ($item['url'] ?? '')),
                ];
            })
            ->filter(fn($item) => $item['marketplace'] !== '' && $item['url'] !== '')
            ->unique('marketplace')
            ->values();

        $keepMarketplaces = $normalized->pluck('marketplace')->all();

        $product->marketplaceLinks()
            ->whereNotIn('marketplace', $keepMarketplaces ?: ['__none__'])
            ->delete();

        foreach ($normalized as $item) {
            MarketplaceLink::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'marketplace' => $item['marketplace'],
                ],
                ['url' => $item['url']]
            );
        }
    }

    /**
     * Map CSV row to product payload.
     */
    public function preparePayloadFromCsvRow(array $row): array
    {
        $categoryId = $this->resolveCategoryId($row);

        return [
            'category_id' => $categoryId,
            'name' => trim((string) ($row['name'] ?? '')),
            'slug' => trim((string) ($row['slug'] ?? '')) ?: null,
            'description' => trim((string) ($row['description'] ?? '')) ?: null,
            'price' => $this->toNullableNumber($row['price'] ?? null),
            'original_price' => $this->toNullableNumber($row['original_price'] ?? null),
            'status' => $this->toBooleanValue($row['status'] ?? '1', true),
            'sold_count' => $this->toIntegerValue($row['sold_count'] ?? 0, 0),
            'likes_count' => $this->toIntegerValue($row['likes_count'] ?? 0, 0),
            'rating_avg' => $this->toNullableNumber($row['rating_avg'] ?? 0) ?? 0,
            'rating_count' => $this->toIntegerValue($row['rating_count'] ?? 0, 0),
            'is_featured' => $this->toBooleanValue($row['is_featured'] ?? '0', false),
            'show_in_promo' => $this->toBooleanValue($row['show_in_promo'] ?? '0', false),
            'marketplace_links' => $this->collectMarketplaceLinks($row),
        ];
    }

    /**
     * Resolve category ID from CSV data.
     */
    private function resolveCategoryId(array $row): ?int
    {
        $categoryIdRaw = trim((string) ($row['category_id'] ?? ''));
        if ($categoryIdRaw !== '' && is_numeric($categoryIdRaw)) {
            return (int) $categoryIdRaw;
        }

        $categorySlug = trim((string) ($row['category_slug'] ?? ''));
        if ($categorySlug !== '') {
            return Category::query()
                ->where('slug', Str::slug($categorySlug))
                ->value('id');
        }

        $categoryName = trim((string) ($row['category_name'] ?? ''));
        if ($categoryName !== '') {
            return Category::query()
                ->whereRaw('LOWER(name) = ?', [Str::lower($categoryName)])
                ->value('id');
        }

        return null;
    }

    /**
     * Collect marketplace links from CSV data.
     */
    private function collectMarketplaceLinks(array $row): array
    {
        $map = [
            'Shopee' => trim((string) ($row['shopee_url'] ?? '')),
            'Tokopedia' => trim((string) ($row['tokopedia_url'] ?? '')),
            'Lazada' => trim((string) ($row['lazada_url'] ?? '')),
            'Blibli' => trim((string) ($row['blibli_url'] ?? '')),
            'Tiktok Shop' => trim((string) ($row['tiktok_shop_url'] ?? '')),
        ];

        return collect($map)
            ->filter(fn($url) => $url !== '')
            ->map(fn($url, $marketplace) => ['marketplace' => $marketplace, 'url' => $url])
            ->values()
            ->all();
    }

    /**
     * Convert value to boolean.
     */
    private function toBooleanValue(mixed $value, bool $default): bool
    {
        $normalized = Str::lower(trim((string) $value));
        if ($normalized === '') {
            return $default;
        }

        if (in_array($normalized, ['1', 'true', 'yes', 'y', 'aktif', 'published'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'n', 'nonaktif', 'draft'], true)) {
            return false;
        }

        return $default;
    }

    /**
     * Convert value to integer.
     */
    private function toIntegerValue(mixed $value, int $default): int
    {
        $normalized = trim((string) $value);
        if ($normalized === '' || !is_numeric($normalized)) {
            return $default;
        }

        return (int) $normalized;
    }

    /**
     * Convert value to nullable float.
     */
    private function toNullableNumber(mixed $value): ?float
    {
        $normalized = trim((string) $value);
        if ($normalized === '' || !is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }
}
