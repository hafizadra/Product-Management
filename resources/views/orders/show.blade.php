<x-layout title="Order Detail">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h6 mb-0">Order #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to orders
        </a>
    </div>

    @php
        $currentStatusIndex = array_search($order->status, $timelineStatuses, true);
        $currentStatusIndex = $currentStatusIndex === false ? -1 : $currentStatusIndex;
    @endphp

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h2 class="h6 fw-semibold mb-3">Order status</h2>

            <div class="order-timeline d-flex align-items-center gap-3 flex-wrap">
                @foreach ($timelineStatuses as $index => $status)
                    @php
                        $isCompleted = $index <= $currentStatusIndex;
                        $isCurrent = $index === $currentStatusIndex;
                        $label = ucfirst($status);
                    @endphp

                    <div class="timeline-step d-flex align-items-center flex-grow-1">
                        <div class="timeline-dot {{ $isCompleted ? 'completed' : '' }} {{ $order->status === 'cancelled' && $isCurrent ? 'cancelled' : '' }}"></div>
                        <div class="ms-3">
                            <div class="fw-semibold small mb-0">{{ $label }}</div>
                            @if ($isCurrent)
                                <div class="text-muted small">Updated {{ $order->updated_at?->diffForHumans() }}</div>
                            @endif
                        </div>
                    </div>

                    @if ($index < count($timelineStatuses) - 1)
                        <div class="timeline-connector flex-grow-1 {{ $index < $currentStatusIndex ? 'completed' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
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

<style>
    .order-timeline {
        position: relative;
    }

    .timeline-step {
        min-width: 160px;
    }

    .timeline-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        background-color: #fff;
        flex-shrink: 0;
    }

    .timeline-dot.completed {
        border-color: #198754;
        background-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    .timeline-dot.cancelled {
        border-color: #dc3545;
        background-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }

    .timeline-connector {
        height: 2px;
        background-color: #dee2e6;
    }

    .timeline-connector.completed {
        background-color: #198754;
    }

    @media (max-width: 768px) {
        .order-timeline {
            flex-direction: column;
            align-items: flex-start;
        }

        .timeline-step {
            width: 100%;
        }

        .order-timeline .timeline-connector {
            flex: none;
            width: 2px;
            height: 32px;
            margin: 6px 0 6px 8px;
        }
    }
</style>
