<div x-data="{ 
    showDeleteModal: false, 
    deleteId: null,
    confirmDelete(id) {
        this.deleteId = id;
        this.showDeleteModal = true;
    }
}">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
        {{-- Existing Images --}}
        @foreach($images as $image)
            <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 group shadow-sm bg-gray-50 dark:bg-gray-800">
                <img src="{{ Storage::url($image->image) }}" class="w-full h-full object-cover">
                
                @if($image->is_primary)
                    <div class="absolute top-2 left-2 bg-emerald-600 text-[8px] font-black text-white px-2 py-0.5 rounded shadow-sm z-10 uppercase tracking-tighter">UTAMA</div>
                @endif
                
                <!-- Image Actions Overlay -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center gap-2 backdrop-blur-[2px]">
                    @if(!$image->is_primary)
                        <button type="button" 
                            wire:click="setPrimary({{ $image->id }})"
                            class="bg-white/95 hover:bg-white text-gray-900 text-[10px] font-black px-3 py-1.5 rounded-xl shadow-sm transition-all transform translate-y-2 group-hover:translate-y-0 active:scale-95 uppercase tracking-tight">
                            Set Utama
                        </button>
                    @endif
                    <button type="button" 
                        @click="confirmDelete({{ $image->id }})"
                        class="bg-rose-600/90 hover:bg-rose-600 text-white p-2 rounded-xl shadow-sm transition-all transform translate-y-2 group-hover:translate-y-0 hover:scale-110 active:scale-90">
                        <i class="ti ti-trash text-sm"></i>
                    </button>
                </div>
            </div>
        @endforeach

        {{-- Add New Image Button (Aligned in the same grid) --}}
        @if($images->count() < 10)
            <div class="relative aspect-square">
                <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50/50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 group overflow-hidden" 
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                        <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform duration-300">
                            <i class="ti ti-photo-plus text-xl text-gray-400 group-hover:text-blue-600"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest group-hover:text-gray-600">Tambah</p>
                    </div>
                    <input type="file" wire:model.live="newImages" multiple class="hidden" accept="image/*" />
                    
                    {{-- Loading State Overlay --}}
                    <div wire:loading wire:target="newImages" class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 flex flex-col items-center justify-center">
                        <div class="w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </label>
            </div>
        @endif
    </div>

    {{-- Limit Info --}}
    <div class="mt-4 flex items-center gap-2">
        <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full transition-all duration-500" style="width: {{ ($images->count() / 10) * 100 }}%"></div>
        </div>
        <span class="text-[10px] font-black {{ $images->count() >= 10 ? 'text-rose-600' : 'text-gray-400' }} uppercase tracking-widest">
            {{ $images->count() }} / 10 GAMBAR
        </span>
    </div>

    <!-- Custom Style Delete Modal -->
    <div x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center px-4 overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        
        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-2xl w-full max-w-sm overflow-hidden z-[210] border border-gray-100 dark:border-gray-800"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-rose-50 dark:bg-rose-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-trash text-3xl text-rose-600"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Hapus Gambar?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Tindakan ini tidak dapat dibatalkan. Gambar akan dihapus permanen dari server.</p>
            </div>
            
            <div class="flex border-t border-gray-100 dark:border-gray-800">
                <button @click="showDeleteModal = false" 
                    class="flex-1 px-6 py-4 text-xs font-black text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-r border-gray-100 dark:border-gray-800 uppercase tracking-widest">
                    Batal
                </button>
                <button @click="$wire.deleteImage(deleteId); showDeleteModal = false;" 
                    class="flex-1 px-6 py-4 text-xs font-black text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/10 transition-colors uppercase tracking-widest">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
