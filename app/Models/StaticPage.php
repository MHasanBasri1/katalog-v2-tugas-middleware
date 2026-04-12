<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StaticPage extends Model
{
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
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
