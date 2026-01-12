@push('styles')
    @once
        <style>
            .checkout-shell {
                background: #f8f9fa;
                border-radius: 1.5rem;
                padding: 1.5rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
            }
            .checkout-shell .card {
                border-radius: 1.25rem;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .checkout-shell .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.12);
            }
        </style>
    @endonce
@endpush

<x-layout title="Checkout">

    <div class="checkout-shell mb-4">
        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-4">
            <div>
                <h1 class="h5 fw-semibold mb-0">Checkout</h1>
                <p class="text-muted small mb-0">Provide shipping address and payment.</p>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                ← Back to cart
            </a>
        </div>

        <div class="row g-3">

        {{-- LEFT: Form --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h2 class="h6 fw-semibold mb-3">Shipping & Payment</h2>

                    <form method="POST" action="{{ route('checkout.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Shipping Address</label>
                            <div class="border rounded-3 p-3 bg-light">
                                @if ($defaultShipping)
                                    <p class="mb-2 small">
                                        {!! nl2br(e($defaultShipping)) !!}
                                    </p>
                                    <a href="{{ route('profile.addresses.index') }}" class="text-decoration-none small">
                                        Kelola melalui Daftar Alamat →
                                    </a>
                                @else
                                    <div class="alert alert-warning small mb-0">
                                        Anda belum memiliki alamat default. <a href="{{ route('profile.addresses.index') }}">Tambahkan alamat</a> sebelum melanjutkan checkout.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <select
                                name="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Choose --</option>
                                <option value="cod" {{ old('payment_method')=='cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="transfer" {{ old('payment_method')=='transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="ewallet" {{ old('payment_method')=='ewallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary rounded-pill px-4" {{ $defaultShipping ? '' : 'disabled' }}>
                            {{ $defaultShipping ? 'Place Order' : 'Lengkapi alamat di profil' }}
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- RIGHT: Summary --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h6 fw-semibold mb-3">Order Summary</h2>

                    <div class="small text-muted mb-3">
                        @foreach ($cart->items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="me-2 text-truncate" style="max-width: 70%;">
                                    {{ $item->product->name }} (x{{ $item->qty }})
                                </span>
                                <span>
                                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between fw-semibold">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

</x-layout>
