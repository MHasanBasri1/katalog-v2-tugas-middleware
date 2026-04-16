<?php

namespace App\Support\Api;

use App\Models\Voucher;

class VoucherTransformer
{
    public static function transform(Voucher $voucher): array
    {
        return [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'description' => $voucher->description,
            'type' => $voucher->type,
            'value' => (float) $voucher->value,
            'min_purchase' => (float) $voucher->min_purchase,
            'max_discount' => $voucher->max_discount !== null ? (float) $voucher->max_discount : null,
            'start_date' => optional($voucher->start_date)->toISOString(),
            'end_date' => optional($voucher->end_date)->toISOString(),
            'usage_limit' => (int) $voucher->usage_limit,
            'used_count' => (int) $voucher->used_count,
            'is_active' => (bool) $voucher->is_active,
            'created_at' => optional($voucher->created_at)->toISOString(),
            'updated_at' => optional($voucher->updated_at)->toISOString(),
        ];
    }
}
