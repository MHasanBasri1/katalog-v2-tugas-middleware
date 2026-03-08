<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Samsung Galaxy S25 Ultra 512GB', 'category' => 'Elektronik', 'price' => 19500000, 'original_price' => 22000000, 'sold_count' => 120, 'likes_count' => 245, 'is_featured' => true],
            ['name' => 'iPhone 17 Pro 256GB', 'category' => 'Elektronik', 'price' => 20999000, 'original_price' => 22999000, 'sold_count' => 98, 'likes_count' => 198, 'is_featured' => true],
            ['name' => 'ASUS ROG Zephyrus G16 RTX 4070', 'category' => 'Elektronik', 'price' => 31200000, 'original_price' => 34000000, 'sold_count' => 87, 'likes_count' => 176, 'is_featured' => true],
            ['name' => 'MacBook Pro M4 14 Inch', 'category' => 'Elektronik', 'price' => 33999000, 'original_price' => 35999000, 'sold_count' => 76, 'likes_count' => 151, 'is_featured' => false],
            ['name' => 'Sony WH-1000XM6', 'category' => 'Elektronik', 'price' => 5650000, 'original_price' => 6300000, 'sold_count' => 143, 'likes_count' => 321, 'is_featured' => true],
            ['name' => 'JBL Charge 6', 'category' => 'Elektronik', 'price' => 2699000, 'original_price' => 2999000, 'sold_count' => 132, 'likes_count' => 210, 'is_featured' => false],
            ['name' => 'LG OLED evo C4 65 Inch 4K', 'category' => 'Elektronik', 'price' => 24850000, 'original_price' => 27000000, 'sold_count' => 64, 'likes_count' => 174, 'is_featured' => true],
            ['name' => 'Samsung Odyssey G8 32 Inch', 'category' => 'Elektronik', 'price' => 10999000, 'original_price' => 11999000, 'sold_count' => 58, 'likes_count' => 167, 'is_featured' => false],
            ['name' => 'Sony Alpha A7 IV', 'category' => 'Elektronik', 'price' => 32999000, 'original_price' => 34999000, 'sold_count' => 39, 'likes_count' => 112, 'is_featured' => false],
            ['name' => 'Canon EOS R8 Body', 'category' => 'Elektronik', 'price' => 21999000, 'original_price' => 23499000, 'sold_count' => 44, 'likes_count' => 121, 'is_featured' => false],
            ['name' => 'PlayStation 5 Slim', 'category' => 'Hobi', 'price' => 8499000, 'original_price' => 8999000, 'sold_count' => 92, 'likes_count' => 255, 'is_featured' => true],
            ['name' => 'Nintendo Switch 2', 'category' => 'Hobi', 'price' => 6999000, 'original_price' => 7499000, 'sold_count' => 101, 'likes_count' => 279, 'is_featured' => true],
            ['name' => 'Xiaomi 15 Pro 512GB', 'category' => 'Elektronik', 'price' => 14999000, 'original_price' => 16500000, 'sold_count' => 88, 'likes_count' => 188, 'is_featured' => true],
            ['name' => 'OPPO Find X9 256GB', 'category' => 'Elektronik', 'price' => 13250000, 'original_price' => 14500000, 'sold_count' => 75, 'likes_count' => 149, 'is_featured' => false],
            ['name' => 'Lenovo Legion Pro 7i RTX 4080', 'category' => 'Elektronik', 'price' => 36999000, 'original_price' => 39250000, 'sold_count' => 66, 'likes_count' => 170, 'is_featured' => true],
            ['name' => 'HP Spectre x360 14 OLED', 'category' => 'Elektronik', 'price' => 26499000, 'original_price' => 28250000, 'sold_count' => 57, 'likes_count' => 138, 'is_featured' => false],
            ['name' => 'Dell UltraSharp 32 4K USB-C', 'category' => 'Elektronik', 'price' => 12999000, 'original_price' => 14250000, 'sold_count' => 49, 'likes_count' => 129, 'is_featured' => false],
            ['name' => 'TCL QLED 75 Inch C755', 'category' => 'Elektronik', 'price' => 18499000, 'original_price' => 20500000, 'sold_count' => 53, 'likes_count' => 146, 'is_featured' => true],
            ['name' => 'Anker Soundcore Liberty 5 Pro', 'category' => 'Elektronik', 'price' => 2199000, 'original_price' => 2599000, 'sold_count' => 124, 'likes_count' => 208, 'is_featured' => true],
            ['name' => 'Marshall Stanmore III', 'category' => 'Elektronik', 'price' => 5799000, 'original_price' => 6299000, 'sold_count' => 71, 'likes_count' => 193, 'is_featured' => false],
            ['name' => 'Fujifilm X-S20 Kit 18-55mm', 'category' => 'Elektronik', 'price' => 24899000, 'original_price' => 26990000, 'sold_count' => 35, 'likes_count' => 118, 'is_featured' => false],
            ['name' => 'DJI Osmo Pocket 4 Creator Combo', 'category' => 'Elektronik', 'price' => 9899000, 'original_price' => 10899000, 'sold_count' => 62, 'likes_count' => 201, 'is_featured' => true],
            ['name' => 'Xbox Series X 2TB', 'category' => 'Hobi', 'price' => 9799000, 'original_price' => 10499000, 'sold_count' => 83, 'likes_count' => 226, 'is_featured' => true],
            ['name' => 'Razer BlackShark V2 Pro', 'category' => 'Hobi', 'price' => 2999000, 'original_price' => 3399000, 'sold_count' => 95, 'likes_count' => 214, 'is_featured' => false],
            ['name' => 'Kursi Kerja Ergonomis Mesh Pro', 'category' => 'Rumah Tangga', 'price' => 1799000, 'original_price' => 2199000, 'sold_count' => 140, 'likes_count' => 265, 'is_featured' => true],
            ['name' => 'Meja Belajar Minimalis Oak 120cm', 'category' => 'Rumah Tangga', 'price' => 899000, 'original_price' => 1099000, 'sold_count' => 112, 'likes_count' => 198, 'is_featured' => false],
            ['name' => 'Kaos Polos Premium Cotton 24S', 'category' => 'Fashion', 'price' => 99000, 'original_price' => 149000, 'sold_count' => 360, 'likes_count' => 402, 'is_featured' => true],
            ['name' => 'Sneakers Casual Urban Flex', 'category' => 'Fashion', 'price' => 349000, 'original_price' => 499000, 'sold_count' => 290, 'likes_count' => 355, 'is_featured' => true],
            ['name' => 'Vitamin C 1000mg 60 Tablet', 'category' => 'Kesehatan', 'price' => 89000, 'original_price' => 129000, 'sold_count' => 418, 'likes_count' => 376, 'is_featured' => true],
            ['name' => 'Tensimeter Digital Akurat Plus', 'category' => 'Kesehatan', 'price' => 429000, 'original_price' => 579000, 'sold_count' => 164, 'likes_count' => 233, 'is_featured' => false],
            ['name' => 'Beras Premium 5kg', 'category' => 'Rumah Tangga', 'price' => 76000, 'original_price' => 92000, 'sold_count' => 520, 'likes_count' => 412, 'is_featured' => true],
            ['name' => 'Minyak Goreng 2L', 'category' => 'Rumah Tangga', 'price' => 33900, 'original_price' => 42000, 'sold_count' => 640, 'likes_count' => 438, 'is_featured' => true],
        ];

        $categoryIds = Category::query()->pluck('id', 'name');

        foreach ($products as $item) {
            $name = $item['name'];
            $slug = Str::slug($name);

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categoryIds[$item['category']] ?? null,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "Produk {$name} dengan kualitas terbaik dan garansi resmi.",
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'status' => true,
                    'sold_count' => $item['sold_count'],
                    'likes_count' => $item['likes_count'],
                    'rating_count' => $item['likes_count'],
                    'rating_avg' => min(5, round(4 + ($item['likes_count'] / 500), 1)),
                    'is_featured' => $item['is_featured'],
                    'show_in_promo' => $item['is_featured'],
                ]
            );
        }
    }
}

