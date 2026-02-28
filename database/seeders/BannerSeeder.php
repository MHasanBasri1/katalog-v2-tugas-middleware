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
                'title' => 'Pilihan Terbaik Hari Ini',
                'subtitle' => 'Temukan produk favorit dengan harga paling menarik.',
                'image_url' => 'https://picsum.photos/seed/vistora-banner-1/1600/700',
                'cta_label' => 'Belanja Sekarang',
                'cta_url' => route('katalog'),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Upgrade Gadget Kamu',
                'subtitle' => 'Smartphone, laptop, dan audio terbaru dengan promo spesial.',
                'image_url' => 'https://picsum.photos/seed/vistora-banner-2/1600/700',
                'cta_label' => 'Lihat Katalog',
                'cta_url' => route('katalog'),
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Promo Eksklusif Mingguan',
                'subtitle' => 'Diskon pilihan untuk produk terlaris.',
                'image_url' => 'https://picsum.photos/seed/vistora-banner-3/1600/700',
                'cta_label' => 'Cek Promo',
                'cta_url' => route('home') . '#flash-sale',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Banner::query()->updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }
    }
}
