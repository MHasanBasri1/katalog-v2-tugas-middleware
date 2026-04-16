@php
    /** @var \App\Models\Voucher|null $voucher */
    $voucher = $voucher ?? new \App\Models\Voucher();
    $isEdit = isset($voucher) && $voucher->exists;
@endphp

<div x-data="{
    type: @js(old('type', $voucher->type ?? 'fixed')),
}">
    <form method="POST" action="{{ $isEdit ? route('admin.voucher.update', $voucher) : route('admin.voucher.store') }}" class="space-y-6 pb-24">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Primary Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Informasi Voucher</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kode Voucher</label>
                                <input type="text" name="code" value="{{ old('code', $voucher->code ?? '') }}" required placeholder="KODEPROMO2024"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-bold p-3 uppercase">
                                @error('code') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Voucher (Internal)</label>
                                <input type="text" name="name" value="{{ old('name', $voucher->name ?? '') }}" required placeholder="Contoh: Diskon Ramadhan 50k"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                @error('name') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Voucher</label>
                            <textarea name="description" rows="3" placeholder="Jelaskan detail voucher ini..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">{{ old('description', $voucher->description ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tipe Diskon</label>
                                <select name="type" x-model="type" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                    <option value="fixed">Nominal (Rp)</option>
                                    <option value="percentage">Persentase (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nilai Diskon</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="value" value="{{ old('value', $voucher->value ?? 0) }}" required
                                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-bold p-3 pr-10">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400" x-text="type === 'percentage' ? '%' : 'Rp'"></span>
                                </div>
                            </div>
                            <div x-show="type === 'percentage'">
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Maksimal Diskon</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount', $voucher->max_discount ?? '') }}"
                                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-bold p-3 pr-10">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Syarat & Pembatasan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Minimal Pembelian</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase ?? 0) }}" required
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-bold p-3 pr-10">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Limit Penggunaan (Kosongkan jika tidak terbatas)</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit ?? '') }}"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-bold p-3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">Status & Periode</h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Aktif</label>
                            <select name="is_active" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                                <option value="1" @selected((string) old('is_active', isset($voucher) ? (int) $voucher->is_active : 1) === '1')>Aktif</option>
                                <option value="0" @selected((string) old('is_active', isset($voucher) ? (int) $voucher->is_active : 1) === '0')>Nonaktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tanggal Mulai</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date', $voucher->start_date?->format('Y-m-d\TH:i') ?? '') }}"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Tanggal Berakhir</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date', $voucher->end_date?->format('Y-m-d\TH:i') ?? '') }}"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-xl outline-none transition-all duration-300 text-sm font-medium p-3">
                        </div>
                    </div>
                </div>

                @if($isEdit)
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Statistik Penggunaan</h4>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Digunakan:</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $voucher->used_count }}</span>
                        </div>
                    </div>
                @endif
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
                <a href="{{ route('admin.voucher.index') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none px-10 py-2.5 rounded-xl bg-blue-600 text-white text-xs sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap">
                    Simpan Voucher
                </button>
            </div>
        </div>
    </form>
</div>
