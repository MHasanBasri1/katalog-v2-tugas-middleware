@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')

@section('content')
    <div class="max-w-3xl">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $kategori->name) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug (opsional)</label>
                    <input type="text" name="slug" value="{{ old('slug', $kategori->slug) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    @error('slug') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('description', $kategori->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="ti ti-device-floppy text-base"></i> Update
                    </button>
                    <a href="{{ route('admin.kategori.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
