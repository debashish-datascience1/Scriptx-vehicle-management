<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Salary Advance Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .form-label{display:block !important;}
    .fullsize{width: 100% !important;}
	  /* .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;} */
	  .dateShow{padding-right: 13px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Salary Advance Report
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.salary-advance','method'=>'post','class'=>'form-block']); ?>

        <div class="row newrow">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('driver', __('fleet.driver'), ['class' => 'form-label']); ?>

              <?php echo Form::select('driver',$drivers,$request['driver'] ?? null,['class'=>'form-control','placeholder'=>'Select Driver']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

              <?php echo Form::text('from_date',$request['from_date'] ?? null,['class'=>'form-control','readonly']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

              <?php echo Form::text('to_date',$request['to_date'] ?? null,['class'=>'form-control','readonly']); ?>

            </div>
          </div>
        </div>
        <div class="row newrow">
          <div class="col-md-4">
            <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-salary-advance-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Date</th>
              <th><span class="fa fa-inr"></span> Salary</th>
              <th><span class="fa fa-inr"></span> Amount</th>
              <th> Remarks </th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $advances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($k+1); ?></td>
              <td><?php echo e($row->driver->name); ?></td>
              <td><strong><?php echo e($row->assigned_vehicle->Vehicle->license_plate); ?></strong></td>
              <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->driver->salary,1,2)); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->amount,1,2)); ?></td>
              <td><?php echo e($row->remarks); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Date</th>
              <th><span class="fa fa-inr"></span> Salary</th>
              <th><span class="fa fa-inr"></span> Amount</th>
              <th> Remarks </th>
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
    // $('#from_date').datepicker({
    //     autoclose: true,
    //     format: 'dd-mm-yyyy'
    // });
    // $('#to_date').datepicker({
    //     autoclose: true,
    //     format: 'dd-mm-yyyy'
    // });
    $("#from_date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });

    $("#to_date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });

    $("#driver").select2();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/daily_advance/report.blade.php ENDPATH**/ ?>