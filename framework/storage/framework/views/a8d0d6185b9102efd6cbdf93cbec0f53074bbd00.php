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
          <h3>Parts Invoice Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php if(isset($request['vendor_id']) && $request['vendor_id']): ?>
            <p><strong>Vendor:</strong> <?php echo e($vendors[$request['vendor_id']]); ?></p>
          <?php endif; ?>
          <p><strong>Date Range:</strong> <?php echo e($request['date1'] ?? 'N/A'); ?> to <?php echo e($request['date2'] ?? 'N/A'); ?></p>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Vendor</th>
                <th>Bill No</th>
                <th>Date of Purchase</th>
                <th>Parts</th>
                <th>Tyre Numbers</th>
                <th>Sub Total</th>
                <th>Grand Total</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($k+1); ?></td>
                  <td><?php echo e($invoice->vendor->name); ?></td>
                  <td><?php echo e($invoice->billno); ?></td>
                  <td><?php echo e(date($date_format_setting, strtotime($invoice->date_of_purchase))); ?></td>
                  <td>
                    <?php $__currentLoopData = $invoice->partsDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($detail->parts_zero): ?>
                        <?php echo e($detail->parts_zero->item ?? 'N/A'); ?> 
                        <?php echo e($detail->parts_zero->category->name ?? 'N/A'); ?> 
                        (<?php echo e($detail->parts_zero->manufacturer_details->name ?? 'N/A'); ?>)
                      <?php else: ?>
                        N/A
                      <?php endif; ?>
                      <br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </td>
                  <td>
                    <?php $__currentLoopData = $invoice->partsDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php
                        $partsModel = App\Model\PartsModel::find($detail->parts_id);
                        $tyre_numbers = $partsModel ? $partsModel->tyres_used : '';
                        $numbers_array = explode(',', $tyre_numbers);
                        $formatted_numbers = [];
                        foreach (array_chunk($numbers_array, 4) as $chunk) {
                          $formatted_numbers[] = implode(', ', $chunk);
                        }
                        echo nl2br(implode("\n", $formatted_numbers));
                      ?>
                      <br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </td>
                  <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($invoice->sub_total, 2)); ?></td>
                  <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($invoice->grand_total, 2)); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          
          <table class="table table-bordered">
            <tr>
              <th>Total Sub Total</th>
              <th>Total Grand Total</th>
            </tr>
            <tr>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_sub_total, 2)); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_grand_total, 2)); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/print_stock.blade.php ENDPATH**/ ?>