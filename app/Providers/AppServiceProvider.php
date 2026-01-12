<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layout', function ($view) {
            $latestOrderId = null;

            if (Auth::check() && Auth::user()->is_admin) {
                $latestOrderId = Order::latest()->value('id') ?? 0;
            }

            $view->with('adminLatestOrderId', $latestOrderId);
        });
    }
}
