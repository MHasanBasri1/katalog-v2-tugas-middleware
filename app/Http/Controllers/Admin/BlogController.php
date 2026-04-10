<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $blogs = Blog::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->q . '%')
                      ->orWhere('slug', 'like', '%' . $request->q . '%');
                })
            )
            ->latest('published_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = BlogCategory::query()->orderBy('name')->get(['id', 'name', 'slug']);
        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create(): View
    {
        $categories = BlogCategory::query()->orderBy('name')->get(['id', 'name', 'slug']);
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
        $data['published_at'] = $data['published_at'] ?: now();
        $data['cover_image'] = $request->hasFile('cover_image_file')
            ? $this->storeImage($request, 'cover_image_file', 'blogs')
            : null;

        $tagsInput = $data['tags'] ?? null;
        unset($data['tags']);

        $blog = Blog::query()->create($data);
        $blog->tags()->sync($this->resolveTagIds($tagsInput));

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.blog.create')->with('status', 'Artikel blog berhasil ditambahkan. Silahkan tambah artikel lainnya.');
        }

        return redirect()->route('admin.blog.index')->with('status', 'Artikel blog berhasil ditambahkan.');
    }

    public function edit(Blog $blog): View
    {
        $categories = BlogCategory::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $blog->load(['category:id,name,slug', 'tags:id,name,slug']);
        
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validatePayload($request, $blog->id);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title'], $blog->id);
        if ($request->hasFile('cover_image_file')) {
            $this->deleteStoredImage($blog->cover_image);
            $data['cover_image'] = $this->storeImage($request, 'cover_image_file', 'blogs');
        }

        $tagsInput = $data['tags'] ?? null;
        unset($data['tags']);

        $blog->update($data);
        $blog->tags()->sync($this->resolveTagIds($tagsInput));

        return redirect()->route('admin.blog.index')->with('status', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deleteStoredImage($blog->cover_image);
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Artikel blog berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:blogs,id'],
        ]);

        $blogs = Blog::query()
            ->whereIn('id', $validated['selected_ids'])
            ->get();

        $deleted = 0;
        foreach ($blogs as $blog) {
            $this->deleteStoredImage($blog->cover_image);
            $blog->delete();
            $deleted++;
        }

        return redirect()->route('admin.blog.index')->with('status', "{$deleted} artikel blog berhasil dihapus.");
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($ignoreId)],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'cover_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:400'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'blog';
        $slug = $base;
        $counter = 1;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function resolveTagIds(?string $tags): array
    {
        if (! $tags) {
            return [];
        }

        $items = collect(explode(',', $tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values();

        if ($items->isEmpty()) {
            return [];
        }

        return $items
            ->map(function (string $name): ?int {
                $slug = Str::slug($name);
                if ($slug === '') {
                    return null;
                }

                $tag = BlogTag::query()->firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name]
                );

                if ($tag->name !== $name) {
                    $tag->update(['name' => $name]);
                }

                return $tag->id;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function storeImage(Request $request, string $field, string $directory): string
    {
        $path = $request->file($field)->store($directory, 'public');

        return Storage::url($path);
    }

    private function deleteStoredImage(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        $path = ltrim(str_replace('/storage/', '', $url), '/');
        if ($path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
