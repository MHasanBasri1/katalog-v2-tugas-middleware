<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceLink extends Model
{
    protected $fillable = [
        'product_id',
        'marketplace',
        'url',
        'click_count',
    ];

    protected $casts = [
        'click_count' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
