<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("vendors.index")); ?>"> <?php echo app('translator')->getFromJson('fleet.vendors'); ?> </a></li>
<li class="breadcrumb-item active"> <?php echo app('translator')->getFromJson('fleet.edit_vendor'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.edit_vendor'); ?>
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

        <?php echo Form::open(['route' => ['vendors.update',$data->id],'files'=>true,'method'=>'PATCH']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('id',$data->id); ?>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('photo', __('fleet.picture'), ['class' => 'form-label']); ?>

              <?php if($data->photo != null): ?>
              <a href="<?php echo e(asset('uploads/'.$data->photo)); ?>" target="_blank">View</a>
              <?php endif; ?>
              <br>
              <?php echo Form::file('photo',null,['class' => 'form-control']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('name',__('fleet.name'), ['class' => 'form-label']); ?>

              <?php echo Form::text('name',$data->name,['class'=>'form-control','required']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('phone',__('fleet.phone'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-phone"></i></span></div>
              <?php echo Form::number('phone',$data->phone,['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('email',__('fleet.email'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
              <?php echo Form::email('email',$data->email,['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('type', __('fleet.vendor_type'), ['class' => 'form-label']); ?>

              <select class="form-control" required id="type" name="type">
                <?php $__currentLoopData = $vendor_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($type == $data->type): ?>
                  <option value="<?php echo e($type); ?>" selected><?php echo e($type); ?></option>
                  <?php else: ?>
                  <option value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('website',__('fleet.website'), ['class' => 'form-label']); ?>

              <?php echo Form::text('website',$data->website,['class'=>'form-control','required']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('address1',__('fleet.address1'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-address-book-o"></i></span></div>
              <?php echo Form::text('address1',$data->address1,['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('address2',__('fleet.address2'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-address-book-o"></i></span></div>
              <?php echo Form::text('address2',$data->address2,['class'=>'form-control']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('city',__('fleet.city'), ['class' => 'form-label']); ?>

              <?php echo Form::text('city',$data->city,['class'=>'form-control','required']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('postal_code',__('fleet.postal_code'), ['class' => 'form-label']); ?>

              <?php echo Form::text('postal_code',$data->postal_code,['class'=>'form-control']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('country',__('fleet.country'), ['class' => 'form-label']); ?>

              <?php echo Form::text('country',$data->country,['class'=>'form-control','required']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('province',__('fleet.province'), ['class' => 'form-label']); ?>

              <?php echo Form::text('province',$data->province,['class'=>'form-control']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',$data->note,['class'=>'form-control','size'=>'30x4']); ?>

            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="form-group col-md-6">
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
        </div>
        <?php if($udfs != null): ?>
        <?php $__currentLoopData = $udfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase"><?php echo e($key); ?></label> <input type="text" name="udf[<?php echo e($key); ?>]" class="form-control" required value="<?php echo e($value); ?>"></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <div class="blank"></div>
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

<?php $__env->startSection("script"); ?>
<script type="text/javascript">
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/vendors/edit.blade.php ENDPATH**/ ?>