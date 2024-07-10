<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo e(trans('installer_messages.title')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/installer/img/favicon/favicon-16x16.png')); ?>" sizes="16x16"/>
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/installer/img/favicon/favicon-32x32.png')); ?>" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/installer/img/favicon/favicon-96x96.png')); ?>" sizes="96x96"/>


<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link href="<?php echo e(asset('assets/installer/css/style.min.css')); ?>" rel="stylesheet"/>
    <?php echo $__env->yieldContent('style'); ?>

</head>
<body>
<div class="master">
    <div class="box" style="width: 50% !important;">
        <div class="header">
            <img src="<?php echo e(asset('/assets/images/logo_install.png')); ?>" height="55px" alt="">
            <h1 class="header__title"><?php echo $__env->yieldContent('title'); ?></h1>
        </div>

        <div class="main">
            <?php echo $__env->yieldContent('container'); ?>
        </div>
    </div>
</div>
</body>
<?php echo $__env->yieldContent('scripts'); ?>
</html>
<?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/layouts/master.blade.php ENDPATH**/ ?>