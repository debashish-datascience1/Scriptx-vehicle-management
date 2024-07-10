<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
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
				<?php echo Form::open(['route' => 'reports.fuel_post','method'=>'post','class'=>'form-inline']); ?>

				<div class="row">
					<div class="form-group" style="margin-right: 10px">
						<?php echo Form::label('year', __('fleet.year1'), ['class' => 'form-label']); ?>

						<?php echo Form::select('year', $years, $year_select,['class'=>'form-control']);; ?>

					</div>

					<div class="form-group" style="margin-right: 10px">
						<?php echo Form::label('month', __('fleet.month'), ['class' => 'form-label']); ?>



						<select name="month" id="month" class="form-control">
							<option value="0" <?php if($month_select == '0'): ?> selected <?php endif; ?>>all</option>
							<option value="1" <?php if($month_select == '1'): ?> selected <?php endif; ?>>January</option>
							<option value="2" <?php if($month_select == '2'): ?> selected <?php endif; ?>>February</option>
							<option value="3" <?php if($month_select == '3'): ?> selected <?php endif; ?>>March</option>
							<option value="4" <?php if($month_select == '4'): ?> selected <?php endif; ?>>April</option>
							<option value="5" <?php if($month_select == '5'): ?> selected <?php endif; ?>>May</option>
							<option value="6" <?php if($month_select == '6'): ?> selected <?php endif; ?>>June</option>
							<option value="7" <?php if($month_select == '7'): ?> selected <?php endif; ?>>July</option>
							<option value="8" <?php if($month_select == '8'): ?> selected <?php endif; ?>>Augest</option>
							<option value="9" <?php if($month_select == '9'): ?> selected <?php endif; ?>>Septeber</option>
							<option value="10" <?php if($month_select == '10'): ?> selected <?php endif; ?>>October</option>
							<option value="11" <?php if($month_select == '11'): ?> selected <?php endif; ?>>November</option>
							<option value="12" <?php if($month_select == '12'): ?> selected <?php endif; ?>>December</option>
						</select>
					</div>

					<div class="form-group" style="margin-right: 10px">
						<?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

						<select id="vehicle_id" name="vehicle_id" class="form-control vehicles"  style="width: 250px;">
							<option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
							<?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($vehicle['id']); ?>" <?php if($vehicle['id']==$vehicle_id): ?> selected <?php endif; ?>><?php echo e($vehicle['make']); ?>-<?php echo e($vehicle['model']); ?>-<?php echo e($vehicle['license_plate']); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
					<div class="form-group">
              <?php echo Form::label('date1','From'); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('date1', $date1,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

              </div>
            </div>
            <div class="form-group" style="margin-right: 5px">
              <?php echo Form::label('date2','To'); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('date2', $date2,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']); ?>

              </div>
            </div>
					<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
					<button type="submit" formaction="<?php echo e(url('admin/print-fuel-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.meter'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.consumption'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e(date($date_format_setting,strtotime($f->date))); ?></td>
							<td><?php echo e($f->vehicle_data->make); ?>-<?php echo e($f->vehicle_data->model); ?>-<?php echo e($f->vehicle_data->license_plate); ?></td>
							<td>
							<b> <?php echo app('translator')->getFromJson('fleet.start'); ?>: </b><?php echo e($f->start_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

							<br>
							<b> <?php echo app('translator')->getFromJson('fleet.end'); ?>:</b><?php echo e($f->end_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

							</td>
							<td><?php echo e($f->consumption); ?>

								<?php if(Hyvikk::get('dis_format') == "km"): ?>
				                 <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?>KMPG <?php else: ?> KMPL <?php endif; ?>
				                <?php else: ?>
				                 <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?>MPG <?php else: ?> MPL <?php endif; ?>
				                <?php endif; ?>
							</td>
							<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($f->qty * $f->cost_per_unit); ?></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.meter'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.consumption'); ?></th>
							<th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
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
		$("#vehicle_id").select2();
		$('#myTable tfoot th').each( function () {
	      var title = $(this).text();
	      $(this).html( '<input type="text" placeholder="'+title+'" />' );
	    });
	    var myTable = $('#myTable').DataTable( {
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
	});

	
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/fuel.blade.php ENDPATH**/ ?>