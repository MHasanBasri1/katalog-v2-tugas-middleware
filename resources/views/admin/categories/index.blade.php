@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('header', 'Kategori')

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

        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Kategori</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori produk sesuai data seed.</p>
            </div>
            <a href="{{ route('admin.kategori.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                <i class="ti ti-plus text-base"></i> Tambah Kategori
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Slug</th>
                            <th class="px-4 py-3 text-left">Deskripsi</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->slug }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.kategori.edit', $category) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Edit</a>
                                        <form method="POST" action="{{ route('admin.kategori.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-500">Belum ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
