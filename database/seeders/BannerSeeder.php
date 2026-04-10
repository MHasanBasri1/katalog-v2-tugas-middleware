<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Gadget Terbaru & Terlengkap',
                'subtitle' => 'Temukan smartphone, laptop, dan tablet impian Anda di sini.',
                'image_url' => 'https://picsum.photos/seed/tech-banner-1/1600/700',
                'cta_label' => 'Belanja Sekarang',
                'cta_url' => route('katalog'),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Top Up Voucher Game Instan',
                'subtitle' => 'Dapatkan Diamond MLBB, UC PUBG, dan Steam Wallet dengan harga termurah.',
                'image_url' => 'https://picsum.photos/seed/tech-banner-2/1600/700',
                'cta_label' => 'Cek Voucher',
                'cta_url' => route('katalog'),
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Promo Aksesoris Gadget',
                'subtitle' => 'Diskon hingga 50% untuk charger, powerbank, dan case premium.',
                'image_url' => 'https://picsum.photos/seed/tech-banner-3/1600/700',
                'cta_label' => 'Klik Di Sini',
                'cta_url' => route('home') . '#flash-sale',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        Banner::query()->delete();

        foreach ($items as $item) {
            Banner::query()->updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }
    }
}
