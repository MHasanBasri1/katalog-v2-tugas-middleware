<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MarketplaceLinkController;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/kategori', function () {
    return view('frontend.kategori');
})->name('kategori');

Route::get('/kategori/{slug}', function ($slug) {
    $category = Category::query()
        ->select('id', 'name', 'slug', 'description')
        ->where('slug', $slug)
        ->firstOrFail();

    $canonical = route('kategori.detail', $category->slug);
    $seoTitle = "{$category->name} - Kategori Produk VISTORA";
    $seoDescription = $category->description
        ?: "Lihat daftar produk kategori {$category->name} di VISTORA dengan update produk terbaru.";
    $ogImage = Product::query()
        ->where('status', true)
        ->where('category_id', $category->id)
        ->with('primaryImage:id,product_id,image')
        ->latest('id')
        ->first()?->primaryImage?->image
        ?: 'https://picsum.photos/seed/vistora-kategori/1200/630';

    return view('frontend.kategori-detail', compact('slug', 'seoTitle', 'seoDescription', 'canonical', 'ogImage'));
})->name('kategori.detail');

Route::get('/produk', function () {
    return view('frontend.produk');
})->name('katalog');

Route::redirect('/katalog', '/produk', 301);

Route::get('/produk/{slug}', function ($slug) {
    $product = Product::query()
        ->select('id', 'name', 'slug', 'description', 'price')
        ->where('slug', $slug)
        ->where('status', true)
        ->with('primaryImage:id,product_id,image')
        ->firstOrFail();

    $canonical = route('produk.detail', $product->slug);
    $seoTitle = "{$product->name} - Detail Produk VISTORA";
    $seoDescription = $product->description
        ? str($product->description)->limit(155)->toString()
        : "Lihat detail {$product->name} di VISTORA, mulai dari harga, spesifikasi, dan link marketplace resmi.";
    $ogImage = $product->primaryImage?->image ?: 'https://picsum.photos/seed/vistora-produk/1200/630';

    return view('frontend.detail', compact('slug', 'seoTitle', 'seoDescription', 'canonical', 'ogImage'));
})->name('produk.detail');

Route::get('/sitemap.xml', function () {
    $products = Product::query()
        ->select('slug', 'updated_at')
        ->where('status', true)
        ->latest('updated_at')
        ->get();

    $categories = Category::query()
        ->select('slug', 'updated_at')
        ->latest('updated_at')
        ->get();

    return response()
        ->view('frontend.sitemap', compact('products', 'categories'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\n\nSitemap: " . route('sitemap') . "\n";

    return response($content, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/setting', SettingController::class)->only(['index', 'store', 'update']);
    Route::resource('/kategori', CategoryController::class);
    Route::resource('/produk', ProductController::class);
    Route::resource('/marketplace-link', MarketplaceLinkController::class);
});
