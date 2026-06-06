<?php $__env->startSection('content'); ?>
<div class="row justify-content-center py-5">
    <div class="col-md-7 col-lg-5">
        <div class="card card-soft">
            <div class="card-body p-4 p-md-5">
                <?php echo $__env->yieldContent('card'); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\OneDrive\Desktop\food-ordering-system\resources\views/layouts/guest.blade.php ENDPATH**/ ?>