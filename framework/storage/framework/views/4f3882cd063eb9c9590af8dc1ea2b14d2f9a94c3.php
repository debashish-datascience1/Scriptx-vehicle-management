<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Advance Report</a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.DailyAdvReport'); ?></li>
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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.DailyAdvReport'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'daily-advance.report','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
        <?php if(count($years)>0): ?>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('year', __('fleet.year1'), ['class' => 'form-label']); ?>

            <?php echo Form::select('year', $years, $request['year'],['class'=>'form-control']);; ?>

          </div>
        <?php endif; ?>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('month', __('fleet.month'), ['class' => 'form-label']); ?>

            <?php echo Form::selectMonth('month',$request['month'],['class'=>'form-control','placeholder'=>'All']);; ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('driver', __('fleet.driver'), ['class' => 'form-label']); ?>

            
            &nbsp; <br>
            <?php echo Form::select('driver',$drivers,$request['driver'],['class'=>'form-control','placeholder'=>'Select Driver']); ?>

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
          <button type="submit" formaction="<?php echo e(url('admin/print-advance-driver-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
              <th>Driver</th>
              <th>Vehicle</th>
              <th><span class="fa fa-inr"></span> Salary</th>
              <th><span class="fa fa-inr"></span> Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $advances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->driver->name); ?></td>
              <td><?php echo e($row->assigned_vehicle->Vehicle->make); ?> - <?php echo e($row->assigned_vehicle->Vehicle->model); ?>-<strong><?php echo e($row->assigned_vehicle->Vehicle->license_plate); ?></strong></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->driver->salary); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(round($row->amount),2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Vendor Name</th>
              <th>Vehicle</th>
              <th>Salary</th>
              <th>Amount</th>
            </tr>
          </tfoot>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/daily_advance/report.blade.php ENDPATH**/ ?>