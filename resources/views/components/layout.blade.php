@props(['title' => 'Aleef'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <title>{{ $title }}</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">

        {{-- BRAND → HOME --}}
        <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
            Aleef
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- LEFT --}}
            <ul class="navbar-nav me-auto">
                {{-- Products (TETAP ADA) --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}"
                        href="{{ route('products') }}"
                    >
                        Products
                    </a>
                </li>
            </ul>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                @auth
                    {{-- Cart --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                            href="{{ route('cart.index') }}"
                        >
                            Cart
                        </a>
                    </li>

                    {{-- Orders --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                            href="{{ route('orders.index') }}"
                        >
                            Orders
                        </a>
                    </li>

                    {{-- User --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    {{-- Login --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                            href="{{ route('login') }}"
                        >
                            Login
                        </a>
                    </li>

                    {{-- Register --}}
                    <li class="nav-item">
                        <a class="btn btn-sm btn-light" href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- MAIN --}}
<div class="container mb-5">

    @if (session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = "opacity 0.5s";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }
            }, 1000);
        </script>
    @endif

    {{ $slot }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
