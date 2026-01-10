<x-layout title="Welcome back, Admin">
    <nav aria-label="breadcrumb" class="small text-muted mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
    <div class="p-4 p-md-5 mb-4 rounded-4 bg-dark text-white shadow-sm">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <h1 class="display-6 fw-semibold mb-2">
                    Welcome back, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-white-50 mb-4">
                    Stay on top of inventory, orders, and users from one place.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-warning fw-semibold">
                        Go to Inventory
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light">
                        Manage Orders
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">
                        Manage Users
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card bg-black bg-opacity-25 border-0 rounded-4">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-white-50 mb-3">Quick snapshot</h6>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                    <div class="text-white-50 small">Products</div>
                                    <div class="h4 fw-semibold mb-0 text-white">
                                        {{ $stats['total_products'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                    <div class="text-white-50 small">Pending Orders</div>
                                    <div class="h4 fw-semibold mb-0 text-white">
                                        {{ $stats['pending_orders'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                    <div class="text-white-50 small">Low stock</div>
                                    <div class="h4 fw-semibold mb-0 text-warning">
                                        {{ $stats['low_stock'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-dark border border-secondary text-center">
                                    <div class="text-white-50 small">Revenue</div>
                                    <div class="h5 fw-semibold mb-0 text-white">
                                        Rp {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary my-4">
                        <div class="text-white-50 small">
                            Signed in as <span class="fw-semibold text-white">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-muted small">Total Orders</div>
                    <div class="h4 fw-semibold">{{ $stats['total_orders'] ?? 0 }}</div>
                    <p class="text-muted small mt-2 mb-0">
                        Update {{ now()->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-muted small">Revenue</div>
                    <div class="h4 fw-semibold">Rp {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div>
                    <p class="text-muted small mt-2 mb-0">
                        Lifetime gross sales
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-muted small">Open Tickets</div>
                    <div class="h4 fw-semibold">{{ $stats['pending_orders'] ?? 0 }}</div>
                    <p class="text-muted small mt-2 mb-0">
                        Pending orders awaiting action
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-muted small">Low Stock</div>
                    <div class="h4 fw-semibold text-danger">{{ $stats['low_stock'] ?? 0 }}</div>
                    <p class="text-muted small mt-2 mb-0">
                        Products at &le; 5 units left
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 h6">Latest Orders</h5>
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none small">View all →</a>
        </div>
        <div class="card-body p-0">
            @forelse ($recentOrders as $order)
                <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-3">
                    <div>
                        <div class="fw-semibold">Order #{{ $order->id }} • Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        <small class="text-muted">
                            {{ $order->user->name ?? 'Unknown' }} • {{ $order->created_at?->format('d M Y H:i') }}
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $order->status === 'pending' ? 'text-bg-warning' : 'text-bg-light' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <div>
                            <a href="{{ route('admin.orders.show', $order) }}" class="small">
                                Details →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted px-3 py-5">
                    <div class="mb-2">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 7h16l-2 10H6L4 7z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            <path d="M15 11v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="small mb-0">No orders yet. New orders will show up here.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 h6">Low Stock Alerts</h5>
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none small">Open inventory →</a>
        </div>
        <div class="card-body p-0">
            @forelse ($lowStockProducts ?? [] as $product)
                <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-3">
                    <div>
                        <div class="fw-semibold">{{ $product->name }}</div>
                        <small class="text-muted">
                            Stock: {{ $product->stock }} • Category: {{ $product->category->name ?? '—' }}
                        </small>
                    </div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                        Restock
                    </a>
                </div>
            @empty
                <div class="text-center text-muted px-3 py-5">
                    <div class="mb-2">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M8 16v2a2 2 0 002 2h4a2 2 0 002-2v-2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="small mb-0">Great! No low stock alerts.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layout>
