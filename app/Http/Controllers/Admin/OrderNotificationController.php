<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $lastSeenId = (int) $request->query('last_id', 0);

        $latestOrder = Order::with('user')
            ->latest()
            ->first();

        if (!$latestOrder || $latestOrder->id <= $lastSeenId) {
            return response()->json([
                'has_new' => false,
                'last_id' => $latestOrder?->id ?? $lastSeenId,
            ]);
        }

        return response()->json([
            'has_new' => true,
            'last_id' => $latestOrder->id,
            'order' => [
                'id' => $latestOrder->id,
                'customer' => $latestOrder->user->name ?? 'Unknown customer',
                'total' => number_format($latestOrder->total, 0, ',', '.'),
                'status' => $latestOrder->status,
                'created_at_human' => $latestOrder->created_at?->diffForHumans(),
                'created_at' => $latestOrder->created_at?->format('d M Y H:i'),
            ],
        ]);
    }
}
