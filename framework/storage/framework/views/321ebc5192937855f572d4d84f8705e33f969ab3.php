<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo e(Hyvikk::get('app_name')); ?> - Fastag Report</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" />
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/font-awesome.min.css')); ?>">
  <link href="<?php echo e(asset('assets/css/cdn/ionicons.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>" rel="stylesheet">

  <style type="text/css">
    body {
      height: auto;
    }
    .table-bordered > thead > tr > th, 
    .table-bordered > tbody > tr > th, 
    .table-bordered > tfoot > tr > th, 
    .table-bordered > thead > tr > td, 
    .table-bordered > tbody > tr > td, 
    .table-bordered > tfoot > tr > td {
      border: 1px solid #000;
    }
    .table > tbody > tr > td, .table > tbody > tr > th, 
    .table > tfoot > tr > td, .table > tfoot > tr > th, 
    .table > thead > tr > td, .table > thead > tr > th {
      padding: 8px;
      line-height: 1.42857143;
      vertical-align: top;
    }
  </style>
</head>
<body onload="window.print();">
<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

  <div class="wrapper">
    <section class="invoice">
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
      </div>

      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Fastag Report</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">
            <p><strong>Bank Account:</strong> <?php echo e($bank_account->bank); ?></p>
            <p><strong>Account Number:</strong> <?php echo e($bank_account->account_no); ?></p>
          </div>
          <div class="col-md-6 text-right">
            <p><strong>From Date:</strong> <?php echo e(Helper::indianDateFormat($from_date)); ?></p>
            <p><strong>To Date:</strong> <?php echo e(Helper::indianDateFormat($to_date)); ?></p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL#</th>
                  <th>Toll Gate Name</th>
                  <th>Amount</th>
                  <th>Fastag</th>
                  <th>Date</th>
                  <th>Vehicle</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($k+1); ?></td>
                  <td><?php echo e($row->toll_gate_name ?? '-'); ?></td>
                  <td><?php echo e(bcdiv($row->amount,1,2)); ?></td>
                  <td><?php echo e($row->fastag ?? '-'); ?></td>
                  <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
                  <td>
                    <?php if($row->vehicle_id): ?>
                      <?php echo e(optional(\App\Model\VehicleModel::find($row->vehicle_id))->license_plate ?? '-'); ?>

                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-right">
          <h4>Total Amount: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($total_amount,1,2)); ?></h4>
        </div>
      </div>
    </section>
  </div>
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/print-fastag.blade.php ENDPATH**/ ?>