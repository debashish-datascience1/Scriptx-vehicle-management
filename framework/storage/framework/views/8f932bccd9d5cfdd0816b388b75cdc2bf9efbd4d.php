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
    .grand_total{
      text-align: center;
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
          <h3><?php echo app('translator')->getFromJson('fleet.fuelReport'); ?></h3>
          <?php if(!empty($vehicle)): ?>
          <h4><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></h4>
          <?php if(!empty($fuel_type)): ?>
          <h4><?php echo e($fuelType->fuel_name); ?></h4>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<?php if(empty($vehicle)): ?>
              <th>Vehicle</th>
              <?php endif; ?>
							
              <th>Fuel</th>
              
							<th>Vendor</th>
							<th>Quantity</th>
              <th>Per Unit</th>
              <th>CGST</th>
              <th>SGST</th>
							<th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
              
              
            </thead>

            <tbody>
            <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
							<td><?php echo e($k+1); ?></td>
							<td nowrap>
								<?php echo e(Helper::getCanonicalDate($f->date,'default')); ?><br>			
							</td>
              <?php if(empty($vehicle)): ?>
							<td><strong><?php echo e($f->vehicle_data->license_plate); ?></strong>
              </td>
              <?php endif; ?>
							<td>
								<?php if(!empty($f->vendor_name) && !empty($f->vendor)): ?>
									<?php echo e($f->vendor->name); ?>

								<?php else: ?>
									<span style="color: red"><small>No Vendor Selected</small></span>
								<?php endif; ?>
							</td>
							<td><?php echo e($f->fuel_details->fuel_name); ?></td>
							<td>
								<?php echo e($f->qty); ?>

							</td>
              <td nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->cost_per_unit,1,2)); ?></td>
              <td nowrap>
                <?php if(!empty($f->is_gst)): ?>
                <?php echo e(!empty($f->cgst) ? $f->cgst."%" : ''); ?> <br>
                <?php echo e(!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''); ?>

                <?php endif; ?>
							</td>
              <td nowrap>
								<?php if(!empty($f->is_gst)): ?>
									<?php echo e(!empty($f->sgst) ? $f->sgst."%" : ''); ?> <br>
									<?php echo e(!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''); ?>

								<?php endif; ?>
							</td>
							<td nowrap>
								<?php if(!empty($f->grand_total)): ?>
								<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->grand_total,1,2)); ?>

								<?php else: ?>
								<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->qty * $f->cost_per_unit,1,2)); ?>

								<?php endif; ?>
							</td>
						</tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              
              <th colspan="<?php echo e(empty($vehicle) ? '8' : '7'); ?>" class="grand_total"><strong>Grand Total</strong></th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($fuel->sum('gtotal'),1,2)); ?></th>
              <th nowrap> <?php echo e(bcdiv($fuel_totalqty,1,2)); ?> Liter</th>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/print_fuel.blade.php ENDPATH**/ ?>