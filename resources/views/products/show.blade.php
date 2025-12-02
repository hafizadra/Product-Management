

<x-layout :title="$product->name . ' - Product Detail'">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h5 mb-0">Product Detail</h1>

                <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary">
                    ← Back to list
                </a>
            </div>

            {{-- DETAIL CARD --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 mb-0">
                            {{ $product->name }}
                        </h2>

                        @if ($product->category)
                            <span class="badge bg-light text-muted border">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    
                    <p class="fw-bold mb-2">
                        Price: Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    
                    <div class="mb-3">
                        <h3 class="h6 text-muted mb-1">Description</h3>
                        <p class="mb-0">
                            {{ $product->description ?: 'No description provided.' }}
                        </p>
                    </div>

                    
                    <div class="mb-3 small text-muted">
                        <div>Product ID: {{ $product->id }}</div>
                        @if ($product->created_at)
                            <div>Created at: {{ $product->created_at->format('Y-m-d H:i') }}</div>
                        @endif
                        @if ($product->updated_at)
                            <div>Last updated: {{ $product->updated_at->format('Y-m-d H:i') }}</div>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="d-flex gap-2">
                        <a 
                            href="{{ route('products.edit', $product->id) }}" 
                            class="btn btn-primary btn-sm"
                        >
                            Edit
                        </a>

                        {{-- Delete --}}
                        <form
                            action="{{ route('products.destroy', $product->id) }}"
                            method="POST"
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

        </div>
    </div>
</x-layout>
