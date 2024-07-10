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

            <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(date($date_format_setting)); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Transaction Report</h3>
          <small><?php echo e(Helper::getCanonicalDate($date['from_date'])); ?></small> to
          <small><?php echo e(Helper::getCanonicalDate($date['to_date'])); ?></small>  
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>Transaction ID</th> 
              <th>Date</th>
              <th>Method</th>
              <th>From</th>
              <th>Bank</th>
              <th>Type</th>
              <th>Status</th>
              <th>Advance</th>
              <th>Remaining</th>
              <th>Total</th>
            </thead>
            <tbody>
                <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->transaction_id); ?></td>
                    <td><?php echo e(!empty($row->date) ? Helper::getCanonicalDate($row->date) : Helper::getCanonicalDate($row->created_at)); ?>

                    </td>
                    <td>
                        <?php echo e($row->pay_method->label); ?>

                    </td>
                    <td>
                        <?php echo e($row->params->label); ?><br>
                        <?php if(in_array($row->advance_for,[21,22]) && $row->param_id==18): ?>
                            <?php echo e($row->advancefor->label); ?>

                        <?php endif; ?>
                    </td>
                    <td>    
                        <?php echo e(empty($row->bank) ? '-' : $row->bank->bank); ?>

                    </td>
                    <td>
                        <?php echo e($row->pay_type->label); ?>

                    </td>
                    <td>
                        <?php if($row->is_completed==1): ?>
                            Completed
                        <?php elseif($row->is_completed==2): ?>
                            In Progress
                        <?php elseif(!$row->is_completed): ?>
                            -
                        <?php endif; ?>
                    </td>
                    <?php if(in_array($row->advance_for,[21,22])): ?>
                        <td><?php echo e(Helper::properDecimals($row->income_expense->amount)); ?></td>
                    <?php else: ?>
                        <td>-</td>
                    <?php endif; ?>
                    <td><?php echo e(Helper::properDecimals($row->incExp->remaining)); ?></td>
                    <td>
                        <?php echo e(Helper::properDecimals($row->total)); ?> <br>
                    </td>
                </tr>
                
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="8"></td>
                    <td><strong>Grand Total</strong></td>
                    <td><strong><?php echo e(Helper::properDecimals($totalTransaction)); ?></strong></td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
  <!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/print_transaction.blade.php ENDPATH**/ ?>