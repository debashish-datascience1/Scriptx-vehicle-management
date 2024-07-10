<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("vehicle_group.index")); ?>"><?php echo app('translator')->getFromJson('fleet.vehicleGroup'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.createGroup'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.createGroup'); ?></h3>
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

                <?php echo Form::open(['route' => 'vehicle_group.store','method'=>'post']); ?>

                <?php echo Form::hidden('user_id',Auth::user()->id); ?>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <?php echo Form::label('name',__('fleet.groupName'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('name',null,['class'=>'form-control','required']); ?>

                        </div>
                        <div class="form-group">
                            <?php echo Form::label('description',__('fleet.description'), ['class' => 'form-label']); ?>

                            <?php echo Form::text('description',null,['class'=>'form-control']); ?>

                        </div>
                        <div class="form-group">
                            <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

                            <?php echo Form::textarea('note',null,['class'=>'form-control','size'=>'30x2']); ?>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.createGroup'), ['class' => 'btn btn-success']); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/vehicle_groups/create.blade.php ENDPATH**/ ?>