
<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
	.vevent{cursor: pointer;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active">Vehicle Advance Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Vehicle Advance Report
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.vehicle-advance-report','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']); ?>

							<?php echo Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control fullsize','id'=>'vehicle_id','placeholder'=>'Select Vehicle']); ?>

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('param_id', "Heads", ['class' => 'form-label']); ?>

							<?php echo Form::select('param_id',$heads,$request['param_id'] ?? null,['class'=>'form-control fullsize','id'=>'param_id','placeholder'=>'Select Head']); ?>

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1',isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('date2',isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']); ?>

							</div>
						</div>
					</div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-vehicle-advance-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
				Vehicle Advance Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
                            <th>Vehicle</th>
                            <th>Head</th>
                            <th><?php echo e(Hyvikk::get('currency')); ?> Amount</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $advanceDriver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
                            <td><?php echo e($k+1); ?></td>
							<td><strong><?php echo e($adv->vehicle->license_plate); ?></strong></td>
							<td><?php echo e($adv->param_name->label); ?></td>
							<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($adv->total,1,2)); ?></td>
							<td>
								<a class="vevent" data-id="<?php echo e($adv->vehicle_id); ?>" data-param="<?php echo e($adv->param_id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
                            <th>SL#</th>
                            <th>Vehicle</th>
                            <th>Head</th>
                            <th><?php echo e(Hyvikk::get('currency')); ?> Amount</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
                        <th style="float:right">Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($advanceDriver->sum('total'),1,2)); ?></th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal view-->
<div id="viewModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content" style="width:158%">
		<div class="modal-header" style="border-bottom:none;">
		  <h5>Fuel Details Report</h5>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		
		<div class="modal-body">
			Loading...
		</div>
		<div class="modal-footer">
		 
		  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
		</div>
		
	  </div>
	</div>
  </div>
  <!-- Modal -->
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
		$("#vehicle_id,#param_id").select2();
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

		$(".vevent").on("click",function(){
			var vehicle = $(this).data("id");
			var param = $(this).data("param");
			var from = $("#date1").val();
			var to = $("#date2").val();
			// var arr = {id:id,fuel:fuel};
			var arr = [vehicle,param,from,to];
			$("#viewModal .modal-body").load('<?php echo e(url("admin/reports/vehicle-advance/vehicle-head-advance-report")); ?>/'+arr,function(res){
				$("#viewModal").modal({show:true})
			})
			
		})


	});

	
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/vehicleadvance.blade.php ENDPATH**/ ?>