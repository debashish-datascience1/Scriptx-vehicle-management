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
          <h3><?php echo app('translator')->getFromJson('fleet.booking_report'); ?>&nbsp;<small><?php echo e(date('F', mktime(0, 0, 0, $month_select, 10))); ?>-<?php echo e($year_select); ?></small></h3>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                
                <th>Advance</th>
                <th>Payment Amount</th>
                <th>Total Amount</th>
                <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($row->customer->name); ?></td>
                <td>
                  <?php if($row->vehicle_id != null): ?>
                  <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?>

                  <?php endif; ?>
                </td>
                <td><?php if($row->advpay_array != null): ?>
                  <i class="fa fa-inr"></i> <?php echo e($row->advpay_array); ?>

                <?php else: ?>
                  <span class="badge badge-danger">N/A</span>
                <?php endif; ?></td>
                <td><?php if($row->payment_amount != null): ?>
                  <i class="fa fa-inr"></i> <?php echo e($row->payment_amount); ?>

                <?php else: ?>
                  <span class="badge badge-danger">N/A</span>
                <?php endif; ?></td>
                <td><?php echo e($row->total_price); ?></td>
                

                <td><?php if($row->status == 0): ?><span style="color:orange;"><?php echo app('translator')->getFromJson('fleet.journey_not_ended'); ?></span> <?php else: ?> <span style="color:green;"><?php echo app('translator')->getFromJson('fleet.journey_ended'); ?></span> <?php endif; ?></td>
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
</html><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/print_bookings.blade.php ENDPATH**/ ?>