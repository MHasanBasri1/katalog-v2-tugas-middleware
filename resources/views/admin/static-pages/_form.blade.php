@php
    /** @var \App\Models\StaticPage|null $page */
    $isEdit = isset($page) && $page->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.halaman-statis.update', $page) : route('admin.halaman-statis.store') }}" class="space-y-6 pb-24" id="static-page-form">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Konten Utama</h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Halaman</label>
                        <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required placeholder="Masukkan judul halaman..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        @error('title') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="url-halaman-statis"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Isi Halaman</label>
                        <div class="relative">
                            <textarea name="content" id="page-content" class="hidden">{{ old('content', $page->content ?? '') }}</textarea>
                            <div id="quill-editor" class="bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl min-h-[400px]">
                                {!! old('content', $page->content ?? '') !!}
                            </div>
                        </div>
                        @error('content') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Pengaturan</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Publikasi</label>
                        <select name="is_published" 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3 appearance-none cursor-pointer">
                            <option value="1" @selected((string) old('is_published', isset($page) ? (int) $page->is_published : 1) === '1')>Diterbitkan</option>
                            <option value="0" @selected((string) old('is_published', isset($page) ? (int) $page->is_published : 1) === '0')>Simpan Draft</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Ringkasan (SEO)</label>
                        <textarea name="excerpt" rows="6" placeholder="Tuliskan ringkasan singkat untuk hasil pencarian Google..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3 resize-none">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
        x-data="{ isExpanded: $store.sidebar.isExpanded }"
        :class="isExpanded ? 'xl:left-72' : 'xl:left-20 left-0'">
        <div class="flex flex-row items-center justify-end gap-3 px-3 sm:px-6">
            <a href="{{ route('admin.halaman-statis.index') }}" 
                class="px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Batal
            </a>
            @if (!$isEdit)
                <button type="submit" name="action" value="save_and_another" 
                    class="px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-sm font-bold hover:bg-blue-50 transition">
                    Simpan & Buat Lagi
                </button>
            @endif
            <button type="submit" 
                class="px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none">
                {{ $isEdit ? 'Perbarui Halaman' : 'Simpan Halaman' }}
            </button>
        </div>
    </div>
</form>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-color: #e5e7eb;
        background: #f9fafb;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 12px;
    }
    .ql-container.ql-snow {
        border-color: #e5e7eb;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
        font-family: inherit;
    }
    .ql-editor { font-size: 0.875rem; min-height: 500px; line-height: 1.6; }
    .dark .ql-toolbar.ql-snow { border-color: #374151; background: #1f2937; }
    .dark .ql-container.ql-snow { border-color: #374151; background: #111827; }
    .dark .ql-editor { color: #f3f4f6; }
    .ql-snow .ql-stroke { stroke: currentColor; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tampilkan kreativitas Anda di sini...',
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

        const form = document.querySelector('#static-page-form');
        const contentInput = document.querySelector('#page-content');

        if (contentInput.value) {
            quill.root.innerHTML = contentInput.value;
        }

        form.onsubmit = function() {
            contentInput.value = quill.root.innerHTML;
        };
    });
</script>
@endpush
