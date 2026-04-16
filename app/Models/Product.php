<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'status',
        'sold_count',
        'views_count',
        'likes_count',
        'rating_avg',
        'rating_count',
        'is_featured',
        'show_in_promo',
        'is_sync_enabled',
        'last_sync_at',
        'review_sync_limit',
    ];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_promo' => 'boolean',
        'is_sync_enabled' => 'boolean',
        'rating_avg' => 'float',
        'views_count' => 'integer',
        'sold_count' => 'integer',
        'last_sync_at' => 'datetime',
        'review_sync_limit' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function marketplaceLinks(): HasMany
    {
        return $this->hasMany(MarketplaceLink::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->latest();
    }
}
