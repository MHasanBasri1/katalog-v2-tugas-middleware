<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'marketplace_links' => MarketplaceLink::count(),
            'admins' => User::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
