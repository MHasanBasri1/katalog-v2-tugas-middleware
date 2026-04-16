<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    use Searchable;

    protected function content(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Purifier::clean($value),
        );
    }
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category_id',
        'author_name',
        'is_published',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag');
    }
}
