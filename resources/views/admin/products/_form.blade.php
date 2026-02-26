@php
    /** @var \App\Models\Product|null $produk */
    $isEdit = isset($produk) && $produk->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.produk.update', $produk) : route('admin.produk.store') }}" class="space-y-5">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <select name="category_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id ?? null) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $produk->name ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $produk->slug ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            @error('slug') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', isset($produk) ? (float) $produk->price : 0) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            @error('price') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Coret (Original)</label>
            <input type="number" step="0.01" min="0" name="original_price" value="{{ old('original_price', isset($produk) && $produk->original_price !== null ? (float) $produk->original_price : '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            @error('original_price') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="1" @selected((string) old('status', isset($produk) ? (int) $produk->status : 1) === '1')>Aktif</option>
                <option value="0" @selected((string) old('status', isset($produk) ? (int) $produk->status : 1) === '0')>Nonaktif</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('description', $produk->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Sold</label>
            <input type="number" min="0" name="sold_count" value="{{ old('sold_count', $produk->sold_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Views</label>
            <input type="number" min="0" name="view_count" value="{{ old('view_count', $produk->view_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Likes</label>
            <input type="number" min="0" name="likes_count" value="{{ old('likes_count', $produk->likes_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Rating Avg</label>
            <input type="number" min="0" max="5" step="0.1" name="rating_avg" value="{{ old('rating_avg', isset($produk) ? (float) $produk->rating_avg : 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Rating Count</label>
            <input type="number" min="0" name="rating_count" value="{{ old('rating_count', $produk->rating_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
    </div>

    <div>
        <label class="inline-flex items-center gap-2">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600" @checked((string) old('is_featured', isset($produk) ? (int) $produk->is_featured : 0) === '1')>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan sebagai produk unggulan</span>
        </label>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
            <i class="ti ti-device-floppy text-base"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
            Batal
        </a>
    </div>
</form>
