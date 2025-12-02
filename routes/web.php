<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Category;


// Home Route
Route::get('/', function () {

    $totalProducts   = Product::count();
    $totalCategories = Category::count();


    $latestProducts = Product::with('category')
        ->latest()
        ->take(4)
        ->get();

    
    return view('home', [
        'totalProducts'   => $totalProducts,
        'totalCategories' => $totalCategories,
        'latestProducts'  => $latestProducts,
    ]);
})->name('home');

// Product Routes
Route::controller(ProductController::class)
    ->prefix('products')
    ->group(function () {
        Route::get('/', 'index')->name('products');
        Route::get('/create', 'create')->name('products.create');
        Route::get('/show/{id}', 'show')->name('products.show');
        Route::get('/edit/{id}', 'edit')->name('products.edit');
        Route::post('/store', 'store')->name('products.store');
        Route::post('/update/{id}', 'update')->name('products.update');
        Route::post('/delete/{id}', 'destroy')->name('products.destroy');

    });
