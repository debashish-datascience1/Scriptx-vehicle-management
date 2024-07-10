<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("parts.index")); ?>"><?php echo app('translator')->getFromJson('menu.manageParts'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.editParts'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.editParts'); ?></h3>
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

        <?php echo Form::open(['route' => ['parts.update',$data->id],'method'=>'PATCH','files'=>true]); ?>

        <?php echo Form::hidden("user_id",Auth::user()->id); ?>

        <?php echo Form::hidden("id",$data->id); ?>

        <div class="row">
          <div class="col-md-6">
          <div class="form-group">
            <?php echo Form::label('image', __('fleet.picture'), ['class' => 'form-label']); ?>

            <?php if($data->image != null): ?>
            <a href="<?php echo e(asset('uploads/'.$data->image)); ?>" target="_blank"> (View)</a>
            <?php endif; ?>
            <br>
            <?php echo Form::file('image',null,['class' => 'form-control']); ?>

          </div>

          <div class="form-group">
            <?php echo Form::label('barcode', __('fleet.barcode'), ['class' => 'form-label']); ?>

            <?php echo Form::text('barcode', $data->barcode,['class' => 'form-control','required']); ?>

          </div>

          <div class="form-group">
            <?php echo Form::label('title', __('fleet.title'), ['class' => 'form-label']); ?>

            <?php echo Form::text('title', $data->title,['class' => 'form-control','required']); ?>

          </div>

          <div class="form-group">
            <?php echo Form::label('number', __('fleet.number'), ['class' => 'form-label']); ?>

            <?php echo Form::text('number', $data->number,['class' => 'form-control','required']); ?>

          </div>

          <div class="form-group">
            <?php echo Form::label('description',__('fleet.description'), ['class' => 'form-label']); ?>

            <?php echo Form::text('description',$data->description,['class'=>'form-control','required']); ?>

          </div>
          <div class="form-group">
            <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

            <?php echo Form::select('status',["Active"=>"Active","Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],$data->status,['class' => 'form-control','required']); ?>

          </div>
          <div class="form-group">
            <?php echo Form::label('availability', __('fleet.availability') , ['class' => 'form-label']); ?><br>
            <input type="radio" name="availability" class="flat-red gender" value="1" <?php if($data->availability == 1): ?> checked <?php endif; ?>> <?php echo app('translator')->getFromJson('fleet.available'); ?> &nbsp; &nbsp;

            <input type="radio" name="availability" class="flat-red gender"  value="0" <?php if($data->availability == 0): ?> checked <?php endif; ?>> <?php echo app('translator')->getFromJson('fleet.not_available'); ?>
          </div>
          <hr>
          <div class="form-group">
            <?php echo Form::label('udf1',__('fleet.add_udf'), ['class' => 'col-xs-5 control-label']); ?>

            <div class="row">
              <div class="col-md-8">
                <?php echo Form::text('udf1', null,['class' => 'form-control']); ?>

              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-info add_udf"> <?php echo app('translator')->getFromJson('fleet.add'); ?></button>
              </div>
            </div>
          </div>
          <div class="blank"></div>
          <?php if($udfs != null): ?>
          <?php $__currentLoopData = $udfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase"><?php echo e($key); ?></label> <input type="text" name="udf[<?php echo e($key); ?>]" class="form-control" required value="<?php echo e($value); ?>"></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>

          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <option value="<?php echo e($vendor->id); ?>" <?php if($vendor->id == $data->vendor_id): ?> selected <?php endif; ?>><?php echo e($vendor->name); ?></option>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <div class="form-group">
              <?php echo Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label']); ?>

              <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php if($cat->id == $data->category_id): ?> selected <?php endif; ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo Form::label('manufacturer', __('fleet.manufacturer'), ['class' => 'form-label']); ?>

              <?php echo Form::text('manufacturer', $data->manufacturer,['class' => 'form-control']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('year', __('fleet.year1'), ['class' => 'form-label']); ?>

              <?php echo Form::text('year', $data->year,['class' => 'form-control','required']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('model', __('fleet.part_model'), ['class' => 'form-label']); ?>

              <?php echo Form::text('model', $data->model,['class' => 'form-control','required']); ?>

            </div>


            <div class="form-group">
              <?php echo Form::label('stock', __('fleet.qty_on_hand'), ['class' => 'form-label']); ?>

              <?php echo Form::number('stock', $data->stock,['class' => 'form-control','required']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend">
                <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span> </div>
                <?php echo Form::number('unit_cost', $data->unit_cost,['class' => 'form-control','required']); ?>

              </div>
            </div>

            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',$data->note,['class'=>'form-control','size'=>'30x2']); ?>

            </div>
          </div>
        </div>
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']); ?>

        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $("#vendor_id").select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.select_vendor'); ?>"});
  $("#category_id").select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.parts_category'); ?>"});
  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter field name');
    }

    else{
      $(".blank").append('<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });

 //Flat green color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/parts/edit.blade.php ENDPATH**/ ?>