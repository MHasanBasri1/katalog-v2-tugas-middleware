<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = Setting::query()->firstOrNew(['id' => 1]);
        
        $section = 'umum';
        if (request()->routeIs('admin.setting.branding')) $section = 'branding';
        if (request()->routeIs('admin.setting.marketplace')) $section = 'marketplace';
        if (request()->routeIs('admin.setting.navigasi')) $section = 'navigasi';
        if (request()->routeIs('admin.setting.seo')) $section = 'seo';
        if (request()->routeIs('admin.setting.sistem')) $section = 'sistem';

        return view('admin.settings.index', compact('setting', 'section'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        if (isset($data['social_media'])) {
            $data['social_media'] = array_values(array_filter($data['social_media'], function ($item) {
                return !empty($item['username']);
            }));
        }

        if (isset($data['marketplaces'])) {
            $data['marketplaces'] = array_filter($data['marketplaces'], function ($url) {
                return !empty($url);
            });
        }

        if (isset($data['header_navigation'])) {
            $data['header_navigation'] = array_values(array_filter($data['header_navigation'], function ($item) {
                return !empty($item['label']);
            }));
        }

        if (isset($data['footer_navigation'])) {
            $data['footer_navigation'] = array_values(array_filter($data['footer_navigation'], function ($item) {
                return !empty($item['label']);
            }));
        }

        if (isset($data['trending_keywords'])) {
            $data['trending_keywords'] = array_values(array_filter($data['trending_keywords'], function ($item) {
                return !empty($item['keyword']);
            }));
        }

        $data = $this->handleFiles($request, $data);
        
        Setting::query()->updateOrCreate(['id' => 1], $data);

        Cache::forget('public.footer.setting');
        Cache::forget('public.whatsapp_setting');
        Cache::forget('global.settings');

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

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::query()->firstOrNew(['id' => 1]);
        $data = $this->validatePayload($request);
        
        if (isset($data['social_media'])) {
            $data['social_media'] = array_values(array_filter($data['social_media'], function ($item) {
                return !empty($item['username']);
            }));
        }

        if (isset($data['marketplaces'])) {
            $data['marketplaces'] = array_filter($data['marketplaces'], function ($url) {
                return !empty($url);
            });
        }

        if (isset($data['header_navigation'])) {
            $data['header_navigation'] = array_values(array_filter($data['header_navigation'], function ($item) {
                return !empty($item['label']);
            }));
        }

        if (isset($data['footer_navigation'])) {
            $data['footer_navigation'] = array_values(array_filter($data['footer_navigation'], function ($item) {
                return !empty($item['label']);
            }));
        }

        if (isset($data['trending_keywords'])) {
            $data['trending_keywords'] = array_values(array_filter($data['trending_keywords'], function ($item) {
                return !empty($item['keyword']);
            }));
        }

        $data = $this->handleFiles($request, $data, $setting);
        
        $setting->fill($data);
        $setting->save();

        Cache::forget('public.footer.setting');
        Cache::forget('public.whatsapp_setting');
        Cache::forget('global.settings');

        return back()->with('status', 'Setting berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('admin.setting.index');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'shop_name' => ['sometimes', 'required', 'string', 'max:255'],
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
            'social_media' => ['nullable', 'array'],
            'social_media.*.platform' => ['nullable', 'string'],
            'social_media.*.username' => ['nullable', 'string'],
            'header_navigation' => ['nullable', 'array'],
            'header_navigation.*.label' => ['nullable', 'string', 'max:50'],
            'header_navigation.*.url' => ['nullable', 'string', 'max:255'],
            'footer_navigation' => ['nullable', 'array'],
            'footer_navigation.*.label' => ['nullable', 'string', 'max:50'],
            'footer_navigation.*.url' => ['nullable', 'string', 'max:255'],
            'trending_keywords' => ['nullable', 'array'],
            'trending_keywords.*.keyword' => ['nullable', 'string', 'max:50'],
            'trending_keywords.*.url' => ['nullable', 'string', 'max:255'],
            'seo_settings' => ['nullable', 'array'],
            'seo_settings.seo_title' => ['nullable', 'string', 'max:255'],
            'seo_settings.seo_keywords' => ['nullable', 'string', 'max:500'],
            'seo_settings.seo_description' => ['nullable', 'string', 'max:500'],
            'seo_settings.og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'seo_settings.twitter_card' => ['nullable', 'string', 'max:50'],
            'seo_settings.google_verification' => ['nullable', 'string', 'max:255'],
            'seo_settings.bing_verification' => ['nullable', 'string', 'max:255'],
            'seo_settings.yandex_verification' => ['nullable', 'string', 'max:255'],
            'seo_settings.robots' => ['nullable', 'string', 'max:50'],
            'seo_settings.author' => ['nullable', 'string', 'max:100'],
            'is_maintenance' => ['nullable', 'boolean'],
            'system_settings' => ['nullable', 'array'],
            'system_settings.maintenance_message' => ['nullable', 'string', 'max:500'],
            'system_settings.google_analytics_id' => ['nullable', 'string', 'max:50'],
            'system_settings.facebook_pixel_id' => ['nullable', 'string', 'max:50'],
            'system_settings.announcement_enabled' => ['nullable', 'boolean'],
            'system_settings.announcement_text' => ['nullable', 'string', 'max:255'],
            'system_settings.announcement_url' => ['nullable', 'string', 'max:255'],
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

        if ($request->hasFile('seo_settings.og_image')) {
            $seo = $data['seo_settings'] ?? [];
            if ($setting && isset($setting->seo_settings['og_image']) && !str_starts_with($setting->seo_settings['og_image'], 'http')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->seo_settings['og_image']));
            }
            $path = $request->file('seo_settings.og_image')->store('settings', 'public');
            $seo['og_image'] = '/storage/' . $path;
            $data['seo_settings'] = $seo;
        }

        return $data;
    }
}
