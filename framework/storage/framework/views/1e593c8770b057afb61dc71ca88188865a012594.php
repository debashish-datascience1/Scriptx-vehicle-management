<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("work_order.index")); ?>"><?php echo app('translator')->getFromJson('fleet.work_orders'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_order'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.create_workorder'); ?>
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

        <?php echo Form::open(['route' => 'work_order.store','method'=>'post']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('type','Created'); ?>

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
              <?php echo Form::label('required_by', __('fleet.required_by'), ['class' => 'form-label']); ?>

              <div class="input-group date">
              <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></div>
              <?php echo Form::text('required_by',null,['class'=>'form-control','required']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('meter',Hyvikk::get('dis_format')." ".__('fleet.reading'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
              <?php echo Form::number('meter',null,['class'=>'form-control']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',null,['class'=>'form-control','size'=>'30x4']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vendor->id); ?>"> <?php echo e($vendor->name); ?> </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <div class="form-group">
              <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

              <?php echo Form::select('status',["Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control','required']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('price',__('fleet.work_order_price'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
              <?php echo Form::number('price',null,['class'=>'form-control','required']); ?>

              </div>
            </div>

            <div class="form-group">
              <?php echo Form::label('description',__('fleet.description'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('description',null,['class'=>'form-control','size'=>'30x4']); ?>

            </div>
          </div>
        </div>
        <hr>
        <div class="row" style="margin-bottom: 25px;">
          <div class="col-md-6">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.selectPart'); ?></label> <select id="select_part" class="form-control" name="part_list"> <option></option><?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($part->id); ?>" title="<?php echo e($part->title); ?>" qty="<?php echo e($part->stock); ?>" price="<?php echo e($part->unit_cost); ?>"><?php echo e($part->title); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </select> </div></div>
          <div class="col-md-6" style="margin-top: 30px">
            <button type="button" class="btn btn-warning attach"><?php echo app('translator')->getFromJson('fleet.attachPart'); ?></button>
          </div>
        </div>
        <div class="row">
          <div class="parts col-md-12"></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.add_order'), ['class' => 'btn btn-success']); ?>

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
  $('#vendor_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.select_vendor'); ?>"});
  $('#select_part').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectPart'); ?>"});
  $('#required_by').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
  $('#created_on').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  //Flat green color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $('.attach').on('click',function(){

    var field = $('#select_part').val();
    if(field == "" || field == null){
      alert('Select Part');
    }
    else{
      var qty=$('#select_part option:selected').attr('qty');
      var title=$('#select_part option:selected').attr('title');
      var price=$('#select_part option:selected').attr('price');
      // alert($('#select_part option:selected').attr('title'));
      // alert($('#select_part option:selected').attr('qty'));
      $(".parts").append('<div class="row col-md-12"><div class="col-md-4">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.selectPart'); ?></label> <select  class="form-control" disabled>  <option value="'+field+'" selected >'+title+'</option> </select> </div></div> <div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.qty'); ?></label> <input type="number" name="parts['+field+']" min="1" value="1" class="form-control calc" max='+qty+' required> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></label> <input type="number" value="'+price+'" class="form-control" disabled> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></label> <input type="number" value="'+price+'" class="form-control total_cost" disabled id="'+field+'"> </div></div> <div class="col-md-2"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('.calc').on('change',function(){
        // alert($(this).val()*price);
        $('#'+field).val($(this).val()*price);
      });
      $('#select_part').val('').change();
    }
  });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/work_orders/create.blade.php ENDPATH**/ ?>