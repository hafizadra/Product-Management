<x-layout title="Admin · Inventory">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">Inventory Management</h1>
            <p class="text-muted mb-0 small">
                Kelola stok, harga, dan detail produk yang tampil di katalog.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a
                href="{{ route('admin.orders.index') }}"
                class="btn btn-outline-secondary btn-sm px-2 py-0"
                style="min-height: 28px;"
            >
                Kelola pesanan
            </a>
            <a
                href="{{ route('admin.products.create') }}"
                class="btn btn-primary btn-sm px-2 py-0"
                style="min-height: 28px;"
            >
                + Produk baru
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger small">
            <strong>Gagal memperbarui data:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Products</div>
                    <div class="h4 mb-0">{{ $stats['total_products'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Low Stock (&le; 5)</div>
                    <div class="h4 mb-0 text-warning">{{ $stats['low_stock'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Orders</div>
                    <div class="h4 mb-0">{{ $stats['pending_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Revenue</div>
                    <div class="h4 mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th class="w-25">Stok</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <img
                                                    src="{{ $product->image_url }}"
                                                    alt="{{ $product->name }}"
                                                    class="rounded border"
                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                >
                                                <div>
                                                    <div class="fw-semibold">{{ $product->name }}</div>
                                                    <div class="text-muted small">
                                                        {{ $product->author ?? 'Unknown author' }} — {{ $product->publisher ?? 'Publisher n/a' }}
                                                    </div>
                                                    <div class="text-muted small">{{ Str::limit($product->description, 40) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name ?? '—' }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <form
                                                action="{{ route('admin.products.stock', $product) }}"
                                                method="POST"
                                                class="d-flex gap-2 align-items-center"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="number"
                                                    name="stock"
                                                    value="{{ old('stock', $product->stock) }}"
                                                    min="0"
                                                    class="form-control form-control-sm {{ $product->stock <= 5 ? 'border-danger' : '' }}"
                                                >

                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    Simpan
                                                </button>
                                            </form>
                                            <small class="text-muted">
                                                Diperbarui {{ $product->updated_at?->diffForHumans() }}
                                            </small>
                                            @if ($product->stock <= 5)
                                                <span class="badge text-bg-warning ms-1">Low</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                                <a
                                                    href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-sm btn-outline-secondary px-3"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('admin.products.destroy', $product) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus produk ini?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada produk. Tambahkan produk pertama sekarang.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Latest Orders</h6>
                </div>
                <div class="card-body">
                    @forelse ($recentOrders as $order)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">#{{ $order->id }}</div>
                                    <small class="text-muted">{{ $order->user->name ?? 'Unknown' }}</small>
                                </div>
                                <span class="badge text-bg-light">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                Rp {{ number_format($order->total, 0, ',', '.') }} • {{ $order->created_at?->diffForHumans() }}
                            </div>
                            <a href="{{ route('admin.orders.show', $order) }}" class="small">
                                View details →
                            </a>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada order.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layout>
