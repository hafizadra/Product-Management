<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cart->load('items.product');

        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->qty);
        $total = $subtotal;

        return view('cart.index', compact('cart', 'subtotal', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $userId = Auth::id();

        if ($product->stock < 1) {
            return $this->respondCart(
                $request,
                false,
                sprintf('"%s" is currently out of stock.', $product->name)
            );
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $desiredQty = ($item?->qty ?? 0) + 1;

        if ($desiredQty > $product->stock) {
            return $this->respondCart(
                $request,
                false,
                'Stock is not sufficient for "' . $product->name . '".'
            );
        }

        if ($item) {
            $item->update(['qty' => $desiredQty]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'qty'        => 1,
                'price'      => $product->price,
            ]);
        }

        return $this->respondCart(
            $request,
            true,
            sprintf('"%s" added to cart.', $product->name)
        );
    }

    public function update(Request $request, CartItem $item)
    {
        $userId = Auth::id();

        abort_unless($item->cart->user_id === $userId, 403);

        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $item->loadMissing('product');

        if (!$item->product) {
            $item->delete();

            return redirect()
                ->route('cart.index')
                ->with('error', 'Produk tidak ditemukan dan telah dihapus dari cart.');
        }

        if ($item->product && $data['qty'] > $item->product->stock) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Stok untuk "' . $item->product->name . '" tidak mencukupi.');
        }

        $item->update(['qty' => $data['qty']]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $userId = Auth::id();

        abort_unless($item->cart->user_id === $userId, 403);

        $item->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed.');
    }

    protected function respondCart(Request $request, bool $success, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $message,
            ], $success ? 200 : 422);
        }

        $key = $success ? 'success' : 'error';
        $redirect = $success ? redirect()->route('cart.index') : redirect()->back();

        return $redirect->with($key, $message);
    }
}
