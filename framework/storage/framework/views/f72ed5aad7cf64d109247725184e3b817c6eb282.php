<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo e(link_to_route('company-services.index', __('fleet.companyServices'))); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.editCompanyService'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.editCompanyService'); ?></h3>
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

        <?php echo Form::open(['route' => ['company-services.update',$data->id],'method'=>'PATCH','files'=>true]); ?>

        <?php echo Form::hidden('id',$data->id); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('title', __('fleet.title'), ['class' => 'form-label']); ?>

              <?php echo Form::text('title', $data->title,['class' => 'form-control','required']); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('image', __('fleet.icon_img'), ['class' => 'form-label']); ?> <?php if($data->image != null): ?> (<a href="<?php echo e(asset('uploads/'.$data->image)); ?>" target="blank"><?php echo app('translator')->getFromJson('fleet.view'); ?></a>) <?php endif; ?>
              <br>
              <?php echo Form::file('image',null,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::label('description', __('fleet.description'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('description', $data->description,['class' => 'form-control','required','size'=>'30x5']); ?>

            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group col-md-4">
            <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']); ?>

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/company_services/edit.blade.php ENDPATH**/ ?>