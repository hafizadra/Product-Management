<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $eligibleStatuses = ['completed'];

        $orderItem = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($user, $eligibleStatuses) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', $eligibleStatuses);
            })
            ->latest()
            ->first();

        if (! $orderItem) {
            return back()->withErrors([
                'rating' => 'Anda hanya dapat memberi ulasan setelah pesanan diterima (status completed).',
            ]);
        }

        Review::updateOrCreate(
            [
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ],
            [
                'order_item_id' => $orderItem->id,
                'rating'        => $data['rating'],
                'comment'       => $data['comment'] ?? null,
            ]
        );

        return back()->with('status', 'Terima kasih! Ulasan berhasil disimpan.');
    }
}
