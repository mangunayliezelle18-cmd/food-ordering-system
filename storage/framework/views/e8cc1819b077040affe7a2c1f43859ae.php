<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="fw-bold mb-1">Menu</h2>
        <p class="text-muted mb-0">Choose food items and submit your order.</p>
    </div>
    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('customer.orders.index')); ?>" class="btn btn-outline-primary">View My Orders</a>
    <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login to Order</a>
    <?php endif; ?>
</div>

<form method="GET" action="<?php echo e(auth()->check() ? route('customer.menu') : route('menu.public')); ?>" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search food, category, or description...">
        <button class="btn btn-outline-secondary">Search</button>
    </div>
</form>

<?php if($items->isEmpty()): ?>
    <div class="alert alert-info">No available menu items yet.</div>
<?php else: ?>
<form method="POST" action="<?php echo e(route('customer.orders.store')); ?>" id="orderForm">
    <?php echo csrf_field(); ?>

    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h4 class="fw-bold mt-4 mb-3"><?php echo e($category ?: 'Uncategorized'); ?></h4>
        <div class="row g-3 mb-4">
            <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-soft menu-card">
                        <?php if($item->image_url): ?>
                            <img src="<?php echo e(str_starts_with($item->image_url, 'http') ? $item->image_url : asset(ltrim($item->image_url, '/'))); ?>"
                                 class="card-img-top"
                                 style="height: 190px; object-fit: cover;"
                                 alt="<?php echo e($item->name); ?>">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center rounded-top" style="height: 190px;">
                                <span class="text-muted">No Image Available</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-2">
                                <h5 class="card-title fw-bold"><?php echo e($item->name); ?></h5>
                                <span class="badge bg-success align-self-start">Available</span>
                            </div>
                            <p class="text-muted small" style="min-height: 44px;"><?php echo e($item->description ?: 'No description provided.'); ?></p>
                            <div class="price mb-3">₱<?php echo e(number_format($item->price, 2)); ?></div>

                            <input type="hidden" name="items[<?php echo e($item->id); ?>][id]" value="<?php echo e($item->id); ?>">
                            <label class="form-label small fw-semibold">Quantity</label>
                            <input type="number" name="items[<?php echo e($item->id); ?>][quantity]" value="0" min="0" class="form-control qty-input" data-price="<?php echo e($item->price); ?>">
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="card card-soft position-sticky bottom-0 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delivery Address</label>
                    <input name="delivery_address" value="<?php echo e(old('delivery_address')); ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Contact Number</label>
                    <input name="contact_number" value="<?php echo e(old('contact_number')); ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Notes</label>
                    <input name="notes" value="<?php echo e(old('notes')); ?>" class="form-control" placeholder="Optional">
                </div>
                <div class="col-md-2 d-grid">
                    <div class="fw-bold mb-2">Total: ₱<span id="totalAmount">0.00</span></div>
                    <button class="btn btn-success fw-bold" <?php if(auth()->guard()->guest()): ?> disabled <?php endif; ?>>Place Order</button>
                </div>
            </div>
            <?php if(auth()->guard()->guest()): ?>
                <p class="text-danger small mb-0 mt-2">Please login first before placing an order.</p>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php endif; ?>

<script>
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalAmount = document.getElementById('totalAmount');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            const qty = parseInt(input.value || '0');
            const price = parseFloat(input.dataset.price || '0');
            if (qty > 0) total += qty * price;
        });
        if (totalAmount) totalAmount.textContent = total.toFixed(2);
    }

    qtyInputs.forEach(input => input.addEventListener('input', updateTotal));
    updateTotal();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/customer/menu.blade.php ENDPATH**/ ?>