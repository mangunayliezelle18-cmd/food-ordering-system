@extends('layouts.app')

@section('content')
<section class="hero p-5 mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold">Food Ordering System</h1>
            <p class="lead mb-4">Browse the menu, place orders, and track delivery status in one simple Laravel system.</p>
            <a href="{{ route('menu.public') }}" class="btn btn-light btn-lg fw-bold">Browse Menu</a>
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg ms-2">Login</a>
            @endguest
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <div style="font-size: 7rem;">🍔</div>
        </div>
    </div>
</section>

<div class="row g-3">
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5>Browse Menu</h5><p class="text-muted mb-0">Customers can view available food items by category.</p></div></div>
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5>Place Orders</h5><p class="text-muted mb-0">Select items, set quantity, and submit delivery details.</p></div></div>
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5>Admin Management</h5><p class="text-muted mb-0">Admins manage menu items, orders, delivery status, and reports.</p></div></div>
</div>
@endsection
