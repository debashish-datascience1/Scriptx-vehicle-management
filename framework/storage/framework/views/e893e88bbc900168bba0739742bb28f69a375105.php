<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("notes.index")); ?>"><?php echo app('translator')->getFromJson('fleet.notes'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_note'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.create_note'); ?></h3>
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

        <?php echo Form::open(['route' => 'notes.store','method'=>'post']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.vehicle'), ['class' => 'form-label']); ?>

              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo Form::label('customer_id',__('fleet.person_incharge'), ['class' => 'form-label']); ?>

              <select id="customer_id" name="customer_id" class="form-control" required>
                <option value="">-</option>
                <?php if(Auth::user()->user_type == "D"): ?>
                <option value="<?php echo e(Auth::user()->id); ?>" selected><?php echo e(Auth::user()->name); ?></option>
                <?php else: ?>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo Form::label('submitted_on', __('fleet.submitted_on'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></div>
                <?php echo Form::text('submitted_on',null,['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

              <?php echo Form::select('status',["Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control','required']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',null,['class'=>'form-control','size'=>'30x2','required']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.add_note'), ['class' => 'btn btn-success']); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#vehicle_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
  $('#customer_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.person_incharge'); ?>"});

  $('#submitted_on').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/notes/create.blade.php ENDPATH**/ ?>