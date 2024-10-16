<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('loan-give.index')); ?>"><?php echo app('translator')->getFromJson('fleet.loan_take'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.loan_details'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.loan_details'); ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong><?php echo app('translator')->getFromJson('fleet.from'); ?>:</strong> <?php echo e($loanTake->from); ?></p>
            <p><strong><?php echo app('translator')->getFromJson('fleet.date'); ?>:</strong> <?php echo e($loanTake->date); ?></p>
            <p><strong><?php echo app('translator')->getFromJson('fleet.amount'); ?>:</strong> <?php echo e(number_format($loanTake->amount, 2)); ?></p>
            <p><strong><?php echo app('translator')->getFromJson('fleet.remaining_amount'); ?>:</strong> <?php echo e(number_format($loanTake->remaining_amount, 2)); ?></p>
          </div>
        </div>
        <hr>
        <h4><?php echo app('translator')->getFromJson('fleet.collect_history'); ?></h4>
        <table class="table">
          <thead>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $loanTake->returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($return->date); ?></td>
              <td><?php echo e(number_format($return->amount, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/loan_give/show.blade.php ENDPATH**/ ?>