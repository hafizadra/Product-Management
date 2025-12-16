<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * HOME guest
     * URL: /
     */
    public function index()
    {
        
        $latestProducts = Product::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('home', [
            'latestProducts' => $latestProducts,
        ]);
    }

    /**
     * HOME USER 
     *
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // User stats
        $cart = Cart::where('user_id', $userId)->with('items')->first();
        $cartItems = $cart?->items->sum('qty') ?? 0;

        $ordersCount = Order::where('user_id', $userId)->count();
        $pendingOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        //  tampilkan produk terbaru
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
