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
          <h3>Customer Payment Report</h3>
          <h4><?php echo e($customer_data->name); ?></h4>
          <small><?php echo e(Helper::getCanonicalDate($from_date,'default')); ?> - <?php echo e(Helper::getCanonicalDate($to_date,'default')); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
        <span style="float: right;font-weight:700"> Opening Balance : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($opening_balance,1,2)); ?></span> 
    <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <thead>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              <th>Ref. No.</th>
              <th>Particulars</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
             <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
            <tr>
              <td><?php echo e($k+1); ?></td>
              <td nowrap><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
              <td><?php echo e($row->transaction_id); ?></td>
              <td>
                <?php if($row->param_id==18): ?>
                  
                  <?php if($row->is_bulk!=1): ?>    
                    Freight of <?php echo e(Hyvikk::get('currency')); ?><?php echo e($row->booking->total_price); ?> containing <?php echo e($row->booking->material); ?>(<?php echo e($row->booking->loadqty); ?> <?php echo e(Helper::getParamFromID($row->booking->loadtype)->label); ?>) transported by <strong><?php echo e($row->booking->vehicle->license_plate); ?></strong>(<?php echo e($row->booking->driver->name); ?>) on <?php echo e(Helper::getCanonicalDateTime($row->booking->pickup,'default')); ?> for <?php echo e($row->booking->distance); ?> from <?php echo e($row->booking->pickup_addr); ?> to <?php echo e($row->booking->dest_addr); ?> in <?php echo e($row->booking->duration_map); ?>

                  <?php endif; ?>
                  <?php if($row->is_bulk==1): ?> 
                    
                    Bulk Paid towards Booking
                  <?php endif; ?>
                <?php else: ?>
                  <?php echo e(dd($row)); ?>

                <?php endif; ?>
              </td>
              <td>
                <?php if($row->is_bulk!=1): ?>
                    <?php echo e(bcdiv($row->total,1,2)); ?>

                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <?php if($row->is_bulk==1): ?>
                   <?php echo e(bcdiv($row->total,1,2)); ?>

                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td><?php echo e(bcdiv($row->new_total,1,2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="3"></th>
              <th>A/C TOTAL</th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->where('is_bulk',null)->sum('total'),1,2)); ?></th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->where('is_bulk',1)->sum('total'),1,2)); ?></th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->reverse()->first()->new_total,1,2)); ?></th>
            </tr>
          </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/customerPaymentPrint.blade.php ENDPATH**/ ?>