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
Route::get('/', [HomeController::class, 'index'])->name('home');

// HOME USER (dashboard)
Route::get('/home', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

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
| Auth (Bootstrap UI)
*/
Auth::routes();

/*
| Dashboard (route default dari Auth UI)
*/
Route::get('/home', [HomeController::class, 'index'])->name('dashboard');

/*
| Cart + Checkout + Orders wajib login
*/
Route::middleware('auth')->group(function () {
    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // ORDERS
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
