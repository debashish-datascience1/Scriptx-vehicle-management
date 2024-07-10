<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .nav-link {
    padding: .5rem !important;
  }

  .custom .nav-link.active {

      background-color: #21bc6c !important;
  }

  .ck-editor__editable {
      min-height: 200px;
  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.email_content'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('menu.email_content'); ?>
        </h3>
      </div>
      <div class="card-body">
        <div>
          <ul class="nav nav-pills custom">
            <li class="nav-item"><a href="#insurance" data-toggle="tab" class="nav-link active"> <?php echo app('translator')->getFromJson('fleet.i_notify'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#vehicle-licence" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.v_lic'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#driver-licence" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.d_lic'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#registration" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.v_reg'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#reminder" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?> <i class="fa"></i></a></li>
          </ul>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="tab-content card-body">
              <div class="tab-pane active" id="insurance">
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger">
                  <ul>
                  <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
                <?php endif; ?>
                <?php echo Form::open(['url' => 'admin/set-content/insurance','method'=>'post']); ?>

                <div class="form-group">
                  <?php echo Form::label('insurance', __('fleet.ins_content'), ['class' => 'form-label']); ?>

                  <textarea name="insurance" id="ins"><?php echo e(Hyvikk::email_msg('insurance')); ?></textarea>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.set'); ?>" />
                  </div>
                </div>
                <?php echo Form::close(); ?>

              </div>

              <div class="tab-pane" id="vehicle-licence">
                <?php echo Form::open(['url' => 'admin/set-content/vehicle-licence','method'=>'post']); ?>

                <div class="form-group">
                  <?php echo Form::label('vehicle_licence', __('fleet.vehicle_lic'), ['class' => 'form-label']); ?>

                  <textarea name="vehicle_licence" id="vl"><?php echo e(Hyvikk::email_msg('vehicle_licence')); ?></textarea>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.set'); ?>" />
                  </div>
                </div>
                <?php echo Form::close(); ?>

              </div>

              <div class="tab-pane" id="driver-licence">
                <?php echo Form::open(['url' => 'admin/set-content/driver-licence','method'=>'post']); ?>

                <div class="form-group">
                  <?php echo Form::label('driving_licence', __('fleet.driver_lic'), ['class' => 'form-label']); ?>

                  <textarea name="driving_licence" id="dl"><?php echo e(Hyvikk::email_msg('driving_licence')); ?></textarea>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.set'); ?>" />
                  </div>
                </div>
                <?php echo Form::close(); ?>

              </div>

              <div class="tab-pane" id="registration">
                <?php echo Form::open(['url' => 'admin/set-content/registration','method'=>'post']); ?>

                <div class="form-group">
                  <?php echo Form::label('registration', __('fleet.reg_content'), ['class' => 'form-label']); ?>

                  <textarea name="registration" id="reg"><?php echo e(Hyvikk::email_msg('registration')); ?></textarea>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.set'); ?>" />
                  </div>
                </div>
                <?php echo Form::close(); ?>

              </div>

              <div class="tab-pane" id="reminder">
                <?php echo Form::open(['url' => 'admin/set-content/reminder','method'=>'post']); ?>

                <div class="form-group">
                  <?php echo Form::label('service_reminder', __('fleet.service_reminder_content'), ['class' => 'form-label']); ?>

                  <textarea name="service_reminder" id="sr"><?php echo e(Hyvikk::email_msg('service_reminder')); ?></textarea>
                </div>
                <?php echo Form::hidden('sr','1'); ?>


                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.set'); ?>" />
                  </div>
                </div>
                <?php echo Form::close(); ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/cdn/ckeditor.js')); ?>"></script>
<script>
    ClassicEditor.create( document.querySelector( '#ins' ) );
    ClassicEditor.create( document.querySelector( '#vl' ) );
    ClassicEditor.create( document.querySelector( '#dl' ) );
    ClassicEditor.create( document.querySelector( '#reg' ) );
    ClassicEditor.create( document.querySelector( '#sr' ) );
</script>
<script type="text/javascript">
$(document).ready(function() {
  <?php if(isset($_GET['tab']) && $_GET['tab']!=""): ?>
  $('.nav-pills a[href="#<?php echo e($_GET['tab']); ?>"]').tab('show')
  <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/utilities/set_email.blade.php ENDPATH**/ ?>