<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'target_id',
        'target_type',
        'activity',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
