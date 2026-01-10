<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Cart;
use App\Models\Order;
use App\Mail\OrderCreatedMail;

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
        $defaultShipping = Auth::user()?->default_shipping_address;

        return view('checkout.create', compact('cart', 'subtotal', 'total', 'defaultShipping'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        if (!$user || !$user->default_shipping_address) {
            return redirect()
                ->route('profile.edit')
                ->with('error', 'Lengkapi alamat pengiriman di halaman profil sebelum melakukan checkout.');
        }

        $validated = $request->validate([
            'payment_method'   => ['required', 'string', 'max:50'],
        ]);
        $shippingAddress = $user->default_shipping_address;

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('success', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            $product = $item->product;

            if (!$product) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Ada produk yang sudah tidak tersedia di katalog.');
            }

            if ($item->qty > $product->stock) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Stok untuk "' . $product->name . '" tidak mencukupi.');
            }
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->qty);
        $total    = $subtotal;

        $order = null;

        DB::transaction(function () use (&$order, $userId, $validated, $cart, $subtotal, $total, $shippingAddress) {
            // 1) Create order
            $order = Order::create([
                'user_id'          => $userId,
                'shipping_address' => $shippingAddress,
                'payment_method'   => $validated['payment_method'],
                'subtotal'         => $subtotal,
                'total'            => $total,
                'status'           => 'pending',
            ]);

            // 2) Create order_items 
            foreach ($cart->items as $item) {
                $product = $item->product;
                $productName = $product?->name ?? 'Unknown Product';

                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'product_name' => $productName,
                    'qty'          => $item->qty,
                    'price'        => $item->price,
                    'line_total'   => $item->price * $item->qty,
                ]);

                if ($product) {
                    $product->decrement('stock', $item->qty);
                }
            }

            // 3) Clear cart
            $cart->items()->delete();
        });

        if ($order && $order->user?->email) {
            Mail::to($order->user->email)->queue(new OrderCreatedMail($order));
        }

        return redirect()->route('orders.index')
            ->with('success', 'Checkout successful! Your order has been created.');
    }
}
