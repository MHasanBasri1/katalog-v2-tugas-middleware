<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Tentang Kami',
                'excerpt' => 'Kenali VISTORA lebih dekat: visi, misi, dan komitmen kami.',
                'content' => 'VISTORA adalah platform katalog produk modern yang membantu pengguna menemukan produk terbaik dengan pengalaman yang cepat dan mudah. Kami berkomitmen pada kualitas informasi produk, kemudahan navigasi, dan kenyamanan pengguna di semua perangkat.',
                'is_published' => true,
            ],
            [
                'title' => 'Kebijakan Privasi',
                'excerpt' => 'Informasi tentang cara kami mengelola data pengguna.',
                'content' => 'Kami menghargai privasi pengguna. Data yang dikumpulkan digunakan untuk meningkatkan layanan, menjaga keamanan, dan memberikan pengalaman yang lebih relevan. Kami tidak menjual data pribadi pengguna kepada pihak ketiga.',
                'is_published' => true,
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'excerpt' => 'Ketentuan penggunaan layanan VISTORA.',
                'content' => 'Dengan menggunakan layanan VISTORA, pengguna dianggap telah memahami dan menyetujui seluruh syarat dan ketentuan yang berlaku. Kami dapat memperbarui ketentuan sewaktu-waktu sesuai kebutuhan layanan.',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $item) {
            $slug = Str::slug($item['title']);

            StaticPage::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $item['title'],
                    'slug' => $slug,
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'is_published' => $item['is_published'],
                ]
            );
        }
    }
}
