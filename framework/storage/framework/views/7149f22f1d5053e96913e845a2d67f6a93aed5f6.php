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
<li class="breadcrumb-item"><a href="<?php echo e(route("service-item.index")); ?>"><?php echo app('translator')->getFromJson('fleet.serviceItems'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_service_item'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo Form::open(['route' => ['service-item.update',$service->id],'method'=>'Patch']); ?>

    <?php echo Form::hidden('user_id',Auth::user()->id); ?>

    <?php echo Form::hidden('id',$service->id); ?>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.edit_service_item'); ?>
        </h3>
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

        <div class="row">
          <div class="col-md-12">
            <?php echo Form::label('description', __('fleet.description'), ['class' => 'col-md-2 control-label']); ?>

            <div class="col-md-10">
              <?php echo Form::textarea('description', $service->description,['class' => 'form-control','required','rows'=>'3']); ?>

              <br/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                "<?php echo app('translator')->getFromJson('fleet.overdue'); ?>" <?php echo app('translator')->getFromJson('fleet.reminder_settings'); ?> <?php echo e($service->overdue_unit); ?>

                </h3>
              </div>
              <div class="card-body">
                <?php echo app('translator')->getFromJson('fleet.time_interval'); ?>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-md-3">
                    <label class="switch">
                    <input type="checkbox" name="chk1" <?php if($service->time_interval == "on"): ?> checked <?php endif; ?> id="chk1">
                    <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="form-group col-md-3">
                    <input type="number" name="time1" class="form-control" value="<?php echo e($service->overdue_time); ?>" id="time1">
                  </div>
                  <div class="col-md-4">
                    <?php echo Form::select('interval1',["day(s)"=>__('fleet.days'),
                    "week(s)"=>__('fleet.weeks'),
                    "month(s)"=>__('fleet.months'),
                    "year(s)"=>__('fleet.years')],
                    $service->overdue_unit,['class' => 'form-control']); ?>

                  </div>
                </div>
                <br>
                <?php echo app('translator')->getFromJson('fleet.meter_interval'); ?>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-md-3">
                    <label class="switch">
                      <input type="checkbox" name="chk2" <?php if($service->meter_interval == "on"): ?> checked <?php endif; ?> id="chk2">
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="form-group col-md-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                      <span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
                      <input type="number" name="meter1" class="form-control" value="<?php echo e($service->overdue_meter); ?>" id="meter1">
                    </div>
                  </div>
                  <div class="col-md-3">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                "<?php echo app('translator')->getFromJson('fleet.due_soon'); ?>" <?php echo app('translator')->getFromJson('fleet.reminder_settings'); ?> <?php echo e($service->duesoon_unit); ?>

                </h3>
              </div>
              <div class="card-body">
                <?php echo app('translator')->getFromJson('fleet.show_reminder_time'); ?>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-md-3">
                    <label class="switch">
                    <input type="checkbox" name="chk3" <?php if($service->show_time == "on"): ?> checked <?php endif; ?> id="chk3">
                    <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="form-group col-md-3">
                    <input type="number" name="time2" class="form-control" value="<?php echo e($service->duesoon_time); ?>" id="time2">
                  </div>
                  <div class="col-md-4">
                    <?php echo Form::select('interval2',["day(s)"=>__('fleet.days'),
                    "week(s)"=>__('fleet.weeks'),
                    "month(s)"=>__('fleet.months'),
                    "year(s)"=>__('fleet.years')],
                    $service->duesoon_unit,['class' => 'form-control']); ?>

                  </div>
                </div>
                <br>
                <?php echo app('translator')->getFromJson('fleet.show_reminder_meter'); ?>

                <div class="row" style="margin-top: 10px;">
                  <div class="col-md-3">
                    <label class="switch">
                    <input type="checkbox" name="chk4" <?php if($service->show_meter == "on"): ?> checked <?php endif; ?> id="chk4">
                    <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="form-group col-md-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                      <span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
                      <input type="number" name="meter2" class="form-control" value="<?php echo e($service->duesoon_meter); ?>" id="meter2">
                    </div>
                  </div>
                  <div class="col-md-3">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
        <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']); ?>

        </div>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $('#time1').on('change',function(){
    $('#chk1').prop('checked',true);
  });

  $("#time2").on('change',function(){
    $('#chk3').prop('checked',true);
  });

  $('#meter1').on('change',function(){
    $('#chk2').prop('checked',true);
  });

  $('#meter2').on('change',function(){
    $('#chk4').prop('checked',true);
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/service_items/edit.blade.php ENDPATH**/ ?>