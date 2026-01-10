<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use Illuminate\Support\Str;

class CouponService
{
    public function validateForCart(string $code, User $user, Cart $cart): array
    {
        $code = Str::upper(trim($code));
        $coupon = Coupon::query()->where('code', $code)->first();

        if (!$coupon) {
            throw new \RuntimeException('Kupon tidak ditemukan.');
        }

        if (!$coupon->is_active) {
            throw new \RuntimeException('Kupon tidak aktif.');
        }

        $now = now();
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            throw new \RuntimeException('Kupon belum bisa digunakan.');
        }
        if ($coupon->ends_at && $coupon->ends_at->isPast()) {
            throw new \RuntimeException('Kupon sudah kadaluarsa.');
        }

        $totalUsage = CouponUsage::where('coupon_id', $coupon->id)->count();
        if ($coupon->usage_limit_total && $totalUsage >= $coupon->usage_limit_total) {
            throw new \RuntimeException('Kupon sudah mencapai batas pemakaian.');
        }

        $limitPerUser = $coupon->usage_limit_per_user ?? 1;
        if ($limitPerUser) {
            $userUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();
            if ($userUsage >= $limitPerUser) {
                throw new \RuntimeException('Anda sudah mencapai batas penggunaan kupon ini.');
            }
        }

        $cart->loadMissing('items.product');
        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Keranjang kosong.');
        }

        $cartSubtotal = $cart->items->sum(fn ($item) => $item->price * $item->qty);
        if ($coupon->min_subtotal && $cartSubtotal < $coupon->min_subtotal) {
            throw new \RuntimeException('Minimal belanja kupon ini adalah Rp ' . number_format($coupon->min_subtotal, 0, ',', '.'));
        }

        $eligibleSubtotal = $this->eligibleSubtotal($coupon, $cart);
        if ($eligibleSubtotal <= 0) {
            throw new \RuntimeException('Tidak ada produk yang memenuhi syarat kupon.');
        }

        $discount = $this->calculateDiscount($coupon, $eligibleSubtotal);
        if ($discount <= 0) {
            throw new \RuntimeException('Kupon tidak memberikan diskon untuk keranjang ini.');
        }

        return [
            'coupon' => $coupon,
            'code' => $coupon->code,
            'discount_amount' => $discount,
            'free_shipping' => (bool) $coupon->free_shipping,
        ];
    }

    protected function eligibleSubtotal(Coupon $coupon, Cart $cart): int
    {
        if ($coupon->scope_type === 'all') {
            return $cart->items->sum(fn ($item) => $item->price * $item->qty);
        }

        if ($coupon->scope_type === 'categories') {
            $categoryIds = $coupon->categories()->pluck('categories.id')->all();
            return $cart->items
                ->filter(fn ($item) => $item->product && in_array($item->product->category_id, $categoryIds))
                ->sum(fn ($item) => $item->price * $item->qty);
        }

        if ($coupon->scope_type === 'products') {
            $productIds = $coupon->products()->pluck('products.id')->all();
            return $cart->items
                ->filter(fn ($item) => in_array($item->product_id, $productIds))
                ->sum(fn ($item) => $item->price * $item->qty);
        }

        return 0;
    }

    protected function calculateDiscount(Coupon $coupon, int $eligibleSubtotal): int
    {
        if ($coupon->discount_type === 'percent') {
            $discount = (int) round($eligibleSubtotal * ($coupon->discount_value / 100));
            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
            return $discount;
        }

        return min($coupon->discount_value, $eligibleSubtotal);
    }
}
