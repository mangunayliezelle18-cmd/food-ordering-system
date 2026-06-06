<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Orders</h2>
        <p class="text-muted mb-0">Track your order and delivery status.</p>
    </div>

    <a href="<?php echo e(route('customer.menu')); ?>" class="btn btn-primary">
        Order Again
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($error); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php if($orders->isEmpty()): ?>
    <div class="alert alert-info">
        No orders yet. Start ordering from the menu.
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
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
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">Order #<?php echo e($order->id); ?></h5>

                            <span class="badge <?php echo e($badgeClass); ?> status-badge text-capitalize">
                                <?php echo e(str_replace('_', ' ', $order->status)); ?>

                            </span>
                        </div>

                        <p class="mb-1">
                            <strong>Total:</strong>
                            ₱<?php echo e(number_format($order->total_amount, 2)); ?>

                        </p>

                        <p class="text-muted small mb-2">
                            <?php echo e($order->created_at->format('M d, Y h:i A')); ?>

                        </p>

                        <p class="small mb-2">
                            <?php echo e($statusMessage); ?>

                        </p>

                        <?php if(!in_array($order->status, ['rejected', 'cancelled'], true)): ?>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: <?php echo e($progressPercent); ?>%;"
                                     aria-valuenow="<?php echo e($progressPercent); ?>"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-secondary"
                                     role="progressbar"
                                     style="width: 100%;"
                                     aria-valuenow="100"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mt-auto d-flex gap-2">
                            <a href="<?php echo e(route('customer.orders.show', $order)); ?>"
                               class="btn btn-outline-primary btn-sm flex-fill">
                                Track Order
                            </a>

                            <?php if(in_array($order->status, ['pending', 'approved'], true)): ?>
                                <form method="POST"
                                      action="<?php echo e(route('customer.orders.cancel', $order)); ?>"
                                      class="flex-fill">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>

                                    <button type="submit"
                                            class="btn btn-outline-danger btn-sm w-100"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/customer/orders/index.blade.php ENDPATH**/ ?>