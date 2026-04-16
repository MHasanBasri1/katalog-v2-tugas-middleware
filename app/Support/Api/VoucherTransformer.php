<?php

namespace App\Support\Api;

use App\Models\Voucher;

class VoucherTransformer
{
    public static function transform(Voucher $voucher): array
    {
        $isClaimed = auth()->check() 
            ? \App\Models\VoucherClaim::where('user_id', auth()->id())->where('voucher_id', $voucher->id)->exists()
            : false;

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
            'is_claimed' => $isClaimed,
            'created_at' => optional($voucher->created_at)->toISOString(),
            'updated_at' => optional($voucher->updated_at)->toISOString(),
        ];
    }
}
