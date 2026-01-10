<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $stats = [
            'total_products' => Product::count(),
            'low_stock'      => Product::where('stock', '<=', 5)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue'  => Order::sum('total'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.products.index', compact('products', 'stats', 'recentOrders'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        Product::create($this->validatedData($request));

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validatedData($request, $product));

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Stock updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    protected function validatedData(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'max:512000'],
            'author'      => ['nullable', 'string', 'max:255'],
            'publisher'   => ['nullable', 'string', 'max:255'],
            'isbn'        => ['nullable', 'string', 'max:50'],
        ]);

        if ($request->hasFile('image')) {
            if ($product?->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $request->file('image')->store('product-images', 'public');
        }

        unset($data['image']);

        return $data;
    }
}
