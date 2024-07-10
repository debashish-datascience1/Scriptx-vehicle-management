<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Transaction</a></li>
<li class="breadcrumb-item active">Transaction Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .form-label{display:block !important;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Transaction Report
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'accounting.report','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
          
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']); ?>

            
            &nbsp; <br>
            <?php echo Form::select('vendor',$from,$request['vendor'],['class'=>'form-control','placeholder'=>'Select Fuel Vendor']); ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']); ?>

            &nbsp; <br>
            <?php echo Form::select('fuel_type',$types,$request['fuel_type'],['class'=>'form-control','placeholder'=>'Select Fuel Type']); ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

            &nbsp;
            <?php echo Form::text('from_date',$request['from_date'],['class'=>'form-control','readonly']); ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

            &nbsp;
            <?php echo Form::text('to_date',$request['to_date'],['class'=>'form-control','readonly']); ?>

          </div>
          <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-vendor-vehicle-fuel-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
              <th>Date</th>
              <?php if($is_vendor!=true): ?>
              <th>Vendor</th>
              <?php endif; ?>
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th>Rate</th>
              <th><span class="fa fa-inr"></span> Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->date); ?></td>
              <?php if($is_vendor!=true): ?>
            <td>
              <?php if(!empty($row->vendor)): ?>
                <?php echo e($row->vendor->name); ?>

              <?php else: ?>
                <span class='badge badge-danger'>Unnamed Vendor</span>
              <?php endif; ?>
            </td>
              <?php endif; ?>
              <td><?php echo e($row->vehicle_data->make); ?>-<?php echo e($row->vehicle_data->model); ?>-<strong><?php echo e(strtoupper($row->vehicle_data->license_plate)); ?></strong></td>
              <td>
                <?php if(!empty($row->fuel_details)): ?>
                  <?php echo e($row->fuel_details->fuel_name); ?>

                <?php else: ?>
                  <span class='badge badge-danger'>Unnamed Fuel</span>
                <?php endif; ?>
              </td>
              <td><?php echo e($row->qty); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->cost_per_unit); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(round($row->qty * $row->cost_per_unit),2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Date</th>
              <?php if($is_vendor!=true): ?>
              <th>Vendor</th>
              <?php endif; ?>
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>

                
                
                <th style="float:right">Grand Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(round($fuelTotal),2)); ?></th>
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
	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
      dom: 'Bfrtip',
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

        // 'initComplete': function (settings, json){
        //     this.api().columns('.sum').every(function(){
        //         var column = this;

        //         var sum = column
        //             .data()
        //             .reduce(function (a, b) { 
        //             a = parseInt(a, 10);
        //             if(isNaN(a)){ a = 0; }                   

        //             b = parseInt(b, 10);
        //             if(isNaN(b)){ b = 0; }

        //             return a + b;
        //             });

        //         $(column.footer()).html('Sum: ' + sum);
        //     });
        // }
    });

    // Dates
    $('#from_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/transactions/report.blade.php ENDPATH**/ ?>