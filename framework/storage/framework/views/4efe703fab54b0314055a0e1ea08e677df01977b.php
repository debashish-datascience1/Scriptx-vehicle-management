<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("service-reminder.index")); ?>"><?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_service_reminder'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?></h3>
      </div>
      <?php echo Form::open(['route' => 'service-reminder.store','method'=>'post']); ?>

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
        <div class="row">
        <div class="form-group col-md-6">
          <?php echo Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']); ?>

          <select id="vehicle_id" name="vehicle_id" class="form-control" required>
            <option value="">-</option>
            <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="form-group col-md-6">
          <?php echo Form::label('start_date', __('fleet.start_date'), ['class' => 'form-label']); ?>

          <div class="input-group date">
            <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></span></div>
            <?php echo Form::text('start_date',Helper::indianDateFormat(),['class'=>'form-control','required','id'=>'start_date','readonly']); ?>

          </div>
        </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead class="thead-inverse">
              <tr>
                <th>
                </th>
                <th><?php echo app('translator')->getFromJson('fleet.description'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.service_interval'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.create_reminder'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td>
                <input type="checkbox" name="chk[]" value="<?php echo e($service->id); ?>" class="flat-red">
                </td>
                <td>
                <?php echo e($service->description); ?>

                </td>
                <td>
                <?php echo e($service->overdue_time); ?> <?php echo e($service->overdue_unit); ?>

                <?php if($service->overdue_meter != null): ?>
                <?php echo app('translator')->getFromJson('fleet.or'); ?> <?php echo e($service->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                <?php endif; ?>
                </td>
                <td>
                <?php echo e($service->duesoon_time); ?> <?php echo e($service->duesoon_unit); ?> <?php echo app('translator')->getFromJson('fleet.before_due'); ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

        </div>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#vehicle_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
    //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

  $('#start_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/service_reminder/create.blade.php ENDPATH**/ ?>