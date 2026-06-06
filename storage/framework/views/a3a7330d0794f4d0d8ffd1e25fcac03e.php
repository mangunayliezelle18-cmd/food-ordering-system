<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Admin Dashboard</h2>
        <p class="text-muted mb-0">Overview of menu items, orders, and sales.</p>
    </div>
    <a href="<?php echo e(route('admin.menu.create')); ?>" class="btn btn-primary">Add Menu Item</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Menu Items</span><h3 class="fw-bold mb-0"><?php echo e($totalMenu); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Total Orders</span><h3 class="fw-bold mb-0"><?php echo e($totalOrders); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Pending Orders</span><h3 class="fw-bold mb-0"><?php echo e($pending); ?></h3></div></div>
    <div class="col-md-6 col-lg-3"><div class="card card-soft p-3"><span class="text-muted">Total Sales</span><h3 class="fw-bold mb-0">₱<?php echo e(number_format($totalSales, 2)); ?></h3></div></div>
</div>

<div class="card card-soft">
    <div class="card-header bg-white fw-bold">Recent Orders</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td>₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                        <td><span class="badge bg-secondary status-badge"><?php echo e(str_replace('_', ' ', $order->status)); ?></span></td>
                        <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\OneDrive\Desktop\food-ordering-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>