<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo e(Hyvikk::get('app_name')); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" />
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/font-awesome.min.css')); ?>">
  <link href="<?php echo e(asset('assets/css/cdn/ionicons.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/fonts.css')); ?>">
  <style type="text/css">
    body { height: auto; }
  </style>
</head>
<body onload="window.print();">
  <?php
    $date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
  ?>

  <div class="wrapper">
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <span class="logo-lg">
              <img src="<?php echo e(asset('assets/images/'. Hyvikk::get('icon_img') )); ?>" class="navbar-brand" style="margin-top: -15px">
              <?php echo e(Hyvikk::get('app_name')); ?>

            </span>
            <small class="pull-right"> <b><?php echo app('translator')->getFromJson('fleet.date'); ?> : </b> <?php echo e(date('Y-m-d')); ?></small>
          </h2>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Parts Stock Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p><strong>Date Range:</strong> <?php echo e($request['date1'] ?? 'N/A'); ?> to <?php echo e($request['date2'] ?? 'N/A'); ?></p>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Part Name</th>
                <th>Category</th>
                <th>Manufacturer</th>
                <th>Stock</th>
                <th>Tyres Used</th>
                <th>Tyre Numbers</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  $total_stock = 0;
                  $total_tyres_used = 0;
              ?>
              <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                    $total_stock += $part->stock ?? 0;
                    $total_tyres_used += $tyres_used[$part->id]->total_used ?? 0;
                  ?>
                <tr>
                  <td><?php echo e($k+1); ?></td>
                  <td><?php echo e($part->item ?? 'N/A'); ?></td>
                  <td><?php echo e($part->category->name ?? 'N/A'); ?></td>
                  <td><?php echo e($part->manufacturer_details->name ?? 'N/A'); ?></td>
                  <td><?php echo e($part->stock ?? 'N/A'); ?></td>
                  <td><?php echo e($tyres_used[$part->id]->total_used ?? 0); ?></td>
                  <td>
                    <?php
                      $tyre_numbers = $part->tyres_used ?? '';
                      if (!empty($tyre_numbers)) {
                        $numbers_array = explode(',', $tyre_numbers);
                        $formatted_numbers = [];
                        foreach (array_chunk($numbers_array, 4) as $chunk) {
                          $formatted_numbers[] = implode(', ', $chunk);
                        }
                        echo nl2br(implode("\n", $formatted_numbers));
                      } else {
                        echo 'N/A';
                      }
                    ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          <table class="table table-bordered">
            <tr>
              <th>Total Stock</th>
              <th>Total Tyres Used</th>
            </tr>
            <tr>
              <td> <?php echo e(number_format($total_stock)); ?></td>
              <td> <?php echo e(number_format($total_tyres_used)); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
</body>
</html>

<?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/print_stock.blade.php ENDPATH**/ ?>