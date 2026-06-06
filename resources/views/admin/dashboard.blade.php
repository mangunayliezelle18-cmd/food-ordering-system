@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Admin Dashboard</h2>
        <p class="text-muted mb-0">Overview of menu items, orders, and sales.</p>
    </div>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">Add Menu Item</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Menu Items</span><h3 class="fw-bold mb-0">{{ $totalMenu }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Total Orders</span><h3 class="fw-bold mb-0">{{ $totalOrders }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Pending Orders</span><h3 class="fw-bold mb-0">{{ $pending }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Total Sales</span><h3 class="fw-bold mb-0">₱{{ number_format($totalSales, 2) }}</h3></div></div>
</div>

<div class="card card-soft">
    <div class="card-header bg-white fw-bold">Recent Orders</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="badge bg-secondary status-badge">{{ str_replace('_', ' ', $order->status) }}</span></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
