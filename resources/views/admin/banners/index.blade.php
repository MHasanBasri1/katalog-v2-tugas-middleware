@extends('admin.layouts.app')

@section('title', 'Banner')
@section('header', 'Banner')

@section('content')
    <div
        class="space-y-4 w-full"
        x-data="{
            currentPageIds: @js($banners->getCollection()->pluck('id')->values()),
            selectedIds: [],
            bulkDeleteUrl: @js(route('admin.banner.bulk-destroy')),
            toggleRowSelection(id) {
                const value = Number(id);
                if (this.selectedIds.includes(value)) {
                    this.selectedIds = this.selectedIds.filter((item) => item !== value);
                    return;
                }
                this.selectedIds.push(value);
            },
            toggleSelectAllOnPage() {
                const allSelected = this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
                if (allSelected) {
                    this.selectedIds = this.selectedIds.filter((id) => !this.currentPageIds.includes(id));
                    return;
                }
                this.selectedIds = Array.from(new Set([...this.selectedIds, ...this.currentPageIds]));
            },
            submitBulkDelete() {
                if (this.selectedIds.length === 0) return;
                if (!confirm(`Hapus ${this.selectedIds.length} banner terpilih?`)) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.bulkDeleteUrl;

                const csrf = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') ?? '';
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrf;
                form.appendChild(tokenInput);

                this.selectedIds.forEach((id) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = String(id);
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            },
            get isAllOnPageSelected() {
                return this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
            }
        }"
    >
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-4">
            <!-- Header & Action Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Banner</h2>
                        <p class="text-sm text-gray-500">Kelola banner promosi dan informasi di homepage.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            x-show="selectedIds.length > 0"
                            x-cloak
                            @click="submitBulkDelete()"
                            class="inline-flex items-center gap-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-bold uppercase tracking-wider border border-rose-100 dark:border-rose-900/10 px-4 py-2"
                        >
                            <i class="ti ti-trash"></i>
                            <span x-text="`Hapus (${selectedIds.length})`"></span>
                        </button>
                        <a href="{{ route('admin.banner.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold uppercase tracking-wider shadow-sm shadow-blue-200 dark:shadow-none">
                            <i class="ti ti-plus"></i>
                            Tambah Banner
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.banner.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Banner</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                                <i class="ti ti-search text-xs"></i>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari banner..." 
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500"
                                style="padding: 0.65rem 1rem 0.65rem 44px;">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                            Filter
                        </button>
                        @if (request()->filled('q'))
                            <a href="{{ route('admin.banner.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-center w-12 border-b border-gray-100 dark:border-gray-800">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800">Banner</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Urutan</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Status</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                                @forelse ($banners as $banner)
                                    <tr>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" :checked="selectedIds.includes({{ $banner->id }})" @change="toggleRowSelection({{ $banner->id }})" class="rounded border-gray-300 text-blue-600">
                                        </td>
                                        <td class="px-4 py-3 min-w-[200px]">
                                            <div class="flex items-center gap-4">
                                                @if($banner->image_url)
                                                    <img src="{{ $banner->image_url }}" class="w-24 h-12 object-cover rounded-lg border border-gray-100 dark:border-gray-800 shadow-sm" alt="Banner">
                                                @else
                                                    <div class="w-24 h-12 rounded-lg bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                                        <i class="ti ti-photo text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="truncate max-w-[200px]">
                                                    <p class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $banner->title }}</p>
                                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-1">Link:</p>
                                                    <span class="text-[10px] text-blue-600 dark:text-blue-400 truncate block">{{ $banner->cta_url ?: '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-gray-600 dark:text-gray-400">
                                            {{ $banner->sort_order }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span @class([
                                                'inline-flex rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                                'bg-emerald-100 text-emerald-700 shadow-sm shadow-emerald-100' => $banner->is_active,
                                                'bg-gray-100 text-gray-700' => ! $banner->is_active,
                                            ])>
                                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.banner.edit', $banner) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit">
                                                    <i class="ti ti-pencil text-base"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.banner.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-lg border border-rose-100 dark:border-rose-900/10 p-2 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-center" title="Hapus">
                                                        <i class="ti ti-trash text-base"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada data banner.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $banners->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection









