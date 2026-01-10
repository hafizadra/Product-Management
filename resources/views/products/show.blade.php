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

                    @if ($product->image_url)
                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="img-fluid rounded mb-3"
                            style="max-height: 320px; object-fit: cover;"
                        >
                    @endif

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

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="fw-bold mb-0">
                            Price: Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        @if ($product->average_rating)
                            <div class="text-end">
                                <div class="fw-semibold">
                                    ★ {{ number_format($product->average_rating, 1) }}
                                </div>
                                <small class="text-muted">
                                    {{ $product->reviews_count }} review{{ $product->reviews_count === 1 ? '' : 's' }}
                                </small>
                            </div>
                        @endif
                    </div>

                    @if ($product->author)
                        <p class="mb-1 text-muted small">Author: {{ $product->author }}</p>
                    @endif
                    @if ($product->publisher)
                        <p class="mb-1 text-muted small">Publisher: {{ $product->publisher }}</p>
                    @endif
                    @if ($product->isbn)
                        <p class="mb-1 text-muted small">ISBN: {{ $product->isbn }}</p>
                    @endif

                    <p class="small {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? 'Stock available: ' . $product->stock : 'Stock is empty' }}
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
                    <div class="d-flex gap-2 flex-wrap">

                        @if (auth()->user()?->is_admin)
                            <a
                                href="{{ route('admin.products.edit', $product) }}"
                                class="btn btn-primary btn-sm"
                            >
                                Edit
                            </a>
                        @endif

                        {{-- Add to Cart (harus login) --}}
                        @if ($product->stock > 0)
                            @auth
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success btn-sm">
                                    Add to Cart
                                </a>
                            @endauth
                        @else
                            <span class="badge bg-secondary align-self-center">
                                Out of stock
                            </span>
                        @endif

                        @if (auth()->user()?->is_admin)
                            {{-- Delete --}}
                            <form
                                action="{{ route('admin.products.destroy', $product) }}"
                                method="POST"
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

        </div>
    </div>

    {{-- REVIEW SECTION --}}
    <div class="row justify-content-center mt-4">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Reviews</h2>
                    <span class="small text-muted">
                        {{ $product->reviews_count }} total
                    </span>
                </div>
                <div class="card-body p-0">
                    @forelse ($reviews as $review)
                        <div class="px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $review->user->name ?? 'User' }}
                                    </div>
                                    <small class="text-muted">
                                        ★ {{ $review->rating }} • {{ $review->created_at?->diffForHumans() }}
                                    </small>
                                </div>
                                @if ($review->user_id === auth()->id())
                                    <span class="badge bg-light text-dark border small">Your review</span>
                                @endif
                            </div>
                            @if ($review->comment)
                                <p class="mb-0 mt-2 small">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted small px-4 py-4 mb-0">
                            Belum ada ulasan untuk buku ini.
                        </p>
                    @endforelse
                </div>
            </div>

            @auth
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="h6 mb-0">
                            {{ $userReview ? 'Perbarui ulasan Anda' : 'Tulis ulasan' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success py-2 small js-flash-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($canReview || $userReview)
                            <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="small">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ (int) old('rating', $userReview->rating ?? '') === $i ? 'selected' : '' }}>
                                                {{ $i }} - {{ str_repeat('★', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Komentar (opsional)</label>
                                    <textarea
                                        name="comment"
                                        class="form-control form-control-sm"
                                        rows="3"
                                        placeholder="Bagikan pendapatmu tentang buku ini"
                                    >{{ old('comment', $userReview->comment ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Simpan ulasan
                                </button>
                            </form>
                        @elseif ($canReview === false && ! $userReview)
                            <div class="alert alert-light border small mb-0">
                                Kamu perlu menyelesaikan pesanan buku ini terlebih dahulu (status completed) sebelum bisa memberi rating.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-light border small">
                    <a href="{{ route('login') }}">Masuk</a> untuk menulis ulasan setelah menyelesaikan pesananmu.
                </div>
            @endauth
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.querySelector('.js-flash-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = 'opacity .3s ease';
                    successAlert.style.opacity = '0';
                    successAlert.addEventListener('transitionend', () => successAlert.remove(), { once: true });
                }, 1000);
            }
        });
    </script>
</x-layout>
