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
          <h3>Service Reminder Report</h3>
          <?php if(!empty($vehicle_id)): ?>
          <h4><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>- <strong><?php echo e($vehicle->license_plate); ?></strong></h4>
          <?php endif; ?>
          <small><?php echo e($from_date); ?> - <?php echo e($to_date); ?></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>SL#</th>
                <?php if(empty($vehicle_id)): ?>
                <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                <?php endif; ?>
                <th><?php echo app('translator')->getFromJson('fleet.service_item'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.start_date'); ?> / <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> </th>
                <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.date'); ?>)</th>
                <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.meter'); ?>)</th>
            </thead>

            <tbody>
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($k+1); ?></td>
                    <?php if(empty($vehicle_id)): ?>
                    <td><strong><?php echo e($reminder->vehicle->license_plate); ?></strong></td>
                    <?php endif; ?>
                    <td>
                        <?php echo e($reminder->services['description']); ?>

                        <br>
                        <?php echo app('translator')->getFromJson('fleet.interval'); ?>: <?php echo e($reminder->services->overdue_time); ?> <?php echo e($reminder->services->overdue_unit); ?>

                        <?php if($reminder->services->overdue_meter != null): ?>
                        <?php echo app('translator')->getFromJson('fleet.or'); ?> <?php echo e($reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                        <?php endif; ?>
                    </td>
                    <td> 
                        <?php echo app('translator')->getFromJson('fleet.start_date'); ?>: <?php echo e(date($date_format_setting,strtotime($reminder->last_date))); ?>

                        <br>
                        <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> <?php echo app('translator')->getFromJson('fleet.meter'); ?>: <?php echo e($reminder->last_meter); ?>

                    </td>
                    <td>
                        <?php ($interval = substr($reminder->services->overdue_unit,0,-3)); ?>
                        <?php if($reminder->services->overdue_time != null): ?>
                          <?php ($int = $reminder->services->overdue_time.$interval); ?>
                        <?php else: ?>
                          <?php ($int = Hyvikk::get('time_interval')."day"); ?>
                        <?php endif; ?>
                          
                        <?php if($reminder->last_date != 'N/D'): ?>
                         <?php ($date = date('Y-m-d', strtotime($int, strtotime($reminder->last_date)))); ?> 
                        <?php else: ?>
                         <?php ($date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))))); ?> 
                        <?php endif; ?>
                        
                        <?php echo e(date($date_format_setting,strtotime($date))); ?>

                        <br>
                        <?php ($to = \Carbon\Carbon::now()); ?>
        
                        <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $date)); ?>
        
                        <?php ($diff_in_days = $to->diffInDays($from)); ?>
                        <?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($diff_in_days); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?>
                    </td>
                    <td>
                        <?php if($reminder->services->overdue_meter != null): ?>
                            <?php if($reminder->last_meter == 0): ?>
                                <?php echo e($reminder->vehicle->int_mileage + $reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                            <?php else: ?>
                                <?php echo e($reminder->last_meter + $reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/service_reminder/print_report.blade.php ENDPATH**/ ?>