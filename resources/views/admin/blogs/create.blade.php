@extends('admin.layouts.app')

@section('title', 'Tambah Artikel')
@section('header', 'Tambah Artikel')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.blog.index') }}" class="hover:text-blue-600 transition-colors">Blog</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Artikel Baru</span>
    </nav>

    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Konten Artikel</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Artikel</label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul artikel yang menarik..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-lg font-bold">
                        @error('title') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto-generate)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="judul-artikel-anda"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" rows="3" placeholder="Ringkasan singkat tentang isi artikel ini..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('excerpt') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Isi Artikel</label>
                        <textarea name="content" rows="15" required placeholder="Tuliskan konten artikel secara mendalam di sini..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium leading-relaxed">{{ old('content') }}</textarea>
                        @error('content') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="space-y-6">
            <!-- Publishing Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Pengaturan Publikasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status</label>
                        <select name="is_published" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="1" @selected(old('is_published', '1') == '1')>Published</option>
                            <option value="0" @selected(old('is_published') == '0')>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tanggal Publish</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori Blog</label>
                        <select name="category_id" 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Cover Image Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Thumbnail Artikel</h3>
                </div>
                <div class="p-6">
                    <label class="group relative flex flex-col items-center justify-center w-full min-h-[160px] border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl hover:border-blue-600 transition-colors cursor-pointer overflow-hidden">
                        <input type="file" name="cover_image_file" accept=".jpg,.jpeg,.png,.webp" class="sr-only peer" onchange="previewImage(this)">
                        
                        <div id="image-preview-container" class="absolute inset-0 hidden">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <span class="text-white text-xs font-bold uppercase tracking-widest bg-black/60 px-3 py-1.5 rounded-full">Ganti Gambar</span>
                            </div>
                        </div>

                        <div id="upload-placeholder" class="flex flex-col items-center justify-center p-6 text-center">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center mb-3">
                                <i class="ti ti-photo-plus text-2xl"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pilih Gambar</span>
                            <span class="text-[10px] text-gray-400 mt-1 italic">JPG, PNG atau WEBP (Maks. 3MB)</span>
                        </div>
                    </label>
                    @error('cover_image_file') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Author Info Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Informasi Penulis</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Penulis</label>
                        <input type="text" name="author_name" value="{{ old('author_name', 'Tim Kataloque') }}" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tags (Pisahkan dengan koma)</label>
                        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="tips, gadget, promo"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
            :class="{
                'xl:left-72': $store.sidebar.isExpanded,
                'xl:left-20': !$store.sidebar.isExpanded,
                'left-0': true
            }">
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 px-4">
                <a href="{{ route('admin.blog.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center">
                    Batal
                </a>
                <button type="submit" name="action" value="save_and_another" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-sm font-bold hover:bg-blue-50 transition text-center">
                    Simpan & Buat Lagi
                </button>
                <button type="submit" class="w-full sm:w-auto px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Publikasikan Artikel
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const placeholder = document.getElementById('upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
