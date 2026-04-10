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
            ['name' => 'Smartphone', 'icon' => 'fa-mobile-screen-button', 'color' => 'bg-blue-50', 'text_color' => 'text-blue-600', 'description' => 'HP & Smartphone terbaru.'],
            ['name' => 'Laptop', 'icon' => 'fa-laptop', 'color' => 'bg-slate-50', 'text_color' => 'text-slate-600', 'description' => 'Laptop & Notebook.'],
            ['name' => 'Gaming Gear', 'icon' => 'fa-gamepad', 'color' => 'bg-orange-50', 'text_color' => 'text-orange-600', 'description' => 'Aksesori Gaming.'],
            ['name' => 'Audio', 'icon' => 'fa-headphones', 'color' => 'bg-purple-50', 'text_color' => 'text-purple-600', 'description' => 'Speaker & Earphone.'],
            ['name' => 'Kamera', 'icon' => 'fa-camera', 'color' => 'bg-gray-50', 'text_color' => 'text-gray-600', 'description' => 'Kamera & Fotografi.'],
            ['name' => 'Tablet', 'icon' => 'fa-tablet-screen-button', 'color' => 'bg-cyan-50', 'text_color' => 'text-cyan-600', 'description' => 'Tablet & iPad.'],
            ['name' => 'Accessories', 'icon' => 'fa-plug', 'color' => 'bg-zinc-50', 'text_color' => 'text-zinc-600', 'description' => 'Kabel, Charger, Case.'],
            ['name' => 'Storage', 'icon' => 'fa-hard-drive', 'color' => 'bg-rose-50', 'text_color' => 'text-rose-600', 'description' => 'SSD & Flashdisk.'],
            ['name' => 'Periferal', 'icon' => 'fa-print', 'color' => 'bg-emerald-50', 'text_color' => 'text-emerald-600', 'description' => 'Monitor & Printer.'],
        ];

        // Delete old categories first to ensure exactly 12
        Category::query()->delete();

        foreach ($categories as $category) {
            Category::query()->create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'color' => $category['color'],
                'text_color' => $category['text_color'],
                'description' => $category['description'],
            ]);
        }

        // Clear public header cache to show new data
        \Illuminate\Support\Facades\Cache::forget('public.header.categories');
    }
}
