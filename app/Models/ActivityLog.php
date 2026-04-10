<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'activity_type',
        'icon',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
