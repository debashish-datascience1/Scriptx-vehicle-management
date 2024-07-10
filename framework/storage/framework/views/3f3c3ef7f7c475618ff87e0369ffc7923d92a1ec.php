
<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
	.itaDates{font-weight: 600;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('vehicle-docs.index')); ?>">Vehicle Documents</a></li>
<li class="breadcrumb-item active">Upcoming Renewal Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Upcoming Renewal Report
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'vehicle-docs.upcoming-report','method'=>'post','class'=>'form-inline']); ?>

				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							<?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicle_Array,$request['vehicle_id'],['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']); ?>

						</div>
					</div>
					
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-upcoming-renewal-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
				Upcoming Renewal Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>	
							<th><?php echo e($dt); ?></th>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					
						<tr>
							<td><?php echo e($k+1); ?></td>
							<th><?php echo e($d->license_plate); ?></th>
							<?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kb=>$db): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<td>
							<?php	$dbDocs = Helper::dynamicLastDate($d->id,$kb); ?>
								
								

								<?php
									if ($dbDocs->status)
										$date = $dbDocs->date;
									else
									 	$date = !empty($d->getMeta($docparamArray[$kb][2])) ? $d->getMeta($docparamArray[$kb][2]) : '';
								?>
								
								<?php if(!empty($date) && strtotime($date)<=strtotime(date('Y-m-d'))): ?>
									<strong><?php echo e(Helper::getCanonicalDate($date,'default')); ?></strong>
								<?php elseif(!empty($date) && strtotime($date)<=strtotime(date('Y-m-d')."+15 days")): ?>
									<i class="itaDates"><?php echo e(Helper::getCanonicalDate($d->getMeta($docparamArray[$kb][2]),'default')); ?></i>
								<?php else: ?>
									<?php echo e(Helper::getCanonicalDate($date,'default')); ?>

								<?php endif; ?>
							</td>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $df): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>	
							<th><?php echo e($df); ?></th>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
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
        format: 'yyyy-mm-dd'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#vehicle_id,#documents").select2();
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/vehicle_docs/upcomingreport.blade.php ENDPATH**/ ?>