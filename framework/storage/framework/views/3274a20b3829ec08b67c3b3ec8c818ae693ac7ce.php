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
          <h3>Driver Leave Report</h3>
          <?php if(!empty($driver_id)): ?>
          <h4><?php echo e($driver_data->name); ?></h4>
          <?php endif; ?>
          <?php $vehicle = !empty($driver_data->driver_vehicle) ? $driver_data->driver_vehicle->vehicle : null; ?>
          <?php if(!empty($vehicle)): ?>
          <h5><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></h4>
          <?php endif; ?>
          <small><?php echo e(Helper::getCanonicalDate($from_date)); ?> - <?php echo e(Helper::getCanonicalDate($to_date)); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>#</th>
              <th>Date</th>
              <?php if(empty($driver_id)): ?>
              <th>Driver</th>
              <th>Vehicle</th>
              <?php endif; ?>
              <th>From - To</th>
              <th>Remarks</th>
            </thead>

            <tbody>
              <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($k+1); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($l->date)); ?></td>
                <?php if(empty($driver_id)): ?>
                <td><?php echo e($l->driver->name); ?></td>
                <td>
                  <?php $vehicle = !empty($l->driver_vehicle) ? $l->driver_vehicle->vehicle : null; ?>
                  <?php if(!empty($vehicle)): ?>
                  <?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?>

                  <?php else: ?>
                    <label>N/A</label>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
                <td>
                  <?php echo e(empty($l->is_present) ? "N/A" : Helper::getLeaveTypes()[$l->is_present]); ?>

                </td>
                <td><?php echo e($l->remarks); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td colspan="<?php echo e(empty($driver_id) ? '4' : '2'); ?>"></td>
              <td><strong>Total Present : <?php echo e($total_present); ?></strong></td>
              <td><strong>Total Absent : <?php echo e($total_absent); ?></strong></td>
            </tr>
            </tbody>

          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/leaves/print-report.blade.php ENDPATH**/ ?>