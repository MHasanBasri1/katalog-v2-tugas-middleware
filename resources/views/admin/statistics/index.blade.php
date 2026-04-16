@extends('admin.layouts.app')

@section('title', 'Statistik')
@section('header', 'Statistik Produk & Marketplace')

@section('content')
    <div class="space-y-6 pb-8 w-full">
        <!-- Range Filter & Export Tools -->
        <div class="flex items-center justify-between flex-wrap gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm print:hidden">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center border border-blue-100 dark:border-blue-900/10">
                    <i class="ti ti-calendar text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Laporan Statistik</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Analisis data & Export Laporan</p>
                </div>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Export Actions -->
                <div class="flex items-center gap-2 border-r border-gray-100 dark:border-gray-800 pr-4 mr-2">
                    <a href="{{ route('admin.statistics', array_merge(request()->query(), ['export' => 'csv'])) }}" 
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 border border-emerald-100 dark:border-emerald-900/10 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                        <i class="ti ti-file-export"></i> CSV
                    </a>
                    <a href="{{ route('admin.statistics', array_merge(request()->query(), ['print' => 1])) }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 border border-rose-100 dark:border-rose-900/10 text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                        <i class="ti ti-printer"></i> Cetak PDF
                    </a>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl border-2 border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 text-xs font-black text-gray-700 dark:text-gray-300 hover:border-blue-500 hover:bg-white transition-all uppercase tracking-widest shadow-sm"
                    >
                        @php
                            $filters = [
                                'all' => 'Semua Waktu',
                                '7d' => '1 Minggu Terakhir',
                                '1m' => '1 Bulan Terakhir',
                                '2m' => '2 Bulan Terakhir',
                                '3m' => '3 Bulan Terakhir',
                                '6m' => '6 Bulan Terakhir',
                                '12m' => '12 Bulan Terakhir'
                            ];
                        @endphp
                        <span>Periode: {{ $filters[(string)$range] ?? 'Semua Waktu' }}</span>
                        <i class="ti ti-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div 
                        x-show="open" 
                        x-cloak
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute right-0 mt-3 w-56 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xl z-[150] overflow-hidden"
                    >
                        <div class="p-2 space-y-1">
                            @foreach($filters as $key => $label)
                                <a href="{{ route('admin.statistics', ['range' => $key]) }}" 
                                   @class([
                                       'flex items-center justify-between px-4 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all',
                                       'bg-blue-600 text-white shadow-lg shadow-blue-500/30' => $range === $key,
                                       'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' => $range !== $key
                                   ])>
                                    <span>{{ $label }}</span>
                                    @if($range === $key)
                                        <i class="ti ti-check text-xs"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Views -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-eye text-6xl text-blue-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Views Produk</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($summary['total_views']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-blue-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Akumulasi semua produk</span>
                    </div>
                </div>
            </div>

            <!-- Total Marketplace Clicks -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-mouse text-6xl text-emerald-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Klik Marketplace</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($summary['total_clicks']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-emerald-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Klik dari detail produk</span>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-package text-6xl text-purple-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Produk</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($summary['total_products']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-purple-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Produk dalam katalog</span>
                    </div>
                </div>
            </div>

            <!-- Average Click-through -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-chart-arrows text-6xl text-orange-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Conversion Rate</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">
                        {{ $summary['total_views'] > 0 ? number_format(($summary['total_clicks'] / $summary['total_views']) * 100, 1) : 0 }}%
                    </h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-orange-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Views to Click ratio</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Viewed Products -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Views Produk</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Nama Produk</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-right">{{ $range === 'all' ? 'Total Views' : 'Views (Filtered)' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($topViewedProducts as $product)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-black">
                                            <i class="ti ti-eye"></i> {{ number_format($product->range_count) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                    {{ $topViewedProducts->links() }}
                </div>
            </div>

            <!-- Clicked Marketplace Links -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Klik Marketplace</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Nama Produk</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Rincian Marketplace</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-right">{{ $range === 'all' ? 'Total Klik' : 'Klik (Filtered)' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($topClickedProducts as $product)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="grid grid-cols-1 gap-1 w-max">
                                            @foreach($product->marketplaceLinks as $link)
                                                @php
                                                    $platform = Str::lower($link->marketplace);
                                                    $colors = [
                                                        'shopee' => 'bg-[#EE4D2D] text-white',
                                                        'tokopedia' => 'bg-[#42B549] text-white',
                                                        'lazada' => 'bg-[#0f146d] text-white',
                                                        'blibli' => 'bg-[#0095DC] text-white',
                                                        'tiktok' => 'bg-black text-white',
                                                        'tiktok shop' => 'bg-black text-white',
                                                    ];
                                                    $colorClass = $colors[$platform] ?? 'bg-gray-100 text-gray-600';
                                                @endphp
                                                <div class="flex items-center justify-between px-2 py-1 rounded-lg {{ $colorClass }} shadow-sm border border-white/5 w-[120px]">
                                                    <span class="text-[9px] font-black uppercase tracking-tight">{{ $link->marketplace }}</span>
                                                    <span class="text-[9px] font-black bg-white/20 px-1.5 py-0.5 rounded-md ml-1">{{ number_format($link->link_clicks) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-black">
                                            <i class="ti ti-mouse"></i> {{ number_format($product->range_count) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                    {{ $topClickedProducts->links() }}
                </div>
            </div>
        </div>

        <!-- Sebaran Marketplace -->
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Performa Per Platform</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Marketplace</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Jumlah Link</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-right">Total Klik</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-right">Avg Klik/Link</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($marketplaceStats as $stat)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $platform = Str::lower($stat->marketplace);
                                            $icons = [
                                                'shopee' => 'ti ti-shopping-cart',
                                                'tokopedia' => 'ti ti-building-store',
                                                'lazada' => 'ti ti-brand-shopee',
                                                'blibli' => 'ti ti-shopping-bag',
                                                'tiktok' => 'ti ti-brand-tiktok',
                                                'tiktok shop' => 'ti ti-brand-tiktok',
                                            ];
                                            $icon = $icons[$platform] ?? 'ti ti-store';
                                        @endphp
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400">
                                            <i class="{{ $icon }} text-lg"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-tight">{{ $stat->marketplace }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-gray-500">{{ number_format($stat->link_count) }} Link</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($stat->total_clicks) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-[11px] font-bold text-gray-400">
                                        {{ $stat->link_count > 0 ? number_format($stat->total_clicks / $stat->link_count, 1) : 0 }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
