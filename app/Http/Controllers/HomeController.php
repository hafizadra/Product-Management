<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * HOME GUEST
     */
    public function index()
    {
        $latestProducts = Product::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('latestProducts'));
    }

    /**
     * HOME USER / DASHBOARD
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // CART
        $cart = Cart::where('user_id', $userId)
            ->with('items')
            ->first();

        $cartItems = $cart?->items->sum('qty') ?? 0;

        // ORDERS
        $ordersCount = Order::where('user_id', $userId)->count();

        $pendingOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // PRODUCTS (tetap tampil)
        $latestProducts = Product::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact(
            'latestProducts',
            'cartItems',
            'ordersCount',
            'pendingOrders'
        ));
    }
}
