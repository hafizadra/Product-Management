<x-layout title="My Wishlist">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-1">Wishlist</h1>
            <p class="text-muted small mb-0">Books you saved for later.</p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary">Browse books</a>
    </div>

    @if ($items->isEmpty())
        <div class="alert alert-light border">
            Wishlist is empty. Start exploring books to add!
        </div>
    @else
        <div class="row g-3">
            @foreach ($items as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex gap-2 mb-2">
                                <img
                                    src="{{ $item->product->image_url }}"
                                    alt="{{ $item->product->name }}"
                                    class="rounded border"
                                    style="width: 80px; height: 80px; object-fit: cover;"
                                >
                                <div>
                                    <div class="fw-semibold">{{ $item->product->name }}</div>
                                    <div class="small text-muted">{{ $item->product->author ?? 'Unknown author' }}</div>
                                    <div class="small">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <p class="small text-muted flex-grow-1">
                                {{ Str::limit($item->product->description, 100) }}
                            </p>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $item->product_id) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                <form action="{{ route('wishlist.destroy', $item->product_id) }}" method="POST" class="ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>
