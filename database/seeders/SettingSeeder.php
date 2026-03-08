<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'shop_name' => 'Kataloque',
                'shop_description' => 'Katalog produk modern, cepat, dan terpercaya.',
                'shop_address' => 'Jl. Sudirman No. 1',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'phone' => '+62 21 5555 8888',
                'whatsapp' => '+62 812 3456 7890',
                'email' => 'cs@kataloque.id',
                'website' => 'https://kataloque.id',
                'facebook' => 'https://facebook.com/kataloque.id',
                'instagram' => 'https://instagram.com/kataloque.id',
                'footer_text' => 'Build with Love in Sidoarjo.',
            ]
        );
    }
}
