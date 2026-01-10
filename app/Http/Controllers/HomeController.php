<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * HOME GUEST
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                $stats = [
                    'total_products' => Product::count(),
                    'total_orders'   => Order::count(),
                    'pending_orders' => Order::where('status', 'pending')->count(),
                    'low_stock'      => Product::where('stock', '<=', 5)->count(),
                    'revenue'        => Order::sum('total'),
                ];

                $recentOrders = Order::with('user')
                    ->latest()
                    ->take(5)
                    ->get();

                $lowStockProducts = Product::with('category')
                    ->where('stock', '<=', 5)
                    ->orderBy('stock')
                    ->take(5)
                    ->get();

                return view('admin.home', compact('stats', 'recentOrders', 'lowStockProducts'));
            }

            return $this->dashboard();
        }

        $latestProducts = Product::with('category')
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(4)
            ->get();

        return view('home', [
            'latestProducts' => $latestProducts,
            'recommendedProducts' => collect(),
        ]);
    }

    /**
     * HOME USER / DASHBOARD
     */
    public function dashboard()
    {
        if (Auth::user()?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

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
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(4)
            ->get();

        $wishlistQuery = Wishlist::where('user_id', $userId);
        $wishlistCount = (clone $wishlistQuery)->count();

        $recommendedIds = collect();

        $orderProductIds = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->pluck('product_id');

        $wishlistProductIds = (clone $wishlistQuery)->pluck('product_id');

        $recommendedIds = $recommendedIds
            ->merge($orderProductIds)
            ->merge($wishlistProductIds)
            ->unique()
            ->filter();

        $recommendedProducts = Product::with('category')
            ->whereIn('id', $recommendedIds)
            ->take(4)
            ->get();

        if ($recommendedProducts->isEmpty()) {
            $recommendedProducts = $latestProducts;
        }

        return view('home', [
            'latestProducts' => $latestProducts,
            'cartItems' => $cartItems,
            'ordersCount' => $ordersCount,
            'pendingOrders' => $pendingOrders,
            'wishlistCount' => $wishlistCount,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}
