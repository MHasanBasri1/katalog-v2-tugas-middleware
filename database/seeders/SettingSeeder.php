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
                'footer_text' => 'Build with Love in Sidoarjo.',
                'marketplaces' => [
                    ['platform' => 'shopee', 'url' => ''],
                    ['platform' => 'tokopedia', 'url' => '']
                ],
                'social_media' => [
                    ['platform' => 'instagram', 'username' => ''],
                    ['platform' => 'facebook', 'username' => '']
                ],
                'trending_keywords' => [
                    ['keyword' => 'iPhone 15 Pro', 'url' => ''],
                    ['keyword' => 'Samsung S24 Ultra', 'url' => ''],
                    ['keyword' => 'MacBook Pro M3', 'url' => ''],
                    ['keyword' => 'Sony WH-1000XM5', 'url' => ''],
                    ['keyword' => 'Logitech G Pro', 'url' => ''],
                    ['keyword' => 'iPad Pro M2', 'url' => '']
                ],
                'header_navigation' => [
                    ['label' => 'Tentang Kami', 'url' => '/tentang-kami'],
                    ['label' => 'Blog & Edukasi', 'url' => '/blog'],
                    ['label' => 'Cara Order', 'url' => '/cara-pesan']
                ],
                'footer_navigation' => [
                    ['label' => 'Tentang Kami', 'url' => '/tentang-kami'],
                    ['label' => 'Blog', 'url' => '/blog'],
                    ['label' => 'Cara Belanja', 'url' => '/cara-pesan'],
                    ['label' => 'Metode Pembayaran', 'url' => '/pembayaran']
                ],
                'seo_settings' => [
                    'seo_title' => 'Kataloque - Katalog Produk Modern',
                    'seo_keywords' => 'katalog, online shop, produk, gadget, fashion',
                    'twitter_card' => 'summary_large_image',
                    'robots' => 'index, follow',
                    'author' => 'Kataloque Team'
                ],
                'system_settings' => [
                    'maintenance_message' => 'Maaf, saat ini sistem kami sedang dalam tahap pemeliharaan rutin.',
                    'google_analytics_id' => '',
                    'facebook_pixel_id' => '',
                    'announcement_enabled' => false,
                    'announcement_text' => '',
                    'announcement_url' => ''
                ],
                'payment_methods' => ['BCA', 'BNI', 'Mandiri', 'E-Wallet']
            ]
        );
    }
}
