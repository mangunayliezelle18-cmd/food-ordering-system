@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Rider Dashboard</h2>
        <p class="text-muted mb-0">View delivery orders, upload proof, and mark orders as delivered.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-soft p-4">
            <span class="text-muted">Active Delivery Orders</span>
            <h3 class="fw-bold mb-0">{{ $activeDeliveries }}</h3>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-soft p-4">
            <span class="text-muted">My Delivered Orders</span>
            <h3 class="fw-bold mb-0">{{ $myDelivered }}</h3>
        </div>
    </div>
</div>

<div class="card card-soft">
    <div class="card-header bg-white fw-bold">Delivery Orders</div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Rider</th>
                    <th width="160">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    @php
                        $badgeClass = match($order->status) {
                            'approved' => 'bg-info text-dark',
                            'preparing' => 'bg-primary',
                            'out_for_delivery' => 'bg-dark',
                            'delivered' => 'bg-success',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ $order->delivery_address }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }} status-badge">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->rider->name ?? 'Not assigned' }}</td>
                        <td>
                            <a href="{{ route('rider.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                View / Update
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No delivery orders available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
