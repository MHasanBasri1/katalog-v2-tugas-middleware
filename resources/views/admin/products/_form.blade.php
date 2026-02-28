@php
    /** @var \App\Models\Product|null $produk */
    $isEdit = isset($produk) && $produk->exists;
    $existingLinks = collect($produk->marketplaceLinks ?? [])->keyBy('marketplace');
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.produk.update', $produk) : route('admin.produk.store') }}" class="space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
        <select name="category_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id ?? null) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $produk->name ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $produk->slug ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', isset($produk) ? (float) $produk->price : 0) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Coret</label>
            <input type="number" step="0.01" min="0" name="original_price" value="{{ old('original_price', isset($produk) && $produk->original_price !== null ? (float) $produk->original_price : '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('description', $produk->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="1" @selected((string) old('status', isset($produk) ? (int) $produk->status : 1) === '1')>Aktif</option>
                <option value="0" @selected((string) old('status', isset($produk) ? (int) $produk->status : 1) === '0')>Nonaktif</option>
            </select>
        </div>
        <div class="flex items-end">
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600" @checked((string) old('is_featured', isset($produk) ? (int) $produk->is_featured : 0) === '1')>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Featured</span>
            </label>
        </div>
        <div class="col-span-2">
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="show_in_promo" value="0">
                <input type="checkbox" name="show_in_promo" value="1" class="rounded border-gray-300 text-blue-600" @checked((string) old('show_in_promo', isset($produk) ? (int) $produk->show_in_promo : 0) === '1')>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Tampilkan di Produk Promo</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div><input type="number" min="0" name="sold_count" placeholder="Sold" value="{{ old('sold_count', $produk->sold_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"></div>
        <div><input type="number" min="0" name="likes_count" placeholder="Likes" value="{{ old('likes_count', $produk->likes_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"></div>
        <div><input type="number" min="0" max="5" step="0.1" name="rating_avg" placeholder="Rating Avg" value="{{ old('rating_avg', isset($produk) ? (float) $produk->rating_avg : 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"></div>
        <div><input type="number" min="0" name="rating_count" placeholder="Rating Count" value="{{ old('rating_count', $produk->rating_count ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"></div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-3">
        <h4 class="text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">Marketplace</h4>
        <div class="space-y-2">
            @foreach ($marketplaceOptions as $index => $marketplace)
                @php $defaultUrl = $existingLinks->get($marketplace)?->url; @endphp
                <input type="hidden" name="marketplace_links[{{ $index }}][marketplace]" value="{{ $marketplace }}">
                <input
                    type="url"
                    name="marketplace_links[{{ $index }}][url]"
                    value="{{ old("marketplace_links.$index.url", $defaultUrl) }}"
                    placeholder="{{ $marketplace }} URL"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"
                >
            @endforeach
        </div>
    </div>

    @if ($errors->any())
        <ul class="text-xs text-rose-600 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="flex items-center gap-2">
        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
            {{ $isEdit ? 'Update' : 'Simpan' }}
        </button>
        @if ($isEdit)
            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                Batal
            </a>
        @endif
    </div>
</form>
