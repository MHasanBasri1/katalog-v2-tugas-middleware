<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Support\Api\BlogTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min(50, $perPage));

        $blogs = Blog::query()
            ->where('is_published', true)
            ->with(['category'])
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->query('category_id'))
            )
            ->when(
                $request->filled('category_slug'),
                fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $request->query('category_slug')))
            )
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(function ($q) use ($request) {
                    $search = trim((string) $request->query('q'));
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                })
            )
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();

        return $this->success([
            'blogs' => collect($blogs->items())->map(fn (Blog $blog) => BlogTransformer::transform($blog))->values(),
        ], 'Daftar blog.', 200, [
            'current_page' => $blogs->currentPage(),
            'last_page' => $blogs->lastPage(),
            'per_page' => $blogs->perPage(),
            'total' => $blogs->total(),
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = \App\Models\BlogCategory::query()
            ->withCount(['blogs' => fn ($q) => $q->where('is_published', true)])
            ->orderBy('name')
            ->get();

        return $this->success($categories, 'Daftar kategori blog.');
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['category'])
            ->firstOrFail();

        return $this->success([
            'blog' => BlogTransformer::transform($blog),
        ]);
    }

    public function related(Request $request, string $slug): JsonResponse
    {
        $blog = Blog::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $limit = (int) $request->integer('limit', 4);
        $limit = max(1, min(10, $limit));

        $related = Blog::query()
            ->where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->with(['category'])
            ->latest('published_at')
            ->limit($limit)
            ->get();

        // If category has no other blogs, get latest blogs
        if ($related->isEmpty()) {
            $related = Blog::query()
                ->where('is_published', true)
                ->where('id', '!=', $blog->id)
                ->with(['category'])
                ->latest('published_at')
                ->limit($limit)
                ->get();
        }

        return $this->success([
            'blogs' => $related->map(fn (Blog $item) => BlogTransformer::transform($item))->values(),
        ], 'Artikel terkait.');
    }
}
