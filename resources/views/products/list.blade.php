<x-layout title="Product List">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h6 mb-0">Products</h1>

        @if (auth()->user()?->is_admin)
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                Kelola stok
            </a>
        @endif
    </div>

    {{-- FILTER DAN SEARCH --}}
    @php
        $activeFilters = [
            $search ? "Search: $search" : null,
            $minPrice ? "Min Rp $minPrice" : null,
            $maxPrice ? "Max Rp $maxPrice" : null,
            $categoryId ? 'Category: ' . optional($categories->firstWhere('id', (int) $categoryId))->name : null,
            $author ? "Author: $author" : null,
            $publisher ? "Publisher: $publisher" : null,
            $isbn ? "ISBN: $isbn" : null,
        ];
        $activeFilters = array_values(array_filter($activeFilters));
    @endphp
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products') }}" class="vstack gap-4">
                <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Refine results</p>
                        <h2 class="h6 mb-0">Filter Products</h2>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products') }}" class="btn btn-outline-secondary btn-sm">
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Apply filters
                        </button>
                    </div>
                </div>

                @if ($activeFilters)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($activeFilters as $label)
                            <span class="badge text-bg-light border rounded-pill px-3 py-2 small">
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.4"/>
                                    <path d="M16 16l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input type="text" name="q" class="form-control border-start-0" placeholder="Name or description" value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted">Min price</label>
                        <input type="number" name="min_price" class="form-control form-control-sm" min="0" value="{{ $minPrice }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted">Max price</label>
                        <input type="number" name="max_price" class="form-control form-control-sm" min="0" value="{{ $maxPrice }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted">Category</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label small text-muted">Sort by</label>
                        <select name="sort_by" class="form-select form-select-sm">
                            <option value="name"  {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                            <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Price</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label small text-muted">Order</label>
                        <select name="sort_dir" class="form-select form-select-sm">
                            <option value="asc"  {{ $sortDir === 'asc' ? 'selected' : '' }}>Asc</option>
                            <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Desc</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted">Author</label>
                        <input type="text" name="author" class="form-control form-control-sm" placeholder="e.g. Tere Liye" value="{{ $author }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label small text-muted">Publisher</label>
                        <input type="text" name="publisher" class="form-control form-control-sm" placeholder="Publisher name" value="{{ $publisher }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label small text-muted">ISBN</label>
                        <input type="text" name="isbn" class="form-control form-control-sm" placeholder="978..." value="{{ $isbn }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- INFO RINGKAS --}}
    <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
        <div>
            {{ $products->count() }} product(s) found
        </div>
        <div>
            Sort: {{ ucfirst($sortBy) }} / {{ strtoupper($sortDir) }}
            @if(!empty($categoryId))
                | Category: {{ optional($categories->firstWhere('id', (int)$categoryId))->name ?? 'Selected' }}
            @endif
            @if($author)
                | Author: {{ $author }}
            @endif
            @if($publisher)
                | Publisher: {{ $publisher }}
            @endif
            @if($isbn)
                | ISBN: {{ $isbn }}
            @endif
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
