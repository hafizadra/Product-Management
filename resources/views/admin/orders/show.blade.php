<x-layout :title="'Order #' . $order->id">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h5 mb-1">Order #{{ $order->id }}</h1>
            <p class="text-muted small mb-0">
                Dipesan pada {{ $order->created_at?->format('d M Y H:i') }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                ← Back to orders
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3">Customer</h6>
                    <p class="mb-0 fw-semibold">{{ $order->user->name ?? 'Unknown' }}</p>
                    <p class="text-muted small mb-0">{{ $order->user->email ?? '-' }}</p>

                    <hr>

                    <h6 class="text-uppercase text-muted small mb-3">Shipping address</h6>
                    <p class="mb-0">{{ $order->shipping_address }}</p>

                    <hr>

                    <h6 class="text-uppercase text-muted small mb-3">Status</h6>
                    <form
                        action="{{ route('admin.orders.update', $order) }}"
                        method="POST"
                        class="vstack gap-2"
                    >
                        @csrf
                        @method('PATCH')

                        <select name="status" class="form-select form-select-sm">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-sm btn-primary" type="submit">
                            Update status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Subtotal</div>
                            <div class="fw-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Total</div>
                            <div class="h5 mb-0">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Items</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th>Price</th>
                                <th class="text-end">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        <small class="text-muted">
                                            #{{ $item->product_id }}
                                            {{ $item->product?->name ? '(' . $item->product->name . ')' : '' }}
                                        </small>
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>
