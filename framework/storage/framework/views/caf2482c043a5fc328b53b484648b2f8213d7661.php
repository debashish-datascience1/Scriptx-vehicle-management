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
          <h3><?php echo app('translator')->getFromJson('fleet.fuelReport'); ?>&nbsp;<small><?php if($month_select != 0): ?><?php echo e(date('F', mktime(0, 0, 0, $month_select, 10))); ?>-<?php endif; ?><?php echo e($year_select); ?></small></h3>
          <h4><?php echo e($vehicle['make']); ?>-<?php echo e($vehicle['model']); ?>-<?php echo e($vehicle['license_plate']); ?></h4>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.meter'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.consumption'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
            </thead>

            <tbody>
            <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e(date($date_format_setting,strtotime($f->date))); ?></td>
              <td><?php echo e($f->vehicle_data->make); ?>-<?php echo e($f->vehicle_data->model); ?>-<?php echo e($f->vehicle_data->license_plate); ?></td>
              <td>
                <b> <?php echo app('translator')->getFromJson('fleet.start'); ?>: </b><?php echo e($f->start_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                <br>
                <b> <?php echo app('translator')->getFromJson('fleet.end'); ?>:</b><?php echo e($f->end_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

              </td>
              <td><?php echo e($f->consumption); ?>

                <?php if(Hyvikk::get('dis_format') == "km"): ?>
                 <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?>KMPG <?php else: ?> KMPL <?php endif; ?>
                <?php else: ?>
                 <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?>MPG <?php else: ?> MPL <?php endif; ?>
                <?php endif; ?>
              </td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($f->qty * $f->cost_per_unit); ?></td>
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
</html><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/print_fuel.blade.php ENDPATH**/ ?>