@push('styles')
    @once
        <style>
            .cart-shell {
                background: #f8f9fa;
                border-radius: 1.5rem;
                padding: 1.5rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
            }
            .cart-shell .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .cart-shell .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.12);
            }
        </style>
    @endonce
@endpush

<x-layout title="Your Cart">

    <div class="cart-shell mb-4">
        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-4">
            <div>
                <h1 class="h5 fw-semibold mb-0">Shopping Cart</h1>
                <p class="text-muted small mb-0">Review items before checkout.</p>
            </div>
            <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                ← Continue shopping
            </a>
        </div>

        @if($cart->items->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <p class="mb-2">Your cart is empty.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4">Browse products</a>
                </div>
            </div>
        @else

        <div class="row g-3">

            {{-- LEFT: Cart Items --}}
            <div class="col-md-8">

                @foreach($cart->items as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        {{ $product->name ?? 'Product unavailable' }}
                                    </h6>

                                    @if ($product)
                                        <p class="text-muted small mb-2">
                                            {{ $product->description }}
                                        </p>

                                        <div class="text-muted small">
                                            Price: Rp {{ number_format($item->price) }}
                                        </div>
                                        <div class="text-muted small">
                                            Stock available: {{ $product->stock }}
                                        </div>
                                    @else
                                        <p class="text-danger small mb-0">
                                            Produk ini sudah tidak tersedia. Silakan hapus dari cart.
                                        </p>
                                    @endif
                                </div>

                                {{-- Remove --}}
                                <form method="POST"
                                      action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">
                                        Remove
                                    </button>
                                </form>

                            </div>

                            {{-- Update Qty --}}
                            <form method="POST"
                                  action="{{ route('cart.update', $item) }}"
                                  class="mt-3 d-flex align-items-center gap-2">
                                @csrf

                                <label class="small text-muted">Qty</label>
                                <input
                                    type="number"
                                    name="qty"
                                    value="{{ $item->qty }}"
                                    min="1"
                                    class="form-control form-control-sm"
                                    style="width: 80px"
                                    {{ $product ? '' : 'disabled' }}
                                >

                                <button class="btn btn-sm btn-outline-primary" {{ $product ? '' : 'disabled' }}>
                                    Update
                                </button>
                            </form>

                        </div>
                    </div>
                @endforeach

            </div>

            {{-- RIGHT: Summary --}}
            <div class="col-md-4">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">

                        <h6 class="fw-semibold mb-3">Summary</h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp {{ number_format($subtotal) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-semibold">
                            <span>Total</span>
                            <span>Rp {{ number_format($total) }}</span>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn btn-primary w-100 mt-4">
                            Proceed to Checkout
                        </a>

                    </div>
                </div>

            </div>

        </div>

        @endif

    </div>

</x-layout>
