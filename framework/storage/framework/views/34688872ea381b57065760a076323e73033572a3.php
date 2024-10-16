<?php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Work Order Vendor Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Work Order Vendor Report
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.vendor-work-order','method'=>'post','class'=>'form-inline']); ?>

        <div class="row newrow">
					<div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              <?php echo Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']); ?>

              <?php echo Form::select('vendor',$vendors,$request['vendor'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Vendor']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

              &nbsp;
              <?php echo Form::text('from_date',isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,['class'=>'form-control fullsize','readonly']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

              &nbsp;
              <?php echo Form::text('to_date',isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,['class'=>'form-control fullsize','readonly']); ?>

            </div>
          </div>
					<div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              <?php echo Form::label('status', __('fleet.status'), ['class' => 'form-label']); ?>

              &nbsp;
              <?php echo Form::select('status',$status,$request['status'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Status']); ?>

            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-4">
            <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
            <button type="submit" formaction="<?php echo e(url('admin/print-work-order-vendor-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
          </div>
        </div>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php if(isset($result)): ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.report'); ?>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover"  id="myTable">
        <thead>
        <tr>
              <th>SL#</th>
              <th width="10%">Date</th>
              <?php if($is_vendor!=true): ?>
              <th>Vendor</th>
              <?php endif; ?>
              <th>Vehicle</th>
              <th>Type</th>
              <th>Is Own</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Part Name</th>
              <th>Quantity</th>
              <th>Tyres Used</th>
              <th>Source</th>
            </tr>
          </thead>
          <tbody>
            <?php $slNo = 1; ?>
            <?php $__currentLoopData = $processedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $__currentLoopData = $order['parts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partName => $partData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($slNo++); ?></td>
                  <td><?php echo e(Helper::getCanonicalDate($order['required_by'],'default')); ?></td>
                  <?php if($is_vendor!=true): ?>
                  <td><?php echo e($order['vendor']->name); ?></td>
                  <?php endif; ?>
                  <td><strong><?php echo e(strtoupper($order['vehicle']->license_plate)); ?></strong></td>
                  <td><?php echo e($order['vendor']->type); ?></td>
                  <td>
                      <?php if($partData['is_own'] == 1): ?>
                          Yes
                      <?php elseif($partData['is_own'] == 0): ?>
                          No
                      <?php else: ?>
                          Unknown
                      <?php endif; ?>
                  </td>
                  <td><?php echo e($order['status']); ?></td>
                  <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($order['price'],2)); ?></td>
                  <td><?php echo e($partName); ?></td>
                  <td><?php echo e($partData['qty']); ?></td>
                  <td><?php echo e(implode(', ', $partData['tyres'])); ?></td>
                  <td><?php echo e($partData['is_own'] ? 'Own Inventory' : 'Vendor'); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              <?php if($is_vendor!=true): ?>
              <th>Vendor</th>
              <?php endif; ?>
              <th>Vehicle</th>
              <th>Type</th>
              <th>Is Own</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Part Name</th>
              <th>Quantity</th>
              <th>Parts Used</th>
              <th>Tyres Used</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>
                <th style="float:right">Grand Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($gtotal,1,2)); ?></th>
            </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#user_id").select2();
	});
</script>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    // $("#driver").select2();
	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
      // dom: 'Bfrtip',
      buttons: [{
           extend: 'collection',
              text: 'Export',
              buttons: [
                  'copy',
                  'excel',
                  'csv',
                  'pdf',
              ]}
      ],
      "language": {
               "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
            },
      "initComplete": function() {
              myTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    that.search(this.value).draw();
                });
              });
            }
    });

    // Dates
    $('#from_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/report.blade.php ENDPATH**/ ?>