<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;

class OrderController extends Controller
{
    /**
     * Status yang diizinkan untuk order.
     *
     * @var string[]
     */
    protected array $statuses = [
        'pending',
        'paid',
        'processing',
        'shipped',
        'completed',
        'cancelled',
    ];

    public function index(Request $request)
    {
        $selectedStatus = $request->query('status');

        $orders = Order::with('user')
            ->when($selectedStatus, function ($query) use ($selectedStatus) {
                $query->where('status', $selectedStatus);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'         => $orders,
            'statuses'       => $this->statuses,
            'selectedStatus' => $selectedStatus,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', [
            'order'    => $order,
            'statuses' => $this->statuses,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', $this->statuses)],
        ]);

        $order->update(['status' => $data['status']]);

        if ($order->user?->email) {
            Mail::to($order->user->email)->queue(new OrderStatusUpdatedMail($order));
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated.');
    }
}
