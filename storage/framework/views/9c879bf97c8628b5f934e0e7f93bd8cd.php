<?php $__env->startSection('content'); ?>
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
        <a class="btn btn-danger" href="<?php echo e(route('admin.reports.pdf')); ?>">Download PDF Report</a>
        <a class="btn btn-primary" href="<?php echo e(route('admin.reports.import.create')); ?>">Import CSV</a>
        <a class="btn btn-success" href="<?php echo e(route('admin.reports.csv')); ?>">Export CSV Report</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Sales</span><h3 class="fw-bold mb-0">₱<?php echo e(number_format($totalSales, 2)); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Orders</span><h3 class="fw-bold mb-0"><?php echo e($totalOrders); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Total Customers</span><h3 class="fw-bold mb-0"><?php echo e($totalCustomers); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card metric-card p-4"><span class="text-muted">Menu Items</span><h3 class="fw-bold mb-0"><?php echo e($totalMenuItems); ?></h3></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card analytics-card h-100">
            <div class="card-header bg-white fw-bold">Best-Selling Items Graph</div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $bestSelling; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $percent = min(100, round(($item->total_qty / $bestSellingMax) * 100));
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold"><?php echo e($item->menuItem->name ?? 'Deleted item'); ?></span>
                            <span class="text-muted"><?php echo e($item->total_qty); ?> sold</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?php echo e($percent); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">No sales data yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card analytics-card h-100">
            <div class="card-header bg-white fw-bold">Order Status Analytics</div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $byStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $percent = min(100, round(($status->count / $statusMax) * 100));
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $status->status))); ?></span>
                            <span class="text-muted"><?php echo e($status->count); ?></span>
                        </div>
                        <div class="bar-track">
                            <div class="status-fill" style="width: <?php echo e($percent); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">No order status data yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card analytics-card mt-4">
    <div class="card-header bg-white fw-bold">Sales Trend</div>
    <div class="card-body">
        <?php if($salesByDate->count()): ?>
            <div class="trend-wrap">
                <?php $__currentLoopData = $salesByDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $height = max(8, round(($day->total_sales / $salesByDateMax) * 150));
                    ?>
                    <div class="flex-fill">
                        <div class="trend-bar" style="height: <?php echo e($height); ?>px" title="₱<?php echo e(number_format($day->total_sales, 2)); ?>"></div>
                        <div class="trend-label"><?php echo e(\Carbon\Carbon::parse($day->report_date)->format('M d')); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">No sales trend data yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card analytics-card mt-4">
    <div class="card-header bg-white fw-bold">Recent Orders</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td>₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                        <td><?php echo e(ucfirst(str_replace('_', ' ', $order->status))); ?></td>
                        <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No recent orders.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>