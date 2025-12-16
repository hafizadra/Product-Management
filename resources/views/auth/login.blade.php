<x-layout title="Login">

    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <h1 class="h5 fw-semibold mb-3">Login</h1>
                    <p class="text-muted small mb-4">
                        Please sign in to continue.
                    </p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
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
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none link-primary-hover"
                                   href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2">
                                Login
                            </button>
                        </div>

                        {{-- Register link --}}
                        @if (Route::has('register'))
                            <div class="text-center mt-3">
                                <span class="text-muted small">Don’t have an account?</span>
                                <a class="small text-decoration-none link-primary-hover"
                                   href="{{ route('register') }}">
                                    Register
                                </a>
                            </div>
                        @endif
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
