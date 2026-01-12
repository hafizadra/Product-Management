<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Update your password regularly to keep the account secure.">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="h6 fw-semibold mb-1">Change Password</h2>
                <p class="text-muted small mb-0">
                    Use at least 8 characters with a mix of letters and numbers.
                </p>
            </div>

            <form action="{{ route('profile.security.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label class="form-label">Current Password</label>
                    <input
                        type="password"
                        name="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        required
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm New Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        Save Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>
