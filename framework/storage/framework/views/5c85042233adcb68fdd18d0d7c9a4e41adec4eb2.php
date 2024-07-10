<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  /* Hide default HTML checkbox */
  .switch input {display:none;}

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo e(link_to_route('vehicle-types.index', __('fleet.vehicle_types'))); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_vehicle_type'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.add_vehicle_type'); ?></h3>
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

      <?php echo Form::open(['route' => 'vehicle-types.store','method'=>'post','files'=>true]); ?>

      <div class="row">
        <div class="form-group col-md-6">
          <?php echo Form::label('vehicletype', __('fleet.vehicle_type'), ['class' => 'form-label']); ?>

          <?php echo Form::text('vehicletype', null,['class' => 'form-control','required']); ?>

        </div>

        <div class="form-group col-md-6">
          <?php echo Form::label('displayname', __('fleet.displayname'), ['class' => 'form-label']); ?>

          <?php echo Form::text('displayname', null,['class' => 'form-control','required']); ?>

        </div>

        <div class="form-group col-md-4">
          <?php echo Form::label('icon', __('fleet.icon_img'), ['class' => 'form-label']); ?>

          <br>
          <?php echo Form::file('icon',null,['class' => 'form-control']); ?>

        </div>

        

        <div class="form-group col-md-4" style="margin-top: 30px">
          <div class="row">
            <div class="col-md-3">
              <label class="switch">
                <input type="checkbox" name="isenable" value="1">
                <span class="slider round"></span>
              </label>
            </div>
            <div class="col-md-9" style="margin-top: 5px">
              <h4><?php echo app('translator')->getFromJson('fleet.isenable'); ?></h4>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group col-md-4">
            <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

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
  //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/vehicle_types/create.blade.php ENDPATH**/ ?>