@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Statistik')

@section('content')
    <div class="space-y-6 pb-8 w-full">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Produk -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-package text-6xl text-blue-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Produk</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($stats['products']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-emerald-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Katalog Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-eye text-6xl text-purple-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Views</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($stats['total_views']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-purple-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Dilihat Pengunjung</span>
                    </div>
                </div>
            </div>

            <!-- Total User -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-users text-6xl text-orange-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Pengguna</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($stats['users']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-orange-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Member Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- Total Artikel -->
            <div class="relative group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ti ti-news text-6xl text-emerald-600"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Artikel</p>
                    <h3 class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($stats['blogs']) }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold text-emerald-500 uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 border border-white dark:border-gray-950"></span>
                        <span>Konten Terbit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Views Chart (Line) -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 flex flex-col hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center border border-blue-100 dark:border-blue-900/10">
                            <i class="ti ti-trending-up text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-none">Tren Views Produk</h3>
                            <p class="mt-1 text-xs text-gray-400 font-medium">Top 10 interaksi produk</p>
                        </div>
                    </div>

                    <!-- Dropdown Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 text-xs font-bold text-gray-700 dark:text-gray-300 hover:border-blue-500 transition-all uppercase tracking-tight"
                        >
                            @php
                                $filters = [
                                    '1d' => '1 Hari',
                                    '7d' => '7 Hari',
                                    '1m' => '1 Bulan',
                                    '6m' => '6 Bulan',
                                    '1y' => '1 Tahun',
                                    'all' => 'Semua'
                                ];
                            @endphp
                            <span>Range: {{ $filters[$range] ?? 'Semua' }}</span>
                            <i class="ti ti-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div 
                            x-show="open" 
                            x-cloak
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-40 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xl z-50 overflow-hidden"
                        >
                            @foreach($filters as $key => $label)
                                <a href="{{ route('admin.dashboard', ['range' => $key]) }}" 
                                   @class([
                                       'block px-4 py-2 text-xs font-bold transition-colors',
                                       'bg-blue-600 text-white' => $range === $key,
                                       'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600' => $range !== $key
                                   ])>
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative flex-1" style="min-height: 260px;">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>

            <!-- Marketplace Sebaran (Pie) -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 flex flex-col hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center border border-orange-100 dark:border-orange-900/10">
                            <i class="ti ti-chart-pie-2 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-none">Ringkasan Klik Marketplace</h3>
                            <p class="mt-1 text-xs text-gray-400 font-medium">Total akumulasi klik per platform</p>
                        </div>
                    </div>
                </div>
                <div class="relative flex-1 flex items-center justify-center" style="min-height: 260px;">
                    <canvas id="marketplaceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Activity Logs Table -->
            <div class="xl:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
                    <a href="{{ route('admin.logs.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest border-b border-transparent hover:border-blue-600 transition-all">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Deskripsi Aktivitas</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">Pemeran</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($activityLogs as $log)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-{{ $log->color }}-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $log->description }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->user)
                                            <span @class([
                                                'px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border',
                                                'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30' => $log->user->hasRole('admin'),
                                                'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-900/30' => !$log->user->hasRole('admin')
                                            ])>
                                                {{ $log->user->hasRole('admin') ? 'ADMIN' : 'MEMBER' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                                GUEST
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-medium text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500 italic text-sm">Belum ada catatan aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 border-dashed border-2 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Akses Cepat</h3>
                <div class="grid grid-cols-1 gap-3">
                    @can('products.manage')
                        <a href="{{ route('admin.produk.create') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-blue-600 hover:border-blue-600 transition-all">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 group-hover:bg-white group-hover:text-blue-600 flex items-center justify-center transition-colors">
                                    <i class="ti ti-plus text-xl"></i>
                                </div>
                                <span class="ml-3 font-semibold text-gray-700 dark:text-gray-300 group-hover:text-white transition-colors">Tambah Produk</span>
                            </div>
                            <i class="ti ti-chevron-right text-gray-400 group-hover:text-white transition-colors"></i>
                        </a>
                    @endcan
                    @can('blogs.manage')
                        <a href="{{ route('admin.blog.create') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-emerald-600 hover:border-emerald-600 transition-all">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 group-hover:bg-white group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                                    <i class="ti ti-edit text-xl"></i>
                                </div>
                                <span class="ml-3 font-semibold text-gray-700 dark:text-gray-300 group-hover:text-white transition-colors">Tulis Artikel</span>
                            </div>
                            <i class="ti ti-chevron-right text-gray-400 group-hover:text-white transition-colors"></i>
                        </a>
                    @endcan
                    @can('settings.manage')
                        <a href="{{ route('admin.setting.index') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-900 dark:hover:bg-white transition-all">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 group-hover:bg-white group-hover:text-gray-900 dark:group-hover:bg-gray-900 dark:group-hover:text-white flex items-center justify-center transition-colors">
                                    <i class="ti ti-settings text-xl"></i>
                                </div>
                                <span class="ml-3 font-semibold text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors">Pengaturan</span>
                            </div>
                            <i class="ti ti-chevron-right text-gray-400 group-hover:text-white transition-colors"></i>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            const textColor = isDark ? '#94a3b8' : '#64748b';

            // Views Chart (Line)
            const ctxViews = document.getElementById('viewsChart').getContext('2d');
            new Chart(ctxViews, {
                type: 'line',
                data: {
                    labels: @json($lineChartData['labels']),
                    datasets: [
                        {
                            label: 'Jumlah Dilihat (Views)',
                            data: @json($lineChartData['data']),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#2563eb',
                            pointRadius: 5,
                            pointHoverRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: isDark ? '#1e293b' : '#fff',
                            titleColor: isDark ? '#fff' : '#1e293b',
                            bodyColor: isDark ? '#94a3b8' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        }
                    }
                }
            });

            // Marketplace Chart (Pie)
            const ctxMarketplace = document.getElementById('marketplaceChart').getContext('2d');
            new Chart(ctxMarketplace, {
                type: 'doughnut',
                data: {
                    labels: @json($pieChartData['labels']),
                    datasets: [{
                        data: @json($pieChartData['data']),
                        backgroundColor: [
                            '#f59e0b', // Shopee
                            '#10b981', // Tokopedia
                            '#2563eb', // Lazada
                            '#06b6d4', // Blibli
                            '#000000', // Tiktok Shop
                            '#9333ea',
                        ],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 30,
                                color: textColor,
                                usePointStyle: true,
                                font: { size: 12, weight: 'bold' }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
