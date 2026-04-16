<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function isExpired(): bool
    {
        if ($this->end_date && $this->end_date->isPast()) {
            return true;
        }
        return false;
    }

    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return true;
        }
        return false;
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired() && !$this->hasReachedLimit();
    }
}
