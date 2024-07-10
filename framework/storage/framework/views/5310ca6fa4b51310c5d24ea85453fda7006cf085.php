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
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("fuel.index")); ?>"><?php echo app('translator')->getFromJson('fleet.fuel'); ?></a></li>
<li class="breadcrumb-item active"> <?php echo app('translator')->getFromJson('fleet.edit_fuel'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.edit_fuel'); ?>
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

        <?php echo Form::open(['route' => ['fuel.update',$data->id],'method'=>'PATCH']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('vehicle_id',$vehicle_id); ?>

        <?php echo Form::hidden('id',$data->id); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']); ?>

              
              <select id="vehicle_id"  name="vehicle_id" class="form-control xxhvk" required>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>" <?php if($data->vehicle_id == $vehicle->id): ?> selected <?php endif; ?>><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?> </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
              </select>
            </div>
            <div class="form-group">
              <?php echo Form::label('date',__('fleet.date'), ['class' => 'form-label']); ?>

              <div class='input-group'>
                <div class="input-group-prepend">
                  <span class="input-group-text"><span class="fa fa-calendar"></span>
                </div>
                <?php echo Form::text('date',$data->date,['class'=>'form-control','required']); ?>

              </div>
            </div>

            <div class="form-group">
              <?php echo Form::label('start_meter',__('fleet.start_meter'), ['class' => 'form-label']); ?>

              <?php echo Form::number('start_meter',$data->start_meter,['class'=>'form-control','required']); ?>

              <small><?php echo app('translator')->getFromJson('fleet.meter_reading'); ?></small>
            </div>

            <div class="form-group">
              <?php echo Form::label('reference',__('fleet.reference'), ['class' => 'form-label']); ?>

              <?php echo Form::text('reference',$data->reference,['class'=>'form-control']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('province',__('fleet.province'), ['class' => 'form-label']); ?>

              <?php echo Form::text('province',$data->province,['class'=>'form-control']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::text('note',$data->note,['class'=>'form-control']); ?>

            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <h4><?php echo app('translator')->getFromJson('fleet.complete_fill_up'); ?></h4>
              </div>
              <div class="col-md-6">
                <label class="switch">
                  <input type="checkbox" name="complete" value="1" <?php if($data->complete == '1'): ?>checked <?php endif; ?>>
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-solid" >
              <div class="card-header">
                <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.fuel_coming_from'); ?></h3>
              </div>
              <div class="card-body">
                <select id="vendor_name" name="vendor_name" class="form-control" required>
                  <option value="">-</option>
                  <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($vendor->id); ?>" <?php if($data->vendor_name == $vendor->id): ?> selected <?php endif; ?>> <?php echo e($vendor->name); ?> </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="card card-solid" >
              <div class="card-header">
                <h3 class="card-title">
                  <?php echo app('translator')->getFromJson('fleet.fuel'); ?>
                </h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <?php echo Form::label('fuel_type',__('fleet.fuelType'), ['class' => 'form-label']); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-exclamation"></i></span></div>
                    
                    <?php echo Form::select('fuel_type',$oil,$data->fuel_type,['class'=>'form-control','placeholder'=>'Choose fuel type','required']); ?>

                  </div>
                </div>
                <div class="form-group">
                  <?php echo Form::label('qty',__('fleet.qty').' ('.Hyvikk::get('fuel_unit').')', ['class' => 'form-label']); ?>

                  <?php echo Form::text('qty',$data->qty,['class'=>'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('cost_per_unit',__('fleet.cost_per_unit'), ['class' => 'form-label']); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                    <?php echo Form::text('cost_per_unit',$data->cost_per_unit,['class'=>'form-control']); ?>

                  </div>
                </div>
                <small class="smallfuel">Total Fuel Price &nbsp;<span class="fa fa-inr"></span>&nbsp;<span class="fueltot"><?php echo e(number_format($data->qty * $data->cost_per_unit,2,'.','')); ?></span></small>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']); ?>

          </div>
        </div>
      </div>
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
  $("#vehicle_id").select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
  $("#vendor_name").select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.select_fuel_vendor'); ?>"});

  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $("#date").on("dp.change", function (e) {
    var date=e.date.format("YYYY-MM-DD");
  });

  //Flat green color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });
  $(document).on('keyup','#cost_per_unit,#qty',function(){
    var cost = $("#cost_per_unit").val();
    var qty = $("#qty").val();
    var total = (parseFloat(cost) * parseFloat(qty)).toFixed(2);
    console.log(total);
    console.log(typeof cost)
    console.log(qty)
    if(total!='NaN' && !isNaN(total)){
      $(".smallfuel").show();
      $(".fueltot").html(total).show();
    }else{
      $(".smallfuel").hide();
      $(".fueltot").html('').hide();
    }
  })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/fuel/edit.blade.php ENDPATH**/ ?>