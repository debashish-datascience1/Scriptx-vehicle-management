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
          <h3>Salary Report</h3>
          <small>Salary for the month of <?php echo e($date1); ?> (<?php echo e($date2); ?>)</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>SL#</th>
                <th>Name</th>
                <th>Vehicle</th>
                <th>Present/Absent</th>
                <th>Net Salary</th>
                <th>Booking Adv. Salary</th>
                <th>Salary Advance</th>
                <th>Absent Deduct</th>
                <th>Payable Amount</th>
            </thead>
            <tbody>
            <?php $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                <tr>
                    <td><?php echo e($k+1); ?></td>
                    <td>
                    <?php if($row->is_payroll): ?>
                        <?php echo e($row->driver->name); ?>

                    <?php else: ?>
                        <?php echo e($row->driver); ?>

                    <?php endif; ?>
                    </td>
                    <td>
                    <?php if($row->is_payroll): ?>
                        <?php echo e($row->driver->driver_vehicle->vehicle->license_plate); ?>

                    <?php else: ?>
                        <?php echo e($row->vehicle); ?>

                    <?php endif; ?>
                    </td>
                    <td><?php echo e($row->days_present); ?>/<?php echo e($row->days_absent); ?></td>
                    <td><?php echo e(bcdiv($row->gross_salary,1,2)); ?></td>
                    <td><?php echo e(bcdiv($row->bookingAdvance,1,2)); ?></td>
                    <td><?php echo e(bcdiv($row->salary_advance,1,2)); ?></td>
                    <td><?php echo e(bcdiv($row->deduct_amount,1,2)); ?></td>
                    <td><?php echo e(bcdiv($row->payable_salary,1,2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="3"></th>
              <th><strong>Total Amount(s)</strong></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($salaries->sum('gross_salary'),1,2)); ?></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($salaries->sum('bookingAdvance'),1,2)); ?></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($salaries->sum('salary_advance'),1,2)); ?></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($salaries->sum('deduct_amount'),1,2)); ?></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($salaries->sum('payable_salary'),1,2)); ?></th>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/print_salary-report.blade.php ENDPATH**/ ?>