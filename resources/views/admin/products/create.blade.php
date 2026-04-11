@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.produk.index') }}" class="hover:text-blue-600 transition-colors">Produk</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Produk Baru</span>
    </nav>

    <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center sm:text-left">Informasi Produk</h3>
                <p class="text-sm text-gray-500 text-center sm:text-left">Detail utama dan kategori produk.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Headphone Wireless Premium"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="category_id" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="headphone-wireless-premium"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Produk</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan detail produk Anda..."
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Stats Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">Harga & Promo</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Coret (Rp)</label>
                        <input type="number" name="original_price" value="{{ old('original_price') }}" step="0.01" min="0"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('original_price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Produk Unggulan (Featured)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="show_in_promo" value="0">
                            <input type="checkbox" name="show_in_promo" value="1" @checked(old('show_in_promo')) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Tampilkan di Produk Promo</span>
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">Statistik & Status</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Terjual</label>
                        <input type="number" name="sold_count" value="{{ old('sold_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Likes</label>
                        <input type="number" name="likes_count" value="{{ old('likes_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Rating (0-5)</label>
                        <input type="number" name="rating_avg" value="{{ old('rating_avg', 0) }}" step="0.1" min="0" max="5" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Jumlah Rating</label>
                        <input type="number" name="rating_count" value="{{ old('rating_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Publikasi</label>
                    <select name="status" required 
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        <option value="1" @selected(old('status', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('status') == '0')>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Gallery & Marketplace Card Group -->
        <div class="space-y-6">
            <!-- Product Gallery Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden" 
                x-data="{ 
                    previews: [],
                    handleFileChange(event) {
                        const files = Array.from(event.target.files);
                        if (files.length > 10) {
                            alert('Maksimal 10 gambar diperbolehkan.');
                            event.target.value = '';
                            return;
                        }
                        this.previews = [];
                        files.forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                }">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Galeri Produk</h3>
                    <p class="text-xs text-gray-500 mt-1">Upload satu atau beberapa gambar produk (Maks 2MB/file).</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        {{-- Previews --}}
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm bg-gray-50 dark:bg-gray-800">
                                <img :src="preview" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 bg-blue-600 text-[8px] font-black text-white px-2 py-0.5 rounded shadow-sm z-10 uppercase tracking-tighter">BARU</div>
                                <button type="button" @click="previews.splice(index, 1); if(previews.length === 0) $refs.fileInput.value = '';" 
                                    class="absolute top-2 right-2 bg-rose-600 text-white w-6 h-6 rounded-lg flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="ti ti-x text-xs"></i>
                                </button>
                            </div>
                        </template>

                        {{-- Add Button --}}
                        <div x-show="previews.length < 10" class="relative aspect-square">
                            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50/50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform duration-300">
                                        <i class="ti ti-photo-plus text-xl text-gray-400 group-hover:text-blue-600"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-tight">Tambah<br>Gambar</p>
                                </div>
                                <input type="file" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileChange" x-ref="fileInput" />
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-500" :style="'width: ' + (previews.length / 10 * 100) + '%'"></div>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400" x-text="previews.length + ' / 10 GAMBAR'"></span>
                    </div>
                    @error('images.*') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Marketplace Links Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Tautan Marketplace</h3>
                    <p class="text-xs text-gray-500 mt-1">Masukkan URL produk di setiap marketplace.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($marketplaceOptions as $index => $marketplace)
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">{{ $marketplace }}</label>
                                <input type="hidden" name="marketplace_links[{{ $index }}][marketplace]" value="{{ $marketplace }}">
                                <input type="url" name="marketplace_links[{{ $index }}][url]" value="{{ old('marketplace_links.'.$index.'.url') }}" placeholder="https://..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                        @endforeach
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
            <div class="flex flex-row items-center justify-end gap-2 sm:gap-3 px-3 sm:px-6">
                <a href="{{ route('admin.produk.index') }}" class="flex-1 sm:flex-none px-3 sm:px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-[10px] sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" name="action" value="save_and_another" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-[10px] sm:text-sm font-bold hover:bg-blue-50 transition text-center whitespace-nowrap">
                    Simpan & Buat Lagi
                </button>
                <button type="submit" class="flex-1 sm:flex-none px-6 sm:px-10 py-2.5 rounded-xl bg-blue-600 text-white text-[10px] sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
