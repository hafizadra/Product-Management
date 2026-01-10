@php
    $menuItems = [
        [
            'label' => 'Edit Profile',
            'route' => route('profile.edit'),
            'active' => request()->routeIs('profile.edit'),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a5 5 0 100-10 5 5 0 000 10z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M20 21a8 8 0 10-16 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
        ],
        [
            'label' => 'Address Book',
            'route' => route('profile.addresses.index'),
            'active' => request()->routeIs('profile.addresses.*'),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 21s7-5.5 7-11.5S16.18 2 12 2 5 6 5 9.5 12 21 12 21z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.4"/></svg>',
        ],
        [
            'label' => 'Account Security',
            'route' => route('profile.security.index'),
            'active' => request()->routeIs('profile.security.*'),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 11.5c0 5.5-8 9-8 9s-8-3.5-8-9V6l8-3 8 3v5.5z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 11v3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="12" cy="9" r="1" fill="currentColor"/></svg>',
        ],
        [
            'label' => 'Payment Information',
            'route' => route('profile.payment.edit'),
            'active' => request()->routeIs('profile.payment.*'),
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M8 15h3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
        ],
    ];

    $ordersCount = $sidebar['ordersCount'] ?? 0;
    $completedCount = $sidebar['completedCount'] ?? 0;
    $pendingCount = $sidebar['pendingCount'] ?? 0;
    $totalSpent = $sidebar['totalSpent'] ?? 0;
    $averageOrder = $sidebar['averageOrder'] ?? 0;
    $pendingAmount = $sidebar['pendingAmount'] ?? 0;
    $lastOrder = $sidebar['lastOrder'] ?? null;
    $wishlistCount = $sidebar['wishlistCount'] ?? 0;
    $wishlistItems = $sidebar['wishlistItems'] ?? collect();
    $wishlistVisible = $wishlistItems instanceof \Illuminate\Support\Collection
        ? $wishlistItems->count()
        : (is_array($wishlistItems) ? count($wishlistItems) : 0);
@endphp

@push('styles')
    @once
        <style>
            .profile-container {
                background: #f8f9fa;
                padding: 1.5rem;
                border-radius: 1.5rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
            }
            .profile-container .card {
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }
            .profile-container .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 1rem 1.5rem rgba(15, 23, 42, 0.12);
            }
        </style>
    @endonce
@endpush

<x-layout :title="$title ?? ($pageTitle ?? 'Profile')">
    <div class="profile-container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <p class="text-muted small mb-1">Signed in as</p>
                <div class="fw-semibold">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h2 class="h6 mb-1">Profile Menu</h2>
                                <p class="text-muted small mb-0">Manage your account and settings.</p>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($menuItems as $item)
                                <a
                                    href="{{ $item['route'] }}"
                                    class="list-group-item list-group-item-action border-0 px-0 d-flex align-items-center gap-2 {{ $item['active'] ? 'text-primary fw-semibold' : '' }}"
                                >
                                    <span class="text-muted">{!! $item['icon'] !!}</span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button
                                    type="submit"
                                    class="list-group-item list-group-item-action border-0 px-0 text-danger d-flex align-items-center gap-2"
                                    onclick="return confirm('Are you sure you want to sign out?')"
                                >
                                    <span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 7V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2h7a2 2 0 002-2v-2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                            <path d="M10 12h11m0 0l-3-3m3 3l-3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <h1 class="h5 mb-1">{{ $pageTitle ?? 'Profile' }}</h1>
                    <p class="text-muted small mb-0">{{ $attributes->get('subtitle', 'Keep your personal data and preferences up to date.') }}</p>
                </div>
                {{ $slot }}
            </div>

            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Order Summary</h2>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Total Orders</span>
                            <span class="fw-semibold">{{ $ordersCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Completed</span>
                            <span class="fw-semibold text-success">{{ $completedCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Pending</span>
                            <span class="fw-semibold text-warning">{{ $pendingCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Total Spent</span>
                            <span class="fw-semibold">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Average Order</span>
                            <span class="fw-semibold">
                                {{ $averageOrder > 0 ? 'Rp ' . number_format($averageOrder, 0, ',', '.') : '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Pending Value</span>
                            <span class="fw-semibold">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Last Order</span>
                            <span class="fw-semibold">
                                {{ $lastOrder?->created_at?->format('d M Y H:i') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <h2 class="h6 mb-1">Saved Payment Method</h2>
                        <p class="text-muted small mb-3">Stored card or bank account information.</p>

                        @php
                            $paymentUser = auth()->user();
                            $accountName = $paymentUser?->payment_account_name;
                            $cardNumber = $paymentUser?->payment_card_number;
                            $cardExpiry = $paymentUser?->payment_card_expiry;
                            $cardCvc = $paymentUser?->payment_card_cvc;
                            $maskedNumber = $cardNumber
                                ? '**** **** **** ' . substr(preg_replace('/\D/', '', $cardNumber), -4)
                                : null;
                            $maskedCvc = $cardCvc ? str_repeat('*', max(strlen($cardCvc) - 1, 0)) . substr($cardCvc, -1) : null;
                        @endphp

                        @if ($accountName || $cardNumber || $cardExpiry || $cardCvc)
                            <div class="mb-2">
                                <div class="text-muted small">Cardholder Name</div>
                                <div class="fw-semibold">{{ $accountName ?? 'Not set' }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small">Card Number</div>
                                <div class="fw-semibold">{{ $maskedNumber ?? 'Not set' }}</div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div>
                                    <div class="text-muted small">Expiry</div>
                                    <div class="fw-semibold">{{ $cardExpiry ?? 'MM/YY' }}</div>
                                </div>
                                <div>
                                    <div class="text-muted small">CVC</div>
                                    <div class="fw-semibold">{{ $maskedCvc ?? '***' }}</div>
                                </div>
                            </div>
                            <div class="alert alert-light border-0 text-muted small mt-3 mb-0">
                                Update this data from the Payment Information page.
                            </div>
                        @else
                            <div class="alert alert-light border text-muted small mb-0">
                                No payment method saved yet. Add it via the Payment Information page.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h6 mb-0">Wishlist</h2>
                            <p class="text-muted small mb-0">{{ $wishlistCount }} items</p>
                        </div>
                        <a href="{{ route('wishlist.index') }}" class="text-decoration-none small">View</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($wishlistItems as $item)
                            @php $product = $item->product; @endphp
                            <div class="px-3 py-3 border-bottom small">
                                <div class="fw-semibold text-truncate">
                                    {{ $product->name ?? 'Product unavailable' }}
                                </div>
                                <div class="text-muted">
                                    {{ $product?->author ?? '-' }}
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="fw-semibold">
                                        @if ($product)
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                    @if ($product)
                                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none small">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small px-3 py-4 mb-0">No wishlist items yet.</p>
                        @endforelse

                        @if ($wishlistCount > $wishlistVisible)
                            <div class="px-3 py-2 text-muted small">
                                +{{ $wishlistCount - $wishlistVisible }} more saved items.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
