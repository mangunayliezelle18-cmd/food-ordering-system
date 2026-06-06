<?php $__env->startSection('content'); ?>
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
            <h3 class="fw-bold mb-0"><?php echo e($activeDeliveries); ?></h3>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-soft p-4">
            <span class="text-muted">My Delivered Orders</span>
            <h3 class="fw-bold mb-0"><?php echo e($myDelivered); ?></h3>
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
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $badgeClass = match($order->status) {
                            'approved' => 'bg-info text-dark',
                            'preparing' => 'bg-primary',
                            'out_for_delivery' => 'bg-dark',
                            'delivered' => 'bg-success',
                            default => 'bg-secondary',
                        };
                    ?>

                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td><?php echo e($order->delivery_address); ?></td>
                        <td>
                            <span class="badge <?php echo e($badgeClass); ?> status-badge">
                                <?php echo e(str_replace('_', ' ', $order->status)); ?>

                            </span>
                        </td>
                        <td><?php echo e($order->rider->name ?? 'Not assigned'); ?></td>
                        <td>
                            <a href="<?php echo e(route('rider.orders.show', $order)); ?>" class="btn btn-sm btn-outline-primary">
                                View / Update
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No delivery orders available.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3">
        <?php echo e($orders->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/rider/dashboard.blade.php ENDPATH**/ ?>