
@props(['title' => 'Products'])

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
</head>
<body>

 <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        {{-- Brand menuju home --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            Products
        </a>

        {{-- Link kanan --}}
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a 
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                        href="{{ route('home') }}"
                    >
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}" 
                        href="{{ route('products') }}"
                    >
                        Products
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    {{-- MAIN CONTAINER --}}
    <div class="container mb-5">

        
        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.transition = "opacity 0.5s";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 1000); 
            </script>
        @endif

        
        {{ $slot }}
    </div>

    {{-- Bootstrap JS --}}
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
