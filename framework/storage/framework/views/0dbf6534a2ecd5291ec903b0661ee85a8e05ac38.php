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
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.booking_report'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.booking_report'); ?>
        </h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.booking','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group">
							<?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicles,isset($request['vehicle_id']) ? $request['vehicle_id'] : null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']); ?>

						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo Form::label('customer_id', "Customer", ['class' => 'form-label']); ?>

							<?php echo Form::select('customer_id',$customers,isset($request['customer_id']) ? $request['customer_id'] : null,['class'=>'form-control vehicles fullsize','id'=>'customer_id','placeholder'=>'ALL']); ?>

						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-booking-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
          Bookings
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<th>Customer</th>
							<th>From-To</th>
							<th>Distance</th>
							<th>Fuel Consumption</th>
							<th>Pickup Date</th>
							<th>Dropoff Date</th>
							<th>Material</th>
							<th>Quantity</th>
							<th>Driver Advance</th>
							<th>Freight Price</th>
						</tr>
					</thead>
					<tbody>
					    
					<?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$bk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($k+1); ?></td>
							<th><?php echo e($bk->vehicle->license_plate); ?></th>
							<td><?php echo e($bk->customer->name); ?></td>
							<td>
								<?php if(!empty($bk->transaction_det)): ?>
								<strong>(<?php echo e($bk->transaction_det->transaction_id); ?>)</strong><br>
								<?php endif; ?>
								<?php echo e($bk->pickup_addr); ?> <i class="fa fa-long-arrow-right "></i> <?php echo e($bk->dest_addr); ?> 
								<?php echo e($bk->getMeta('fodder_km')); ?>

								<?php if(!empty($bk->getMeta('fodder_km'))): ?>
								<?php if(!empty($bk->transaction_details) && !empty($bk->transaction_details->booking)): ?>
								<br>
								<small><?php echo e($bk->dest_addr); ?> <span class="fa fa-long-arrow-right"></span>
									<?php echo e($bk->transaction_details->booking->pickup_addr); ?>

								</small><br>
								<small>Distance : <?php echo e(!empty($bk->getMeta('fodder_km')) ? Helper::properDecimal($bk->getMeta('fodder_km') ?? 0)."km" :null); ?></small><br>
								<small>Fuel : <?php echo e(!empty($bk->getMeta('fodder_consumption')) ? Helper::properDecimal($bk->getMeta('fodder_consumption') ?? 0)."ltr" :null); ?></small><br>
								<small>References Booking <strong><?php echo e($bk->transaction_details->transaction_id); ?></strong></small>
								<?php endif; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo e($bk->getMeta('distance')); ?>

								<?php if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption'))): ?>
								<br>
								<strong>+ <?php echo e(Helper::properDecimal($bk->getMeta('fodder_km') ?? 0)); ?> km</strong>
								<?php endif; ?>
							</td>
							<td><?php if($bk->getMeta('pet_required') != "Infinity"): ?>
								<?php echo e(Helper::properDecimal($bk->getMeta('pet_required') ?? 0)); ?> ltr
								<?php else: ?>
								0
								<?php endif; ?>
								<?php if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption'))): ?>
								<br>
								<strong>+ <?php echo e($bk->getMeta('fodder_consumption') ?? 0); ?> ltr</strong>
								<?php endif; ?>
							</td>
							<td nowrap><?php echo e(Helper::getCanonicalDate($bk->pickup,'default')); ?></td>
							<td nowrap><?php echo e(Helper::getCanonicalDate($bk->dropoff,'default')); ?></td>
							<td><?php echo e($bk->material); ?></td>
							<td><?php echo e($bk->loadqty); ?> <?php echo e($loadset[$bk->getMeta('loadtype')]); ?></td>
							<td><?php echo e($bk->advance_pay); ?></td>
							<td><?php echo e($bk->total_price); ?></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<th>Customer</th>
							<th>From-To</th>
							<th>Distance</th>
							<th>Fuel Consumption</th>
							<th>Pickup Date</th>
							<th>Dropoff Date</th>
							<th>Material</th>
							<th>Quantity</th>
							<th>Driver Advance</th>
							<th>Freight Price</th>
						</tr>
					</tfoot>
				</table>
				<br>
			
				<table class="table">
					<tr>
						<th style="float:right">Addtional Distance : <?php echo e(bcdiv($fodderdistance,1,2)); ?> km</th>
						<th style="float:right">Total Distance : <?php echo e(bcdiv($total_distance,1,2)); ?> km</th>
						<th style="float:right">Additional Fuel : <?php echo e(bcdiv($fodderfuel,1,2)); ?> ltr</th>
						<th style="float:right">Total Fuel: <?php echo e(bcdiv($total_fuel,1,2)); ?> ltr</th>
						<th style="float:right">Grand Total: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($total_price,1,2)); ?></th>
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
    $('#date1,#date2').datepicker({
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
		$("#vehicle_id,#customer_id").select2();
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/booking.blade.php ENDPATH**/ ?>