<?php

use App\Models\Category;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\StaticPage;
use App\Models\Product;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PanelController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\UserGoogleAuthController;
use App\Http\Controllers\Auth\UserDeviceVerificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminNewPasswordController;
use App\Http\Controllers\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LogController;

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/admin/lupa-password', [AdminPasswordResetLinkController::class, 'create'])->name('admin.password.request');
    Route::post('/admin/lupa-password', [AdminPasswordResetLinkController::class, 'store'])->name('admin.password.email')->middleware('throttle:3,1');
    Route::get('/admin/reset-password/{token}', [AdminNewPasswordController::class, 'create'])->name('admin.password.reset');
    Route::post('/admin/reset-password', [AdminNewPasswordController::class, 'store'])->name('admin.password.store')->middleware('throttle:5,1');

    Route::get('/masuk', [UserAuthController::class, 'showLoginForm'])->name('user.login');
    Route::post('/masuk', [UserAuthController::class, 'login'])->name('user.login.store')->middleware('throttle:5,1');
    Route::get('/auth/google/redirect', [UserGoogleAuthController::class, 'redirect'])->name('user.google.redirect');
    Route::get('/auth/google/callback', [UserGoogleAuthController::class, 'callback'])->name('user.google.callback');
    Route::get('/verifikasi-device/{token}', UserDeviceVerificationController::class)
        ->middleware('throttle:6,1')
        ->name('user.device.verify');
        
    Route::get('/lupa-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetLinkController::class, 'store'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store')->middleware('throttle:5,1');
    
    Route::get('/daftar', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/daftar', [UserAuthController::class, 'register'])->name('user.register.store')->middleware('throttle:5,1');
});

Route::redirect('/login', '/masuk', 301);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/email/verifikasi', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verifikasi/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('user.panel')->with('status', 'Email berhasil diverifikasi.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verifikasi/kirim', function (Request $request) {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'role:user', 'verified'])->group(function () {
    Route::get('/profil-saya', [PanelController::class, 'index'])->name('user.panel');
    Route::put('/profil-saya/profil', [PanelController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/profil-saya/password', [PanelController::class, 'updatePassword'])->name('user.password.update');
    Route::post('/profil-saya/avatar', [PanelController::class, 'updateAvatar'])->name('user.avatar.update');
    Route::delete('/profil-saya/avatar', [PanelController::class, 'destroyAvatar'])->name('user.avatar.destroy');
    Route::delete('/profil-saya/favorit/{productId}', [PanelController::class, 'destroyFavorite'])->name('user.favorite.destroy');
});

Route::middleware(['web', 'throttle:60,1'])->group(function () {
    Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');

    Route::get('/kategori', [App\Http\Controllers\Public\CategoryController::class, 'index'])->name('kategori');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Public\CategoryController::class, 'show'])->name('kategori.detail');

    Route::get('/produk', function () {
        return view('frontend.produk');
    })->name('katalog');

    Route::get('/produk/{slug}', [App\Http\Controllers\Public\ProductController::class, 'show'])->name('produk.detail');

    Route::get('/blog', function (Request $request) {
        $canonical = route('blog.index');
        $seoTitle = 'Blog - Kataloque';
        $seoDescription = 'Artikel terbaru Kataloque seputar tips belanja online, gadget, dan rekomendasi produk.';
        $selectedCategory = (string) $request->query('kategori', '');
        $selectedTag = (string) $request->query('tag', '');

        $postQuery = Blog::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->where('is_published', true)
            ->when(
                $selectedCategory !== '',
                fn ($query) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $selectedCategory))
            )
            ->when(
                $selectedTag !== '',
                fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $selectedTag))
            );

        $ogImage = (clone $postQuery)
            ->orderByDesc('published_at')
            ->value('cover_image') ?: 'https://picsum.photos/seed/kataloque-blog/1200/630';

        $posts = $postQuery
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = BlogCategory::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $tags = BlogTag::query()->orderBy('name')->get(['id', 'name', 'slug']);

        return view('frontend.blog', compact(
            'posts',
            'canonical',
            'seoTitle',
            'seoDescription',
            'ogImage',
            'categories',
            'tags',
            'selectedCategory',
            'selectedTag'
        ));
    })->name('blog.index');

    Route::get('/blog/{slug}', function (string $slug) {
        $post = Blog::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $canonical = route('blog.detail', $post->slug);
        $seoTitle = "{$post->title} - Blog Kataloque";
        $seoDescription = $post->excerpt;
        $ogImage = $post->cover_image;

        $relatedPosts = Blog::query()
            ->with(['category:id,name,slug'])
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$post->category_id])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.blog-detail', compact('post', 'relatedPosts', 'canonical', 'seoTitle', 'seoDescription', 'ogImage'));
    })->name('blog.detail');

    Route::get('/robots.txt', function () {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /masuk\n";
        $robots .= "Disallow: /daftar\n";
        $robots .= "Disallow: /profil-saya\n";
        $robots .= "Disallow: /reset-password/\n";
        $robots .= "Disallow: /verifikasi-device/\n\n";
        $robots .= "Sitemap: " . url('/sitemap.xml');

        return response($robots)->header('Content-Type', 'text/plain');
    });

    Route::get('/{slug}', function (string $slug) {
        $page = StaticPage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $canonical = route('halaman.show', $page->slug);
        $seoTitle = "{$page->title} - Kataloque";
        $seoDescription = $page->excerpt ?: str($page->content)->limit(155)->toString();
        $ogImage = 'https://picsum.photos/seed/kataloque-static-page/1200/630';

        return view('frontend.static-page', compact('page', 'canonical', 'seoTitle', 'seoDescription', 'ogImage'));
    })->name('halaman.show');
});

