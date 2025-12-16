<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Cart;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function create()
    {
        $userId = Auth::id();

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('success', 'Your cart is empty.');
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->qty);
        $total    = $subtotal;

        return view('checkout.create', compact('cart', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method'   => ['required', 'string', 'max:50'],
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('success', 'Your cart is empty.');
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->qty);
        $total    = $subtotal;

        DB::transaction(function () use ($userId, $validated, $cart, $subtotal, $total) {
            // 1) Create order
            $order = Order::create([
                'user_id'          => $userId,
                'shipping_address' => $validated['shipping_address'],
                'payment_method'   => $validated['payment_method'],
                'subtotal'         => $subtotal,
                'total'            => $total,
                'status'           => 'pending',
            ]);

            // 2) Create order_items 
            foreach ($cart->items as $item) {
                $productName = $item->product?->name ?? 'Unknown Product';

                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'product_name' => $productName,
                    'qty'          => $item->qty,
                    'price'        => $item->price,
                    'line_total'   => $item->price * $item->qty,
                ]);
            }

            // 3) Clear cart
            $cart->items()->delete();
        });

        return redirect()->route('orders.index')
            ->with('success', 'Checkout successful! Your order has been created.');
    }
}
