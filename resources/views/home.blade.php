

<x-layout title="Home">

    {{-- WRAPPER --}}
    <div class="fade-container py-4 py-md-5">

        {{-- HERO SECTION --}}
        <section class="text-center mb-5">
            <h1 class="fw-semibold mb-3 display-6">
                Product Dashboard
            </h1>

            <p class="text-muted mb-4 fs-6" style="max-width: 620px; margin: 0 auto;">
                A simple and elegant dashboard to manage your products and categories.  
                Designed with a clean minimalistic style.
            </p>

            <div class="d-inline-flex gap-3 mt-2">
                <a href="{{ route('products') }}" class="btn btn-primary px-4 py-2">
                    View Products
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-outline-secondary px-4 py-2">
                    Add Product
                </a>
            </div>
        </section>

        {{-- STATS FRAME --}}
        <section class="mb-4 d-flex justify-content-center">
            <div 
                class="shadow-sm rounded-4 px-4 py-3 d-flex flex-row gap-5 text-center bg-white"
                style="border: 1px solid #f1f1f1; min-width: 280px;"
            >
                <div>
                    <div class="text-muted small mb-1">Products</div>
                    <div class="fw-bold fs-3">{{ $totalProducts }}</div>
                </div>

                <div class="d-flex align-items-center text-muted">•</div>

                <div>
                    <div class="text-muted small mb-1">Categories</div>
                    <div class="fw-bold fs-3">{{ $totalCategories }}</div>
                </div>
            </div>
        </section>

        {{-- DIVIDER --}}
        <hr class="my-5 opacity-25">

        {{-- LATEST PRODUCTS GRID --}}
        <section>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-semibold fs-5 mb-0">Latest Products</h2>

                <a 
                    href="{{ route('products') }}" 
                    class="text-decoration-none text-muted fs-6 link-primary-hover"
                >
                    View all →
                </a>
            </div>

            @if ($latestProducts->isEmpty())
                <p class="text-muted small">No products yet.</p>
            @else
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                    @foreach ($latestProducts as $product)
                        <div class="col fade-item">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>


    {{-- UNTUK FADE IN --}}
    <style>
        
        .fade-container {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
        }

        
        .fade-item {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeUp 0.7s ease forwards;
        }
        .fade-item:nth-child(1) { animation-delay: .05s; }
        .fade-item:nth-child(2) { animation-delay: .1s; }
        .fade-item:nth-child(3) { animation-delay: .15s; }
        .fade-item:nth-child(4) { animation-delay: .2s; }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover */
        .link-primary-hover {
            transition: color .2s ease;
        }
        .link-primary-hover:hover,
        .link-primary-hover:active,
        .link-primary-hover:focus {
            color: #0d6efd !important;
        }
    </style>

</x-layout>
