<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $blogCategories = BlogCategory::query()
            ->withCount('blogs')
            ->latest('id')
            ->paginate(10);

        $editBlogCategory = null;
        if ($request->filled('edit')) {
            $editBlogCategory = BlogCategory::query()->find($request->integer('edit'));
        }

        return view('admin.blog-categories.index', compact('blogCategories', 'editBlogCategory'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.blog-kategori.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')],
        ]);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

        BlogCategory::query()->create($data);

        return redirect()->route('admin.blog-kategori.index')->with('status', 'Kategori blog berhasil ditambahkan.');
    }

    public function show(BlogCategory $blog_kategori): RedirectResponse
    {
        return redirect()->route('admin.blog-kategori.edit', $blog_kategori);
    }

    public function edit(BlogCategory $blog_kategori): RedirectResponse
    {
        return redirect()->route('admin.blog-kategori.index', ['edit' => $blog_kategori->id]);
    }

    public function update(Request $request, BlogCategory $blog_kategori): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')->ignore($blog_kategori->id)],
        ]);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name'], $blog_kategori->id);

        $blog_kategori->update($data);

        return redirect()->route('admin.blog-kategori.index')->with('status', 'Kategori blog berhasil diperbarui.');
    }

    public function destroy(BlogCategory $blog_kategori): RedirectResponse
    {
        if ($blog_kategori->blogs()->exists()) {
            return redirect()->route('admin.blog-kategori.index')->with('error', 'Kategori blog tidak bisa dihapus karena masih dipakai artikel.');
        }

        $blog_kategori->delete();

        return redirect()->route('admin.blog-kategori.index')->with('status', 'Kategori blog berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:blog_categories,id'],
        ]);

        $categories = BlogCategory::query()
            ->whereIn('id', $validated['selected_ids'])
            ->get();

        $deleted = 0;
        $skipped = 0;

        foreach ($categories as $category) {
            if ($category->blogs()->exists()) {
                $skipped++;
                continue;
            }

            $category->delete();
            $deleted++;
        }

        if ($deleted === 0) {
            return redirect()->route('admin.blog-kategori.index')->with('error', 'Tidak ada kategori blog yang dihapus. Pastikan kategori tidak dipakai artikel.');
        }

        $message = "{$deleted} kategori blog berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} kategori dilewati karena masih dipakai artikel.";
        }

        return redirect()->route('admin.blog-kategori.index')->with('status', $message);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'kategori-blog';
        $slug = $base;
        $counter = 1;

        while (
            BlogCategory::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
