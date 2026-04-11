<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = Setting::query()->firstOrNew(['id' => 1]);
        
        $section = 'umum';
        if (request()->routeIs('admin.setting.branding')) $section = 'branding';
        if (request()->routeIs('admin.setting.marketplace')) $section = 'marketplace';
        if (request()->routeIs('admin.setting.seo')) $section = 'seo';

        return view('admin.settings.index', compact('setting', 'section'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data = $this->handleFiles($request, $data);
        
        Setting::query()->updateOrCreate(['id' => 1], $data);

        return back()->with('status', 'Setting berhasil disimpan.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $data = $this->handleFiles($request, $data, $setting);
        
        $setting->update($data);

        return back()->with('status', 'Setting berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'shop_description' => ['nullable', 'string'],
            'shop_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,ico,webp', 'max:1024'],
            'marketplaces' => ['nullable', 'array'],
        ]);
    }

    private function handleFiles(Request $request, array $data, ?Setting $setting = null): array
    {
        if ($request->hasFile('shop_logo')) {
            if ($setting && $setting->shop_logo && !str_starts_with($setting->shop_logo, 'http')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->shop_logo));
            }
            $path = $request->file('shop_logo')->store('settings', 'public');
            $data['shop_logo'] = '/storage/' . $path;
        }

        if ($request->hasFile('favicon')) {
            if ($setting && $setting->favicon && !str_starts_with($setting->favicon, 'http')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->favicon));
            }
            $path = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = '/storage/' . $path;
        }

        return $data;
    }
}
