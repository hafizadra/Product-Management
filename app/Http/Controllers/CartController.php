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

    public function add(Product $product)
    {
        $userId = Auth::id();

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('qty');
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'qty'        => 1,
                'price'      => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $userId = Auth::id();

        abort_unless($item->cart->user_id === $userId, 403);

        $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $item->update(['qty' => $request->qty]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $userId = Auth::id();

        abort_unless($item->cart->user_id === $userId, 403);

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }
}
