
<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active">Service Reminder Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Service Reminder Report
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.service-reminder','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-4">
                        
						<div class="form-group">
							<?php echo Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control fullsize','id'=>'vehicle_id','placeholder'=>'Select Vehicle']); ?>

						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1', $request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('date2', $request['date2'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-service-reminder-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
					</div>
				</div>
				<?php echo Form::close(); ?>

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
					Service Reminder Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
                            <th>SL#</th>
                            <?php if(empty($vehicle_id)): ?>
                            <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                            <?php endif; ?>
							<th><?php echo app('translator')->getFromJson('fleet.service_item'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.start_date'); ?> / <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> </th>
							<th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.date'); ?>)</th>
							<th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.meter'); ?>)</th>
						</tr>
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
					<tfoot>
						<tr>
                            <th>SL#</th>
                            <?php if(empty($vehicle_id)): ?>
                            <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                            <?php endif; ?>
							<th><?php echo app('translator')->getFromJson('fleet.service_item'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.start_date'); ?> / <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> </th>
							<th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.date'); ?>)</th>
							<th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.meter'); ?>)</th>
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
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#date1').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
  });
</script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#vehicle_id").select2();
		$('#myTable tfoot th').each( function () {
	      var title = $(this).text();
	      $(this).html( '<input type="text" placeholder="'+title+'" />' );
	    });
	    var myTable = $('#myTable').DataTable( {
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
	});

	
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/service_reminder/report.blade.php ENDPATH**/ ?>