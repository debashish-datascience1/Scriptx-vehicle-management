<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("daily-advance.index")); ?>"><?php echo app('translator')->getFromJson('fleet.daily_advance'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_daily_advance'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.edit_daily_advance'); ?></h3>
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

        <?php echo Form::model($dailyAdvance,['route' => ['daily-advance.update',$dailyAdvance->id],'files'=>true,'method'=>'PATCH']); ?>

        <?php echo Form::hidden('id',$dailyAdvance->id); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('driver_id', 'Driver', ['class' => 'form-label required']); ?>

              <?php echo Form::select('driver_id',$drivers,$dailyAdvance->driver_id,['class' => 'form-control drivers','required','id'=>'driver_id','readonly','style'=>'pointer-events:none']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('date', 'Date', ['class' => 'form-label required']); ?>

              <?php echo Form::text('date',Helper::indianDateFormat($dailyAdvance->date),['class' => 'form-control','id'=>'date','required','readonly']); ?>

            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                <?php echo Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']); ?>

                <?php echo Form::text('amount', $dailyAdvance->amount,['class' => 'form-control','required','onkeypress'=>'return isNumber(event)']); ?>

              </div>
          </div>
          <div class="col-md-12">
            <div class="form-group remarks">
              <?php echo Form::label('remarks', 'Remarks', ['class' => 'form-label required']); ?>

              <?php echo Form::textarea('remarks',$dailyAdvance->remarks,['class' => 'form-control remarks','style'=>'resize:none;height:100px;']); ?>

            </div>
          </div>
            <div class="col-md-12">
            <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

            </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
});
function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/daily_advance/edit.blade.php ENDPATH**/ ?>