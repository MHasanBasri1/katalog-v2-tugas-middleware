@extends('admin.layouts.app')

@section('title', 'Banner')
@section('header', 'Banner')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            init() {
                this.toggleBodyLock(this.drawerOpen);
                this.$watch('drawerOpen', (value) => this.toggleBodyLock(value));
            },
            drawerOpen: {{ ($editBanner || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editBanner ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editBanner->id ?? null)),
            bannersMap: @js(
                $banners->getCollection()->mapWithKeys(fn($item) => [
                    $item->id => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'subtitle' => $item->subtitle,
                        'image_url' => $item->image_url,
                        'cta_label' => $item->cta_label,
                        'cta_url' => $item->cta_url,
                        'sort_order' => (int) $item->sort_order,
                        'is_active' => (int) $item->is_active,
                    ],
                ])
            ),
            form: {
                title: @js(old('title', $editBanner->title ?? '')),
                subtitle: @js(old('subtitle', $editBanner->subtitle ?? '')),
                image_url: @js(old('image_url', $editBanner->image_url ?? '')),
                cta_label: @js(old('cta_label', $editBanner->cta_label ?? '')),
                cta_url: @js(old('cta_url', $editBanner->cta_url ?? '')),
                sort_order: @js(old('sort_order', $editBanner->sort_order ?? 0)),
                is_active: @js((string) old('is_active', isset($editBanner) ? (int) $editBanner->is_active : 1)),
            },
            currentPageIds: @js($banners->getCollection()->pluck('id')->values()),
            selectedIds: [],
            storeUrl: @js(route('admin.banner.store')),
            updateUrlTemplate: @js(route('admin.banner.update', ['banner' => '__ID__'])),
            bulkDeleteUrl: @js(route('admin.banner.bulk-destroy')),
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
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.form = {
                    title: '',
                    subtitle: '',
                    image_url: '',
                    cta_label: '',
                    cta_url: '',
                    sort_order: 0,
                    is_active: '1',
                };
            },
            openEdit(id) {
                const item = this.bannersMap[id];
                if (!item) return;
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = id;
                this.form = {
                    title: item.title ?? '',
                    subtitle: item.subtitle ?? '',
                    image_url: item.image_url ?? '',
                    cta_label: item.cta_label ?? '',
                    cta_url: item.cta_url ?? '',
                    sort_order: item.sort_order ?? 0,
                    is_active: String(item.is_active ?? 1),
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
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Banner</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola banner homepage.</p>
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
                                <th class="px-4 py-3 text-left">Banner</th>
                                <th class="px-4 py-3 text-center">Urutan</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($banners as $banner)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $banner->id }})" @change="toggleRowSelection({{ $banner->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $banner->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $banner->subtitle }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $banner->sort_order }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-emerald-100 text-emerald-700' => $banner->is_active,
                                            'bg-gray-200 text-gray-700' => ! $banner->is_active,
                                        ])>
                                            {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="openEdit({{ $banner->id }})" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit" aria-label="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.banner.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')">
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
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada banner.</td>
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

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="admin-drawer-overlay fixed -inset-2 bg-black/70 z-[12000]" @click="closeDrawer()"></div>
        <aside
            class="admin-drawer-panel fixed inset-y-0 right-0 h-[100dvh] min-h-[100dvh] w-full sm:max-w-xl bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-[13000] shadow-2xl transform-gpu will-change-transform overflow-y-auto transition-transform duration-300 ease-out"
            :class="drawerOpen ? 'translate-x-0' : 'translate-x-full pointer-events-none'"
            x-cloak
        >
            <div class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="isEdit ? 'Edit Banner' : 'Tambah Banner'"></h3>
                    <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center" @click="closeDrawer()">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>

                <form method="POST" :action="actionUrl" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="edit_id" :value="editId ?? ''">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Banner</label>
                            <input type="text" name="title" x-model="form.title" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
                            <input type="text" name="subtitle" x-model="form.subtitle" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Gambar Banner</label>
                        <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" :required="!isEdit" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG/WEBP, maksimal 3MB.</p>
                        <div x-show="form.image_url" x-cloak class="mt-2">
                            <img :src="form.image_url" alt="Preview Banner" class="w-full max-h-40 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">CTA Label</label>
                            <input type="text" name="cta_label" x-model="form.cta_label" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">CTA URL</label>
                            <input type="url" name="cta_url" x-model="form.cta_url" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                            <input type="number" min="0" name="sort_order" x-model="form.sort_order" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="is_active" x-model="form.is_active" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
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









