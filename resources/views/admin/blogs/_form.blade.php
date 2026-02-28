@php
    /** @var \App\Models\Blog|null $blog */
    $isEdit = isset($blog) && $blog->exists;
    $blogCategories = $categories ?? \App\Models\BlogCategory::query()->orderBy('name')->get(['id', 'name']);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.blog.update', $blog) : route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul</label>
        <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $blog->slug ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Konten</label>
        <textarea name="content" rows="8" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('content', $blog->content ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Cover Artikel</label>
            <input type="file" name="cover_image_file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
            <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG/WEBP, maksimal 3MB.</p>
            @if (! empty($blog?->cover_image))
                <img src="{{ $blog->cover_image }}" alt="Preview Cover Blog" class="mt-2 w-full max-h-40 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
            @endif
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <select name="category_id" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="">Tanpa kategori</option>
                @foreach ($blogCategories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', (string) ($blog->category_id ?? '')) === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Author</label>
        <input type="text" name="author_name" value="{{ old('author_name', $blog->author_name ?? 'Tim VISTORA') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="is_published" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="1" @selected((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '1')>Published</option>
                <option value="0" @selected((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '0')>Draft</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Publish</label>
            <input
                type="datetime-local"
                name="published_at"
                value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"
            >
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
        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>
</form>
