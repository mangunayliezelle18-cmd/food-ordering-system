@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="fw-bold mb-1">Menu</h2>
        <p class="text-muted mb-0">Choose food items and submit your order.</p>
    </div>
    @auth
        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary">View My Orders</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">Login to Order</a>
    @endauth
</div>

<form method="GET" action="{{ auth()->check() ? route('customer.menu') : route('menu.public') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search food, category, or description...">
        <button class="btn btn-outline-secondary">Search</button>
    </div>
</form>

@if($items->isEmpty())
    <div class="alert alert-info">No available menu items yet.</div>
@else
<form method="POST" action="{{ route('customer.orders.store') }}" id="orderForm">
    @csrf

    @foreach($items as $category => $group)
        <h4 class="fw-bold mt-4 mb-3">{{ $category ?: 'Uncategorized' }}</h4>
        <div class="row g-3 mb-4">
            @foreach($group as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-soft menu-card">
                        @if($item->image_url)
                            <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset(ltrim($item->image_url, '/')) }}"
                                 class="card-img-top"
                                 style="height: 190px; object-fit: cover;"
                                 alt="{{ $item->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded-top" style="height: 190px;">
                                <span class="text-muted">No Image Available</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-2">
                                <h5 class="card-title fw-bold">{{ $item->name }}</h5>
                                <span class="badge bg-success align-self-start">Available</span>
                            </div>
                            <p class="text-muted small" style="min-height: 44px;">{{ $item->description ?: 'No description provided.' }}</p>
                            <div class="price mb-3">₱{{ number_format($item->price, 2) }}</div>

                            <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                            <label class="form-label small fw-semibold">Quantity</label>
                            <input type="number" name="items[{{ $item->id }}][quantity]" value="0" min="0" class="form-control qty-input" data-price="{{ $item->price }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="card card-soft position-sticky bottom-0 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delivery Address</label>
                    <input name="delivery_address" value="{{ old('delivery_address') }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Contact Number</label>
                    <input name="contact_number" value="{{ old('contact_number') }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Notes</label>
                    <input name="notes" value="{{ old('notes') }}" class="form-control" placeholder="Optional">
                </div>
                <div class="col-md-2 d-grid">
                    <div class="fw-bold mb-2">Total: ₱<span id="totalAmount">0.00</span></div>
                    <button class="btn btn-success fw-bold" @guest disabled @endguest>Place Order</button>
                </div>
            </div>
            @guest
                <p class="text-danger small mb-0 mt-2">Please login first before placing an order.</p>
            @endguest
        </div>
    </div>
</form>
@endif

<script>
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalAmount = document.getElementById('totalAmount');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            const qty = parseInt(input.value || '0');
            const price = parseFloat(input.dataset.price || '0');
            if (qty > 0) total += qty * price;
        });
        if (totalAmount) totalAmount.textContent = total.toFixed(2);
    }

    qtyInputs.forEach(input => input.addEventListener('input', updateTotal));
    updateTotal();
</script>
@endsection
