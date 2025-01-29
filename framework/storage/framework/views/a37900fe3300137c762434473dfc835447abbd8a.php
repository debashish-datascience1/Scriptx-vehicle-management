<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo e(Hyvikk::get('app_name')); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" />
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/font-awesome.min.css')); ?>">
  <link href="<?php echo e(asset('assets/css/cdn/ionicons.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/fonts.css')); ?>">
  <style type="text/css">
    body {
      height: auto;
    }
    .grand_total {
      text-align: center;
    }
    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body onload="window.print();">
<?php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
?>

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
    </div>

    <!-- Regular Fuel Report -->
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
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
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
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($k+1); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($f->date,'default')); ?></td>
                <?php if(empty($vehicle)): ?>
                  <td><strong><?php echo e($f->vehicle_data->license_plate); ?></strong></td>
                <?php endif; ?>
                <td><?php echo e($f->fuel_details->fuel_name); ?></td>
                <td>
                  <?php if(!empty($f->vendor_name) && !empty($f->vendor)): ?>
                    <?php echo e($f->vendor->name); ?>

                  <?php else: ?>
                    <span style="color: red"><small>No Vendor Selected</small></span>
                  <?php endif; ?>
                </td>
                <td><?php echo e($f->qty); ?></td>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->cost_per_unit,1,2)); ?></td>
                <td>
                  <?php if(!empty($f->is_gst)): ?>
                    <?php echo e(!empty($f->cgst) ? $f->cgst."%" : ''); ?> <br>
                    <?php echo e(!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <?php if(!empty($f->is_gst)): ?>
                    <?php echo e(!empty($f->sgst) ? $f->sgst."%" : ''); ?> <br>
                    <?php echo e(!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''); ?>

                  <?php endif; ?>
                </td>
                <td>
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
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($fuel->sum('gtotal'),1,2)); ?></th>
              <th><?php echo e(bcdiv($fuel_totalqty,1,2)); ?> Liter</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Own Stock Report -->
    <div class="page-break"></div>
    <div class="row">
      <div class="col-md-12 text-center">
        <h3>Own Stock Report</h3>
        <?php if(!empty($vehicle)): ?>
          <h4><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></h4>
        <?php endif; ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>SL#</th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <?php if(empty($vehicle)): ?>
                <th>Vehicle</th>
              <?php endif; ?>
              <th>Vendor</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $own_stock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($k+1); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($stock->date,'default')); ?></td>
                <?php if(empty($vehicle)): ?>
                  <td>
                    <?php
                      $vehicle = App\Model\VehicleModel::find($stock->vehicle_id);
                    ?>
                    <strong><?php echo e($vehicle ? $vehicle->license_plate : 'N/A'); ?></strong>
                  </td>
                <?php endif; ?>
                <td>Own Stock</td>
                <td><?php echo e($stock->quantity); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="<?php echo e(empty($vehicle) ? '4' : '3'); ?>" class="grand_total"><strong>Total Quantity</strong></th>
              <th><?php echo e(bcdiv($own_stock_total_qty,1,2)); ?> Liter</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/print_fuel.blade.php ENDPATH**/ ?>