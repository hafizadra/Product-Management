

<x-layout title="Product List">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h6 mb-0">Products</h1>

        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
            Add new product
        </a>
    </div>

    {{-- FILTER DAN SEARCH --}}
    <form 
        method="GET" 
        action="{{ route('products') }}" 
        class="border-bottom pb-3 mb-3"
    >
        <div class="row g-2 align-items-center small">

            {{-- SEARCH --}}
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Search</label>
                <input 
                    type="text" 
                    name="q" 
                    class="form-control form-control-sm"
                    placeholder="Name or description"
                    value="{{ $search }}"
                >
            </div>

            {{-- MIN PRICE --}}
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Min price</label>
                <input 
                    type="number" 
                    name="min_price" 
                    class="form-control form-control-sm"
                    min="0"
                    value="{{ $minPrice }}"
                >
            </div>

            {{-- MAX PRICE --}}
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Max price</label>
                <input 
                    type="number" 
                    name="max_price" 
                    class="form-control form-control-sm"
                    min="0"
                    value="{{ $maxPrice }}"
                >
            </div>

            {{-- SORT BY --}}
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Sort by</label>
                <select name="sort_by" class="form-select form-select-sm">
                    <option value="name"  {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Price</option>
                </select>
            </div>

            {{-- ORDER --}}
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Order</label>
                <select name="sort_dir" class="form-select form-select-sm">
                    <option value="asc"  {{ $sortDir === 'asc' ? 'selected' : '' }}>Asc</option>
                    <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Desc</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-2 small">
            <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary">
                Reset
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                Apply
            </button>
        </div>
    </form>

    {{-- INFO RINGKAS --}}
    <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
        <div>
            {{ $products->count() }} product(s) found
        </div>
        <div>
            Sort: {{ ucfirst($sortBy) }} / {{ strtoupper($sortDir) }}
        </div>
    </div>

    {{-- GRID PRODUK --}}
    @if ($products->isEmpty())
        <div class="alert alert-light border text-center small">
            No products found. Try adjusting your search or filters.
        </div>
    @else
        <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
            @foreach ($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    @endif

</x-layout>
