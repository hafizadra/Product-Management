<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    public function store(Request $request, Product $product)
    {
        $request->user()->wishlist()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Added to wishlist']);
        }

        return back()->with('success', 'Product added to wishlist.');
    }

    public function destroy(Request $request, Product $product)
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Removed from wishlist']);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }
}
