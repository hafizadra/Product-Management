<x-layout title="Reset Password">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h5 fw-semibold mb-3">Forgot password</h1>
                    <p class="text-muted small mb-4">Enter your registered email and we'll send you a reset link.</p>

                    @if (session('status'))
                        <div class="alert alert-success" data-flash>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
                        @csrf

                        <div>
                            <label for="email" class="form-label">Email address</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill py-2">
                            Send reset link
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="small text-decoration-none">← Back to login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
