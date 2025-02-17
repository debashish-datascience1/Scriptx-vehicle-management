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
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.fuelReport'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.fuelReport'); ?>
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.fuel_post','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']); ?>

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']); ?>

							<?php echo Form::select('fuel_type',$fuel_types,$request['fuel_type'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'fuel_type','placeholder'=>'ALL']); ?>

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1', isset($date1) ? Helper::indianDateFormat($date1) : null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('date2', isset($date2) ? Helper::indianDateFormat($date2) : null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-fuel-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
				Fuel Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th width="12%"><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
							<th>Vendor</th>
							<th>Fuel</th>
							<th>Quantity</th>
              				<th>Price Per Litre</th>
              				<th>CGST</th>
              				<th>SGST</th>
							<th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($k+1); ?></td>
							<td>
								<?php echo e(Helper::getCanonicalDate($f->date,'default')); ?>			
							</td>
							<td><strong><?php echo e($f->vehicle_data->license_plate); ?></strong></td>
							<td>
								<?php if(!empty($f->vendor_name) && !empty($f->vendor)): ?>
									<?php echo e($f->vendor->name); ?>

								<?php else: ?>
									<span style="color: red"><small>No Vendor Selected</small></span>
								<?php endif; ?>
							</td>
							<td><?php echo e($f->fuel_details->fuel_name); ?></td>
							<td>
								<?php echo e($f->qty); ?>

							</td>
              				<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->cost_per_unit,1,2)); ?></td>
              				<td>
								  <?php if(!empty($f->is_gst)): ?>
									<?php echo e(!empty($f->cgst) ? $f->cgst."%" : ''); ?> <br>
									<?php echo e(!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''); ?>

								  <?php endif; ?>
							</td>
              				<td>
								<?php if(!empty($f->is_gst)): ?>
									<?php echo e(!empty($f->sgst) ? $f->sgst."%" : ''); ?> <br>
									<?php echo e(!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''); ?>

								<?php endif; ?>
							</td>
							<td>
								<?php if(!empty($f->grand_total)): ?>
								<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->grand_total,1,2)); ?>

								<?php else: ?>
								<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($f->qty * $f->cost_per_unit,1,2)); ?>

								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
							<th>Vendor</th>
							<th>Fuel</th>
							<th>Quantity</th>
              				<th>Price Per Litre</th>
              				<th>CGST</th>
              				<th>SGST</th>
							<th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
						<th style="float:right">Total Amount: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($fuel->sum('gtotal'),1,2)); ?></th>
						<th style="float:right">Total Liter:  <?php echo e(bcdiv($fuel_totalqty,1,2)); ?> ltr</th>
					</tr>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/fuel.blade.php ENDPATH**/ ?>