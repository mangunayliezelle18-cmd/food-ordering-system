@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Orders</h2>
        <p class="text-muted mb-0">Track your order and delivery status.</p>
    </div>

    <a href="{{ route('customer.menu') }}" class="btn btn-primary">
        Order Again
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

@if($orders->isEmpty())
    <div class="alert alert-info">
        No orders yet. Start ordering from the menu.
    </div>
@else
    <div class="row g-3">
        @foreach($orders as $order)
            @php
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

                $statusMessage = match($order->status) {
                    'pending' => 'Waiting for admin approval.',
                    'approved' => 'Your order has been approved.',
                    'preparing' => 'Your food is being prepared.',
                    'out_for_delivery' => 'Your order is out for delivery.',
                    'delivered' => 'Your order has been delivered.',
                    'rejected' => 'Your order was rejected.',
                    'cancelled' => 'This order was cancelled.',
                    default => 'Order status updated.',
                };

                $steps = [
                    'pending',
                    'approved',
                    'preparing',
                    'out_for_delivery',
                    'delivered',
                ];

                $currentIndex = array_search($order->status, $steps, true);

                if ($order->status === 'rejected' || $order->status === 'cancelled') {
                    $progressPercent = 0;
                } elseif ($currentIndex === false) {
                    $progressPercent = 0;
                } else {
                    $progressPercent = (($currentIndex + 1) / count($steps)) * 100;
                }
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">Order #{{ $order->id }}</h5>

                            <span class="badge {{ $badgeClass }} status-badge text-capitalize">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </div>

                        <p class="mb-1">
                            <strong>Total:</strong>
                            ₱{{ number_format($order->total_amount, 2) }}
                        </p>

                        <p class="text-muted small mb-2">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </p>

                        <p class="small mb-2">
                            {{ $statusMessage }}
                        </p>

                        @if(!in_array($order->status, ['rejected', 'cancelled'], true))
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: {{ $progressPercent }}%;"
                                     aria-valuenow="{{ $progressPercent }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        @else
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-secondary"
                                     role="progressbar"
                                     style="width: 100%;"
                                     aria-valuenow="100"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="btn btn-outline-primary btn-sm flex-fill">
                                Track Order
                            </a>

                            @if(in_array($order->status, ['pending', 'approved'], true))
                                <form method="POST"
                                      action="{{ route('customer.orders.cancel', $order) }}"
                                      class="flex-fill">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-outline-danger btn-sm w-100"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection