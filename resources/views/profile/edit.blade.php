<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Update your personal details and default address.">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h6 fw-semibold mb-1">Personal Information</h2>
                    <p class="text-muted small mb-0">Make quick changes to your account data.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    ← Dashboard
                </a>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="+62..."
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Default Shipping Address</span>
                        <a href="{{ route('profile.addresses.index') }}" class="small text-decoration-none">
                            Manage via Address Book →
                        </a>
                    </label>
                    <div class="border rounded-3 p-3 bg-light">
                        @if ($user->default_shipping_address)
                            <p class="mb-2 small">
                                {!! nl2br(e($user->default_shipping_address)) !!}
                            </p>
                            <span class="badge bg-primary-subtle text-primary">Default address</span>
                        @else
                            <div class="alert alert-warning small mb-0">
                                No default address yet. Add one from the Address Book page.
                            </div>
                        @endif
                    </div>
                    <div class="form-text small">
                        Default address changes can only be made from the Address Book to keep shipments consistent.
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>
