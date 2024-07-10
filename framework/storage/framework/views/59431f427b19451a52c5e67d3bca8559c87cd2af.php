
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
<li class="breadcrumb-item active">Vehicle Overview Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Vehicle Overview Report
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.vehicles-overview','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-4">
                        
						<div class="form-group">
							<?php echo Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control','id'=>'vehicle_id','placeholder'=>'Select Vehicle','required']); ?>

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1', $request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group" style="margin-right: 5px">
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
						<button type="submit" formaction="<?php echo e(url('admin/print-vehicle-overview-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
					Vehicle Overview Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable1">
					
					<tr>
						<td align="center" style="font-size:23px;">
							<strong><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></strong>
							<?php if(!empty($vehicle->driver)): ?>
								<br><span><?php echo e(ucwords(strtolower($vehicle->driver->assigned_driver->name))); ?></span>
							<?php endif; ?>
							<?php if(!empty($vehicle->driver)): ?>
								<h6><?php echo e(Helper::getCanonicalDate($from_date)); ?> - <?php echo e(Helper::getCanonicalDate($to_date)); ?></h6>
							<?php endif; ?>
						</td>
					</tr>
					
					<tr>
						<table class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
								</tr>
								<tr>
									<th>No. of Booking(s)</th>
									<th>Total KM</th>
									<th>Total Fuel</th>
									<th>Total Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php if($book->totalbooking!=0 && !empty($book->totalbooking)): ?>
								<tr>
									<td><?php echo e($book->totalbooking); ?> bookings</td>
									<td><?php echo e($book->totalkms); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
									<td><?php echo e($book->totalfuel); ?> <?php echo e(Hyvikk::get('fuel_unit')); ?></td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($book->totalprice); ?></td>
								</tr>
								<?php else: ?>
								<tr>
									<td colspan="4" align='center' style="color: red">No Records Found...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</tr>
					<tr>
						<table class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
								</tr>
								<tr>
									<th>Fuel Type</th>
									<th>No. of Refuel(s)</th>
									<th>Quantity</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($fuels)): ?>
								<?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$fs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e($k); ?></td>
									<td><?php echo e(count($fs->id)); ?> time(s)</td>
									<td><?php echo e(array_sum($fs->ltr)); ?> <?php echo e($k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'); ?></td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals(array_sum($fs->total))); ?></td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php else: ?>
								<tr>
									<td colspan="4" align='center' style="color: red">No Records Found...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</tr>
					<tr>
						<table class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($advances->details)): ?>
								
								<tr>
									
									<td>
										<table class="table tabl-bordered table-striped">
											<thead>
												<th>#</th>
												<th>Head</th>
												<th>No. of Time(s)</th>
												<th>Amount</th>
											</thead>
											<tbody>
												<?php $__currentLoopData = $advances->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$det): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td><?php echo e($k+1); ?></td>
													<td><?php echo e($det->label); ?></td>
													<td><?php echo e($det->times); ?></td>
													<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)); ?></td>
												</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<th colspan="3" style="text-align:right;">Total</th>
													<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($advances->amount) ? Helper::properDecimals(array_sum($advances->amount)) : Helper::properDecimals(0)); ?></th>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								
								<?php else: ?>
								<tr>
									<td colspan="4" align='center' style="color: red">No Records Found...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</tr>
					<tr>
						<table class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
								</tr>
								<tr>
									<th>No. of Work Order(s)</th>
									<th>GST</th>
									<th>Total</th>
									<th>No. of Vendors</th>
									<th>Status</th>
									<th>Parts Used</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($wo->count) && $wo->count!=0): ?>
								
								<tr>
									<td><?php echo e($wo->count); ?></td>
									<td>
										<table class="table table-striped">
											<tr>
												<th>CGST</th>
												<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->cgst)); ?></td>
											</tr>
											<tr>
												<th>SGST</th>
												<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->sgst)); ?></td>
											</tr>
										</table>
									</td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->grand_total)); ?></td>
									<td><?php echo e($wo->vendors); ?></td>
									<td>
										<table class="table table-striped">
											<?php $__currentLoopData = $wo->status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<tr>
												<th><?php echo e($k); ?></th>
												<td><?php echo e(count($s)); ?></td>
											</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</table>
									</td>
									<td>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>Part</th>
													<th>Quantity</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php if(empty($partsUsed)): ?>
												<?php $__currentLoopData = $partsUsed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td><?php echo e($pu->part->title); ?></td>
													<td><?php echo e($pu->qty); ?></td>
													<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($pu->total)); ?></td>
												</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php else: ?>
												<tr>
													<td colspan="3" align='center' style="color: red">No Parts Used...</td>
												</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</td>
								</tr>
								
								<?php else: ?>
								<tr>
									<td colspan="6" align='center' style="color: red">No Records Found...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/vehicles/report.blade.php ENDPATH**/ ?>