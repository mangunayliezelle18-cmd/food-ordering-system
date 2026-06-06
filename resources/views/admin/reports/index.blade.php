@extends('layouts.app')

@section('content')
<style>
    .analytics-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .metric-card {
        border: 0;
        border-radius: 18px;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
    }

    .bar-track {
        height: 14px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #22c55e);
        border-radius: 999px;
    }

    .status-fill {
        height: 100%;
        background: linear-gradient(90deg, #f97316, #ef4444);
        border-radius: 999px;
    }

    .trend-wrap {
        height: 170px;
        display: flex;
        align-items: end;
        gap: 14px;
        padding-top: 20px;
    }

    .trend-bar {
        flex: 1;
        min-height: 8px;
        border-radius: 10px 10px 0 0;
        background: linear-gradient(180deg, #6366f1, #14b8a6);
    }

    .trend-label {
        font-size: 11px;
        color: #64748b;
        text-align: center;
        margin-top: 8px;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Auto-Generated Reports</h2>
        <p class="text-muted mb-0">System summary, sales, order status, recent orders, and best-selling analytics.</p>
    </div>

    <div class="d-flex gap-2">
        <a class="btn btn-danger" href="{{ route('admin.reports.pdf') }}">Download PDF Report</a>
        <a class="btn btn-primary" href="{{ route('admin.reports.import.create') }}">Import CSV</a>
        <a class="btn btn-success" href="{{ route('admin.reports.csv') }}">Export CSV Report</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Sales</span><h3 class="fw-bold mb-0">₱{{ number_format($totalSales, 2) }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Orders</span><h3 class="fw-bold mb-0">{{ $totalOrders }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Customers</span><h3 class="fw-bold mb-0">{{ $totalCustomers }}</h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Menu Items</span><h3 class="fw-bold mb-0">{{ $totalMenuItems }}</h3></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card analytics-card h-100">
            <div class="card-header bg-white fw-bold">Best-Selling Items Graph</div>
            <div class="card-body">
                @forelse($bestSelling as $item)
                    @php
                        $percent = min(100, round(($item->total_qty / $bestSellingMax) * 100));
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">{{ $item->menuItem->name ?? 'Deleted item' }}</span>
                            <span class="text-muted">{{ $item->total_qty }} sold</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No sales data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card analytics-card h-100">
            <div class="card-header bg-white fw-bold">Order Status Analytics</div>
            <div class="card-body">
                @forelse($byStatus as $status)
                    @php
                        $percent = min(100, round(($status->count / $statusMax) * 100));
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                            <span class="text-muted">{{ $status->count }}</span>
                        </div>
                        <div class="bar-track">
                            <div class="status-fill" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No order status data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card analytics-card mt-4">
    <div class="card-header bg-white fw-bold">Sales Trend</div>
    <div class="card-body">
        @if($salesByDate->count())
            <div class="trend-wrap">
                @foreach($salesByDate as $day)
                    @php
                        $height = max(8, round(($day->total_sales / $salesByDateMax) * 150));
                    @endphp
                    <div class="flex-fill">
                        <div class="trend-bar" style="height: {{ $height }}px" title="₱{{ number_format($day->total_sales, 2) }}"></div>
                        <div class="trend-label">{{ \Carbon\Carbon::parse($day->report_date)->format('M d') }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">No sales trend data yet.</p>
        @endif
    </div>
</div>

<div class="card analytics-card mt-4">
    <div class="card-header bg-white fw-bold">Recent Orders</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recent as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No recent orders.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
