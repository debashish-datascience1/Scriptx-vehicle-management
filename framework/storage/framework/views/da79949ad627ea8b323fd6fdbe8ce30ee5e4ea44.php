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

            
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Transaction Details Report</h3>
           
         
          
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <thead>
            <tr>
              <th>Date</th>
              <th>From</th>
              <th>Transaction ID</th>
              <th>Method</th>
              <th>Payment Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
             <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
            <tr>
              <td><?php echo e($row->created_at); ?></td>
              <td>
              <?php if($row->param_id==18): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                  <br>
                  <?php if($row->advance_for==21): ?>
                  <?php echo e($row->advancefor->label); ?>

                  <?php endif; ?>
                <?php elseif($row->param_id==19): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==20): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==25): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==26): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==27): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==28): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php elseif($row->param_id==29): ?>
                  <?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>

                <?php else: ?><?php echo e(dd($row->param_id)); ?>

               <?php endif; ?></td>
               <td><?php echo e($row->transaction_id); ?></td>
               <td><?php echo e($row->incExp->method->label); ?></td>
              <td> <?php if($row->type==23): ?>
                <span><?php echo e($row->pay_type->label); ?></span>
            <?php elseif($row->type==24): ?>
                <span><?php echo e($row->pay_type->label); ?></span>
            <?php endif; ?></td>
              <td><?php echo e($row->total); ?></td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <th colspan="4"></th>
              <th>Grand Total</th>
              <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($sumoftotal,2,'.','')); ?></th>
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
</html><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/transactions/reportDebitCredit-print.blade.php ENDPATH**/ ?>