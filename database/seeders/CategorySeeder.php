<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'icon' => 'fa-laptop', 'color' => 'bg-blue-50', 'text_color' => 'text-blue-600', 'description' => 'Gadget dan elektronik.'],
            ['name' => 'Fashion', 'icon' => 'fa-tshirt', 'color' => 'bg-indigo-50', 'text_color' => 'text-indigo-600', 'description' => 'Pakaian dan aksesoris.'],
            ['name' => 'Kesehatan', 'icon' => 'fa-heart-pulse', 'color' => 'bg-rose-50', 'text_color' => 'text-rose-600', 'description' => 'Produk kesehatan.'],
            ['name' => 'Kecantikan', 'icon' => 'fa-spa', 'color' => 'bg-pink-50', 'text_color' => 'text-pink-600', 'description' => 'Perawatan tubuh dan makeup.'],
            ['name' => 'Rumah Tangga', 'icon' => 'fa-house-user', 'color' => 'bg-amber-50', 'text_color' => 'text-amber-600', 'description' => 'Kebutuhan rumah.'],
            ['name' => 'Olahraga', 'icon' => 'fa-dumbbell', 'color' => 'bg-orange-50', 'text_color' => 'text-orange-600', 'description' => 'Alat dan perlengkapan olahraga.'],
            ['name' => 'Otomotif', 'icon' => 'fa-car-side', 'color' => 'bg-emerald-50', 'text_color' => 'text-emerald-600', 'description' => 'Aksesoris kendaraan.'],
            ['name' => 'Hobi', 'icon' => 'fa-puzzle-piece', 'color' => 'bg-purple-50', 'text_color' => 'text-purple-600', 'description' => 'Mainan dan koleksi.'],
            ['name' => 'Ibu & Bayi', 'icon' => 'fa-baby-carriage', 'color' => 'bg-sky-50', 'text_color' => 'text-sky-600', 'description' => 'Kebutuhan ibu dan bayi.'],
            ['name' => 'Makanan & Minuman', 'icon' => 'fa-utensils', 'color' => 'bg-lime-50', 'text_color' => 'text-lime-600', 'description' => 'Produk konsumsi.'],
            ['name' => 'Buku & Alat Tulis', 'icon' => 'fa-book-open', 'color' => 'bg-cyan-50', 'text_color' => 'text-cyan-600', 'description' => 'Media baca dan tulis.'],
            ['name' => 'Musik & Audio', 'icon' => 'fa-music', 'color' => 'bg-violet-50', 'text_color' => 'text-violet-600', 'description' => 'Alat musik dan audio.'],
            ['name' => 'Fotografi', 'icon' => 'fa-camera-retro', 'color' => 'bg-gray-50', 'text_color' => 'text-gray-600', 'description' => 'Kamera dan aksesoris.'],
            ['name' => 'Gaming', 'icon' => 'fa-gamepad', 'color' => 'bg-red-50', 'text_color' => 'text-red-600', 'description' => 'Consoles dan aksesori gaming.'],
            ['name' => 'Perhiasan', 'icon' => 'fa-gem', 'color' => 'bg-teal-50', 'text_color' => 'text-teal-600', 'description' => 'Aksesoris dan perhiasan.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'text_color' => $category['text_color'],
                    'description' => $category['description'],
                ]
            );
        }

        // Clear public header cache to show new data
        \Illuminate\Support\Facades\Cache::forget('public.header.categories');
    }
}
