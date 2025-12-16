@props(['product'])

<div class="card h-100 shadow-sm border-0">
    <div class="card-body d-flex flex-column">

        {{-- judul dan kategori --}}
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5
                class="card-title fs-6 fw-semibold mb-0 text-truncate"
                title="{{ $product->name }}"
            >
                {{ $product->name }}
            </h5>

            @if ($product->category)
                <span class="badge bg-light text-muted border">
                    {{ $product->category->name }}
                </span>
            @endif
        </div>

        {{-- deskripsi --}}
        <p
            class="card-text small text-muted mb-2"
            style="min-height: 2.8em; max-height: 2.8em; overflow: hidden;"
        >
            {{ $product->description }}
        </p>

        {{-- harga --}}
        <p class="fw-bold mb-3 small">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>

        {{-- aksi --}}
        <div class="mt-auto d-flex gap-1 align-items-center">
            <a
                href="{{ route('products.show', $product->id) }}"
                class="btn btn-outline-secondary btn-sm"
            >
                View
            </a>

            <a
                href="{{ route('products.edit', $product->id) }}"
                class="btn btn-outline-primary btn-sm"
            >
                Edit
            </a>

            {{-- Add to Cart (harus login) --}}
            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        Add
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                    Add
                </a>
            @endauth

            {{-- Delete --}}
            <form
                action="{{ route('products.destroy', $product->id) }}"
                method="POST"
                class="ms-auto"
                onsubmit="return confirm('Are you sure you want to delete this product?');"
            >
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
