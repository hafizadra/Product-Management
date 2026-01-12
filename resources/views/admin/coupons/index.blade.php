<x-layout title="Admin · Coupons">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">Coupons</h1>
            <p class="text-muted small mb-0">Manage discount codes and track usage.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm rounded-pill">
            + New Coupon
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Period</th>
                        <th>Usage</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="fw-semibold">{{ $coupon->code }}</td>
                            <td>
                                <div>{{ $coupon->title }}</div>
                                <small class="text-muted">{{ $coupon->description }}</small>
                            </td>
                            <td>
                                {{ ucfirst($coupon->discount_type) }}
                                <div class="text-muted small">
                                    {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : 'Rp ' . number_format($coupon->discount_value, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="small text-muted">
                                {{ optional($coupon->starts_at)->format('d M Y') ?? '—' }}
                                –
                                {{ optional($coupon->ends_at)->format('d M Y') ?? 'No end' }}
                            </td>
                            <td>
                                <span class="badge {{ $coupon->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span>
                                <div class="text-muted small">{{ $coupon->usages_count }} used</div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No coupons yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $coupons->links() }}
        </div>
    </div>
</x-layout>
