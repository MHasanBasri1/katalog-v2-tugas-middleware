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
            ['name' => 'Elektronik', 'icon' => 'fa-laptop', 'color' => 'bg-blue-50', 'description' => 'Gadget dan elektronik.'],
            ['name' => 'Fashion', 'icon' => 'fa-tshirt', 'color' => 'bg-indigo-50', 'description' => 'Pakaian dan aksesoris.'],
            ['name' => 'Kesehatan', 'icon' => 'fa-heartbeat', 'color' => 'bg-rose-50', 'description' => 'Produk kesehatan.'],
            ['name' => 'Kecantikan', 'icon' => 'fa-magic', 'color' => 'bg-pink-50', 'description' => 'Perawatan tubuh dan makeup.'],
            ['name' => 'Rumah Tangga', 'icon' => 'fa-home', 'color' => 'bg-amber-50', 'description' => 'Kebutuhan rumah.'],
            ['name' => 'Olahraga', 'icon' => 'fa-running', 'color' => 'bg-orange-50', 'description' => 'Alat dan perlengkapan olahraga.'],
            ['name' => 'Otomotif', 'icon' => 'fa-car', 'color' => 'bg-emerald-50', 'description' => 'Aksesoris kendaraan.'],
            ['name' => 'Hobi', 'icon' => 'fa-gamepad', 'color' => 'bg-purple-50', 'description' => 'Mainan dan koleksi.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
