<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Order Management</h2>
        <p class="text-muted mb-0">Review, approve, reject, and update delivery status.</p>
    </div>
    <a href="<?php echo e(route('admin.reports.index')); ?>" class="btn btn-outline-primary">View Reports</a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <?php $__currentLoopData = \App\Models\Order::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>><?php echo e(ucfirst(str_replace('_', ' ', $status))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-secondary">Filter</button>
    </div>
</form>

<div class="card card-soft">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Rider</th><th>Proof</th><th>Date</th><th width="220">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td>₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                        <td><span class="badge bg-secondary status-badge"><?php echo e(str_replace('_', ' ', $order->status)); ?></span></td>
                        <td><?php echo e($order->rider->name ?? 'Not assigned'); ?></td>
                        <td>
                            <?php if($order->delivery_proof_path): ?>
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>#delivery-proof" class="badge bg-success text-decoration-none">Uploaded</a>
                            <?php else: ?>
                                <span class="text-muted small">None</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.orders.show', $order)); ?>">View</a>
                            <?php if($order->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('admin.orders.approve', $order)); ?>" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-success">Approve</button></form>
                                <form method="POST" action="<?php echo e(route('admin.orders.reject', $order)); ?>" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-danger">Reject</button></form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($orders->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>