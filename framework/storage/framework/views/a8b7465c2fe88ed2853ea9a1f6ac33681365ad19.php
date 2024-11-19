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
            <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(Helper::getCanonicalDateTime($date,'default')); ?> / <?php echo e(Helper::getCanonicalDateTime($date)); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vehicle Documents Report</h3>
          <?php if(!empty($vehicle)): ?>
          <h4><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></h4>
          <?php if(!empty($d->driver_id) && !empty($d->drivervehicle) && !empty($d->drivervehicle->assigned_driver)): ?>
          <h4><?php echo e($d->drivervehicle->assigned_driver->name); ?></h4>
          <?php else: ?>
          <span style="color: red"><small><i>Driver not assigned</i></small></span><br>
          <?php endif; ?>
          <?php endif; ?>
          <small><?php echo e($from_date); ?> - <?php echo e($to_date); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover"  id="myTable">
            <thead>
              <tr>
                <th>SL#</th>
                <?php if(empty($vehicle)): ?>
                <th>Vehicle</th>
                
                <?php endif; ?>
                <th>Vendor</th>
                <th>Documents</th>
                <th>Method / Ref. No.</th>
                <th>Renewed On</th>
                <th>Valid Till</th>
                <th>Remaining Days</th>
                <th>Amount</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($k+1); ?></td>
                <?php if(empty($vehicle)): ?>
                <td><?php echo e($d->vehicle->license_plate); ?></td>
                
                <?php endif; ?>
                <td>
                  <?php echo e($d->vendor->name); ?>

                </td>
                <td><?php echo e($d->document->label); ?></td>
                <td>
                  <span class="badge badge-primary"><?php echo e($d->method_param->label); ?></span><br>
                  <?php echo e($d->ddno); ?>

                </td>
                <td><?php echo e(Helper::getCanonicalDate($d->date,'default')); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($d->till,'default')); ?></td>
                <td><?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e(Helper::renewLastday($d->till)); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?></td>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($d->amount)); ?></td>
                <td width="17%"><?php echo e($d->remarks); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td colspan="<?php echo e(empty($vehicle) ? '7' : '6'); ?>"></td>
                <td><strong>Grand Total</strong></td>
                <td><strong><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($docs->sum('amount'))); ?></strong></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>
<?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicle_docs/print-report.blade.php ENDPATH**/ ?>