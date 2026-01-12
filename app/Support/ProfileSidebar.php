<?php

namespace App\Support;

use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;

class ProfileSidebar
{
    public static function data(User $user): array
    {
        $ordersQuery = Order::where('user_id', $user->id);

        $ordersCount = (clone $ordersQuery)->count();
        $completedCount = (clone $ordersQuery)->where('status', 'completed')->count();
        $pendingCount = (clone $ordersQuery)->where('status', 'pending')->count();
        $totalSpent = (clone $ordersQuery)->sum('total');
        $pendingAmount = (clone $ordersQuery)->where('status', 'pending')->sum('total');
        $averageOrder = $ordersCount > 0 ? (int) round($totalSpent / max($ordersCount, 1)) : 0;
        $lastOrder = (clone $ordersQuery)->latest()->first();

        $wishlistQuery = Wishlist::where('user_id', $user->id);
        $wishlistCount = (clone $wishlistQuery)->count();
        $wishlistItems = (clone $wishlistQuery)
            ->with('product')
            ->latest()
            ->take(3)
            ->get();

        return [
            'ordersCount'   => $ordersCount,
            'completedCount'=> $completedCount,
            'pendingCount'  => $pendingCount,
            'totalSpent'    => $totalSpent,
            'pendingAmount' => $pendingAmount,
            'averageOrder'  => $averageOrder,
            'lastOrder'     => $lastOrder,
            'wishlistCount' => $wishlistCount,
            'wishlistItems' => $wishlistItems,
        ];
    }
}
