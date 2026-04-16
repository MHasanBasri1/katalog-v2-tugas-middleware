<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use App\Support\Api\VoucherTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends BaseApiController
{
    /**
     * Get list of active and valid vouchers.
     */
    public function index(): JsonResponse
    {
        $vouchers = Voucher::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->latest('id')
            ->get();

        $transformed = $vouchers->map(fn ($voucher) => VoucherTransformer::transform($voucher));

        return $this->success($transformed, 'Success fetch vouchers');
    }

    /**
     * Get single voucher detail and validate it by code.
     */
    public function show(string $code): JsonResponse
    {
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return $this->error('Voucher tidak ditemukan', 404);
        }

        if (!$voucher->is_active) {
            return $this->error('Voucher sudah tidak aktif', 400);
        }

        if ($voucher->isExpired()) {
            return $this->error('Voucher sudah kadaluarsa', 400);
        }

        if ($voucher->hasReachedLimit()) {
            return $this->error('Batas penggunaan voucher telah tercapai', 400);
        }

        return $this->success(VoucherTransformer::transform($voucher), 'Voucher valid');
    }
}
