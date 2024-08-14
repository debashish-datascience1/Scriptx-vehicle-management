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
<?php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')?>

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

          <strong><small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(Helper::getCanonicalDateTime(date('Y-m-d H:i:s'),'default')); ?> / <?php echo e(Helper::getCanonicalDateTime(date('Y-m-d H:i:s'))); ?></small></strong>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Salary Processing Report</h3>
          <small>Salary for the month of <?php echo e($date1); ?> (<?php echo e($date2); ?>)</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
              <th>Name</th>
              <th>Bank</th>
              <th>A/C No.</th>
              <th>Payable Amount</th>
            </thead>
            <tbody>
            <?php $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                <?php
                  $bankInfo = $row->is_payroll ? $row->driver->bank : $row->bank;
                  $showRow = !$request['payment_type'] || 
                             ($request['payment_type'] == 'bank' && !empty($bankInfo)) || 
                             ($request['payment_type'] == 'cash' && empty($bankInfo));
                ?>
                <?php if($showRow): ?>
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
                    <?php if(!empty($bankInfo)): ?>
                      <?php echo e($bankInfo); ?>

                    <?php else: ?>
                      Cash
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if($row->is_payroll): ?>
                      <?php echo e($row->driver->account_no ?? 'N/A'); ?>

                    <?php else: ?>
                      <?php echo e($row->account_no ?? 'N/A'); ?>

                    <?php endif; ?>
                  </td>
                  <td>
                    <?php echo e(bcdiv($row->payable_salary,1,2)); ?>

                    <?php if($row->is_payroll): ?>
                    <span title="Paid"><i class="fa fa-check"></i></span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          
          <?php
            $totalPayableSalary = $salaries->filter(function($row) use ($request) {
              $bankInfo = $row->is_payroll ? $row->driver->bank : $row->bank;
              return !$request['payment_type'] || 
                     ($request['payment_type'] == 'bank' && !empty($bankInfo)) || 
                     ($request['payment_type'] == 'cash' && empty($bankInfo));
            })->sum('payable_salary');
          ?>
          
          <table class="table">
            <tr>
              <th colspan="3"></th>
              <th><strong>Total Amount</strong></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($totalPayableSalary,1,2)); ?></th>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/print_salary-processing.blade.php ENDPATH**/ ?>