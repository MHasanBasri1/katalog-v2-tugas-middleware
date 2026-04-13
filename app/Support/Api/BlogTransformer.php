<?php

namespace App\Support\Api;

use App\Models\Blog;

class BlogTransformer
{
    public static function transform(Blog $blog): array
    {
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'excerpt' => $blog->excerpt,
            'content' => $blog->content,
            'cover_image' => self::formatImageUrl($blog->cover_image),
            'author_name' => $blog->author_name,
            'category' => $blog->category ? [
                'id' => $blog->category->id,
                'name' => $blog->category->name,
                'slug' => $blog->category->slug,
            ] : null,
            'published_at' => optional($blog->published_at)->toISOString(),
            'created_at' => optional($blog->created_at)->toISOString(),
        ];
    }

    private static function formatImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            return url($cleanPath);
        }

        return url('storage/' . $cleanPath);
    }
}
