<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $banners = Banner::query()
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('subtitle', 'like', '%' . $request->q . '%')
            )
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data['image_url'] = $this->storeImage($request, 'image_file', 'banners');
        Banner::query()->create($data);

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.banner.create')->with('status', 'Banner berhasil ditambahkan. Silahkan tambah banner lainnya.');
        }

        return redirect()->route('admin.banner.index')->with('status', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', ['banner' => $banner]);
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $this->validatePayload($request);
        if ($request->hasFile('image_file')) {
            $this->deleteStoredImage($banner->image_url);
            $data['image_url'] = $this->storeImage($request, 'image_file', 'banners');
        }
        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('status', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deleteStoredImage($banner->image_url);
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('status', 'Banner berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:banners,id'],
        ]);

        $banners = Banner::query()
            ->whereIn('id', $validated['selected_ids'])
            ->get();

        $deleted = 0;
        foreach ($banners as $banner) {
            $this->deleteStoredImage($banner->image_url);
            $banner->delete();
            $deleted++;
        }

        return redirect()->route('admin.banner.index')->with('status', "{$deleted} banner berhasil dihapus.");
    }

    private function validatePayload(Request $request): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'cta_label' => ['nullable', 'string', 'max:60'],
            'cta_url' => ['nullable', 'url', 'max:2048'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];

        $isUpdate = $request->route('banner') !== null;
        $rules['image_file'] = [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'];

        return $request->validate($rules);
    }

    private function storeImage(Request $request, string $field, string $directory): string
    {
        $path = $request->file($field)->store($directory, 'public');

        return Storage::url($path);
    }

    private function deleteStoredImage(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        $path = ltrim(str_replace('/storage/', '', $url), '/');
        if ($path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
