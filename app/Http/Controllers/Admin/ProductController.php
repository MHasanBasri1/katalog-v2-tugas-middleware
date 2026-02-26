<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category:id,name')
            ->latest('id')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

        Product::query()->create($data);

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $produk): RedirectResponse
    {
        return redirect()->route('admin.produk.edit', $produk);
    }

    public function edit(Product $produk): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.products.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, Product $produk): RedirectResponse
    {
        $data = $this->validatePayload($request, $produk->id);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name'], $produk->id);

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk): RedirectResponse
    {
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil dihapus.');
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
            'view_count' => ['required', 'integer', 'min:0'],
            'likes_count' => ['required', 'integer', 'min:0'],
            'rating_avg' => ['required', 'numeric', 'min:0', 'max:5'],
            'rating_count' => ['required', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
        ]);
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
}
