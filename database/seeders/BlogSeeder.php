<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Tips Belanja Online Aman dan Hemat',
                'excerpt' => 'Langkah sederhana agar transaksi online tetap aman, cepat, dan tidak boros.',
                'content' => 'Belanja online yang aman dimulai dari toko terpercaya, cek ulasan asli, dan bandingkan harga sebelum checkout. Gunakan metode pembayaran yang memiliki perlindungan transaksi dan hindari membagikan OTP kepada siapa pun. Simpan bukti transaksi sampai barang diterima dengan baik. Supaya hemat, manfaatkan promo berkala, voucher, dan gratis ongkir secara terencana.',
                'author_name' => 'Tim VISTORA',
                'published_at' => now()->subDays(10),
                'cover_image' => 'https://picsum.photos/seed/blog-vistora-1/1200/700',
                'category' => 'Tips Belanja',
                'tags' => ['Belanja Online', 'Promo', 'Keamanan'],
            ],
            [
                'title' => 'Cara Memilih Smartphone Sesuai Kebutuhan',
                'excerpt' => 'Fokus ke performa, kamera, baterai, dan budget agar tidak salah pilih.',
                'content' => 'Tentukan prioritas utama sebelum membeli smartphone. Jika banyak aktivitas harian, pilih baterai besar dan pengisian cepat. Untuk konten, utamakan kamera yang stabil. Perhatikan juga kapasitas penyimpanan agar tidak cepat penuh. Sesuaikan spesifikasi dengan budget supaya pembelian lebih rasional dan maksimal dipakai dalam jangka panjang.',
                'author_name' => 'Editor Teknologi',
                'published_at' => now()->subDays(7),
                'cover_image' => 'https://picsum.photos/seed/blog-vistora-2/1200/700',
                'category' => 'Teknologi',
                'tags' => ['Smartphone', 'Gadget'],
            ],
            [
                'title' => 'Panduan Setup Audio Rumah Biar Makin Jernih',
                'excerpt' => 'Posisi speaker dan pengaturan dasar yang berdampak besar ke kualitas suara.',
                'content' => 'Kualitas audio tidak hanya ditentukan produk, tetapi juga tata letaknya. Jaga jarak speaker kiri kanan seimbang dan arahkan ke posisi duduk utama. Hindari meletakkan speaker menempel dinding jika bass terasa berlebih. Gunakan equalizer secara halus, lalu tes beberapa genre musik untuk mendapatkan karakter suara yang paling nyaman.',
                'author_name' => 'Tim Audio VISTORA',
                'published_at' => now()->subDays(4),
                'cover_image' => 'https://picsum.photos/seed/blog-vistora-3/1200/700',
                'category' => 'Audio',
                'tags' => ['Audio', 'Setup Rumah'],
            ],
        ];

        foreach ($posts as $item) {
            $slug = Str::slug($item['title']);
            $categoryName = $item['category'] ?? null;
            $categoryId = null;

            if ($categoryName) {
                $category = BlogCategory::query()->updateOrCreate(
                    ['slug' => Str::slug($categoryName)],
                    ['name' => $categoryName]
                );
                $categoryId = $category->id;
            }

            $blog = Blog::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $item['title'],
                    'slug' => $slug,
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'cover_image' => $item['cover_image'],
                    'category_id' => $categoryId,
                    'author_name' => $item['author_name'],
                    'is_published' => true,
                    'published_at' => $item['published_at'],
                ]
            );

            $tagIds = collect($item['tags'] ?? [])
                ->map(function (string $tagName): int {
                    $tag = BlogTag::query()->updateOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );

                    return $tag->id;
                })
                ->all();

            $blog->tags()->sync($tagIds);
        }
    }
}
