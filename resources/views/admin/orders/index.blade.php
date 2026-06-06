@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Order Management</h2>
        <p class="text-muted mb-0">Review, approve, reject, and update delivery status.</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">View Reports</a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            @foreach(\App\Models\Order::STATUSES as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary">Filter</button>
    </div>
</form>

<div class="card card-soft">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Rider</th><th>Proof</th><th>Date</th><th width="220">Actions</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="badge bg-secondary status-badge">{{ str_replace('_', ' ', $order->status) }}</span></td>
                        <td>{{ $order->rider->name ?? 'Not assigned' }}</td>
                        <td>
                            @if($order->delivery_proof_path)
                                <a href="{{ route('admin.orders.show', $order) }}#delivery-proof" class="badge bg-success text-decoration-none">Uploaded</a>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">View</a>
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('admin.orders.approve', $order) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success">Approve</button></form>
                                <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger">Reject</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $orders->links() }}</div>
</div>
@endsection
