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
            ['name' => 'iPhone 15 Pro 128GB Natural Titanium', 'category' => 'Smartphone', 'price' => 18999000, 'original_price' => 20999000, 'sold_count' => 150, 'likes_count' => 245, 'is_featured' => true],
            ['name' => 'Samsung Galaxy S24 Ultra 12/256GB Titanium Gray', 'category' => 'Smartphone', 'price' => 21999000, 'original_price' => 23999000, 'sold_count' => 120, 'likes_count' => 198, 'is_featured' => true],
            ['name' => 'Laptop Gaming ASUS ROG Strix G16', 'category' => 'Laptop', 'price' => 25499000, 'original_price' => 27999000, 'sold_count' => 42, 'likes_count' => 98, 'is_featured' => true],
            ['name' => 'MacBook Pro M3 Max 14-inch Space Black', 'category' => 'Laptop', 'price' => 44999000, 'original_price' => 47999000, 'sold_count' => 25, 'likes_count' => 88, 'is_featured' => true],
            ['name' => 'Oppo Reno 10 5G 8/256GB Silver', 'category' => 'Smartphone', 'price' => 5999000, 'original_price' => 6499000, 'sold_count' => 210, 'likes_count' => 156, 'is_featured' => false],
            ['name' => 'Logitech G Pro X Superlight 2 Wireless', 'category' => 'Gaming Gear', 'price' => 2199000, 'original_price' => 2499000, 'sold_count' => 120, 'likes_count' => 140, 'is_featured' => true],
            ['name' => 'Keychron K2 V2 Wireless Mechanical Keyboard', 'category' => 'Gaming Gear', 'price' => 1299000, 'original_price' => 1499000, 'sold_count' => 75, 'likes_count' => 95, 'is_featured' => false],
            ['name' => 'Apple Watch Series 9 GPS 45mm Midnight', 'category' => 'Accessories', 'price' => 6999000, 'original_price' => 7999000, 'sold_count' => 55, 'likes_count' => 67, 'is_featured' => true],
            ['name' => 'Sony WH-1000XM5 Wireless Noise Cancelling', 'category' => 'Audio', 'price' => 4999000, 'original_price' => 5999000, 'sold_count' => 88, 'likes_count' => 112, 'is_featured' => true],
            ['name' => 'AirPods Pro Gen 2 with MagSafe USB-C', 'category' => 'Audio', 'price' => 3899000, 'original_price' => 4299000, 'sold_count' => 150, 'likes_count' => 180, 'is_featured' => true],
            ['name' => 'Sony Alpha A7 IV Mirrorless Camera Body', 'category' => 'Kamera', 'price' => 33999000, 'original_price' => 35999000, 'sold_count' => 12, 'likes_count' => 45, 'is_featured' => false],
            ['name' => 'DJI Mini 4 Pro Fly More Combo RC 2', 'category' => 'Kamera', 'price' => 15999000, 'original_price' => 17499000, 'sold_count' => 34, 'likes_count' => 76, 'is_featured' => true],
            ['name' => 'iPad Pro M2 11-inch Wi-Fi 128GB Space Gray', 'category' => 'Tablet', 'price' => 14499000, 'original_price' => 15999000, 'sold_count' => 42, 'likes_count' => 56, 'is_featured' => true],
            ['name' => 'Samsung 990 PRO NVMe M.2 2TB SSD', 'category' => 'Storage', 'price' => 2999000, 'original_price' => 3499000, 'sold_count' => 45, 'likes_count' => 88, 'is_featured' => false],
            ['name' => 'LG 27GP850-B UltraGear QHD 165Hz Monitor', 'category' => 'Periferal', 'price' => 5499000, 'original_price' => 6299000, 'sold_count' => 38, 'likes_count' => 62, 'is_featured' => true],
            ['name' => 'Razer DeathAdder V3 Pro Wireless Mouse', 'category' => 'Gaming Gear', 'price' => 2399000, 'original_price' => 2699000, 'sold_count' => 65, 'likes_count' => 92, 'is_featured' => false],
        ];

        Product::query()->delete();

        $categoryIds = Category::query()->pluck('id', 'name');

        foreach ($products as $item) {
            $name = $item['name'];
            $slug = Str::slug($name);

            Product::query()->create([
                'category_id' => $categoryIds[$item['category']] ?? null,
                'name' => $name,
                'slug' => $slug,
                'description' => "Produk {$name} dengan kualitas terbaik dan garansi resmi. Cocok untuk kebutuhan digital dan gaya hidup modern Anda.",
                'price' => $item['price'],
                'original_price' => $item['original_price'],
                'status' => true,
                'sold_count' => $item['sold_count'],
                'likes_count' => $item['likes_count'],
                'rating_count' => $item['likes_count'],
                'rating_avg' => min(5, round(4 + ($item['likes_count'] / 500), 1)),
                'is_featured' => $item['is_featured'],
                'show_in_promo' => $item['is_featured'],
            ]);
        }
    }
}

