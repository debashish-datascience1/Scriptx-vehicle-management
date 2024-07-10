<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo e(Hyvikk::get('app_name')); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <!-- Bootstrap 3.3.7 -->
 <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/font-awesome.min.css')); ?>">
  <!-- Ionicons -->
  <link href="<?php echo e(asset('assets/css/cdn/ionicons.min.css')); ?>" rel="stylesheet">
  <!-- Theme style -->
   <link href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/fonts.css')); ?>">
  <style type="text/css">
    body {
      height: auto;
    }
  </style>
</head>
<body onload="window.print();">
  <?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
  <div class="wrapper">
  <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <span class="logo-lg">
          <img src="<?php echo e(asset('assets/images/'. Hyvikk::get('icon_img') )); ?>" class="navbar-brand" style="margin-top: -15px">
          <?php echo e(Hyvikk::get('app_name')); ?>

          </span>

            <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(date($date_format_setting)); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3><?php echo app('translator')->getFromJson('fleet.monthlyReport'); ?>&nbsp;<small><?php echo e(date('F', mktime(0, 0, 0, $month_select, 10))); ?>-<?php echo e($year_select); ?></small></h3>
          <?php if($vehicle_select != null): ?><h4><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></h4><?php endif; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h3><?php echo app('translator')->getFromJson('fleet.income'); ?>-<?php echo app('translator')->getFromJson('fleet.expense'); ?></h3>
          <table class="table table-bordered table-striped table-hover">
            <?php ($income_amt = (is_null($income[0]->income) ? 0 : $income[0]->income)); ?>
            <?php ($expense_amt = (is_null($expenses[0]->expense) ? 0 : $expenses[0]->expense)); ?>
            <thead>
              <tr>
                <th scope="row"><?php echo app('translator')->getFromJson('fleet.pl'); ?></th>
                <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($income_amt-$expense_amt); ?></strong></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row"><?php echo app('translator')->getFromJson('fleet.income'); ?></th>
                <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($income_amt); ?></td>
              </tr>
              <tr>
                <th scope="row"><?php echo app('translator')->getFromJson('fleet.expenses'); ?></th>
                <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($expense_amt); ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-4">
          <h3><?php echo app('translator')->getFromJson('fleet.incomeByCategory'); ?></h3>
          <table class="table table-bordered table-striped table-hover">
            <?php ($tot = 0); ?>
            <?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            	<?php ($tot = $tot + $exp->amount); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <thead>
              <tr>
                <th scope="row"><?php echo app('translator')->getFromJson('fleet.incomeByCategory'); ?></th>
                <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($tot); ?></strong></td>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <th scope="row"><?php echo e($income_cats[$exp->income_cat]); ?></th>
                  <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($exp->amount); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>

        <div class="col-md-4">
          <h3><?php echo app('translator')->getFromJson('fleet.expensesByCategory'); ?></h3>
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
              <?php ($tot = 0); ?>
              <?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php ($tot = $tot + $exp->expense); ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th scope="row"><?php echo app('translator')->getFromJson('fleet.expensesByCategory'); ?></th>
                <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($tot); ?></strong></td>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <th scope="row">
                  <?php if($exp->type == "s"): ?>
                  <?php echo e($service[$exp->expense_type]); ?>

                  <?php else: ?>
                  <?php echo e($expense_cats[$exp->expense_type]); ?>

                  <?php endif; ?>
                </th>
                <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($exp->expense); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/print_monthly.blade.php ENDPATH**/ ?>