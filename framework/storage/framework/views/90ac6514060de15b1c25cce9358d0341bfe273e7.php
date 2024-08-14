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
          <h3>Work-Order Vendor Report</h3>
          <?php if($is_vendor): ?>
          <h4><strong><i><?php echo e(strtoupper($vendorName)); ?></i></strong></h4>
          <?php endif; ?>
          <small><?php echo e(Helper::getCanonicalDate($date['from_date'],'default')); ?></small> to
          <small><?php echo e(Helper::getCanonicalDate($date['to_date'],'default')); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
              <th width="10%">Date</th>
              <?php if($is_vendor!=true): ?>
              <th>Vendor</th>
              <?php endif; ?>
              <th>Vehicle</th>
              <th>Type</th>
              <!-- <th width="15%">Description</th> -->
              <th>Status</th>
              <th>Amount</th>
              <th>Parts Details</th>
            </thead>

            <tbody>
              <?php $__currentLoopData = $processedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($k+1); ?></td>
                    <td nowrap><?php echo e(Helper::getCanonicalDate($row['required_by'],'default')); ?></td>
                    <?php if($is_vendor!=true): ?>
                    <td><?php echo e($row['vendor']->name); ?></td>
                    <?php endif; ?>
                    <td><strong><?php echo e(strtoupper($row['vehicle']->license_plate)); ?></strong></td>
                    <td><?php echo e($row['vendor']->type); ?></td>
                    <!-- <td><?php echo e($row['description']); ?></td> -->
                    <td><?php echo e($row['status']); ?></td>
                    <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($row['price'],2)); ?></td>
                    <td>
                      <?php $__currentLoopData = $row['parts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partName => $partData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="parts-details">
                          <strong><?php echo e($partName); ?></strong><br>
                          Qty: <?php echo e($partData['qty']); ?><br>
                          Tyres: <?php echo e(implode(', ', $partData['tyres'])); ?><br>
                          Source: <?php echo e($partData['is_own'] ? 'Own Inventory' : 'Vendor'); ?>

                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <th colspan="<?php echo e($is_vendor ? 6 : 7); ?>"></th>
                  <th>Grand Total</th>
                  <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($gtotal,1,2)); ?></th>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/report-print.blade.php ENDPATH**/ ?>