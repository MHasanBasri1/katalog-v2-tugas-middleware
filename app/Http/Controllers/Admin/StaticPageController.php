<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = StaticPage::query()
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('slug', 'like', '%' . $request->q . '%')
            )
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.static-pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.static-pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);

        StaticPage::query()->create($data);

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.halaman-statis.create')->with('status', 'Halaman statis berhasil ditambahkan. Silahkan buat halaman lainnya.');
        }

        return redirect()->route('admin.halaman-statis.index')->with('status', 'Halaman statis berhasil ditambahkan.');
    }

    public function edit(StaticPage $halaman_stati): View
    {
        return view('admin.static-pages.edit', ['page' => $halaman_stati]);
    }

    public function update(Request $request, StaticPage $halaman_stati): RedirectResponse
    {
        $data = $this->validatePayload($request, $halaman_stati->id);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title'], $halaman_stati->id);

        $halaman_stati->update($data);

        return redirect()->route('admin.halaman-statis.index')->with('status', 'Halaman statis berhasil diperbarui.');
    }

    public function destroy(StaticPage $halaman_stati): RedirectResponse
    {
        $halaman_stati->delete();

        return redirect()->route('admin.halaman-statis.index')->with('status', 'Halaman statis berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:static_pages,id'],
        ]);

        $deleted = StaticPage::query()
            ->whereIn('id', $validated['selected_ids'])
            ->delete();

        return redirect()->route('admin.halaman-statis.index')->with('status', "{$deleted} halaman statis berhasil dihapus.");
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('static_pages', 'slug')->ignore($ignoreId)],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'halaman';
        $slug = $base;
        $counter = 1;

        while (
            StaticPage::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
