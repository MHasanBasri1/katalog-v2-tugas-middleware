@php
    /** @var \App\Models\Blog|null $blog */
    $isEdit = isset($blog) && $blog->exists;
    $blogCategories = $categories ?? \App\Models\BlogCategory::query()->orderBy('name')->get(['id', 'name']);
    $authors = $authors ?? \App\Models\User::query()->orderBy('name')->get(['id', 'name']);
@endphp

<div x-data="{
    title: @js(old('title', $blog->title ?? '')),
    slug: @js(old('slug', $blog->slug ?? '')),
    imageUrl: @js($isEdit && $blog->cover_image ? (str_starts_with($blog->cover_image, 'http') ? $blog->cover_image : asset($blog->cover_image)) : null),
    init() {
        if (!@js($isEdit)) {
            this.$watch('title', value => {
                this.slug = this.slugify(value);
            });
        }
    },
    slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    },
    updatePreview(event) {
        const file = event.target.files[0];
        if (file) {
            this.imageUrl = URL.createObjectURL(file);
        }
    }
}">
    <form method="POST" action="{{ $isEdit ? route('admin.blog.update', $blog) : route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Artikel</label>
                            <input type="text" name="title" x-model="title" required placeholder="Masukkan judul menarik..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (URL)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">/blog/</span>
                                <input type="text" name="slug" x-model="slug" placeholder="link-artikel-otomatis"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium pl-14">
                            </div>
                            <p class="mt-1.5 text-[10px] text-gray-500">Slug akan digenerate otomatis jika dikosongkan.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Isi Artikel</label>
                            <textarea name="content" id="editor" class="wysiwyg-editor">{{ old('content', $blog->content ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" rows="3" placeholder="Tulis ringkasan singkat artikel di sini..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Media -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <i class="ti ti-photo text-blue-600 text-base"></i>
                        Media Cover
                    </h3>
                    
                    <div>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="cover_image_file" @change="updatePreview" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <template x-if="imageUrl">
                                <div class="relative rounded-2xl overflow-hidden aspect-video bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 group-hover:border-blue-500 transition-colors">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="ti ti-camera text-white text-2xl"></i>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!imageUrl">
                                <div class="rounded-2xl aspect-video bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center gap-2 group-hover:bg-gray-100 dark:group-hover:bg-gray-700/50 transition-all">
                                    <i class="ti ti-photo-plus text-3xl text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Pilih Gambar</span>
                                </div>
                            </template>
                        </div>
                        <p class="mt-3 text-[10px] text-gray-400 leading-relaxed text-center italic">Rekomendasi ukuran 1200x630 pixels. Maksimal 3MB.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <i class="ti ti-adjustments text-blue-600 text-base"></i>
                        Pengaturan
                    </h3>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="category_id" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="">Tanpa Kategori</option>
                            @foreach ($blogCategories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', (string) ($blog->category_id ?? '')) === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Penulis (Author)</label>
                        <div class="relative">
                            <input list="author_list" name="author_name" value="{{ old('author_name', $blog->author_name ?? '') }}" placeholder="Pilih atau ketik penulis..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium pl-10">
                            <i class="ti ti-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <datalist id="author_list">
                            @foreach ($authors as $user)
                                <option value="{{ $user->name }}">
                            @endforeach
                            <option value="Tim Kataloque">
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Publikasi</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="is_published" value="1" class="peer sr-only" @checked((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '1')>
                                <div class="p-3 text-center rounded-xl border border-gray-200 dark:border-gray-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 peer-checked:text-emerald-700 transition-all">
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Publish</span>
                                </div>
                            </label>
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="is_published" value="0" class="peer sr-only" @checked((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '0')>
                                <div class="p-3 text-center rounded-xl border border-gray-200 dark:border-gray-700 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 peer-checked:text-amber-700 transition-all">
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Draft</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Jadwal Publikasi</label>
                        <input
                            type="datetime-local"
                            name="published_at"
                            value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                        >
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-900/10">
                <ul class="text-xs text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <i class="ti ti-alert-circle text-base"></i>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center justify-between gap-4 py-6 border-t border-gray-100 dark:border-gray-800">
            <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                <i class="ti ti-arrow-left"></i>
                Batal
            </a>
            <div class="flex items-center gap-3">
                @if(!$isEdit)
                <button type="submit" name="action" value="save_and_another" class="hidden md:inline-flex items-center gap-2 rounded-xl px-6 py-3 border border-blue-600 text-blue-600 text-sm font-bold hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all">
                    Simpan & Tambah Lagi
                </button>
                @endif
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-8 py-3 bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 dark:shadow-none transition-all">
                    <i class="ti ti-device-floppy"></i>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}
                </button>
            </div>
        </div>
    </form>
</div>

{{-- WYSIWYG Editor Script --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        height: 500,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | removeformat | help',
        content_style: 'body { font-family: Plus Jakarta Sans, sans-serif; font-size: 14px; line-height: 1.6; }',
        skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
        content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
        branding: false,
        promotion: false,
        image_advtab: true,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
</script>

<style>
    /* Custom styles for TinyMCE to match the design */
    .tox-tinymce {
        border: 1px solid #e5e7eb !important;
        border-radius: 1rem !important;
        overflow: hidden;
    }
    .dark .tox-tinymce {
        border-color: #374151 !important;
    }
    .tox-editor-header {
        background-color: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    .dark .tox-editor-header {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }
</style>
