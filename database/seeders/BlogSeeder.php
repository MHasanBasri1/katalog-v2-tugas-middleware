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
                'title' => 'Review iPhone 15 Pro: Apakah Material Titanium Sehebat Itu?',
                'excerpt' => 'Mengulas kenyamanan dan ketahanan iPhone terbaru dengan bingkai titanium.',
                'content' => 'iPhone 15 Pro membawa perubahan besar dengan penggunaan titanium kelas dirgantara. Tidak hanya lebih ringan, material ini juga memberikan kesan premium yang berbeda. Dengan chip A17 Pro, performa gaming dan multitasking menjadi sangat lancar. Kamera 48MP terbarunya juga memberikan detail yang luar biasa untuk fotografi profesional.',
                'author_name' => 'Editor Gadget',
                'published_at' => now()->subDays(5),
                'cover_image' => 'https://picsum.photos/seed/tech-1/1200/700',
                'category' => 'Gadget Review',
                'tags' => ['iPhone', 'Smartphone', 'Apple'],
            ],
            [
                'title' => 'Tips Memilih Sepatu Nike Original Agar Tidak Tertipu',
                'excerpt' => 'Kenali ciri-ciri fisik dan kode produk Nike asli sebelum membeli.',
                'content' => 'Populeritas sepatu Nike sering dimanfaatkan oleh pihak tidak bertanggung jawab untuk menjual produk palsu. Selalu cek nomor SKU pada lidah sepatu dan kotak, pastikan keduanya cocok. Kualitas jahitan dan bahan midsole juga menjadi pembeda utama. Belanja di toko resmi atau marketplace terpercaya adalah cara paling aman untuk mendapatkan produk orisinal.',
                'author_name' => 'Fashionista',
                'published_at' => now()->subDays(3),
                'cover_image' => 'https://picsum.photos/seed/fashion-1/1200/700',
                'category' => 'Lifestyle',
                'tags' => ['Nike', 'Sepatu', 'Fashion'],
            ],
            [
                'title' => 'Investasi Emas Antam: Tabungan Masa Depan Aman',
                'excerpt' => 'Mengapa Logam Mulia tetap menjadi pilihan favorit para investor pemula.',
                'content' => 'Emas dikenal sebagai safe haven karena nilainya yang cenderung stabil bahkan naik saat kondisi ekonomi tidak menentu. Logam Mulia Antam dengan sertifikat LBMA menawarkan kemurnian dan likuiditas tinggi. Mulailah menabung emas dari ukuran kecil secara rutin untuk mempersiapkan dana pendidikan atau masa pensiun yang lebih terjamin.',
                'author_name' => 'Finansial Advisor',
                'published_at' => now()->subDays(1),
                'cover_image' => 'https://picsum.photos/seed/gold-1/1200/700',
                'category' => 'Investasi',
                'tags' => ['Emas', 'Antam', 'Keuangan'],
            ],
        ];

        // Clear existing data
        Blog::query()->delete();
        BlogCategory::query()->delete();
        BlogTag::query()->delete();

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

            $blog = Blog::query()->create([
                'title' => $item['title'],
                'slug' => $slug,
                'excerpt' => $item['excerpt'],
                'content' => $item['content'],
                'cover_image' => $item['cover_image'],
                'category_id' => $categoryId,
                'author_name' => $item['author_name'],
                'is_published' => true,
                'published_at' => $item['published_at'],
            ]);

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
