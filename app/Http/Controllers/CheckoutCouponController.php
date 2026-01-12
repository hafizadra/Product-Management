<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutCouponController extends Controller
{
    public function __construct(private CouponService $couponService)
    {
        $this->middleware('auth');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['coupon_code' => ['required', 'string']]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('checkout.create')->with('error', 'Keranjang kosong.');
        }

        try {
            $result = $this->couponService->validateForCart($request->coupon_code, Auth::user(), $cart);
            session(['checkout_coupon' => [
                'coupon_id' => $result['coupon']->id,
                'code' => $result['code'],
                'discount_amount' => $result['discount_amount'],
                'free_shipping' => $result['free_shipping'],
            ]]);
            return redirect()->route('checkout.create')->with('success', 'Kupon berhasil diterapkan.');
        } catch (\Throwable $e) {
            session()->forget('checkout_coupon');
            return redirect()->route('checkout.create')->with('error', $e->getMessage());
        }
    }

    public function destroy(): RedirectResponse
    {
        session()->forget('checkout_coupon');
        return redirect()->route('checkout.create')->with('success', 'Kupon dihapus.');
    }
}
