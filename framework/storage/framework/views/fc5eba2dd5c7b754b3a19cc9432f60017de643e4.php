<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
  .select2-selection{
    height: 38px !important;
  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("drivers.index")); ?>"><?php echo app('translator')->getFromJson('fleet.drivers'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.addDriver'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.addDriver'); ?></h3>
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

        <?php echo Form::open(['route' => 'drivers.store','files'=>true,'method'=>'post']); ?>

        <?php echo Form::hidden('is_active',0); ?>

        <?php echo Form::hidden('is_available',0); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label required','autofocus']); ?>

              <?php echo Form::text('first_name', null,['class' => 'form-control','required','autofocus']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('middle_name', __('fleet.middlename'), ['class' => 'form-label']); ?>

              <?php echo Form::text('middle_name', null,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']); ?>

              <?php echo Form::text('last_name', null,['class' => 'form-control','required']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.assign_vehicle'), ['class' => 'form-label']); ?>


              <select id="vehicle_id" name="vehicle_id" class="form-control" >
                <option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('address', __('fleet.address'), ['class' => 'form-label required']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                </div>
                <?php echo Form::text('address', null,['class' => 'form-control']); ?>

              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <?php echo Form::select('phone_code',$phone_code,"+91",['class' => 'form-control code','style'=>'width:80px']); ?>

                </div>
                <?php echo Form::number('phone', null,['class' => 'form-control']); ?>

              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('adphone', __('fleet.additionalPhone'), ['class' => 'form-label required']); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <?php echo Form::select('adphone_code',$phone_code,"+91",['class' => 'form-control code','style'=>'width:80px']); ?>

                </div>
                <?php echo Form::number('adphone', null,['class' => 'form-control']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('emp_id', __('fleet.employee_id'), ['class' => 'form-label']); ?>

              <?php echo Form::text('emp_id', null,['class' => 'form-control']); ?>

            </div>
          </div>
          
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('license_number', __('fleet.licenseNumber'), ['class' => 'form-label required']); ?>

              <?php echo Form::text('license_number', null,['class' => 'form-control']); ?>

            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <?php echo Form::label('issue_date', __('fleet.issueDate'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('issue_date', null,['class' => 'form-control','readonly']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('exp_date', __('fleet.expirationDate'), ['class' => 'form-label required']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('exp_date', null,['class' => 'form-control','readonly']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <?php echo Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('start_date', null,['class' => 'form-control','readonly']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <?php echo Form::label('end_date', __('fleet.leave_date'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('end_date', null,['class' => 'form-control','readonly']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('salary', __('fleet.driverSalary'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-inr"></i></span></div>
                <?php echo Form::text('salary', null,['class' => 'form-control','onkeypress'=>'return isNumber(event)']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('bank', __('fleet.bank'), ['class' => 'form-label']); ?>

              <?php echo Form::text('bank', null,['class' => 'form-control bank','placeholder'=>'Enter Bank Name','id'=>'bank']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('account_no', "Bank Account No.", ['class' => 'form-label']); ?>

              <?php echo Form::text('account_no', null,['class' => 'form-control account_no','placeholder'=>'Enter Bank Account No.','id'=>'account_no']); ?>

            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('gender', __('fleet.gender') , ['class' => 'form-label']); ?><br>
              <input type="radio" name="gender" class="flat-red gender" value="1" checked> <?php echo app('translator')->getFromJson('fleet.male'); ?><br>

              <input type="radio" name="gender" class="flat-red gender" value="0"> <?php echo app('translator')->getFromJson('fleet.female'); ?>
            </div>

            <div class="form-group">
              <?php echo Form::label('driver_image', __('fleet.driverImage'), ['class' => 'form-label']); ?>


              <?php echo Form::file('driver_image',null,['class' => 'form-control']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('documents', __('fleet.documents'), ['class' => 'form-label']); ?>

              <?php echo Form::file('documents',null,['class' => 'form-control']); ?>

            </div>


            <div class="form-group">
              <?php echo Form::label('license_image', __('fleet.licenseImage'), ['class' => 'form-label']); ?>

              <?php echo Form::file('license_image',null,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('econtact', __('fleet.emergency_details'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('econtact',null,['class' => 'form-control']); ?>

            </div>
          </div>
        </div>
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.saveDriver'), ['class' => 'btn btn-success']); ?>

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
  $('.code').select2();
  $('#vehicle_id').select2();
  $("#first_name").focus();
  $('#end_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#exp_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#issue_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#start_date').datepicker({
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

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/drivers/create.blade.php ENDPATH**/ ?>