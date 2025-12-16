<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * INDEX — sort
     */
    public function index(Request $request)
    {
        
        $search   = $request->query('q');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sortBy   = $request->query('sort_by', 'name');   
        $sortDir  = $request->query('sort_dir', 'asc');  

        
        $query = Product::query()->with('category'); 

       
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

      
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (int) $minPrice);
        }

        
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (int) $maxPrice);
        }

        
        if (! in_array($sortBy, ['name', 'price'], true)) {
            $sortBy = 'name';
        }

        if (! in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'asc';
        }

       
        $products = $query->orderBy($sortBy, $sortDir)->get();

        
        $categories = Category::all();

        
        return view('products.list', compact(
            'products',
            'categories',
            'search',
            'minPrice',
            'maxPrice',
            'sortBy',
            'sortDir'
        ));
    }

    /**
     * CREATE 
     */
    public function create()
    {
        $categories = Category::all();

       
        return view('products.form', [
            'categories' => $categories,
        ]);
    }

    /**
     * STORE 
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        
        Product::create($validated);

        return redirect()
            ->route('products')
            ->with('success', 'Product created successfully.');
    }

    /**
     * SHOW 
     */
    public function show(int $id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('products.show', compact('product'));
    }

    /**
     * EDIT 
     */
    public function edit(int $id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.form', [
            'product'    => $product,    
            'categories' => $categories,
        ]);
    }

    /**
     * UPDATE 
     */
    public function update(Request $request, int $id)
    {
        
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $product = Product::findOrFail($id);

        
        $product->update($validated);

        return redirect()
            ->route('products')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * DELETE 
     */
    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('products')
            ->with('success', 'Product deleted successfully.');
    }
}
