@extends('admin.layouts.app')

@section('title', 'Marketplace Link')
@section('header', 'Marketplace Link')

@section('content')
    <div class="space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                {{ $editLink ? 'Edit Marketplace Link' : 'Tambah Marketplace Link' }}
            </h2>
            <form method="POST" action="{{ $editLink ? route('admin.marketplace-link.update', $editLink) : route('admin.marketplace-link.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                @if ($editLink)
                    @method('PUT')
                @endif
                <select name="product_id" required class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">Pilih Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id', $editLink->product_id ?? null) == $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
                <select name="marketplace" required class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">Marketplace</option>
                    @foreach ($marketplaceOptions as $option)
                        <option value="{{ $option }}" @selected(old('marketplace', $editLink->marketplace ?? '') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <input type="url" name="url" placeholder="https://..." value="{{ old('url', $editLink->url ?? '') }}" required class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        {{ $editLink ? 'Update' : 'Simpan' }}
                    </button>
                    @if ($editLink)
                        <a href="{{ route('admin.marketplace-link.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">Batal</a>
                    @endif
                </div>
            </form>
            @if ($errors->any())
                <ul class="mt-3 text-xs text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-left">Marketplace</th>
                            <th class="px-4 py-3 text-left">URL</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($links as $link)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $link->product?->name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $link->marketplace }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:underline">{{ \Illuminate\Support\Str::limit($link->url, 60) }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.marketplace-link.index', ['edit' => $link->id]) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Edit</a>
                                        <form method="POST" action="{{ route('admin.marketplace-link.destroy', $link) }}" onsubmit="return confirm('Hapus marketplace link ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-500">Belum ada marketplace link.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $links->links() }}
            </div>
        </div>
    </div>
@endsection
