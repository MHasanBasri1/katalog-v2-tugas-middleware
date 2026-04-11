@php
    /** @var \App\Models\Banner|null $banner */
    $isEdit = isset($banner) && $banner->exists;
@endphp

<div x-data="{
    imageUrl: @js($isEdit && $banner->image_url ? (str_starts_with($banner->image_url, 'http') ? $banner->image_url : asset($banner->image_url)) : null),
    updatePreview(event) {
        const file = event.target.files[0];
        if (file) {
            this.imageUrl = URL.createObjectURL(file);
        }
    },
    removeImage() {
        this.imageUrl = null;
        this.$refs.imageInput.value = '';
    }
}">
    <form method="POST" action="{{ $isEdit ? route('admin.banner.update', $banner) : route('admin.banner.store') }}" enctype="multipart/form-data" class="space-y-6 pb-24">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Banner Media -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Konten Banner</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Judul Banner (Hanya untuk Admin)</label>
                            <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required placeholder="Contoh: Promo Ramadhan"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div class="relative group cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <input type="file" x-ref="imageInput" name="image_file" @change="updatePreview" accept="image/*" class="sr-only">
                            
                            <template x-if="imageUrl">
                                <div class="relative aspect-[21/9] bg-gray-100 dark:bg-gray-800">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 backdrop-blur-[2px]">
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="$refs.imageInput.click()" class="px-4 py-2 bg-white text-gray-900 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-100 transition-all">Ganti Banner</button>
                                            <button type="button" @click="removeImage" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-rose-700 transition-all">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="!imageUrl">
                                <div @click="$refs.imageInput.click()" class="aspect-[21/9] bg-gray-50 dark:bg-gray-800 flex flex-col items-center justify-center gap-3 group-hover:bg-gray-100 dark:group-hover:bg-gray-700/50 transition-all">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center">
                                        <i class="ti ti-photo-plus text-2xl"></i>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Pilih Gambar Banner</span>
                                        <span class="block text-[10px] text-gray-400 mt-1 italic">Rekomendasi ukuran: 1920x800 pixel</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <p class="text-[10px] text-gray-400 italic text-center leading-relaxed font-medium">Format: JPG, PNG, WEBP. Maksimal ukuran file 3MB.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Konfigurasi</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tautan Link (URL)</label>
                            <input type="url" name="cta_url" value="{{ old('cta_url', $banner->cta_url ?? '') }}" placeholder="https://..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Urutan Tampil</label>
                            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status</label>
                            <select name="is_active" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                <option value="1" @selected((string) old('is_active', isset($banner) ? (int) $banner->is_active : 1) === '1')>Aktif</option>
                                <option value="0" @selected((string) old('is_active', isset($banner) ? (int) $banner->is_active : 1) === '0')>Nonaktif</option>
                            </select>
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

        <!-- Sticky Bottom Actions -->
        <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
            :class="{
                'xl:left-72': $store.sidebar.isExpanded,
                'xl:left-20': !$store.sidebar.isExpanded,
                'left-0': true
            }">
            <div class="flex flex-row items-center justify-end gap-2 sm:gap-3 px-3 sm:px-6">
                <a href="{{ route('admin.banner.index') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none px-10 py-2.5 rounded-xl bg-blue-600 text-white text-xs sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap">
                    Simpan Banner
                </button>
            </div>
        </div>
    </form>
</div>
