<?php $__env->startSection('title', trans('installer_messages.welcome.title')); ?>
<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('assets/installer/froiden-helper/helper.css')); ?>" rel="stylesheet"/>
    <style>
        .form-control{
            height: 14px;
            width: 100%;
        }
        .has-error{
            color: red;
        }
        .has-error input{
            color: black;
            border:1px solid red;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('container'); ?>

<?php if(session('message')!="" || session('response')!="" || session('database')!="" ): ?>
    <ul style="list-style-type: none;">
        <div class="alert alert-danger">
                <li> <?php echo e(session('message')); ?> </li>
        </div>
    </ul>
<?php endif; ?>
<?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
        <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
    <p class="paragraph" style="text-align: center;"><?php echo e(trans('installer_messages.welcome.message')); ?></p>
       <form method="post" action="<?php echo e(url('installed')); ?>" id="env-form">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label class="col-sm-2 control-label">Purchase Code:</label>

            <div class="col-sm-10">
                <input type="text" name="purchase_code" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Hostname</label>

            <div class="col-sm-10">
                <input type="text" name="hostname" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Database</label>
            <div class="col-sm-10">
                <input type="text" name="database" class="form-control" required>
            </div>
        </div>

        <div class="modal-footer">
            <div class="buttons">
                <button class="button" type="submit">
                    <?php echo e(trans('installer_messages.next')); ?>

                </button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/installer/js/jQuery-2.2.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/installer/froiden-helper/helper.js')); ?>"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/laravel_web_installer/laravel_web_installer.blade.php ENDPATH**/ ?>