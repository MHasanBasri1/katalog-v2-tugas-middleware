@extends('admin.layouts.app')

@section('title', 'Edit Banner')
@section('header', 'Edit Banner')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.banner.index') }}" class="hover:text-blue-600 transition-colors">Banner</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Edit Banner</span>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-500 truncate max-w-[200px]">{{ $banner->title }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.banner.update', $banner) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Informasi Banner</h3>
                        <span class="text-xs font-mono text-gray-400">#{{ $banner->id }}</span>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Banner</label>
                            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            @error('title') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Subtitle / Deskripsi Singkat</label>
                            <textarea name="subtitle" rows="3"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('subtitle', $banner->subtitle) }}</textarea>
                            @error('subtitle') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Label Tombol (CTA)</label>
                                <input type="text" name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Link Tujuan (CTA URL)</label>
                                <input type="text" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Image Upload Section -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Visual Banner</h3>
                    </div>
                    <div class="p-6">
                        <label class="group relative flex flex-col items-center justify-center w-full min-h-[200px] border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl hover:border-blue-600 transition-colors cursor-pointer overflow-hidden">
                            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" class="sr-only peer" onchange="previewBanner(this)">
                            
                            <div id="banner-preview-container" class="absolute inset-0 {{ $banner->image_url ? '' : 'hidden' }}">
                                <img id="banner-preview" src="{{ $banner->image_url ?: '#' }}" alt="Preview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                    <span class="text-white text-xs font-bold uppercase tracking-widest bg-black/60 px-3 py-1.5 rounded-full">Ganti Gambar</span>
                                </div>
                            </div>

                            <div id="upload-placeholder" class="flex flex-col items-center justify-center p-6 text-center {{ $banner->image_url ? 'hidden' : '' }}">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center mb-3">
                                    <i class="ti ti-photo-plus text-2xl"></i>
                                </div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Unggah Gambar Banner</span>
                            </div>
                        </label>
                        <p class="mt-2 text-[10px] text-gray-400 italic text-center">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        @error('image_file') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Right Side: Secondary Settings -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Pengaturan</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Urutan Tampil (Sort)</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0" required
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status</label>
                            <select name="is_active" required 
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                                <option value="1" @selected(old('is_active', $banner->is_active) == '1')>Aktif</option>
                                <option value="0" @selected(old('is_active', $banner->is_active) == '0')>Nonaktif</option>
                            </select>
                        </div>
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
                <a href="{{ route('admin.banner.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function previewBanner(input) {
    const preview = document.getElementById('banner-preview');
    const container = document.getElementById('banner-preview-container');
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
