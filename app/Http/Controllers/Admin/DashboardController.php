<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use App\Models\StaticPage;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'marketplace_links' => MarketplaceLink::count(),
            'users' => User::count(),
            'admins' => User::role('admin')->count(),
            'blogs' => Blog::count(),
            'banners' => Banner::count(),
            'static_pages' => StaticPage::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
