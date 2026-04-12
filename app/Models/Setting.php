<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'shop_name',
        'shop_logo',
        'shop_description',
        'shop_address',
        'city',
        'province',
        'phone',
        'whatsapp',
        'email',
        'website',
        'facebook',
        'instagram',
        'footer_text',
        'favicon',
        'marketplaces',
        'social_media',
        'seo_settings',
        'system_settings',
        'is_maintenance',
    ];

    protected $casts = [
        'marketplaces' => 'array',
        'social_media' => 'array',
        'seo_settings' => 'array',
        'system_settings' => 'array',
        'is_maintenance' => 'boolean',
    ];
}
