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
            'cover_image' => $blog->cover_image,
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
}
