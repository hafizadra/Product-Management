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

                        @if (session('status'))
                            <div class="alert alert-success mt-2 mb-3" data-flash>
                                {{ session('status') }}
                            </div>
                        @endif

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
                            <div class="position-relative">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control pe-5 @error('password') is-invalid @enderror"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                >
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link position-absolute end-0 password-toggle text-secondary"
                                    aria-label="Show password"
                                    style="top: 50%; transform: translateY(-50%);"
                                >
                                    <span class="toggle-open">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                            <path d="M12 5c-5 0-9 5-9 7s4 7 9 7 9-5 9-7-4-7-9-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    <span class="toggle-closed d-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                            <path d="M3 3l18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M4 6.8C5.7 5.1 8.4 4 12 4c5 0 9 5 9 7 0 1.1-1 2.8-2.6 4.2M14.1 14.1a3 3 0 0 1-4.2-4.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>

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
        .password-toggle { text-decoration: none; }
    </style>
    <script>
        (() => {
            const toggleBtn = document.querySelector('.password-toggle');
            const input = document.getElementById('password');
            const openIcon = toggleBtn?.querySelector('.toggle-open');
            const closedIcon = toggleBtn?.querySelector('.toggle-closed');
            if (!toggleBtn || !input) return;

            toggleBtn.addEventListener('click', () => {
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                toggleBtn.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
                toggleBtn.classList.toggle('text-primary', hidden);
                if (openIcon && closedIcon) {
                    openIcon.classList.toggle('d-none', hidden);
                    closedIcon.classList.toggle('d-none', !hidden);
                }
            });
        })();
    </script>

</x-layout>
