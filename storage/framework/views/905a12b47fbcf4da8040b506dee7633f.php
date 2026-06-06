<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Rider Delivery - Order #<?php echo e($order->id); ?></h2>
        <p class="text-muted mb-0">Upload proof once the order has been delivered.</p>
    </div>

    <a href="<?php echo e(route('rider.dashboard')); ?>" class="btn btn-outline-secondary">
        Back to Rider Dashboard
    </a>
</div>

<?php
    $badgeClass = match($order->status) {
        'approved' => 'bg-info text-dark',
        'preparing' => 'bg-primary',
        'out_for_delivery' => 'bg-dark',
        'delivered' => 'bg-success',
        'rejected' => 'bg-danger',
        'cancelled' => 'bg-secondary',
        default => 'bg-warning text-dark',
    };
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-soft mb-4">
            <div class="card-header bg-white fw-bold">Order Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Customer:</strong><br>
                        <?php echo e($order->user->name ?? 'Guest'); ?>

                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Contact Number:</strong><br>
                        <?php echo e($order->contact_number); ?>

                    </div>

                    <div class="col-12 mb-3">
                        <strong>Delivery Address:</strong><br>
                        <?php echo e($order->delivery_address); ?>

                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge <?php echo e($badgeClass); ?> status-badge">
                            <?php echo e(str_replace('_', ' ', $order->status)); ?>

                        </span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Total:</strong><br>
                        ₱<?php echo e(number_format($order->total_amount, 2)); ?>

                    </div>
                </div>

                <?php if($order->notes): ?>
                    <p class="mb-0">
                        <strong>Notes:</strong><br>
                        <?php echo e($order->notes); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card card-soft">
            <div class="card-header bg-white fw-bold">Order Items</div>

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
                                <td><?php echo e($item->menuItem->name ?? 'Deleted item'); ?></td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td>₱<?php echo e(number_format($item->price, 2)); ?></td>
                                <td>₱<?php echo e(number_format($item->subtotal, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Delivery Action</h5>

                <?php if($order->status !== 'delivered'): ?>
                    <form method="POST" action="<?php echo e(route('rider.orders.out_for_delivery', $order)); ?>" class="mb-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <button type="submit" class="btn btn-dark w-100">
                            Mark Out for Delivery
                        </button>
                    </form>

                    <form method="POST" action="<?php echo e(route('rider.orders.delivered', $order)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="mb-3">
                            <label for="delivery_proof" class="form-label fw-semibold">Delivery Proof File</label>
                            <input
                                type="file"
                                name="delivery_proof"
                                id="delivery_proof"
                                class="form-control <?php $__errorArgs = ['delivery_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                accept="image/*"
                            >

                            <input type="hidden" name="delivery_proof_base64" id="delivery_proof_base64">

                            <?php $__errorArgs = ['delivery_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Choose any photo proof. The page also sends a backup copy so admin can see the image even if normal upload fails.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Delivery Note</label>
                            <textarea name="delivery_note" class="form-control" rows="3" placeholder="Optional note for admin"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Upload Proof & Mark Delivered
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success mb-0">
                        This order has already been marked as delivered.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($order->delivery_proof_path): ?>
            <div class="card card-soft">
                <div class="card-header bg-white fw-bold">Uploaded Delivery Proof</div>
                <div class="card-body">
                    <img src="<?php echo e($order->delivery_proof_url); ?>" alt="Delivery Proof" class="img-fluid rounded border mb-3">
                    <p class="mb-1"><strong>Rider:</strong> <?php echo e($order->rider->name ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>Delivered At:</strong> <?php echo e($order->delivered_at?->format('M d, Y h:i A') ?? 'N/A'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('delivery_proof');
    const hidden = document.getElementById('delivery_proof_base64');

    if (!input || !hidden) {
        return;
    }

    input.addEventListener('change', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;

        hidden.value = '';

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            hidden.value = event.target.result;
        };

        reader.readAsDataURL(file);
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\OneDrive\Desktop\food-ordering-system\resources\views/rider/orders/show.blade.php ENDPATH**/ ?>