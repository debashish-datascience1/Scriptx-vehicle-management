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
          <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(Helper::getCanonicalDateTime($date,'default')); ?> / <?php echo e(Helper::getCanonicalDateTime($date)); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vendor Payment Report</h3>
          <?php if(!empty($vendor_data)): ?>
            <h4><?php echo e($vendor_data->name); ?></h4>
          <?php endif; ?>
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
              <td nowrap><?php echo e($row->transaction_id); ?></td>
              <td>
                <?php if($row->param_id==20): ?>
                  
                  <?php if($row->is_bulk!=1): ?>    
                    <?php echo e($row->fuel->qty); ?> ltr <?php echo e($row->fuel->fuel_details->fuel_name); ?> <?php echo e($row->fuel->cost_per_unit); ?> per unit total of  <?php echo e(bcdiv($row->fuel->qty * $row->fuel->cost_per_unit,1,2)); ?> fuel filled for <?php echo e($row->fuel->vehicle_data->license_plate); ?> 
                  <?php endif; ?>
                  <?php if($row->is_bulk==1): ?> 
                    
                    Bulk Paid towards Fuel
                    
                  <?php endif; ?>
                <?php elseif($row->param_id==26): ?>
                  <?php if($row->is_bulk!=1): ?>    
                   <?php echo e($row->parts->partsDetails->count()); ?> items added to sum total of <?php echo e($row->parts->partsDetails->sum('total')); ?>

                  <?php endif; ?>
                  <?php if($row->is_bulk==1): ?> 
                    Bulk Paid towards PartsInvoice
                  <?php endif; ?>
                <?php elseif($row->param_id==28): ?>
                  <?php if($row->is_bulk!=1): ?>    
                    WorkOrder having bill <?php echo e($row->workorders->bill_no); ?> amount <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->workorders->grand_total,1,2)); ?> paid to <?php echo e($row->workorders->vendor->name); ?> for <?php echo e($row->workorders->vehicle->license_plate); ?>

                  <?php endif; ?>
                  <?php if($row->is_bulk==1): ?> 
                    Bulk Paid towards WorkOrder
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
              <td><?php echo e($row->new_total); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="3"></th>
              <th>A/C TOTAL</th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->where('is_bulk',null)->sum('total'),1,2)+$vendor_data->opening_balance); ?></th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->where('is_bulk',1)->sum('total'),1,2)); ?></th>
              <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($transactions->reverse()->first()->new_total,1,2)); ?></th>
          </tr>
          </tbody>
            <tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/vendorPaymentPrint.blade.php ENDPATH**/ ?>