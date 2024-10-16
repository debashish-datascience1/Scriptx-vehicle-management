<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('loan-take.index')); ?>"><?php echo app('translator')->getFromJson('fleet.loan_take'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.return_loan'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.return_loan'); ?></h3>
      </div>

      <div class="card-body">
        <?php if(count($errors) > 0): ?>
          <div class="alert alert-danger">
            <ul>
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php echo Form::open(['route' => ['loan-take.process-return', $loanTake->id], 'method' => 'POST']); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('from', __('fleet.from'), ['class' => 'form-label']); ?>

              <?php echo Form::text('from', $loanTake->from, ['class' => 'form-control', 'readonly']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('date', __('fleet.return_date'), ['class' => 'form-label']); ?>

              <?php echo Form::date('date', null, ['class' => 'form-control', 'required']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('amount', __('fleet.return_amount'), ['class' => 'form-label']); ?>

              <?php echo Form::number('amount', null, ['class' => 'form-control', 'required', 'step' => 'any']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('remaining_amount', __('fleet.remaining_amount'), ['class' => 'form-label']); ?>

              <?php echo Form::number('remaining_amount', $loanTake->remaining_amount, ['class' => 'form-control', 'readonly']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']); ?>

          </div>
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/loan_take/return.blade.php ENDPATH**/ ?>