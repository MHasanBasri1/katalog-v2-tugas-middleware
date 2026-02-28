@php
    /** @var \App\Models\StaticPage|null $page */
    $isEdit = isset($page) && $page->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.halaman-statis.update', $page) : route('admin.halaman-statis.store') }}" class="space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul</label>
        <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Konten</label>
        <textarea name="content" rows="10" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('content', $page->content ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
        <select name="is_published" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            <option value="1" @selected((string) old('is_published', isset($page) ? (int) $page->is_published : 1) === '1')>Published</option>
            <option value="0" @selected((string) old('is_published', isset($page) ? (int) $page->is_published : 1) === '0')>Draft</option>
        </select>
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
        <a href="{{ route('admin.halaman-statis.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>
</form>
