<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("parts-category.index")); ?>"><?php echo app('translator')->getFromJson('fleet.partsCategory'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_part_category'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.add_part_category'); ?></h3>
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

        <?php echo Form::open(['route' => 'parts-category.store','method'=>'post']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('name',__('fleet.category_name'), ['class' => 'form-label']); ?>

              <?php echo Form::text('name',null,['class'=>'form-control','required']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/parts_category/create.blade.php ENDPATH**/ ?>