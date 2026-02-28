<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(): View
    {
        $wishlistProducts = Wishlist::query()
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

        return view('user.panel', compact('wishlistProducts'));
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

    public function destroyWishlist(Request $request, Product $product): RedirectResponse
    {
        Wishlist::query()
            ->where('product_id', $product->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return back()
            ->with('status_wishlist', 'Wishlist diperbarui.')
            ->with('active_tab', 'wishlist');
    }
}
