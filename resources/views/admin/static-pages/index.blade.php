@extends('admin.layouts.app')

@section('title', 'Halaman Statis')
@section('header', 'Halaman Statis')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            init() {
                this.toggleBodyLock(this.drawerOpen);
                this.$watch('drawerOpen', (value) => this.toggleBodyLock(value));
            },
            drawerOpen: {{ ($editPage || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editPage ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editPage->id ?? null)),
            pagesMap: @js(
                $pages->getCollection()->mapWithKeys(fn($item) => [
                    $item->id => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'slug' => $item->slug,
                        'excerpt' => $item->excerpt,
                        'content' => $item->content,
                        'is_published' => (int) $item->is_published,
                    ],
                ])
            ),
            form: {
                title: @js(old('title', $editPage->title ?? '')),
                slug: @js(old('slug', $editPage->slug ?? '')),
                excerpt: @js(old('excerpt', $editPage->excerpt ?? '')),
                content: @js(old('content', $editPage->content ?? '')),
                is_published: @js((string) old('is_published', isset($editPage) ? (int) $editPage->is_published : 1)),
            },
            currentPageIds: @js($pages->getCollection()->pluck('id')->values()),
            selectedIds: [],
            storeUrl: @js(route('admin.halaman-statis.store')),
            updateUrlTemplate: @js(route('admin.halaman-statis.update', ['halaman_stati' => '__ID__'])),
            bulkDeleteUrl: @js(route('admin.halaman-statis.bulk-destroy')),
            toggleBodyLock(isOpen) {
                document.documentElement.classList.toggle('overflow-hidden', isOpen);
                document.body.classList.toggle('overflow-hidden', isOpen);
                document.body.classList.toggle('drawer-open', isOpen);
            },
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
                if (!confirm(`Hapus ${this.selectedIds.length} halaman terpilih?`)) return;

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
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.form = {
                    title: '',
                    slug: '',
                    excerpt: '',
                    content: '',
                    is_published: '1',
                };
            },
            openEdit(id) {
                const item = this.pagesMap[id];
                if (!item) return;
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = id;
                this.form = {
                    title: item.title ?? '',
                    slug: item.slug ?? '',
                    excerpt: item.excerpt ?? '',
                    content: item.content ?? '',
                    is_published: String(item.is_published ?? 1),
                };
            },
            closeDrawer() {
                this.drawerOpen = false;
            },
            get actionUrl() {
                return this.isEdit
                    ? this.updateUrlTemplate.replace('__ID__', this.editId)
                    : this.storeUrl;
            },
            get isAllOnPageSelected() {
                return this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
            }
        }"
        @keydown.escape.window="if (drawerOpen) closeDrawer()"
    >
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Halaman</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola halaman statis.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        x-show="selectedIds.length > 0"
                        x-cloak
                        @click="submitBulkDelete()"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2.5 transition"
                    >
                        <i class="ti ti-trash text-base"></i>
                        <span x-text="`Hapus Terpilih (${selectedIds.length})`"></span>
                    </button>
                    <button type="button" @click="openCreate()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="ti ti-plus text-base"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[980px] lg:min-w-0 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-4 py-3 text-left">Judul</th>
                                <th class="px-4 py-3 text-left">Slug</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($pages as $page)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $page->id }})" @change="toggleRowSelection({{ $page->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $page->title }}</p>
                                        <p class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($page->excerpt, 90) }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $page->slug }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-emerald-100 text-emerald-700' => $page->is_published,
                                            'bg-gray-200 text-gray-700' => ! $page->is_published,
                                        ])>
                                            {{ $page->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="openEdit({{ $page->id }})" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit" aria-label="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.halaman-statis.destroy', $page) }}" onsubmit="return confirm('Hapus halaman ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 p-2 text-rose-600 hover:bg-rose-50" title="Hapus" aria-label="Hapus">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada halaman statis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $pages->links() }}
                </div>
            </div>
        </div>

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="admin-drawer-overlay fixed -inset-2 bg-black/70 z-[12000]" @click="closeDrawer()"></div>
        <aside
            class="admin-drawer-panel fixed inset-y-0 right-0 h-[100dvh] min-h-[100dvh] w-full sm:max-w-2xl bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-[13000] shadow-2xl transform-gpu will-change-transform overflow-y-auto transition-transform duration-300 ease-out"
            :class="drawerOpen ? 'translate-x-0' : 'translate-x-full pointer-events-none'"
            x-cloak
        >
            <div class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="isEdit ? 'Edit Halaman' : 'Tambah Halaman'"></h3>
                    <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center" @click="closeDrawer()">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>

                <form method="POST" :action="actionUrl" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="edit_id" :value="editId ?? ''">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul</label>
                            <input type="text" name="title" x-model="form.title" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                            <input type="text" name="slug" x-model="form.slug" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Excerpt</label>
                        <textarea name="excerpt" rows="2" x-model="form.excerpt" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Konten</label>
                        <textarea name="content" rows="10" x-model="form.content" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="is_published" x-model="form.is_published" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    @if ($errors->any())
                        <ul class="text-xs text-rose-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="flex items-center gap-2 pt-1">
                        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                            <span x-text="isEdit ? 'Update' : 'Simpan'"></span>
                        </button>
                        <button type="button" @click="closeDrawer()" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </aside>
    </div>
@endsection









