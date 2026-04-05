<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Cache::remember(
            'public.categories.all',
            now()->addMinutes(15),
            fn () => Category::query()
                ->select('id', 'name', 'slug', 'icon', 'color', 'description')
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('status', true),
                ])
                ->orderBy('name')
                ->get()
        );

        $seoTitle = 'Semua Kategori Produk - Kataloque';
        $seoDescription = 'Temukan ribuan produk pilihan berdasarkan kategori favorit Anda di Kataloque.';
        $canonical = route('kategori');

        return view('frontend.kategori', compact('categories', 'seoTitle', 'seoDescription', 'canonical'));
    }

    public function show(string $slug)
    {
        $category = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->where('slug', $slug)
            ->firstOrFail();

        $seoTitle = "Category: {$category->name} - Kataloque";
        $seoDescription = $category->description ?: "Lihat semua produk di kategori {$category->name} hanya di Kataloque.";
        $canonical = route('kategori.detail', $category->slug);

        return view('frontend.kategori-detail', compact('category', 'seoTitle', 'seoDescription', 'canonical'));
    }
}
