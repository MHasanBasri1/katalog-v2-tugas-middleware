@extends('admin.layouts.app')

@section('title', 'Halaman Statis')
@section('header', 'Halaman Statis')

@section('content')
    <div
        class="space-y-4 w-full"
        x-data="{
            currentPageIds: @js($pages->getCollection()->pluck('id')->values()),
            selectedIds: [],
            bulkDeleteUrl: @js(route('admin.halaman-statis.bulk-destroy')),
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
                
                $store.confirm.open({
                    title: 'Hapus Halaman Terpilih',
                    message: `Apakah Anda yakin ingin menghapus ${this.selectedIds.length} halaman yang dipilih?`,
                    confirmText: 'Ya, Hapus Semua',
                    onConfirm: () => {
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
                    }
                });
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

        <!-- Header & Action Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Halaman Statis</h2>
                    <p class="text-sm text-gray-500">Kelola informasi seperti Tentang Kami, Kebijakan Privasi, dan lainnya.</p>
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
                    <a href="{{ route('admin.halaman-statis.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold uppercase tracking-wider shadow-sm shadow-blue-200 dark:shadow-none">
                        <i class="ti ti-plus"></i>
                        Tambah Halaman
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.halaman-statis.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Halaman</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                            <i class="ti ti-search text-xs"></i>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul halaman..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500"
                            style="padding: 0.65rem 1rem 0.65rem 44px;">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        Filter
                    </button>
                    @if (request('q'))
                        <a href="{{ route('admin.halaman-statis.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="mt-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-center w-12 border-b border-gray-100 dark:border-gray-800">
                                <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800">Halaman</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                            @forelse ($pages as $page)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $page->id }})" @change="toggleRowSelection({{ $page->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3 min-w-[300px]">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100 leading-none">{{ $page->title }}</p>
                                        <p class="text-[10px] text-gray-500 mt-2 font-mono">{{ $page->slug }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div x-data="{ 
                                            id: {{ $page->id }}, 
                                            status: {{ $page->is_published ? 1 : 0 }},
                                            loading: false,
                                            async toggleStatus(val) {
                                                if (this.loading) return;
                                                this.loading = true;
                                                try {
                                                    const res = await fetch(`{{ route('admin.halaman-statis.update-status', $page->id) }}`, {
                                                        method: 'PATCH',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        body: JSON.stringify({ is_published: val })
                                                    });
                                                    const data = await res.json();
                                                    if (data.status === 'success') {
                                                        this.status = val;
                                                    }
                                                } catch (err) {
                                                    console.error(err);
                                                } finally {
                                                    this.loading = false;
                                                }
                                            }
                                        }" class="relative inline-block text-left">
                                            <div class="group relative">
                                                <select 
                                                    @change="toggleStatus($event.target.value)"
                                                    :disabled="loading"
                                                    :class="{
                                                        'bg-emerald-50 text-emerald-600 border-emerald-100': status == 1,
                                                        'bg-gray-50 text-gray-500 border-gray-100': status == 0,
                                                        'opacity-50': loading
                                                    }"
                                                    class="appearance-none pl-3 pr-8 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider border transition-all cursor-pointer outline-none focus:ring-2 focus:ring-blue-500/20"
                                                >
                                                    <option value="1" :selected="status == 1">Published</option>
                                                    <option value="0" :selected="status == 0">Draft</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-current opacity-50">
                                                    <i class="ti ti-chevron-down text-[10px]" x-show="!loading"></i>
                                                    <i class="ti ti-loader animate-spin text-[10px]" x-show="loading"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.halaman-statis.edit', $page) }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-800 p-2.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Edit Halaman">
                                                <i class="ti ti-pencil text-sm"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.halaman-statis.destroy', $page) }}" x-ref="deleteForm{{ $page->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button" 
                                                    @click="$store.confirm.open({
                                                        title: 'Hapus Halaman',
                                                        message: 'Apakah Anda yakin ingin menghapus halaman statis ini?',
                                                        confirmText: 'Ya, Hapus',
                                                        onConfirm: () => $refs.deleteForm{{ $page->id }}.submit()
                                                    })"
                                                    class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-800 p-2.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all" title="Hapus Halaman"
                                                >
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                                                <i class="ti ti-file-text text-3xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-400 italic">Belum ada halaman statis yang dibuat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if ($pages->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
    </div>
@endsection
