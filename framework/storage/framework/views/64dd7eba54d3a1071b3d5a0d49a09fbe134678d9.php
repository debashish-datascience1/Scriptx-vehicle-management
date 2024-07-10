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
    .balances{font-weight: 700;}
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

          <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(Helper::getCanonicalDateTime(date("Y-m-d H:i:s"),'default')); ?> / <?php echo e(Helper::getCanonicalDateTime(date("Y-m-d H:i:s"))); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Account Statement</h3>
          <h6><?php echo e(Helper::getCanonicalDate($from_date,'default')); ?> - <?php echo e(Helper::getCanonicalDate($to_date,'default')); ?></h6>
          
          <span class='balances'>Opening Balance : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($openingBalance,1,2)); ?></span> &nbsp;&nbsp;&nbsp;
         | &nbsp;&nbsp;&nbsp;<span class='balances'>Closing Balance : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($closingBalance,1,2)); ?></span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>SL#</th>
                <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
                <th>Invoice ID</th>
                <th>Method</th>
                <th>Type</th>
                <th>Particulars</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($k+1); ?></td>
                  <td nowrap><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></td>
                  <td><?php echo e($t->transaction->transaction_id); ?></td>
                  <td><?php echo e($t->method->label); ?></td>
                  <td><?php echo e($t->transaction->pay_type->label); ?></td>
                  <td>
                    <?php if($t->transaction->param_id==18 && $t->transaction->advance_for==21): ?>
                      <?php echo e(Hyvikk::get('currency')); ?>  <?php echo e($t->transaction->booking->advance_pay); ?> advance given to <?php echo e($t->transaction->booking->driver->name); ?> for Booking references <strong><?php echo e(Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)->transaction_id); ?></strong>   on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
                    <?php elseif($t->transaction->param_id==18 && $t->transaction->advance_for==22): ?>
                      <?php echo e(Hyvikk::get('currency')); ?>  <?php echo e($t->transaction->booking->payment_amount); ?> paid by <?php echo e($t->transaction->booking->customer->name); ?> for Booking on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
                    <?php elseif($t->transaction->param_id==19): ?>
                      <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed towards <?php echo e($t->transaction->payroll->driver->name); ?> for the month of <strong><?php echo e(date('F-Y',strtotime($t->transaction->payroll->for_date))); ?>/<?php echo e(date('m-Y',strtotime($t->transaction->payroll->for_date))); ?></strong>  <?php echo e($t->transaction->type==23 ? "to" : "from"); ?> <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
                    <?php elseif($t->transaction->param_id==20): ?>
                      <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed towards <?php echo e($t->transaction->fuel->vendor->name); ?> for <strong><?php echo e($t->transaction->fuel->vehicle_data->license_plate); ?></strong> <?php echo e($t->transaction->type==23 ? "to" : "from"); ?>  <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
                    <?php else: ?>
                      <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed <?php echo e($t->transaction->type==23 ? "to" : "from"); ?> <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if(!in_array($t->transaction->param_id,[18,20,26])): ?>
                      <?php echo e(bcdiv($t->transaction->total,1,2)); ?>

                    <?php else: ?>
                      <?php echo e(bcdiv($t->amount,1,2)); ?>

                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="3"></th>
              <th><strong>Closing Amount</strong></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($closingAmount,1,2)); ?></th>
              <th><strong>Grand Total</strong></th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->sum('amount'),1,2)); ?></th>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/statement-report.blade.php ENDPATH**/ ?>