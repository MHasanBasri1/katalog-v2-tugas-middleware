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

        $transformed = $vouchers->map(fn (Voucher $voucher) => VoucherTransformer::transform($voucher));

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

        return $this->success(VoucherTransformer::transform($voucher), 'Voucher valid');
    }

    /**
     * Increment voucher usage (Claim/Copy).
     */
    public function claim(string $code): JsonResponse
    {
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return $this->error('Voucher tidak ditemukan', 404);
        }

        if (!$voucher->isValid()) {
            return $this->error('Voucher sudah tidak berlaku atau kuota habis', 400);
        }

        $voucher->increment('used_count');

        return $this->success(null, 'Voucher berhasil diklaim');
    }
}
