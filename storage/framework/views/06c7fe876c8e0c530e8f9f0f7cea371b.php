<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Order #<?php echo e($order->id); ?></h2>
        <p class="text-muted mb-0">Placed on <?php echo e($order->created_at->format('M d, Y h:i A')); ?></p>
    </div>

    <a href="<?php echo e(route('customer.orders.index')); ?>" class="btn btn-outline-secondary">
        Back to Orders
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

<?php
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
?>

<div class="card card-soft mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Delivery Tracking</h4>
                <p class="text-muted mb-0">Track the progress of your food order.</p>
            </div>

            <span class="badge <?php echo e($badgeClass); ?> status-badge text-capitalize px-3 py-2">
                <?php echo e(str_replace('_', ' ', $order->status)); ?>

            </span>
        </div>

        <?php if($order->status === 'rejected'): ?>
            <div class="alert alert-danger mb-0">
                Your order has been rejected by the admin.
            </div>
        <?php elseif($order->status === 'cancelled'): ?>
            <div class="alert alert-secondary mb-0">
                This order has been cancelled.
            </div>
        <?php else: ?>
            <div class="tracking-wrapper">
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $stepIndex = array_search($key, $statusOrder, true);
                        $isActive = $currentIndex !== false && $stepIndex <= $currentIndex;
                        $isCurrent = $order->status === $key;
                    ?>

                    <div class="tracking-step <?php echo e($isActive ? 'active' : ''); ?>">
                        <div class="tracking-circle">
                            <?php if($isActive): ?>
                                ✓
                            <?php else: ?>
                                <?php echo e($stepIndex + 1); ?>

                            <?php endif; ?>
                        </div>

                        <div class="tracking-label <?php echo e($isCurrent ? 'fw-bold text-primary' : ''); ?>">
                            <?php echo e($label); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-4">
                <?php if($order->status === 'pending'): ?>
                    <div class="alert alert-warning mb-0">
                        Your order is waiting for admin approval.
                    </div>
                <?php elseif($order->status === 'approved'): ?>
                    <div class="alert alert-info mb-0">
                        Your order has been approved and will be prepared soon.
                    </div>
                <?php elseif($order->status === 'preparing'): ?>
                    <div class="alert alert-primary mb-0">
                        Your food is now being prepared.
                    </div>
                <?php elseif($order->status === 'out_for_delivery'): ?>
                    <div class="alert alert-dark mb-0">
                        Your order is now out for delivery.
                    </div>
                <?php elseif($order->status === 'delivered'): ?>
                    <div class="alert alert-success mb-0">
                        Your order has been delivered. Enjoy your meal!
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
                            <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            <?php echo e($item->menuItem->name ?? 'Deleted item'); ?>

                                        </div>
                                    </td>
                                    <td><?php echo e($item->quantity); ?></td>
                                    <td>₱<?php echo e(number_format($item->price, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($item->subtotal, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>₱<?php echo e(number_format($order->total_amount, 2)); ?></th>
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
                    <span class="badge <?php echo e($badgeClass); ?> status-badge text-capitalize">
                        <?php echo e(str_replace('_', ' ', $order->status)); ?>

                    </span>
                </p>

                <p>
                    <strong>Total:</strong>
                    ₱<?php echo e(number_format($order->total_amount, 2)); ?>

                </p>

                <p>
                    <strong>Address:</strong><br>
                    <?php echo e($order->delivery_address); ?>

                </p>

                <p>
                    <strong>Contact:</strong><br>
                    <?php echo e($order->contact_number); ?>

                </p>

                <?php if($order->notes): ?>
                    <p>
                        <strong>Notes:</strong><br>
                        <?php echo e($order->notes); ?>

                    </p>
                <?php endif; ?>

                <?php if($order->delivery_proof_path): ?>
                    <hr>
                    <p>
                        <strong>Delivery Proof:</strong><br>
                        <img src="<?php echo e($order->delivery_proof_url); ?>" alt="Delivery Proof" class="img-fluid rounded border mt-2">
                    </p>
                    <p class="mb-0">
                        <strong>Delivered At:</strong><br>
                        <?php echo e($order->delivered_at?->format('M d, Y h:i A') ?? 'N/A'); ?>

                    </p>
                <?php endif; ?>

                <?php if(in_array($order->status, ['pending', 'approved'], true)): ?>
                    <form method="POST" action="<?php echo e(route('customer.orders.cancel', $order)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\OneDrive\Desktop\food-ordering-system\resources\views/customer/orders/show.blade.php ENDPATH**/ ?>