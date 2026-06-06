<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Menu Management</h2>
        <p class="text-muted mb-0">Create, update, and delete menu items.</p>
    </div>
    <a class="btn btn-primary" href="<?php echo e(route('admin.menu.create')); ?>">Add Item</a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search menu...">
        <button class="btn btn-outline-secondary">Search</button>
    </div>
</form>

<div class="card card-soft">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th width="90">Image</th><th>Name</th><th>Category</th><th>Price</th><th>Available</th><th width="180">Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($item->image_url): ?>
                                <img src="<?php echo e(str_starts_with($item->image_url, 'http') ? $item->image_url : asset(ltrim($item->image_url, '/'))); ?>"
                                     alt="<?php echo e($item->name); ?>"
                                     class="rounded"
                                     style="width: 70px; height: 55px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 55px;">🍽️</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo e($item->name); ?></strong><br><span class="text-muted small"><?php echo e(Str::limit($item->description, 60)); ?></span></td>
                        <td><?php echo e($item->category); ?></td>
                        <td>₱<?php echo e(number_format($item->price, 2)); ?></td>
                        <td><span class="badge bg-<?php echo e($item->is_available ? 'success' : 'secondary'); ?>"><?php echo e($item->is_available ? 'Yes' : 'No'); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('admin.menu.edit', $item)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.menu.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Delete this item?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No menu items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($items->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\Downloads\food-ordering-system-BASE64-PROOF-FIX-ALL\base64fix\resources\views/admin/menu/index.blade.php ENDPATH**/ ?>