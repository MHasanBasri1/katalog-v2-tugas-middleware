<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(): View
    {
        $favoriteProducts = Favorite::query()
            ->where('user_id', auth()->id())
            ->with([
                'product' => fn ($query) => $query
                    ->select('id', 'name', 'slug', 'price', 'original_price')
                    ->with('primaryImage:id,product_id,image'),
            ])
            ->latest('id')
            ->get()
            ->pluck('product')
            ->filter()
            ->unique('id')
            ->values();

        $vouchers = \App\Models\Voucher::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->latest('id')
            ->get();

        return view('user.panel', compact('favoriteProducts', 'vouchers'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('profileUpdate', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()
            ->with('status', 'Profil berhasil diperbarui.')
            ->with('active_tab', 'profil');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check((string) $request->input('current_password'), (string) $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'], 'passwordUpdate');
        }

        $user->update([
            'password' => Hash::make((string) $request->input('password')),
        ]);

        return back()
            ->with('status_password', 'Password berhasil diperbarui.')
            ->with('active_tab', 'profil');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('avatarUpdate', [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $newPath = $request->file('avatar')->store("avatars/users/{$user->id}", 'public');

        if ($user->hasUploadedAvatar()) {
            Storage::disk('public')->delete((string) $user->avatar);
        }

        $user->forceFill([
            'avatar' => $newPath,
        ])->save();

        return back()
            ->with('status_avatar', 'Avatar berhasil diperbarui.')
            ->with('active_tab', 'profil');
    }

    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasUploadedAvatar()) {
            Storage::disk('public')->delete((string) $user->avatar);
        }

        $user->forceFill([
            'avatar' => $user->google_avatar,
        ])->save();

        return back()
            ->with('status_avatar', 'Avatar berhasil dihapus.')
            ->with('active_tab', 'profil');
    }

    public function destroyFavorite(Request $request, $productId): RedirectResponse
    {
        Favorite::query()
            ->where('product_id', $productId)
            ->where('user_id', $request->user()->id)
            ->delete();

        return back()
            ->with('status_favorite', 'Favorit diperbarui.')
            ->with('active_tab', 'favorit');
    }
}
