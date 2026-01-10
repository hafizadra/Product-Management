<x-layout title="Admin · Orders">
    <nav aria-label="breadcrumb" class="small text-muted mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h5 mb-1">Orders</h1>
            <p class="text-muted small mb-0">
                Track and process every user order from a single workspace.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                ← Back to inventory
            </a>
        </div>
    </div>

    <form class="card border-0 shadow-sm mb-4" method="GET">
        <div class="card-body row gy-2 gx-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Filter status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                    Apply
                </button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order->id }}</td>
                            <td>
                                <div>{{ $order->user->name ?? 'Unknown' }}</div>
                                <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge text-bg-light border">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a
                                    href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <div class="mb-2">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 4h12l2 4H4l2-4z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 8h12v10a2 2 0 01-2 2H8a2 2 0 01-2-2V8z" stroke="currentColor" stroke-width="1.4"/>
                                    </svg>
                                </div>
                                <p class="small mb-0">No orders yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layout>