Route::middleware(['auth', 'role:admin', 'log.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('/branding', [SettingController::class, 'index'])->name('branding');
        Route::get('/marketplace', [SettingController::class, 'index'])->name('marketplace');
        Route::get('/navigasi', [SettingController::class, 'index'])->name('navigasi');
        Route::get('/seo', [SettingController::class, 'index'])->name('seo');
        Route::get('/sistem', [SettingController::class, 'index'])->name('sistem');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('/', [SettingController::class, 'store'])->name('store');
    });

    Route::resource('/kategori', CategoryController::class)
        ->middleware('permission:categories.manage');
    Route::post('/kategori/bulk-delete', [CategoryController::class, 'bulkDestroy'])
        ->middleware('permission:categories.manage')
        ->name('kategori.bulk-destroy');

    Route::post('/produk/bulk-delete', [ProductController::class, 'bulkDestroy'])
        ->middleware('permission:products.manage')
        ->name('produk.bulk-destroy');
    Route::post('/produk/import-csv', [ProductController::class, 'importCsv'])
        ->middleware('permission:products.manage')
        ->name('produk.import-csv');
    Route::get('/produk/template-csv', [ProductController::class, 'downloadCsvTemplate'])
        ->middleware('permission:products.manage')
        ->name('produk.template-csv');
    Route::get('/produk/export-csv', [ProductController::class, 'exportCsv'])
        ->middleware('permission:products.manage')
        ->name('produk.export-csv');
    Route::resource('/produk', ProductController::class)
        ->whereNumber('produk')
        ->middleware('permission:products.manage');
    Route::post('/produk/{produk}/sync-market', [ProductController::class, 'syncMarketplace'])
        ->name('produk.sync-market');
    Route::post('/produk/{produk}/images/{image}/set-primary', [ProductController::class, 'setPrimaryImage'])
        ->name('produk.images.set-primary');
    Route::delete('/produk/{produk}/images/{image}', [ProductController::class, 'deleteImage'])
        ->name('produk.images.delete');

    Route::resource('/blog', BlogController::class)
        ->except(['show'])
        ->middleware('permission:blogs.manage');
    Route::patch('/blog/{blog}/status', [BlogController::class, 'updateStatus'])
        ->middleware('permission:blogs.manage')
        ->name('blog.update-status');
    Route::post('/blog/bulk-delete', [BlogController::class, 'bulkDestroy'])
        ->middleware('permission:blogs.manage')
        ->name('blog.bulk-destroy');

    Route::resource('/blog-kategori', BlogCategoryController::class)
        ->except(['show'])
        ->middleware('permission:blogs.manage');
    Route::post('/blog-kategori/bulk-delete', [BlogCategoryController::class, 'bulkDestroy'])
        ->middleware('permission:blogs.manage')
        ->name('blog-kategori.bulk-destroy');

    Route::get('/user/export-csv', [UserController::class, 'exportCsv'])
        ->middleware('permission:users.manage')
        ->name('user.export-csv');
    Route::post('/user/import-csv', [UserController::class, 'importCsv'])
        ->middleware('permission:users.manage')
        ->name('user.import-csv');

    Route::resource('/user', UserController::class)
        ->except(['show'])
        ->middleware('permission:users.manage');
    Route::post('/user/bulk-delete', [UserController::class, 'bulkDestroy'])
        ->middleware('permission:users.manage')
        ->name('user.bulk-destroy');

    Route::post('/user/{user}/freeze', [UserController::class, 'freeze'])
        ->middleware('permission:users.manage')
        ->name('user.freeze');
    Route::post('/user/{user}/unfreeze', [UserController::class, 'unfreeze'])
        ->middleware('permission:users.manage')
        ->name('user.unfreeze');

    Route::resource('/banner', BannerController::class)
        ->except(['show'])
        ->middleware('permission:banners.manage');
    Route::post('/banner/bulk-delete', [BannerController::class, 'bulkDestroy'])
        ->middleware('permission:banners.manage')
        ->name('banner.bulk-destroy');

    Route::resource('/halaman-statis', StaticPageController::class)
        ->except(['show'])
        ->middleware('permission:static_pages.manage');
    Route::patch('/halaman-statis/{halaman_stati}/status', [StaticPageController::class, 'updateStatus'])
        ->middleware('permission:static_pages.manage')
        ->name('halaman-statis.update-status');
    Route::post('/halaman-statis/bulk-delete', [StaticPageController::class, 'bulkDestroy'])
        ->middleware('permission:static_pages.manage')
        ->name('halaman-statis.bulk-destroy');

    // Activity Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
});
