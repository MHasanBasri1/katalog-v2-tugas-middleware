@extends('admin.layouts.app')

@section('title', 'Marketplace Link')
@section('header', 'Marketplace Link')

@section('content')
    <div class="space-y-4 w-full" x-data>
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

        <!-- Header & Action Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Link Marketplace</h2>
                    <p class="text-sm text-gray-500">Hubungkan produk dengan toko di berbagai platform e-commerce.</p>
                </div>
            </div>

            <!-- Add/Edit form in a cleaner layout -->
            <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                <form method="POST" action="{{ $editLink ? route('admin.marketplace-link.update', $editLink) : route('admin.marketplace-link.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf
                    @if ($editLink)
                        @method('PUT')
                    @endif
                    <select name="product_id" required class="rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 dark:text-gray-100 text-sm py-2">
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id', $editLink->product_id ?? null) == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <select name="marketplace" required class="rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 dark:text-gray-100 text-sm py-2">
                        <option value="">Marketplace</option>
                        @foreach ($marketplaceOptions as $option)
                            <option value="{{ $option }}" @selected(old('marketplace', $editLink->marketplace ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    <input type="url" name="url" placeholder="https://..." value="{{ old('url', $editLink->url ?? '') }}" required class="rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 dark:text-gray-100 text-sm py-2">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider py-2.5 transition shadow-sm">
                            {{ $editLink ? 'Update' : 'Simpan' }}
                        </button>
                        @if ($editLink)
                            <a href="{{ route('admin.marketplace-link.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-2 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800"><i class="ti ti-x"></i></a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.marketplace-link.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Link</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                            <i class="ti ti-search text-xs"></i>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau URL..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500"
                            style="padding: 0.65rem 1rem 0.65rem 44px;">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        Cari
                    </button>
                    @if (request()->filled('q'))
                        <a href="{{ route('admin.marketplace-link.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Produk</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Marketplace</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">URL</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                        @forelse ($links as $link)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $link->product?->name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $link->marketplace }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:underline">{{ \Illuminate\Support\Str::limit($link->url, 60) }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.marketplace-link.index', ['edit' => $link->id]) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit" aria-label="Edit">
                                            <i class="ti ti-pencil text-base"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.marketplace-link.destroy', $link) }}" x-ref="deleteForm{{ $link->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button" 
                                                @click="$store.confirm.open({
                                                    title: 'Hapus Link',
                                                    message: 'Apakah Anda yakin ingin menghapus link marketplace ini?',
                                                    confirmText: 'Ya, Hapus',
                                                    onConfirm: () => $refs.deleteForm{{ $link->id }}.submit()
                                                })"
                                                class="inline-flex items-center rounded-lg border border-rose-200 p-2 text-rose-600 hover:bg-rose-50" title="Hapus" aria-label="Hapus"
                                            >
                                                <i class="ti ti-trash text-base"></i>
                                            </button>
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
