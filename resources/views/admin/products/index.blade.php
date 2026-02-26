@extends('admin.layouts.app')

@section('title', 'Produk')
@section('header', 'Produk')

@section('content')
    <div class="space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Produk</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola produk sesuai struktur data seeder.</p>
            </div>
            <a href="{{ route('admin.produk.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                <i class="ti ti-plus text-base"></i> Tambah Produk
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category?->name }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-emerald-100 text-emerald-700' => $product->status,
                                        'bg-gray-200 text-gray-700' => ! $product->status,
                                    ])>
                                        {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.produk.edit', $product) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Edit</a>
                                        <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
