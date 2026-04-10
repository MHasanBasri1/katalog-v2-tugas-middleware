<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceLink;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarketplaceLinkController extends Controller
{
    public function index(Request $request): View
    {
        $links = MarketplaceLink::query()
            ->with('product:id,name')
            ->when(
                $request->filled('q'),
                fn ($query) => $query->whereHas('product', fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
                    ->orWhere('marketplace', 'like', '%' . $request->q . '%')
                    ->orWhere('url', 'like', '%' . $request->q . '%')
            )
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $editLink = null;
        if ($request->filled('edit')) {
            $editLink = MarketplaceLink::query()->find($request->integer('edit'));
        }

        return view('admin.marketplace-links.index', [
            'links' => $links,
            'editLink' => $editLink,
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'marketplaceOptions' => ['Shopee', 'Tokopedia', 'Lazada', 'Blibli'],
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.marketplace-link.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'marketplace' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2000'],
        ]);

        $isUnique = MarketplaceLink::query()
            ->where('product_id', $data['product_id'])
            ->where('marketplace', $data['marketplace'])
            ->doesntExist();

        if (! $isUnique) {
            return back()->withInput()->with('error', 'Marketplace untuk produk ini sudah ada.');
        }

        MarketplaceLink::query()->create($data);

        return redirect()->route('admin.marketplace-link.index')->with('status', 'Marketplace link berhasil ditambahkan.');
    }

    public function show(MarketplaceLink $marketplaceLink): RedirectResponse
    {
        return redirect()->route('admin.marketplace-link.edit', $marketplaceLink);
    }

    public function edit(MarketplaceLink $marketplaceLink): RedirectResponse
    {
        return redirect()->route('admin.marketplace-link.index', ['edit' => $marketplaceLink->id]);
    }

    public function update(Request $request, MarketplaceLink $marketplaceLink): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'marketplace' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2000'],
        ]);

        $isUnique = MarketplaceLink::query()
            ->where('product_id', $data['product_id'])
            ->where('marketplace', $data['marketplace'])
            ->where('id', '!=', $marketplaceLink->id)
            ->doesntExist();

        if (! $isUnique) {
            return back()->withInput()->with('error', 'Marketplace untuk produk ini sudah ada.');
        }

        $marketplaceLink->update($data);

        return redirect()->route('admin.marketplace-link.index')->with('status', 'Marketplace link berhasil diperbarui.');
    }

    public function destroy(MarketplaceLink $marketplaceLink): RedirectResponse
    {
        $marketplaceLink->delete();

        return redirect()->route('admin.marketplace-link.index')->with('status', 'Marketplace link berhasil dihapus.');
    }
}
