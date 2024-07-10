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
          <h3><?php echo app('translator')->getFromJson('fleet.booking_report'); ?></h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Vehicle</th>
                <th>Customer</th>
                <th>From-To</th>
                <th>Distance</th>
                <th>Fuel Consumption</th>
                <th>Pickup Date</th>
                <th>Dropoff Date</th>
                <th>Material</th>
                <th>Quantity</th>
                <th>Driver Advance</th>
                <th>Freight Price</th>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$bk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($k+1); ?></td>
                <th><?php echo e($bk->vehicle->license_plate); ?></th>
                <td><?php echo e($bk->customer->name); ?></td>
                <td>
                  <?php if(!empty($bk->transaction_det)): ?>
                  <strong>(<?php echo e($bk->transaction_det->transaction_id); ?>)</strong><br>
                  <?php endif; ?>
                  <?php echo e($bk->pickup_addr); ?> <i class="fa fa-long-arrow-right "></i> <?php echo e($bk->dest_addr); ?> 
                  
                  <?php if(!empty($bk->getMeta('fodder_km'))): ?>
                  <?php if(!empty($bk->transaction_details) && !empty($bk->transaction_details->booking)): ?>
                  <br>
                  <small><?php echo e($bk->dest_addr); ?> <span class="fa fa-long-arrow-right"></span>
                    <?php echo e($bk->transaction_details->booking->pickup_addr); ?>

                  </small><br>
                  <small>Distance : <?php echo e(!empty($bk->getMeta('fodder_km')) ? $bk->getMeta('fodder_km')."km" :null); ?></small><br>
                  <small>Fuel : <?php echo e(!empty($bk->getMeta('fodder_consumption')) ? bcdiv($bk->getMeta('fodder_consumption'),1,2)."ltr" :null); ?></small><br>
                  <small>References Booking <strong><?php echo e($bk->transaction_details->transaction_id); ?></strong></small>
                  <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td>
                   
                  <?php echo e($bk->getMeta('distance')); ?>

                  <?php if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption'))): ?>
                    <br>
                    <strong>+ <?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->getMeta('fodder_km')) === 1 ? bcdiv($bk->getMeta('fodder_km'),1,2) : 0); ?> km</strong>
                  <?php endif; ?>
                </td>
				<td>
				    <?php if($bk->getMeta('pet_required') != "Infinity"): ?>
							    <?php echo e(bcdiv($bk->getMeta('pet_required'),1,2)); ?> ltr
								<?php else: ?>
								0
								<?php endif; ?>
              
                  <?php if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption'))): ?>
                    <br>
                    <strong>+ <?php echo e(bcdiv($bk->getMeta('fodder_consumption'),1,2)); ?> ltr</strong>
                  <?php endif; ?>
                </td>
                <td><?php echo e(Helper::getCanonicalDate($bk->pickup,'default')); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($bk->dropoff,'default')); ?></td>
                <td><?php echo e($bk->material); ?></td>
                <td><?php echo e($bk->loadqty); ?> <?php echo e($loadset[$bk->getMeta('loadtype')]); ?></td>
                <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->advance_pay) === 1 ? bcdiv($bk->advance_pay,1,2) : 0); ?></td>
                <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->total_price) === 1 ? bcdiv($bk->total_price,1,2) : 0); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          <table class="table table-bordered">
            <tr>
              <th>Addtional Distance</th>
              <th>Total Distance</th>
              <th>Additional Fuel</th>
              <th>Total Fuel</th>
              <th>Grand Total</th>
            </tr>
           
            <tr>
              <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $fodderdistance) === 1 ? bcdiv($fodderdistance,1,2) : 0); ?> km</td>
              <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_distance) === 1 ? bcdiv($total_distance,1,2) : 0); ?> ltr</td>
              <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $fodderfuel) === 1 ? bcdiv($fodderfuel,1,2) : 0); ?> ltr</td>
              <td><?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_fuel) === 1 ? bcdiv($total_fuel,1,2) : 0); ?> ltr</td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_price) === 1 ? bcdiv($total_price,1,2) : 0); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
  <!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/print_bookings.blade.php ENDPATH**/ ?>