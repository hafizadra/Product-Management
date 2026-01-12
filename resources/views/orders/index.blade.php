@push('styles')
    @once
        <style>
            .orders-shell {
                background: #f8f9fa;
                border-radius: 1.5rem;
                padding: 1.5rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
            }
            .orders-shell .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .orders-shell .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.12);
            }
        </style>
    @endonce
@endpush

<x-layout title="My Orders">

    <div class="orders-shell mb-4">
        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-4">
            <div>
                <p class="text-muted small mb-1">Order history</p>
                <h1 class="h6 mb-0">My Orders</h1>
            </div>
            <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Back to Products</a>
        </div>

        @if($orders->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <p class="mb-2">You have no orders yet.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4">Browse products</a>
                </div>
            </div>
        @else
            <div class="row g-3">
                @foreach($orders as $order)
                    <div class="col-md-6 col-lg-4">
                        <a
                            href="{{ route('orders.show', $order) }}"
                            class="text-decoration-none text-body d-block h-100"
                        >
                            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-semibold">Order #{{ $order->id }}</div>
                                        <span class="badge bg-light text-muted border text-capitalize">
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    <div class="small text-muted mt-2">
                                        Payment: {{ $order->payment_method }}
                                    </div>

                                    <div class="mt-2 fw-semibold">
                                        Total: Rp {{ number_format($order->total) }}
                                    </div>

                                    <div class="small text-muted mt-2">
                                        {{ $order->created_at?->format('Y-m-d H:i') }}
                                    </div>

                                    <div class="text-primary small fw-semibold mt-3">
                                        View detail →
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-layout>

<style>
    .hover-card {
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.15);
    }
</style>
