<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category:id,name', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%')
                      ->orWhere('slug', 'like', '%' . $request->q . '%');
                })
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $marketplaces = Setting::query()->first()?->marketplaces ?? [];
        $marketplaceOptions = !empty($marketplaces) && !isset($marketplaces[0])
            ? array_map(fn($k) => Str::title($k), array_keys($marketplaces))
            : ($marketplaces ?: ['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop']);

        return view('admin.products.create', compact('categories', 'marketplaceOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $links = $data['marketplace_links'] ?? [];
        $images = $request->file('images') ?: [];
        unset($data['marketplace_links']);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

        DB::transaction(function () use ($data, $links, $images) {
            $product = Product::query()->create($data);
            $this->syncMarketplaceLinks($product, $links);
            $this->syncProductImages($product, $images);
        });

        $this->clearProductCaches();

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.produk.create')->with('status', 'Produk berhasil ditambahkan. Silahkan tambah produk lainnya.');
        }

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $produk): RedirectResponse
    {
        return redirect()->route('admin.produk.edit', $produk);
    }

    public function edit(Product $produk): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $marketplaces = Setting::query()->first()?->marketplaces ?? [];
        $marketplaceOptions = !empty($marketplaces) && !isset($marketplaces[0])
            ? array_map(fn($k) => Str::title($k), array_keys($marketplaces))
            : ($marketplaces ?: ['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop']);
        
        $produk->load(['marketplaceLinks', 'images']);

        return view('admin.products.edit', [
            'product' => $produk,
            'categories' => $categories,
            'marketplaceOptions' => $marketplaceOptions
        ]);
    }

    public function update(Request $request, Product $produk): RedirectResponse
    {
        $data = $this->validatePayload($request, $produk->id);
        $links = $data['marketplace_links'] ?? [];
        $images = $request->file('images') ?: [];
        unset($data['marketplace_links']);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name'], $produk->id);

        DB::transaction(function () use ($produk, $data, $links, $images) {
            $produk->update($data);
            $this->syncMarketplaceLinks($produk, $links);
            $this->syncProductImages($produk, $images);
        });

        $this->clearProductCaches();

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk): RedirectResponse
    {
        $produk->delete();
        $this->clearProductCaches();

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function clearProductCaches(): void
    {
        Cache::forget('public.home.bestseller_products');
        Cache::forget('public.home.flashsale_products');
        Cache::forget('public.home.new_products');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:products,id'],
        ]);

        $deleted = Product::query()
            ->whereIn('id', $validated['selected_ids'])
            ->delete();

        return redirect()->route('admin.produk.index')->with('status', "{$deleted} produk berhasil dihapus.");
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimetypes:text/plain,text/csv,text/tsv,application/csv,application/vnd.ms-excel', 'max:5120'],
        ]);

        $filePath = $request->file('csv_file')?->getRealPath();
        if (! $filePath || ! is_readable($filePath)) {
            return redirect()->route('admin.produk.index')->with('error', 'File CSV tidak dapat dibaca.');
        }

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            return redirect()->route('admin.produk.index')->with('error', 'Gagal membuka file CSV.');
        }

        $headers = fgetcsv($handle);
        if (! is_array($headers) || count($headers) === 0) {
            fclose($handle);
            return redirect()->route('admin.produk.index')->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $headers = array_map(function ($header, $index) {
            $value = trim((string) $header);
            if ($index === 0) {
                $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;
            }
            return Str::of($value)->lower()->replace(' ', '_')->toString();
        }, $headers, array_keys($headers));

        $requiredColumns = ['name', 'price'];
        $missingColumns = array_values(array_diff($requiredColumns, $headers));
        if ($missingColumns !== []) {
            fclose($handle);
            return redirect()->route('admin.produk.index')->with('error', 'Kolom wajib CSV tidak lengkap: '.implode(', ', $missingColumns).'.');
        }

        $createdCount = 0;
        $failedRows = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            if ($this->isCsvRowEmpty($row)) {
                continue;
            }

            $row = array_pad($row, count($headers), null);
            $payloadRaw = array_combine($headers, array_slice($row, 0, count($headers)));

            if (! is_array($payloadRaw)) {
                $failedRows[] = "Baris {$lineNumber}: format kolom tidak sesuai header.";
                continue;
            }

            $payload = $this->mapCsvRowToPayload($payloadRaw);

            $validator = Validator::make($payload, [
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'original_price' => ['nullable', 'numeric', 'min:0'],
                'status' => ['required', 'boolean'],
                'sold_count' => ['required', 'integer', 'min:0'],
                'likes_count' => ['required', 'integer', 'min:0'],
                'rating_avg' => ['required', 'numeric', 'min:0', 'max:5'],
                'rating_count' => ['required', 'integer', 'min:0'],
                'is_featured' => ['required', 'boolean'],
                'show_in_promo' => ['required', 'boolean'],
                'marketplace_links' => ['nullable', 'array'],
                'marketplace_links.*.marketplace' => ['nullable', 'string', 'max:100'],
                'marketplace_links.*.url' => ['nullable', 'url', 'max:2000'],
            ]);

            if ($validator->fails()) {
                $failedRows[] = "Baris {$lineNumber}: ".$validator->errors()->first();
                continue;
            }

            $data = $validator->validated();
            $links = $data['marketplace_links'] ?? [];
            unset($data['marketplace_links']);

            $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

            DB::transaction(function () use ($data, $links) {
                $product = Product::query()->create($data);
                $this->syncMarketplaceLinks($product, $links);
            });

            $createdCount++;
        }

        fclose($handle);

        if ($createdCount === 0 && $failedRows !== []) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Import gagal. Tidak ada data yang berhasil ditambahkan.')
                ->with('import_errors', array_slice($failedRows, 0, 15));
        }

        $statusMessage = "{$createdCount} produk berhasil diimport.";
        if ($failedRows !== []) {
            $statusMessage .= ' '.count($failedRows).' baris gagal diproses.';
        }

        return redirect()->route('admin.produk.index')
            ->with('status', $statusMessage)
            ->with('import_errors', array_slice($failedRows, 0, 15));
    }

    public function downloadCsvTemplate(): StreamedResponse
    {
        $headers = [
            'name',
            'price',
            'category_id',
            'category_slug',
            'category_name',
            'slug',
            'description',
            'original_price',
            'status',
            'sold_count',
            'likes_count',
            'rating_avg',
            'rating_count',
            'is_featured',
            'show_in_promo',
            'shopee_url',
            'tokopedia_url',
            'lazada_url',
            'blibli_url',
            'tiktok_shop_url',
        ];

        $example = [
            'Contoh Produk',
            '199000',
            '',
            'audio',
            '',
            'contoh-produk',
            'Deskripsi singkat produk.',
            '249000',
            '1',
            '0',
            '0',
            '4.5',
            '10',
            '0',
            '1',
            'https://shopee.co.id/example',
            'https://tokopedia.com/example',
            '',
            '',
            '',
        ];

        return response()->streamDownload(function () use ($headers, $example) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            fputcsv($output, $example);
            fclose($output);
        }, 'template-import-produk.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $headers = [
            'id',
            'name',
            'slug',
            'category_id',
            'category_name',
            'price',
            'original_price',
            'sold_count',
            'rating_avg',
            'rating_count',
            'is_featured',
            'show_in_promo',
            'status',
            'marketplace_count',
            'marketplace_urls',
        ];

        return response()->streamDownload(function () use ($headers, $request) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);

            Product::query()
                ->with(['category:id,name', 'marketplaceLinks:id,product_id,marketplace,url'])
                ->when(
                    $request->filled('category_id'),
                    fn ($query) => $query->where('category_id', $request->integer('category_id'))
                )
                ->chunkById(200, function ($products) use ($output) {
                    foreach ($products as $product) {
                        $urls = $product->marketplaceLinks
                            ->map(fn ($link) => "{$link->marketplace}:{$link->url}")
                            ->implode(' | ');

                        fputcsv($output, [
                            $product->id,
                            $product->name,
                            $product->slug,
                            $product->category_id,
                            $product->category?->name,
                            (float) $product->price,
                            $product->original_price !== null ? (float) $product->original_price : '',
                            (int) $product->sold_count,
                            (float) $product->rating_avg,
                            (int) $product->rating_count,
                            $product->is_featured ? 1 : 0,
                            $product->show_in_promo ? 1 : 0,
                            $product->status ? 1 : 0,
                            $product->marketplaceLinks->count(),
                            $urls,
                        ]);
                    }
                });

            fclose($output);
        }, 'export-produk.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function setPrimaryImage(Product $produk, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $produk->id) {
            abort(403);
        }

        $produk->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('status', 'Gambar utama berhasil diperbarui.');
    }

    public function deleteImage(Product $produk, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $produk->id) {
            abort(403);
        }

        $isPrimary = $image->is_primary;
        
        // Delete file
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        
        $image->delete();

        // If primary was deleted, set another one as primary if available
        if ($isPrimary) {
            $nextImage = $produk->images()->first();
            if ($nextImage instanceof \App\Models\ProductImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('status', 'Gambar berhasil dihapus.');
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'boolean'],
            'sold_count' => ['required', 'integer', 'min:0'],
            'likes_count' => ['required', 'integer', 'min:0'],
            'rating_avg' => ['required', 'numeric', 'min:0', 'max:5'],
            'rating_count' => ['required', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
            'show_in_promo' => ['required', 'boolean'],
            'marketplace_links' => ['nullable', 'array'],
            'marketplace_links.*.marketplace' => ['nullable', 'string', 'max:100'],
            'marketplace_links.*.url' => ['nullable', 'url', 'max:2000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);
    }

    private function syncProductImages(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        foreach ($images as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image' => $path,
                'is_primary' => ! $product->images()->exists(),
            ]);
        }
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'produk';
        $slug = $base;
        $counter = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function syncMarketplaceLinks(Product $product, array $links): void
    {
        $normalized = collect($links)
            ->map(function ($item) {
                return [
                    'marketplace' => trim((string) ($item['marketplace'] ?? '')),
                    'url' => trim((string) ($item['url'] ?? '')),
                ];
            })
            ->filter(fn ($item) => $item['marketplace'] !== '' && $item['url'] !== '')
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

    private function isCsvRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function mapCsvRowToPayload(array $row): array
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
            ->filter(fn ($url) => $url !== '')
            ->map(fn ($url, $marketplace) => ['marketplace' => $marketplace, 'url' => $url])
            ->values()
            ->all();
    }

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

    private function toIntegerValue(mixed $value, int $default): int
    {
        $normalized = trim((string) $value);
        if ($normalized === '' || ! is_numeric($normalized)) {
            return $default;
        }

        return (int) $normalized;
    }

    private function toNullableNumber(mixed $value): ?float
    {
        $normalized = trim((string) $value);
        if ($normalized === '' || ! is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }
}
