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
                'excerpt' => 'Kenali Kataloque lebih dekat: visi, misi, dan komitmen kami.',
                'content' => 'Kataloque adalah platform katalog produk modern yang membantu pengguna menemukan produk terbaik dengan pengalaman yang cepat dan mudah. Kami berkomitmen pada kualitas informasi produk, kemudahan navigasi, dan kenyamanan pengguna di semua perangkat.',
                'is_published' => true,
            ],
            [
                'title' => 'Kebijakan Privasi',
                'excerpt' => 'Informasi tentang cara kami mengelola data pengguna.',
                'content' => 'Kami menghargai privasi pengguna. Data yang dikumpulkan digunakan untuk meningkatkan layanan, menjaga keamanan, dan memberikan pengalaman yang lebih relevan. Kami tidak menjual data pribadi pengguna kepada pihak ketiga.',
                'is_published' => true,
            ],
            [
                'title' => 'Cara Pesan',
                'excerpt' => 'Panduan lengkap cara berbelanja dan memesan produk di Kataloque.',
                'content' => 'Untuk memesan produk, Anda bisa menekan tombol Beli Sekarang dan akan langsung diarahkan ke Admin WhatsApp kami, atau Anda dapat memesan langsung via Marketplace seperti Tokopedia atau Shopee jika tautan tersedia di halaman produk.',
                'is_published' => true,
            ],
            [
                'title' => 'Lokasi Toko',
                'excerpt' => 'Detail alamat dan lokasi toko fisik kami.',
                'content' => 'Kunjungi toko offline kami di Jalan Jenderal Sudirman No. 123, Jakarta Selatan, untuk pembelanjaan langsung. Buka Senin-Jumat dari 09:00 hingga 18:00 WIB.',
                'is_published' => true,
            ],
            [
                'title' => 'Pembayaran',
                'excerpt' => 'Metode pembayaran yang kami terima.',
                'content' => 'Kami menerima berbagai metode pembayaran transaksi online, termasuk Transfer Bank (BCA, Mandiri, BRI), E-Wallet (OVO, Gopay, Dana), dan Virtual Account.',
                'is_published' => true,
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'excerpt' => 'Ketentuan penggunaan layanan Kataloque.',
                'content' => 'Dengan menggunakan layanan Kataloque, pengguna dianggap telah memahami dan menyetujui seluruh syarat dan ketentuan yang berlaku. Kami dapat memperbarui ketentuan sewaktu-waktu sesuai kebutuhan layanan.',
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
