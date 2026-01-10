<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Manage the saved payment details used at checkout.">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-semibold mb-1">Payment Details</h2>
                    <p class="text-muted small mb-0">
                        This information will auto-fill when you place an order.
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                    ← Back to profile
                </a>
            </div>

            <form action="{{ route('profile.payment.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Cardholder Name</label>
                    <input
                        type="text"
                        name="payment_account_name"
                        value="{{ old('payment_account_name', $user->payment_account_name) }}"
                        class="form-control @error('payment_account_name') is-invalid @enderror"
                        placeholder="Name shown on card"
                    >
                    @error('payment_account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Card Number</label>
                    <input
                        type="text"
                        name="payment_card_number"
                        value="{{ old('payment_card_number', $user->payment_card_number) }}"
                        class="form-control @error('payment_card_number') is-invalid @enderror"
                        placeholder="16 digits / account number"
                    >
                    @error('payment_card_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expiry (MM/YY)</label>
                    <input
                        type="text"
                        name="payment_card_expiry"
                        value="{{ old('payment_card_expiry', $user->payment_card_expiry) }}"
                        class="form-control @error('payment_card_expiry') is-invalid @enderror"
                        placeholder="MM/YY"
                    >
                    @error('payment_card_expiry')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">CVC</label>
                    <input
                        type="text"
                        name="payment_card_cvc"
                        value="{{ old('payment_card_cvc', $user->payment_card_cvc) }}"
                        class="form-control @error('payment_card_cvc') is-invalid @enderror"
                        placeholder="3 digits"
                    >
                    @error('payment_card_cvc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="alert alert-warning small">
                        For security reasons we only display the last few digits of your card on the profile page.
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        Save Payment Information
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>
