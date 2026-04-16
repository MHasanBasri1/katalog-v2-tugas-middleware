<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', 'all');

        $stats = [
            'products' => Product::count(),
            'total_views' => Product::sum('views_count'),
            'users' => User::count(),
            'blogs' => Blog::count(),
        ];

        // Pie Chart: Marketplace Clicks Distribution
        $marketplaceStats = MarketplaceLink::query()
            ->select('marketplace', \DB::raw('SUM(click_count) as total_clicks'))
            ->groupBy('marketplace')
            ->orderByDesc('total_clicks')
            ->get();
        
        $pieChartData = [
            'labels' => $marketplaceStats->pluck('marketplace'),
            'data' => $marketplaceStats->pluck('total_clicks'),
        ];

        // Line Chart: Product Views (Filtering by Top Products based on views within range or overall)
        // Since I don't have a history of views per day in the DB (only a single column),
        // I'll show the Top Products overall but label it to respect the "Real Data" request.
        // If the user wants 7 days total etc, they'd need a separate analytics table.
        // For now, I'll provide the top products based on the global views_count.
        $topProducts = Product::query()
            ->select('name', 'views_count')
            ->orderByDesc('views_count')
            ->take(10)
            ->get();

        $lineChartData = [
            'labels' => $topProducts->pluck('name')->map(fn($n) => \Str::limit($n, 12)),
            'data' => $topProducts->pluck('views_count'),
        ];

        // Bottom section: Activity Logs
        $activityLogs = ActivityLog::with('user')
            ->whereNotNull('user_id')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'pieChartData', 'lineChartData', 'activityLogs', 'range'));
    }
}
