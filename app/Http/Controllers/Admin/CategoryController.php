<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%')
                      ->orWhere('slug', 'like', '%' . $request->q . '%');
                })
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'text_color' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['icon'] = $data['icon'] ?: 'fa-layer-group';
        $data['color'] = $data['color'] ?: 'bg-blue-50';
        $data['text_color'] = $data['text_color'] ?: 'text-blue-500';

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

        Category::query()->create($data);

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.kategori.create')->with('status', 'Kategori berhasil ditambahkan. Silahkan tambah kategori lainnya.');
        }

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function show(Category $kategori): RedirectResponse
    {
        return redirect()->route('admin.kategori.edit', $kategori);
    }

    public function edit(Category $kategori): View
    {
        return view('admin.categories.edit', ['category' => $kategori]);
    }

    public function update(Request $request, Category $kategori): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($kategori->id)],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'text_color' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['icon'] = $data['icon'] ?: 'fa-layer-group';
        $data['color'] = $data['color'] ?: 'bg-blue-50';
        $data['text_color'] = $data['text_color'] ?: 'text-blue-500';

        $slugSource = $data['slug'] ?: $data['name'];
        $data['slug'] = $this->makeUniqueSlug($slugSource, $kategori->id);

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori): RedirectResponse
    {
        if ($kategori->products()->exists()) {
            return redirect()->route('admin.kategori.index')->with('error', 'Kategori tidak bisa dihapus karena masih dipakai produk.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('status', 'Kategori berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $categories = Category::query()
            ->whereIn('id', $validated['selected_ids'])
            ->get();

        $deleted = 0;
        $skipped = 0;

        foreach ($categories as $category) {
            if ($category->products()->exists()) {
                $skipped++;
                continue;
            }

            $category->delete();
            $deleted++;
        }

        if ($deleted === 0) {
            return redirect()->route('admin.kategori.index')->with('error', 'Tidak ada kategori yang dihapus. Pastikan kategori tidak dipakai produk.');
        }

        $message = "{$deleted} kategori berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} kategori dilewati karena masih dipakai produk.";
        }

        return redirect()->route('admin.kategori.index')->with('status', $message);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'kategori';
        $slug = $base;
        $counter = 1;

        while (
            Category::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
