<?php if(!empty($advance)): ?>
<table class="table table-striped">
    <?php $__currentLoopData = $advance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <th><?php echo e($adv->param_name->label); ?></th>
        <th>
            <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($adv->value,2)); ?>

        </th>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <tr style="border-top:2px solid #02bcd1">
        <th>Grand Total</th>
        <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($gtotal,2)); ?></th>
    </tr>
</table>
<?php else: ?>
<span>Advance to <?php echo e($transaction->booking->driver->name); ?> is <span class="badge badge-danger"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($transaction->total); ?></span> for this booking. You need to <span class="badge badge-success">Mark as Complete</span>in <strong>Bookings</strong> to view the adjusted details.</span>
<?php endif; ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/advance_for.blade.php ENDPATH**/ ?>