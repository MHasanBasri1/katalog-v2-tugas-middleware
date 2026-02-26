<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = Setting::query()->firstOrNew(['id' => 1]);

        return view('admin.settings.index', compact('setting'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        Setting::query()->updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.setting.index')->with('status', 'Setting berhasil disimpan.');
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
        $setting->update($data);

        return redirect()->route('admin.setting.index')->with('status', 'Setting berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_logo' => ['nullable', 'string', 'max:2048'],
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
            'favicon' => ['nullable', 'string', 'max:2048'],
        ]);
    }
}
