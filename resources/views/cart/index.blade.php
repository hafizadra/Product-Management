<x-layout title="Your Cart">

<div class="container">

    <h1 class="h4 fw-semibold mb-4">Shopping Cart</h1>

    @if($cart->items->isEmpty())
        <div class="alert alert-light border">
            Your cart is empty.
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
