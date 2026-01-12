<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;

class ProductController extends Controller
{
    /**
     * INDEX SORT FILTER
     */
    public function index(Request $request)
    {
        $search     = $request->query('q');
        $minPrice   = $request->query('min_price');
        $maxPrice   = $request->query('max_price');
        $categoryId = $request->query('category_id');
        $author     = $request->query('author');
        $publisher  = $request->query('publisher');
        $isbn       = $request->query('isbn');
        $sortBy     = $request->query('sort_by', 'name');
        $sortDir    = $request->query('sort_dir', 'asc');

        $query = Product::query()
            ->with('category')
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews');

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter min price
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (int) $minPrice);
        }

        // Filter max price
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (int) $maxPrice);
        }

        // ✅ Filter category
        if ($categoryId !== null && $categoryId !== '') {
            $query->where('category_id', (int) $categoryId);
        }

        if (!empty($author)) {
            $query->where('author', 'like', '%' . $author . '%');
        }

        if (!empty($publisher)) {
            $query->where('publisher', 'like', '%' . $publisher . '%');
        }

        if (!empty($isbn)) {
            $query->where('isbn', 'like', '%' . $isbn . '%');
        }

        // Sort allowlist
        if (!in_array($sortBy, ['name', 'price'], true)) {
            $sortBy = 'name';
        }

        if (!in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'asc';
        }

        $products = $query->orderBy($sortBy, $sortDir)->get();

        $categories = Category::orderBy('name')->get();

        return view('products.list', compact(
            'products',
            'categories',
            'search',
            'minPrice',
            'maxPrice',
            'categoryId', 
            'author',
            'publisher',
            'isbn',
            'sortBy',
            'sortDir'
        ));
    }

    /**
     * SHOW
     */
    public function show(int $id)
    {
        $product = Product::with(['category', 'reviews.user'])
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $reviews = $product->reviews
            ->sortByDesc('created_at');

        $userReview = null;
        $canReview = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $userReview = $reviews->firstWhere('user_id', $userId);

            $eligibleStatuses = ['completed'];
            $canReview = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($query) use ($userId, $eligibleStatuses) {
                    $query->where('user_id', $userId)
                        ->whereIn('status', $eligibleStatuses);
                })
                ->exists();
        }

        return view('products.show', compact('product', 'reviews', 'userReview', 'canReview'));
    }

}
