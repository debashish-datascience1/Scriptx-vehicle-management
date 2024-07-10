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

          <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(Helper::getCanonicalDateTime(date('Y-m-d H:i:s'),'default')); ?> / <?php echo e(Helper::getCanonicalDateTime(date('Y-m-d H:i:s'))); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Driver Advance Report</h3>
          <?php if(!empty($driver_id)): ?>
          <h4><?php echo e($driver_name); ?></h4>
          <?php endif; ?>
          <?php if(!empty($vehicleData)): ?>
          <h5><?php echo e($vehicleData['make']); ?>-<?php echo e($vehicleData['model']); ?>-<?php echo e($vehicleData['license_plate']); ?></h4>
          <?php endif; ?>
          <small><?php echo e($from_date); ?> - <?php echo e($to_date); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover table-responsive">
            <thead>
                <th>SL#</th>
                <th>Date</th>
                
                <th>Driver</th>
                <th>Vehicle</th>
                
                <th >From - To</th>
                <th>Distance Duration</th>
                <th>Party</th>
                <th style="width: 12%"><?php echo e(Hyvikk::get('currency')); ?> Advance</th>
            </thead>

            <tbody>
            <?php $__currentLoopData = $advance_bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key+1); ?></td>
                    <td nowrap><?php echo e(Helper::getCanonicalDate($adv->pickup,'default')); ?><br><?php echo e(date("h:i:s A",strtotime($adv->pickup))); ?></td>
                    
                    <td><?php echo e($adv->driver->name); ?></td>
                    <td><?php echo e($adv->vehicle->license_plate); ?></td>
                    
                    <td><?php echo e($adv->pickup_addr); ?> <span class="fa fa-long-arrow-right"></span> <?php echo e($adv->dest_addr); ?></td>
                    <td><?php echo e($adv->distance); ?> <br><?php echo e($adv->duration_map); ?></td>
                    <td><?php echo e($adv->party_name); ?></td>
                    <td nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($adv->advance_pay,1,2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td colspan="6"></td>
              <td><strong>Total Advance</strong></td>
              <td><strong><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($total_advance,1,2)); ?></strong></td>
            </tr>
            </tbody>

          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/print-driveradvance.blade.php ENDPATH**/ ?>