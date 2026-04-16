<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/search', [SearchController::class, 'global'])->middleware('throttle:30,1');
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/home/promo-products', [HomeController::class, 'promoProducts']);
    Route::get('/home/popular-products', [HomeController::class, 'popularProducts']);
    Route::get('/home/latest-products', [HomeController::class, 'latestProducts']);
    
    // Indonesian Aliases for Home Section (Flutter Recomended)
    Route::get('/home/flashsale', [HomeController::class, 'flashSale']);
    Route::get('/home/terlaris', [HomeController::class, 'terlaris']);
    Route::get('/home/terbaru', [HomeController::class, 'terbaru']);
    
    Route::get('/banners', [BannerController::class, 'index']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/top-rated', [ProductController::class, 'topRated']);
    Route::get('/products/best-sold', [ProductController::class, 'bestSold']);
    Route::get('/products/category/{slug}', [ProductController::class, 'byCategory']);
    Route::get('/products/detail/{slug}', [ProductController::class, 'detail']);
    Route::get('/products/{slug}/related', [ProductController::class, 'related']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/google', [AuthController::class, 'google']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('api.token')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/vouchers', [VoucherController::class, 'index']);
        Route::get('/vouchers/{code}', [VoucherController::class, 'show']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
        Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar']);

        Route::get('/favorit', [FavoriteController::class, 'index']);
        Route::post('/favorit', [FavoriteController::class, 'store']);
        Route::post('/favorit/toggle', [FavoriteController::class, 'toggle']);
        Route::delete('/favorit/{product}', [FavoriteController::class, 'destroy']);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/test', [NotificationController::class, 'sendTestNotification']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    });

    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blog-categories', [BlogController::class, 'categories']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);
    Route::get('/blogs/{slug}/related', [BlogController::class, 'related']);
});
