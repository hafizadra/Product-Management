<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::withCount('usages')
            ->latest()
            ->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return $this->formView(new Coupon());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $coupon = Coupon::create($data);
        $this->syncRelations($coupon, $request);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon): View
    {
        return $this->formView($coupon);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $this->validateRequest($request, $coupon->id);
        $coupon->update($data);
        $this->syncRelations($coupon, $request);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon deleted.');
    }

    protected function validateRequest(Request $request, ?int $couponId = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . ($couponId ?? 'NULL')],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'min_subtotal' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'usage_limit_total' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'scope_type' => ['required', 'in:all,categories,products'],
            'category_ids' => ['array'],
            'category_ids.*' => ['exists:categories,id'],
            'product_ids' => ['array'],
            'product_ids.*' => ['exists:products,id'],
            'free_shipping' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($data['discount_type'] === 'percent' && empty($data['max_discount'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'max_discount' => 'Maksimal diskon wajib diisi untuk tipe persen.',
            ]);
        }

        $data['code'] = strtoupper($data['code']);
        $data['free_shipping'] = $request->boolean('free_shipping');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function syncRelations(Coupon $coupon, Request $request): void
    {
        if ($coupon->scope_type === 'categories') {
            $coupon->categories()->sync($request->input('category_ids', []));
            $coupon->products()->detach();
        } elseif ($coupon->scope_type === 'products') {
            $coupon->products()->sync($request->input('product_ids', []));
            $coupon->categories()->detach();
        } else {
            $coupon->categories()->detach();
            $coupon->products()->detach();
        }
    }

    protected function formView(Coupon $coupon): View
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.coupons.form', [
            'coupon' => $coupon,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
