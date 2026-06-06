<?php $__env->startSection('card'); ?>
<div class="text-center mb-4">
    <h2 class="fw-bold mb-1">Welcome Back</h2>
    <p class="text-muted mb-0">Login to order food or manage the system.</p>
</div>

<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input name="email" type="email" value="<?php echo e(old('email')); ?>" class="form-control" required autofocus autocomplete="username">
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input name="password" type="password" class="form-control" required autocomplete="current-password">
    </div>
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
    </div>
    <button class="btn btn-primary w-100 py-2 fw-bold">Login</button>
</form>

<p class="text-center mt-4 mb-0">
    No account yet? <a href="<?php echo e(route('register')); ?>">Create account</a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\leeji\OneDrive\Desktop\food-ordering-system\resources\views/auth/login.blade.php ENDPATH**/ ?>