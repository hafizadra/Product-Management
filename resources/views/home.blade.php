<x-layout title="Home">
    {{-- HERO --}}
    <div class="p-4 p-md-5 mb-4 rounded-4 text-bg-dark shadow-sm">
        <div class="row align-items-center g-4">

            <div class="col-lg-7">
                <h1 class="display-6 fw-semibold mb-2">
                    @auth
                        Welcome back, {{ Auth::user()->name }} 👋
                    @else
                        Welcome to <span class="text-warning">Aleef</span>
                    @endauth
                </h1>

                <p class="text-white-50 mb-4">
                    Discover products you’ll love, manage your cart, and checkout in seconds.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('products') }}" class="btn btn-warning fw-semibold">
                        Browse Products
                    </a>

                    @auth
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                                Open Admin
                            </a>
                        @else
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-light">
                                View Cart
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-light">
                                My Orders
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-light">
                            Create Account
                        </a>
                    @endauth
                </div>

                @if (session('status'))
                    <div class="alert alert-success mt-4 mb-0">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="card bg-black bg-opacity-25 border-0 rounded-4">
                    <div class="card-body p-4">

                        @auth
                            <h6 class="text-uppercase text-white-50 mb-3">Your Activity</h6>

                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                        <div class="text-white-50 small">Cart</div>
                                        <div class="h4 fw-semibold mb-0 text-white">
                                            {{ $cartItems ?? 0 }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                        <div class="text-white-50 small">Orders</div>
                                        <div class="h4 fw-semibold mb-0 text-white">
                                            {{ $ordersCount ?? 0 }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                        <div class="text-white-50 small">Pending</div>
                                        <div class="h4 fw-semibold mb-0 text-white">
                                            {{ $pendingOrders ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-secondary my-4">

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-white-50 small">Signed in as</div>
                                <div class="fw-semibold text-white">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        @else
                            <h6 class="text-uppercase text-white-50 mb-3">Quick Stats</h6>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-dark border border-secondary">
                                        <div class="text-white-50 small">Products</div>
                                        <div class="h4 fw-semibold mb-0 text-white">
                                            {{ $totalProducts ?? '—' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-dark border border-secondary">
                                        <div class="text-white-50 small">Categories</div>
                                        <div class="h4 fw-semibold mb-0 text-white">
                                            {{ $totalCategories ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-secondary my-4">

                            <p class="text-white-50 small mb-0">
                                Sign in to save orders and track your checkout history.
                            </p>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- LATEST PRODUCTS --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 fw-semibold mb-0">Latest Products</h2>
        <a href="{{ route('products') }}" class="text-decoration-none">View all →</a>
    </div>

    @php
        $products = $latestProducts ?? collect();
    @endphp

    @if ($products->isEmpty())
        <div class="alert alert-light border rounded-4">
            No products yet. Add some products to see them here.
        </div>
    @else
        <div class="row g-3">
            @foreach ($products as $p)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 d-flex flex-column">

                            @if ($p->image_url)
                                <img
                                    src="{{ $p->image_url }}"
                                    alt="{{ $p->name }}"
                                    class="img-fluid rounded mb-3"
                                    style="max-height: 160px; object-fit: cover;"
                                >
                            @endif

                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-muted small mb-1">
                                        {{ $p->category->name ?? 'Uncategorized' }}
                                    </div>
                                    <h3 class="h6 fw-semibold mb-2">
                                        {{ $p->name }}
                                    </h3>
                                </div>

                                <span class="badge text-bg-dark rounded-pill">New</span>
                            </div>

                            @if (!empty($p->description))
                                <p class="text-muted small mb-3"
                                   style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $p->description }}
                                </p>
                            @else
                                <p class="text-muted small mb-3">No description.</p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="fw-semibold">
                                    Rp {{ number_format($p->price ?? 0) }}
                                </div>
                                <div class="text-muted small text-end">
                                    <div>{{ $p->author ?? 'Unknown author' }}</div>
                                    @if ($p->isbn)
                                        <div>ISBN: {{ $p->isbn }}</div>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                    Details
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- FOOTER NOTE --}}
    <div class="mt-4 text-muted small">
        @auth
            You are logged in. Happy shopping!
        @else
            You are browsing as guest. Login to checkout and view order history.
        @endauth
    </div>
</x-layout>
