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
<li class="breadcrumb-item"><a href="<?php echo e(route("users.index")); ?>"> <?php echo app('translator')->getFromJson('fleet.users'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.addUser'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.addUser'); ?></h3>
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

        <?php echo Form::open(['route' => 'users.store','files'=>true,'method'=>'post']); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']); ?>

              <?php echo Form::text('first_name', null,['class' => 'form-control','required']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']); ?>

              <?php echo Form::text('last_name', null,['class' => 'form-control','required']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>


              <?php echo Form::file('profile_image',null,['class' => 'form-control']); ?>

            </div>
            <div class="form-group" style="margin-top: 30px">
              <div class="row">
                <div class="col-md-3">
                  <label class="switch">
                  <input type="checkbox" name="is_admin" value="1">
                  <span class="slider round"></span>
                  </label>
                </div>
                <div class="col-md-3" style="margin-top: 5px">
                  <h4><?php echo app('translator')->getFromJson('fleet.is_admin'); ?></h4>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span> </div>
                <?php echo Form::email('email', null,['class' => 'form-control','required']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('password', __('fleet.password'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                <?php echo Form::password('password', ['class' => 'form-control','required']); ?>

              </div>
            </div>

            <div class="form-group">
              <?php echo Form::label('group_id',__('fleet.selectGroup'), ['class' => 'form-label']); ?>

              <select id="group_id" name="group_id" class="form-control">
                <option value=""><?php echo app('translator')->getFromJson('fleet.vehicleGroup'); ?></option>
                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($group->id == 1): ?>
                <option value="<?php echo e($group->id); ?>" selected><?php echo e($group->name); ?></option>
                <?php else: ?>
                <option value="<?php echo e($group->id); ?>" ><?php echo e($group->name); ?></option>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <div class="form-group">
              <?php echo Form::label('module',__('fleet.select_modules'), ['class' => 'form-label']); ?> <br>
              <div class="row">
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="0" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('menu.users'); ?><br>
                  <input type="checkbox" name="module[]" value="1" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.vehicles'); ?><br>
                  <input type="checkbox" name="module[]" value="2" class="flat-red form-control"> &nbsp;<?php echo app('translator')->getFromJson('menu.transactions'); ?> <br>
                  <input type="checkbox" name="module[]" value="3" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.bookings'); ?><br>
                  <input type="checkbox" name="module[]" value="13" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.helpus'); ?>
                </div>
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="4" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('menu.reports'); ?><br>
                  <input type="checkbox" name="module[]" value="5" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.fuel'); ?><br>

                  <input type="checkbox" name="module[]" value="6" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.vendors'); ?><br>
                  <input type="checkbox" name="module[]" value="7" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.work_orders'); ?><br>
                  <input type="checkbox" name="module[]" value="14" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.parts'); ?>
                </div>
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="8" class="flat-red form-control">&nbsp; <?php echo app('translator')->getFromJson('fleet.notes'); ?><br>
                  <input type="checkbox" name="module[]" value="9" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?><br>
                  <input type="checkbox" name="module[]" value="10" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.reviews'); ?><br>
                  <input type="checkbox" name="module[]" value="12" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.maps'); ?><br>
                  <input type="checkbox" name="module[]" value="15" class="flat-red form-control">&nbsp;  <?php echo app('translator')->getFromJson('fleet.testimonials'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.addUser'), ['class' => 'btn btn-success']); ?>

        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#group_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectGroup'); ?>"});
    //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/users/create.blade.php ENDPATH**/ ?>