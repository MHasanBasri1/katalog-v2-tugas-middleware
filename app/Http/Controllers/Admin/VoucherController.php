<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = Voucher::query()
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where('code', 'like', '%' . $request->q . '%')
                    ->orWhere('name', 'like', '%' . $request->q . '%')
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create(): View
    {
        return view('admin.vouchers.create', [
            'voucher' => new Voucher(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        Voucher::query()->create($data);

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.voucher.create')->with('status', 'Voucher berhasil ditambahkan. Silahkan tambah voucher lainnya.');
        }

        return redirect()->route('admin.voucher.index')->with('status', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher): View
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher): RedirectResponse
    {
        $data = $this->validatePayload($request, $voucher);
        $voucher->update($data);

        return redirect()->route('admin.voucher.index')->with('status', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        $voucher->delete();
        return redirect()->route('admin.voucher.index')->with('status', 'Voucher berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:vouchers,id'],
        ]);

        $deleted = Voucher::query()->whereIn('id', $validated['selected_ids'])->delete();

        return redirect()->route('admin.voucher.index')->with('status', "{$deleted} voucher berhasil dihapus.");
    }

    private function validatePayload(Request $request, ?Voucher $voucher = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vouchers')->ignore($voucher?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['required', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
