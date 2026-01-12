<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderNotificationController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePaymentController;
use App\Http\Controllers\ProfileAddressController;
use App\Http\Controllers\ProfileSecurityController;

/*
 Home
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

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
        Route::get('/show/{id}', 'show')->name('products.show');
    });

/*
Admin inventory
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('dashboard');

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/create', [InventoryController::class, 'create'])->name('create');
            Route::post('/', [InventoryController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [InventoryController::class, 'edit'])->name('edit');
            Route::put('/{product}', [InventoryController::class, 'update'])->name('update');
            Route::patch('/{product}/stock', [InventoryController::class, 'updateStock'])->name('stock');
            Route::delete('/{product}', [InventoryController::class, 'destroy'])->name('destroy');
        });

        Route::resource('categories', AdminCategoryController::class)->except(['show'])->names('categories');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::patch('/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('toggle-admin');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
            Route::patch('/{order}', [AdminOrderController::class, 'updateStatus'])->name('update');
        });

        Route::get('/notifications/latest-order', OrderNotificationController::class)->name('notifications.latest-order');
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

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('products.reviews.store');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        Route::get('/addresses', [ProfileAddressController::class, 'index'])->name('addresses.index');
        Route::post('/addresses', [ProfileAddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{address}', [ProfileAddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [ProfileAddressController::class, 'destroy'])->name('addresses.destroy');
        Route::patch('/addresses/{address}/default', [ProfileAddressController::class, 'makeDefault'])->name('addresses.default');

        Route::get('/security', [ProfileSecurityController::class, 'index'])->name('security.index');
        Route::put('/security', [ProfileSecurityController::class, 'update'])->name('security.update');

        Route::get('/payment', [ProfilePaymentController::class, 'edit'])->name('payment.edit');
        Route::put('/payment', [ProfilePaymentController::class, 'update'])->name('payment.update');
    });
});
