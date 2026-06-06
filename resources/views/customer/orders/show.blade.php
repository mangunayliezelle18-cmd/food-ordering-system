@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Order #{{ $order->id }}</h2>
        <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
        Back to Orders
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@php
    $steps = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'preparing' => 'Preparing',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
    ];

    $statusOrder = array_keys($steps);
    $currentIndex = array_search($order->status, $statusOrder, true);

    $badgeClass = match($order->status) {
        'pending' => 'bg-warning text-dark',
        'approved' => 'bg-info text-dark',
        'preparing' => 'bg-primary',
        'out_for_delivery' => 'bg-dark',
        'delivered' => 'bg-success',
        'rejected' => 'bg-danger',
        'cancelled' => 'bg-secondary',
        default => 'bg-primary',
    };
@endphp

<div class="card card-soft mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Delivery Tracking</h4>
                <p class="text-muted mb-0">Track the progress of your food order.</p>
            </div>

            <span class="badge {{ $badgeClass }} status-badge text-capitalize px-3 py-2">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
        </div>

        @if($order->status === 'rejected')
            <div class="alert alert-danger mb-0">
                Your order has been rejected by the admin.
            </div>
        @elseif($order->status === 'cancelled')
            <div class="alert alert-secondary mb-0">
                This order has been cancelled.
            </div>
        @else
            <div class="tracking-wrapper">
                @foreach($steps as $key => $label)
                    @php
                        $stepIndex = array_search($key, $statusOrder, true);
                        $isActive = $currentIndex !== false && $stepIndex <= $currentIndex;
                        $isCurrent = $order->status === $key;
                    @endphp

                    <div class="tracking-step {{ $isActive ? 'active' : '' }}">
                        <div class="tracking-circle">
                            @if($isActive)
                                ✓
                            @else
                                {{ $stepIndex + 1 }}
                            @endif
                        </div>

                        <div class="tracking-label {{ $isCurrent ? 'fw-bold text-primary' : '' }}">
                            {{ $label }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                @if($order->status === 'pending')
                    <div class="alert alert-warning mb-0">
                        Your order is waiting for admin approval.
                    </div>
                @elseif($order->status === 'approved')
                    <div class="alert alert-info mb-0">
                        Your order has been approved and will be prepared soon.
                    </div>
                @elseif($order->status === 'preparing')
                    <div class="alert alert-primary mb-0">
                        Your food is now being prepared.
                    </div>
                @elseif($order->status === 'out_for_delivery')
                    <div class="alert alert-dark mb-0">
                        Your order is now out for delivery.
                    </div>
                @elseif($order->status === 'delivered')
                    <div class="alert alert-success mb-0">
                        Your order has been delivered. Enjoy your meal!
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-soft">
            <div class="card-header bg-white fw-bold">Items</div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $item->menuItem->name ?? 'Deleted item' }}
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>₱{{ number_format($order->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Order Summary</h5>

                <p>
                    <strong>Status:</strong>
                    <span class="badge {{ $badgeClass }} status-badge text-capitalize">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </p>

                <p>
                    <strong>Total:</strong>
                    ₱{{ number_format($order->total_amount, 2) }}
                </p>

                <p>
                    <strong>Address:</strong><br>
                    {{ $order->delivery_address }}
                </p>

                <p>
                    <strong>Contact:</strong><br>
                    {{ $order->contact_number }}
                </p>

                @if($order->notes)
                    <p>
                        <strong>Notes:</strong><br>
                        {{ $order->notes }}
                    </p>
                @endif

                @if($order->delivery_proof_path)
                    <hr>
                    <p>
                        <strong>Delivery Proof:</strong><br>
                        <img src="{{ $order->delivery_proof_url }}" alt="Delivery Proof" class="img-fluid rounded border mt-2">
                    </p>
                    <p class="mb-0">
                        <strong>Delivered At:</strong><br>
                        {{ $order->delivered_at?->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                @endif

                @if(in_array($order->status, ['pending', 'approved'], true))
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wrapper {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        position: relative;
    }

    .tracking-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .tracking-step:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 4px;
        background: #dee2e6;
        z-index: 0;
    }

    .tracking-step.active:not(:last-child)::after {
        background: #0d6efd;
    }

    .tracking-circle {
        width: 42px;
        height: 42px;
        margin: 0 auto 10px;
        border-radius: 50%;
        background: #dee2e6;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        position: relative;
        z-index: 1;
    }

    .tracking-step.active .tracking-circle {
        background: #0d6efd;
        color: #fff;
    }

    .tracking-label {
        font-size: 14px;
        color: #495057;
    }

    @media (max-width: 768px) {
        .tracking-wrapper {
            flex-direction: column;
            gap: 18px;
        }

        .tracking-step {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .tracking-step:not(:last-child)::after {
            display: none;
        }

        .tracking-circle {
            margin: 0;
        }
    }
</style>
@endsection