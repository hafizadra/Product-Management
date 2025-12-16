<x-layout title="Register">

    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <h1 class="h5 fw-semibold mb-3">Create an account</h1>
                    <p class="text-muted small mb-4">
                        Register to start shopping and manage your cart.
                    </p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label">Password</label>
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

                        {{-- Confirm --}}
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid">
                            <button class="btn btn-primary rounded-pill py-2">
                                Register
                            </button>
                        </div>

                        {{-- Login link --}}
                        <div class="text-center mt-3">
                            <span class="text-muted small">Already registered?</span>
                            <a class="small text-decoration-none link-primary-hover"
                               href="{{ route('login') }}">
                                Login
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <style>
        .link-primary-hover { transition: color .15s ease; }
        .link-primary-hover:hover,
        .link-primary-hover:active,
        .link-primary-hover:focus { color: #0d6efd !important; }
    </style>

</x-layout>
