@php
    /** @var \App\Models\Banner|null $banner */
    $isEdit = isset($banner) && $banner->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.banner.update', $banner) : route('admin.banner.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Banner</label>
            <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Gambar Banner</label>
        <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" {{ $isEdit ? '' : 'required' }} class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG/WEBP, maksimal 3MB.</p>
        @if (! empty($banner?->image_url))
            <img src="{{ $banner->image_url }}" alt="Preview Banner" class="mt-2 w-full max-h-40 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">CTA Label</label>
            <input type="text" name="cta_label" value="{{ old('cta_label', $banner->cta_label ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">CTA URL</label>
            <input type="url" name="cta_url" value="{{ old('cta_url', $banner->cta_url ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="is_active" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="1" @selected((string) old('is_active', isset($banner) ? (int) $banner->is_active : 1) === '1')>Aktif</option>
                <option value="0" @selected((string) old('is_active', isset($banner) ? (int) $banner->is_active : 1) === '0')>Nonaktif</option>
            </select>
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
        <a href="{{ route('admin.banner.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>
</form>
