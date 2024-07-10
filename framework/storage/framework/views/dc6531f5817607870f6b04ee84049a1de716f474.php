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
          <h3>Fuel Details Report</h3>
           
          <h5>Vendor : <strong><?php echo e($vendor_name); ?></strong> &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;  Fuel : <strong><?php echo e($fuel_name); ?></strong></h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <tr>
          <th>SL#</th>
          <th>Date</th>
          <th>Vehicle</th>
          <th>Quantity(ltr)</th> 
          <th>Cost per unit</th> 
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
         
        <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
        <tr>
          <td><?php echo e($k+1); ?></td>  
          <td><?php echo e(Helper::getCanonicalDate($data->date,'default')); ?></td> 
          <td><strong><?php echo e(strtoupper($data->vehicle_data->license_plate)); ?></strong></td>
           <td><?php echo e($data->qty); ?></td>  
           <td><?php echo e($data->cost_per_unit); ?></td>  
          <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($data->qty * $data->cost_per_unit,2,'.','')); ?></td> 
          
        </tr>
  
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
           <th colspan="4"></th>
           <th>Grand Total</th>
           <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($fuelSum,2,'.','')); ?></th>
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
</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/fuel/fuelDetails-print.blade.php ENDPATH**/ ?>