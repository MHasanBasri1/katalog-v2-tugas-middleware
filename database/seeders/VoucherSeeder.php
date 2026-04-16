<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'PROMOHEMAT',
                'name' => 'Promo Hemat Belanja',
                'description' => 'Potongan harga Rp 50.000 untuk minimal pembelian Rp 250.000.',
                'type' => 'fixed',
                'value' => 50000,
                'min_purchase' => 250000,
                'max_discount' => null,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'KatalogDISKON',
                'name' => 'Diskon Kilat 10%',
                'description' => 'Diskon 10% maksimal Rp 100.000 untuk semua produk.',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 100000,
                'max_discount' => 100000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'usage_limit' => 50,
                'is_active' => true,
            ],
            [
                'code' => 'PENGGUNA-BARU',
                'name' => 'Spesial Pengguna Baru',
                'description' => 'Potongan langsung Rp 25.000 tanpa minimal belanja khusus pengguna baru.',
                'type' => 'fixed',
                'value' => 25000,
                'min_purchase' => 0,
                'max_discount' => null,
                'start_date' => Carbon::now(),
                'end_date' => null,
                'usage_limit' => 500,
                'is_active' => true,
            ],
        ];

        // Clear existing vouchers
        Voucher::query()->delete();

        foreach ($vouchers as $voucher) {
            Voucher::query()->create($voucher);
        }
    }
}
