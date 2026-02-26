@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Total Produk</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ number_format($stats['products']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Total Kategori</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ number_format($stats['categories']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Marketplace Link</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ number_format($stats['marketplace_links']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">User Admin</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ number_format($stats['admins']) }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Akses Cepat</h2>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="ti ti-package text-base"></i> Kelola Produk
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <i class="ti ti-category text-base"></i> Kelola Kategori
                </a>
                <a href="{{ route('admin.marketplace-link.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <i class="ti ti-link text-base"></i> Kelola Marketplace
                </a>
                <a href="{{ route('admin.setting.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <i class="ti ti-settings text-base"></i> Pengaturan
                </a>
            </div>
        </div>
    </div>
@endsection
