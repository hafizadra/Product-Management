@props(['title' => 'Aleef'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <title>{{ $title }}</title>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">

        {{-- BRAND → HOME --}}
        <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
            Aleef
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- LEFT --}}
            <ul class="navbar-nav me-auto">
                @if (!Auth::check() || !Auth::user()->is_admin)
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}"
                            href="{{ route('products') }}"
                        >
                            Products
                        </a>
                    </li>
                @endif

                @auth
                    @if (Auth::user()->is_admin)
                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}"
                            >
                                Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}"
                            >
                                Manage Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                href="{{ route('admin.categories.index') }}"
                            >
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}"
                            >
                                Users
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                @auth
                    @if (!request()->routeIs('admin.*') && !Auth::user()->is_admin)
                        @php
                            $navIcons = [
                                [
                                    'route' => route('cart.index'),
                                    'active' => request()->routeIs('cart.*'),
                                    'title' => 'Cart',
                                    'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6h15l-1.5 7.5H8L6 6z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="18" cy="19" r="1" fill="currentColor"/></svg>',
                                ],
                                [
                                    'route' => route('orders.index'),
                                    'active' => request()->routeIs('orders.*'),
                                    'title' => 'Orders',
                                    'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 4h12l2 4H4l2-4z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 8h12v10a2 2 0 01-2 2H8a2 2 0 01-2-2V8z" stroke="currentColor" stroke-width="1.6"/></svg>',
                                ],
                                [
                                    'route' => route('profile.edit'),
                                    'active' => request()->routeIs('profile.*'),
                                    'title' => 'Profile',
                                    'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M6 20a6 6 0 0112 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
                                ],
                            ];
                        @endphp
                        @foreach ($navIcons as $item)
                            <li class="nav-item">
                                <a
                                    class="nav-link d-flex align-items-center gap-1 {{ $item['active'] ? 'active' : '' }}"
                                    href="{{ $item['route'] }}"
                                    title="{{ $item['title'] }}"
                                >
                                    {!! $item['icon'] !!}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    @if (Auth::user()->is_admin)
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative p-0 me-2" href="#" role="button" data-bs-toggle="dropdown">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 3a6 6 0 00-6 6v3.5l-.9 2.2a1 1 0 00.93 1.3h11.86a1 1 0 00.93-1.3l-.9-2.2V9a6 6 0 00-6-6z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                    <path d="M9.75 18a2.25 2.25 0 004.5 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                </svg>
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="admin-notif-count" style="display:none;">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 260px;" id="admin-notif-list">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span>Latest Orders</span>
                                    <span class="badge bg-secondary" id="admin-notif-label">0</span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center text-muted small py-2" id="admin-notif-empty">No new orders</li>
                            </ul>
                        </li>
                    @endif

                    {{-- Logout --}}
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-light" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>

                @else
                    {{-- Login --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                            href="{{ route('login') }}"
                        >
                            Login
                        </a>
                    </li>

                    {{-- Register --}}
                    <li class="nav-item">
                        <a class="btn btn-sm btn-light" href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- MAIN --}}
<div class="container mb-5">
    @auth
        @if (Auth::user()->is_admin)
            <div id="admin-order-toast" class="toast align-items-center text-bg-primary border-0 position-fixed end-0 top-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1200; display: none;">
                <div class="d-flex">
                    <div class="toast-body" id="admin-order-toast-body">
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endauth

    @php
        $flash = session('flash');
        if (!$flash && session('success')) {
            $flash = ['type' => 'success', 'message' => session('success')];
        } elseif (!$flash && session('error')) {
            $flash = ['type' => 'danger', 'message' => session('error')];
        }
    @endphp

    @if ($flash)
        <div id="global-flash" class="alert alert-{{ $flash['type'] ?? 'info' }} shadow-sm border-0" role="alert" data-flash>
            {{ $flash['message'] ?? '' }}
        </div>
    @endif

    {{ $slot }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const hideFlash = () => {
            setTimeout(() => {
                document.querySelectorAll('[data-flash]').forEach((el) => {
                    el.style.transition = 'opacity 0.3s, transform 0.3s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-6px)';
                    setTimeout(() => el.remove(), 300);
                });
            }, 1000);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', hideFlash);
        } else {
            hideFlash();
        }
    })();
</script>
@auth
    @if (Auth::user()->is_admin)
        <script>
            (function () {
                if (window.__adminOrderToastPolling) return;
                window.__adminOrderToastPolling = true;

                const toastEl = document.getElementById('admin-order-toast');
                const toastBody = document.getElementById('admin-order-toast-body');
                const notifList = document.getElementById('admin-notif-list');
                const notifCount = document.getElementById('admin-notif-count');
                const notifLabel = document.getElementById('admin-notif-label');
                const notifEmpty = document.getElementById('admin-notif-empty');
                if (!toastEl || !toastBody) return;

                let lastOrderId = {{ $adminLatestOrderId ?? 0 }};
                let notified = [];

                function renderDropdown() {
                    if (!notifList || !notifCount || !notifLabel || !notifEmpty) return;

                    notifList.querySelectorAll('.admin-notif-item').forEach((el) => el.remove());
                    notifCount.style.display = notified.length ? 'inline' : 'none';
                    notifCount.textContent = notifLabel.textContent = notified.length;
                    notifEmpty.style.display = notified.length ? 'none' : 'block';

                    notified.slice(-5).reverse().forEach((order) => {
                        const li = document.createElement('li');
                        li.className = 'admin-notif-item px-3 py-2 small border-top';
                        li.innerHTML = `<div class="fw-semibold">#${order.id} • Rp ${order.total}</div><div class="text-muted">${order.customer} • ${order.time}</div>`;
                        notifList.insertBefore(li, notifList.children[notifList.children.length - 1]);
                    });
                }

                const poll = async () => {
                    try {
                        const res = await fetch('{{ route('admin.notifications.latest-order') }}?last_id=' + lastOrderId, {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) return;

                        const data = await res.json();
                        if (data.has_new) {
                            lastOrderId = data.last_id;
                            toastBody.innerHTML = `Order #${data.order.id} • ${data.order.customer} • Rp ${data.order.total}`;
                            toastEl.style.display = 'block';
                            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                            toast.show();

                            notified.push({
                                id: data.order.id,
                                customer: data.order.customer,
                                total: data.order.total,
                                time: data.order.created_at_human || data.order.created_at,
                            });
                            renderDropdown();
                        }
                    } catch (err) {
                        console.error('Failed fetching latest order info', err);
                    }
                };

                renderDropdown();
                setInterval(poll, 10000);
            })();
        </script>
    @endif
@endauth
@stack('scripts')

</body>
</html>
