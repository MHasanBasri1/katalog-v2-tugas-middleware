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
                <!-- Konten Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Artikel</label>
                            <input type="text" name="title" x-model="title" required placeholder="Masukkan judul artikel..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (URL)</label>
                            <input type="text" name="slug" x-model="slug" placeholder="link-artikel-anda"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Isi Artikel</label>
                            <div class="relative">
                                <textarea name="content" id="content-textarea" class="hidden">{{ old('content', $blog->content ?? '') }}</textarea>
                                <div id="quill-editor" class="bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl min-h-[400px]">
                                    {!! old('content', $blog->content ?? '') !!}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Ringkasan (Excerpt)</label>
                            <textarea name="excerpt" rows="3" placeholder="Tulis ringkasan singkat artikel..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Media Cover Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Media Cover</h3>
                    
                    <div class="space-y-4">
                        <div class="relative group cursor-pointer">
                            <input type="file" name="cover_image_file" @change="updatePreview" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <template x-if="imageUrl">
                                <div class="relative rounded-2xl overflow-hidden aspect-video bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!imageUrl">
                                <div class="rounded-2xl aspect-video bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center gap-2 group-hover:bg-gray-100 dark:group-hover:bg-gray-700/50 transition-all">
                                    <i class="ti ti-photo-plus text-2xl text-gray-400"></i>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pilih Gambar</span>
                                </div>
                            </template>
                        </div>
                        <p class="text-[10px] text-gray-400 italic text-center">Maksimal 3MB. Format: JPG, PNG, WEBP.</p>
                    </div>
                </div>

                <!-- Pengaturan Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Pengaturan</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori</label>
                            <select name="category_id" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
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
                            <select name="author_name" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                <option value="Tim Kataloque">Pilih Penulis</option>
                                @foreach ($authors as $user)
                                    <option value="{{ $user->name }}" @selected(old('author_name', $blog->author_name ?? '') === $user->name)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status</label>
                            <select name="is_published" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                <option value="1" @selected((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '1')>Published</option>
                                <option value="0" @selected((string) old('is_published', isset($blog) ? (int) $blog->is_published : 1) === '0')>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tanggal Publish</label>
                            <input type="datetime-local" name="published_at"
                                value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-900/10">
                <ul class="text-xs text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center gap-2 mt-8">
            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Artikel' }}
            </button>
            <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-6 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Kembali
            </a>
            @if(!$isEdit)
                <button type="submit" name="action" value="save_and_another" class="ml-auto hidden md:inline-flex items-center rounded-xl border border-blue-600 text-blue-600 text-sm font-semibold px-6 py-2.5 hover:bg-blue-50 transition">
                    Simpan & Tambah Lagi
                </button>
            @endif
        </div>
    </form>
</div>

{{-- Quill WYSIWYG Editor Assets --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tuliskan isi artikel di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        var textarea = document.getElementById('content-textarea');
        quill.on('text-change', function() {
            textarea.value = quill.root.innerHTML;
        });

        if (textarea.value) {
            quill.root.innerHTML = textarea.value;
        }
    });
</script>

<style>
    .ql-toolbar.ql-snow {
        border: 1px solid #e5e7eb !important;
        border-radius: 1rem 1rem 0 0 !important;
        background-color: #f9fafb !important;
        padding: 0.75rem !important;
    }
    .dark .ql-toolbar.ql-snow {
        border-color: #374151 !important;
        background-color: #1f2937 !important;
    }
    .ql-container.ql-snow {
        border: 1px solid #e5e7eb !important;
        border-top: none !important;
        border-radius: 0 0 1rem 1rem !important;
        font-family: inherit !important;
    }
    .dark .ql-container.ql-snow {
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    .ql-editor { font-size: 0.875rem !important; min-height: 400px !important; }
    .dark .ql-snow .ql-stroke { stroke: #9ca3af !important; }
    .dark .ql-snow .ql-fill { fill: #9ca3af !important; }
    .dark .ql-snow .ql-picker { color: #9ca3af !important; }
</style>
