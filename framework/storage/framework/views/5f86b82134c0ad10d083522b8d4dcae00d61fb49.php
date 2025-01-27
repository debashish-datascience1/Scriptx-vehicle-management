<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('fuel_purchase.index')); ?>"><?php echo app('translator')->getFromJson('fleet.fuel_purchase'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_fuel_purchase'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.add_fuel_purchase'); ?></h3>
      </div>

      <?php echo Form::open(['route' => 'fuel_purchase.store', 'method' => 'post']); ?>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

              <?php echo Form::date('date', date('Y-m-d'), ['class' => 'form-control', 'required']); ?>

            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vendor_id', __('fleet.vendor'), ['class' => 'form-label']); ?>

              <?php echo Form::select('vendor_id', $vendors, null, ['class' => 'form-control', 'placeholder' => 'Select Vendor', 'required']); ?>

            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('fuel_type_id', __('fleet.fuel_type'), ['class' => 'form-label']); ?>

              <?php echo Form::select('fuel_type_id', $data['oil'], null, ['class' => 'form-control', 'placeholder' => 'Select Fuel Type', 'required']); ?>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('quantity', __('fleet.quantity'), ['class' => 'form-label']); ?>

              <?php echo Form::number('quantity', null, ['class' => 'form-control', 'step' => '0.01', 'required']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('amount', __('fleet.amount'), ['class' => 'form-label']); ?>

              <?php echo Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'required']); ?>

            </div>
          </div>
        </div>

        <div class="form-group">
            <?php echo Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label']); ?>

            <?php echo Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3]); ?>

        </div>
      </div>

      <div class="card-footer">
        <div class="row">
            <div class="col-md-12">
            <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-info btn-block']); ?>

            </div>
        </div>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
$(document).ready(function() {
  $('#vendor_id, #fuel_type_id').select2();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/fuel_purchase/create.blade.php ENDPATH**/ ?>