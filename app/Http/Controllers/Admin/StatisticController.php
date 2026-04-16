<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MarketplaceLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', 'all');
        $startDate = null;

        if ($range !== 'all') {
            $startDate = match($range) {
                '7d' => now()->subDays(7),
                '1m' => now()->subMonth(),
                '2m' => now()->subMonths(2),
                '3m' => now()->subMonths(3),
                '6m' => now()->subMonths(6),
                '12m' => now()->subMonths(12),
                default => null,
            };
        }

        // Viewed Products - Top 15
        $productsQuery = Product::query()
            ->select('products.id', 'products.name', 'products.category_id', 'products.views_count as total_views')
            ->with('category:id,name');

        if ($startDate) {
            $productsQuery->selectSub(function ($query) use ($startDate) {
                $query->from('analytics_logs')
                    ->whereColumn('target_id', 'products.id')
                    ->where('target_type', 'product')
                    ->where('activity', 'view')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('count(*)');
            }, 'range_count');
        } else {
            $productsQuery->selectRaw('views_count as range_count');
        }
        
        $topViewedProducts = $productsQuery->orderByDesc('range_count')->paginate(10, ['*'], 'page_views')->withQueryString();

        // Marketplace Clicks - Top Products
        $topClickedProducts = Product::query()
            ->select('products.id', 'products.name')
            ->selectSub(function ($query) use ($startDate) {
                $query->from('analytics_logs')
                    ->join('marketplace_links as ml', 'analytics_logs.target_id', '=', 'ml.id')
                    ->whereColumn('ml.product_id', 'products.id')
                    ->where('analytics_logs.target_type', 'marketplace_link')
                    ->where('analytics_logs.activity', 'click')
                    ->when($startDate, fn($q) => $q->where('analytics_logs.created_at', '>=', $startDate))
                    ->selectRaw('count(*)');
            }, 'range_count')
            ->with(['marketplaceLinks' => function($query) use ($startDate) {
                if ($startDate) {
                    $query->select('id', 'product_id', 'marketplace')
                        ->selectSub(function ($q) use ($startDate) {
                            $q->from('analytics_logs')
                                ->whereColumn('target_id', 'marketplace_links.id')
                                ->where('target_type', 'marketplace_link')
                                ->where('activity', 'click')
                                ->where('created_at', '>=', $startDate)
                                ->selectRaw('count(*)');
                        }, 'link_clicks');
                } else {
                    $query->select('id', 'product_id', 'marketplace', 'click_count as link_clicks');
                }
            }])
            ->orderByDesc('range_count')
            ->paginate(10, ['*'], 'page_clicks')->withQueryString();

        // Stats by Marketplace Platform
        $marketplaceStatsQuery = MarketplaceLink::query()
            ->select('marketplace', DB::raw('COUNT(*) as link_count'));

        if ($startDate) {
            $marketplaceStatsQuery->selectSub(function ($query) use ($startDate) {
                $query->from('analytics_logs')
                    ->join('marketplace_links as ml', 'analytics_logs.target_id', '=', 'ml.id')
                    ->whereColumn('ml.marketplace', 'marketplace_links.marketplace')
                    ->where('analytics_logs.target_type', 'marketplace_link')
                    ->where('analytics_logs.activity', 'click')
                    ->where('analytics_logs.created_at', '>=', $startDate)
                    ->selectRaw('count(*)');
            }, 'total_clicks');
        } else {
            $marketplaceStatsQuery->addSelect(DB::raw('SUM(click_count) as total_clicks'));
        }

        $marketplaceStats = $marketplaceStatsQuery->groupBy('marketplace')
            ->orderByDesc('total_clicks')
            ->get();

        // Summary Stats
        $summary = [
            'total_views' => $startDate 
                ? \App\Models\AnalyticsLog::where('target_type', 'product')->where('activity', 'view')->where('created_at', '>=', $startDate)->count()
                : Product::sum('views_count'),
            'total_clicks' => $startDate
                ? \App\Models\AnalyticsLog::where('target_type', 'marketplace_link')->where('activity', 'click')->where('created_at', '>=', $startDate)->count()
                : MarketplaceLink::sum('click_count'),
            'total_products' => Product::count(),
            'total_links' => MarketplaceLink::count(),
        ];

        if ($request->has('print')) {
            $topViewedProducts = $productsQuery->orderByDesc('range_count')->get();
            $topClickedProducts = Product::query()
                ->select('products.id', 'products.name')
                ->selectSub(function ($query) use ($startDate) {
                    $query->from('analytics_logs')
                        ->join('marketplace_links as ml', 'analytics_logs.target_id', '=', 'ml.id')
                        ->whereColumn('ml.product_id', 'products.id')
                        ->where('analytics_logs.target_type', 'marketplace_link')
                        ->where('analytics_logs.activity', 'click')
                        ->when($startDate, fn($q) => $q->where('analytics_logs.created_at', '>=', $startDate))
                        ->selectRaw('count(*)');
                }, 'range_count')
                ->with(['marketplaceLinks' => function($query) use ($startDate) {
                    if ($startDate) {
                        $query->select('id', 'product_id', 'marketplace')
                            ->selectSub(function ($q) use ($startDate) {
                                $q->from('analytics_logs')
                                    ->whereColumn('target_id', 'marketplace_links.id')
                                    ->where('target_type', 'marketplace_link')
                                    ->where('activity', 'click')
                                    ->where('created_at', '>=', $startDate)
                                    ->selectRaw('count(*)');
                            }, 'link_clicks');
                    } else {
                        $query->select('id', 'product_id', 'marketplace', 'click_count as link_clicks');
                    }
                }])
                ->orderByDesc('range_count')
                ->get();

            return view('admin.statistics.print', compact(
                'topViewedProducts',
                'topClickedProducts',
                'marketplaceStats',
                'summary',
                'range'
            ));
        }

        if ($request->has('export')) {
            return $this->exportCsv($range, $summary, $topViewedProducts, $topClickedProducts);
        }

        return view('admin.statistics.index', compact(
            'topViewedProducts',
            'topClickedProducts',
            'marketplaceStats',
            'summary',
            'range'
        ));
    }

    private function exportCsv($range, $summary, $topViewed, $topClicked)
    {
        $filename = "laporan-statistik-{$range}-" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tipe', 'Nama/Platform', 'Data 1', 'Data 2'];

        $callback = function() use ($range, $summary, $topViewed, $topClicked, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['LAPORAN STATISTIK KATALOQUE - PERIODE: ' . strtoupper($range)]);
            fputcsv($file, []);
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Views', $summary['total_views']]);
            fputcsv($file, ['Total Klik Marketplace', $summary['total_clicks']]);
            fputcsv($file, ['Total Produk', $summary['total_products']]);
            fputcsv($file, []);
            
            fputcsv($file, ['DAFTAR VIEWS PRODUK (TOP 15)']);
            fputcsv($file, ['Nama Produk', 'Views']);
            foreach ($topViewed as $p) {
                fputcsv($file, [$p->name, $p->range_count]);
            }
            fputcsv($file, []);

            fputcsv($file, ['DAFTAR KLIK MARKETPLACE (TOP 15)']);
            fputcsv($file, ['Nama Produk', 'Rincian Klik Marketplace', 'Total Klik']);
            foreach ($topClicked as $p) {
                $details = $p->marketplaceLinks->map(fn($l) => "{$l->marketplace}: {$l->link_clicks}")->implode(' | ');
                fputcsv($file, [$p->name, $details, $p->range_count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
