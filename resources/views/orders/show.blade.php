<x-layout title="Order Detail">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h6 mb-0">Order #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to orders
        </a>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h2 class="h6 fw-semibold mb-3">Items</h2>

                    @foreach ($order->items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold">{{ $item->product->name }}</div>
                                <div class="small text-muted">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->qty }}
                                </div>
                            </div>
                            <div class="fw-semibold">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between fw-semibold">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h6 fw-semibold mb-3">Shipping & Payment</h2>

                    <div class="small text-muted mb-2">Shipping Address</div>
                    <div class="mb-3">{{ $order->shipping_address }}</div>

                    <div class="small text-muted mb-2">Payment Method</div>
                    <div class="mb-3">{{ strtoupper($order->payment_method) }}</div>

                    <div class="small text-muted mb-2">Status</div>
                    <div class="fw-semibold">{{ strtoupper($order->status) }}</div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
