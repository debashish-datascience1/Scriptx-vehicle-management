<?php $__env->startSection('title', trans('installer_messages.final.title')); ?>
<?php $__env->startSection('container'); ?>
    <p class="paragraph" style="text-align: center;">
    THANK YOU
	</p>
    <div class="buttons">
        <a href="<?php echo e(url('admin/')); ?>" class="button"><?php echo e(trans('installer_messages.final.exit')); ?></a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/laravel_web_installer/finished.blade.php ENDPATH**/ ?>