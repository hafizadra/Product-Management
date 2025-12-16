<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
 Home
*/
Route::get('/', function () {
    if (request()->user()) {
        return redirect()->route('dashboard'); 
    }

    return app(HomeController::class)->index(); 
})->name('home');

/*
Dashboard (User Home)
*/
Route::get('/home', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

/*
 Auth (Bootstrap UI)
*/
Auth::routes();

/*
Products
*/
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

/*
Cart + Checkout + Orders (auth)
*/
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
