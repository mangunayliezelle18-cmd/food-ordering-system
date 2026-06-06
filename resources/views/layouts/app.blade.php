<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Food Ordering System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .navbar-brand { font-weight: 800; }
        .hero { background: linear-gradient(135deg, #dc2626, #f97316); color: white; border-radius: 24px; }
        .card-soft { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); }
        .status-badge { text-transform: capitalize; }
        .menu-card { height: 100%; transition: .2s ease; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 14px 34px rgba(15,23,42,.12); }
        .price { font-size: 1.2rem; font-weight: 800; color: #dc2626; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">🍽️ Food Ordering</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.menu.index') }}">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a></li>
                    @elseif(auth()->user()->role === 'rider')
                        <li class="nav-item"><a class="nav-link" href="{{ route('rider.dashboard') }}">Rider Dashboard</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.menu') }}">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.orders.index') }}">My Orders</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('menu.public') }}">Menu</a></li>
                    <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
