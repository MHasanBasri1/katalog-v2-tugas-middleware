<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use App\Models\Setting;
use App\Services\ProductService;
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
use Illuminate\Support\Facades\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request): View
    {
        if ($request->filled('q')) {
            $products = Product::search($request->q)
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    return $query->where('category_id', $request->integer('category_id'));
                })
                ->query(fn ($query) => $query->with(['category:id,name', 'marketplaceLinks:id,product_id,marketplace,url'])->latest('id'))
                ->paginate(15)
                ->withQueryString();
        } else {
            $products = Product::query()
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    return $query->where('category_id', $request->integer('category_id'));
                })
                ->with(['category:id,name', 'marketplaceLinks:id,product_id,marketplace,url'])
                ->latest('id')
                ->paginate(15)
                ->withQueryString();
        }

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
        $payload = $this->validatePayload($request);

        // Anti-Duo Creation Check (prevent rapid multi-clicks bypassing frontend)
        $lastCreated = Product::query()
            ->where('name', $payload['name'])
            ->where('category_id', $payload['category_id'])
            ->where('created_at', '>=', now()->subSeconds(3))
            ->first();
            
        if ($lastCreated) {
            return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
        }

        return DB::transaction(function () use ($request, $payload) {
            $payload['slug'] = $this->productService->makeUniqueSlug($payload['slug'] ?: $payload['name']);

            $product = Product::query()->create($payload);

            $this->productService->syncProductImages($product, $request->file('images', []), $request->input('primary_image_index'));
            $this->productService->syncMarketplaceLinks($product, $payload['marketplace_links'] ?? []);

            $this->clearProductCaches();

            if ($request->input('action') === 'save_and_another') {
                return redirect()->route('admin.produk.create')->with('status', 'Produk berhasil ditambahkan. Silahkan tambah produk lainnya.');
            }

            return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
        });
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
        $payload = $this->validatePayload($request, $produk->id);
        
        if (trim((string) ($payload['slug'] ?? '')) === '') {
            $payload['slug'] = $this->productService->makeUniqueSlug($payload['name'], $produk->id);
        }

        $produk->update($payload);

        $this->productService->syncProductImages($produk, $request->file('images', []), $request->input('primary_image_index'));
        $this->productService->syncMarketplaceLinks($produk, $payload['marketplace_links'] ?? []);

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

            $row = array_pad($row, count($headers), null);
            $payloadRaw = array_combine($headers, array_slice($row, 0, count($headers)));

            if (! is_array($payloadRaw)) {
                $failedRows[] = "Baris {$lineNumber}: format kolom tidak sesuai header.";
                continue;
            }

            $payload = $this->productService->preparePayloadFromCsvRow($payloadRaw);

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

            $data['slug'] = $this->productService->makeUniqueSlug($data['slug'] ?: $data['name']);

            DB::transaction(function () use ($data, $links) {
                $product = Product::query()->create($data);
                $this->productService->syncMarketplaceLinks($product, $links);
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
            if ($nextImage instanceof ProductImage) {
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

    private function isCsvRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    public function syncMarketplace(Product $produk)
    {
        // Temukan tautan marketplace apa pun yang tersedia (prioritas: Tokopedia, Blibli, Shopee)
        $link = $produk->marketplaceLinks()
            ->whereIn('marketplace', ['Tokopedia', 'Blibli', 'Shopee', 'Lazada'])
            ->orderByRaw("FIELD(marketplace, 'Tokopedia', 'Blibli', 'Shopee', 'Lazada')")
            ->first();
        
        if (!$link || !$link->url) {
            return response()->json(['message' => 'Tautan marketplace belum diisi untuk produk ini.'], 422);
        }

        $scraperPath = base_path('tools/tokped-scraper/single_product.cjs');
        
        // Ensure the directory exists
        if (!file_exists($scraperPath)) {
            return response()->json(['message' => 'Scraper script tidak ditemukan di: ' . $scraperPath], 500);
        }

        // Run Node.js through Laravel Process with dynamic limit
        $limit = $produk->review_sync_limit ?: 5;
        $result = Process::timeout(60)->run("node \"$scraperPath\" \"{$link->url}\" \"$limit\"");
        if ($result->failed()) {
            return response()->json([
                'message' => 'Gagal menjalankan scraper.',
                'error' => $result->errorOutput()
            ], 500);
        }

        $data = json_decode($result->output(), true);

        if (!$data || isset($data['error'])) {
            return response()->json([
                'message' => $data['error'] ?? 'Scraper tidak mengembalikan data valid.'
            ], 500);
        }

        // Update product statistics
        $produk->update([
            'rating_avg' => $data['rating_avg'] ?? $produk->rating_avg,
            'rating_count' => $data['rating_count'] ?? $produk->rating_count,
            'sold_count' => $data['sold_count'] ?? $produk->sold_count,
            'last_sync_at' => now(),
        ]);

        // Save reviews if present
        if (!empty($data['reviews'])) {
            \App\Models\ProductReview::where('product_id', $produk->id)->delete();
            foreach ($data['reviews'] as $rev) {
                \App\Models\ProductReview::create([
                    'product_id' => $produk->id,
                    'reviewer_name' => $rev['name'],
                    'rating' => $rev['rating'],
                    'comment' => $rev['comment'],
                    'review_date' => $rev['date_text'],
                    'source' => $link->marketplace,
                ]);
            }
        }

        return response()->json([
            'rating_avg' => $produk->rating_avg,
            'rating_count' => $produk->rating_count,
            'sold_count' => $produk->sold_count,
            'last_sync_at' => $produk->last_sync_at->format('d/m/Y H:i'),
            'total_reviews' => $produk->reviews()->count(),
            'message' => 'Berhasil sinkronisasi data dari ' . $link->marketplace
        ]);
    }
}
