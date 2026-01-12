@props(['product'])

<style>
    .product-card { cursor: pointer; transition: transform 0.18s ease, box-shadow 0.18s ease; }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.6rem rgba(0,0,0,.10);
    }
    .product-card:active {
        transform: translateY(-1px) scale(0.997);
    }
</style>

<div
    class="card h-100 shadow-sm border-0 product-card"
    data-url="{{ route('products.show', $product->id) }}"
>
    <div class="card-body d-flex flex-column">

        @if ($product->image_url)
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="img-fluid rounded mb-3 w-100"
                style="height: 160px; object-fit: cover;"
            >
        @endif

        {{-- judul dan kategori --}}
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h5
                    class="card-title fs-6 fw-semibold mb-0 text-truncate"
                    title="{{ $product->name }}"
                >
                    {{ $product->name }}
                </h5>
                @if ($product->author)
                    <small class="text-muted d-block">by {{ $product->author }}</small>
                @endif
            </div>

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
            @if ($product->isbn)
                <br>
                <span class="text-muted">ISBN: {{ $product->isbn }}</span>
            @endif
        </p>

        {{-- harga & rating --}}
        <div class="d-flex justify-content-between align-items-center mb-1">
            <p class="fw-bold mb-0 small">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            @if ($product->average_rating)
                <div class="badge bg-warning-subtle text-dark border small">
                    ★ {{ number_format($product->average_rating, 1) }}
                    <span class="text-muted">({{ $product->reviews_count }})</span>
                </div>
            @endif
        </div>

        {{-- stok --}}
        <p class="small {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
            {{ $product->stock > 0 ? 'Stok tersedia: ' . $product->stock : 'Stok habis' }}
        </p>

        {{-- aksi --}}
        <div class="mt-auto d-flex gap-1 align-items-center flex-wrap">
            @if (auth()->user()?->is_admin)
                <a
                    href="{{ route('admin.products.edit', $product) }}"
                    class="btn btn-outline-primary btn-sm"
                >
                    Edit
                </a>
            @endif

            {{-- Add to Cart (harus login) --}}
            @if ($product->stock > 0)
                @auth
                    <form
                        action="{{ route('cart.add', $product->id) }}"
                        method="POST"
                        class="add-to-cart-form"
                        data-product="{{ $product->name }}"
                    >
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
            @else
                <span class="badge bg-secondary">Out of stock</span>
            @endif

            @auth
                <form action="{{ route('wishlist.store', $product) }}" method="POST" class="wishlist-form ms-auto" data-product="{{ $product->name }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        ♡
                    </button>
                </form>
            @endauth

            @if (auth()->user()?->is_admin)
                {{-- Delete --}}
                <form
                    action="{{ route('admin.products.destroy', $product) }}"
                    method="POST"
                    class="ms-auto"
                    onsubmit="return confirm('Are you sure you want to delete this product?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Toast (dibuat sekali saja meskipun component dipakai banyak) --}}
@if (!defined('CART_TOAST_RENDERED'))
    @php define('CART_TOAST_RENDERED', true); @endphp

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
        <div id="cartToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div id="cartToastBody" class="toast-body"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        // ✅ cegah script terpasang berkali-kali kalau component di-loop
        if (!window.__productCardScriptsMounted) {
            window.__productCardScriptsMounted = true;

            // Klik card -> menuju detail
            document.addEventListener('click', function (e) {
                const card = e.target.closest('.product-card');
                if (!card) return;

                // kalau klik berasal dari elemen interaktif, jangan redirect
                if (
                    e.target.closest('a') ||
                    e.target.closest('button') ||
                    e.target.closest('form') ||
                    e.target.closest('input') ||
                    e.target.closest('textarea') ||
                    e.target.closest('select') ||
                    e.target.closest('label')
                ) {
                    return;
                }

                window.location.href = card.dataset.url;
            });

            // Add to Cart via fetch (tanpa redirect)
            document.addEventListener('submit', async function (e) {
                const form = e.target.closest('.add-to-cart-form');
                if (!form) return;

                e.preventDefault();

                const url = form.action;
                const token = form.querySelector('input[name="_token"]').value;
                const productName = form.dataset.product || 'Product';
                const btn = form.querySelector('button[type="submit"]');

                // disable button biar gak spam klik
                if (btn) {
                    btn.disabled = true;
                    btn.dataset.oldText = btn.innerText;
                    btn.innerText = 'Adding...';
                }

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        throw new Error(data?.message || 'Failed to add to cart');
                    }

                    showCartToast(data?.message || `✅ "${productName}" added to cart`, false);
                } catch (err) {
                    showCartToast(err?.message || '❌ Failed to add to cart', true);
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerText = btn.dataset.oldText || 'Add';
                    }
                }
            });

            document.addEventListener('submit', async function (e) {
                const form = e.target.closest('.wishlist-form');
                if (!form) return;

                e.preventDefault();

                const url = form.action;
                const token = form.querySelector('input[name="_token"]').value;
                const productName = form.dataset.product || 'Product';
                const btn = form.querySelector('button[type="submit"]');

                if (btn) {
                    btn.disabled = true;
                }

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                    });

                    if (!res.ok) throw new Error();

                    showCartToast(`❤️ "${productName}" added to wishlist`, false);
                } catch (err) {
                    showCartToast('Failed to add to wishlist', true);
                } finally {
                    if (btn) btn.disabled = false;
                }
            });

            function showCartToast(message, isError = false) {
                const toastEl = document.getElementById('cartToast');
                const bodyEl = document.getElementById('cartToastBody');
                if (!toastEl || !bodyEl) return;

                bodyEl.textContent = message;

                
                toastEl.classList.remove('text-bg-success', 'text-bg-danger');
                toastEl.classList.add(isError ? 'text-bg-danger' : 'text-bg-success');

                
                if (window.bootstrap && window.bootstrap.Toast) {
                    const t = window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 2500 });
                    t.show();
                } else {
                    
                    alert(message);
                }
            }
        }
    </script>
@endif
