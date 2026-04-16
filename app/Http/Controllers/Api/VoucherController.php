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

        $userId = auth()->id();
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($userId, $voucher) {
            // Record the claim if not already claimed by this user
            $claim = \App\Models\VoucherClaim::query()
                ->where('user_id', $userId)
                ->where('voucher_id', $voucher->id)
                ->lockForUpdate() // Lock to prevent race conditions
                ->first();

            if (!$claim) {
                \App\Models\VoucherClaim::query()->create([
                    'user_id' => $userId,
                    'voucher_id' => $voucher->id,
                ]);
                
                // Only increment global used_count if it's the first claim by this user
                $voucher->increment('used_count');
            }
        });

        return $this->success(null, 'Voucher berhasil diklaim');
    }
}
