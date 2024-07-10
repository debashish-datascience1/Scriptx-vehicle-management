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
<li class="breadcrumb-item active">Account Statement</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Account Statement
				</h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.statement','method'=>'post','class'=>'form-inline']); ?>

				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<?php echo Form::text('date1', isset($from_date) ? Helper::indianDateFormat($from_date) : null,['class' => 'form-control fullsize','placeholder'=>__('fleet.start_date'),'readonly']); ?>

						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<?php echo Form::text('date2', isset($to_date) ? Helper::indianDateFormat($to_date) : null,['class' => 'form-control fullsize','placeholder'=>__('fleet.end_date'),'readonly']); ?>

						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_statement'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-statement')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
				Account Statement
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th>Invoice ID</th>
							<th>Method</th>
							<th>Type</th>
							<th>Particulars</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($k+1); ?></td>
							<td><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></td>
							<td><?php echo e($t->transaction->transaction_id); ?></td>
							<td><?php echo e($t->method->label); ?></td>
							<td><?php echo e($t->transaction->pay_type->label); ?></td>
							<td>
								<?php if($t->transaction->param_id==18 && $t->transaction->advance_for==21): ?>
									<?php echo e(Hyvikk::get('currency')); ?>  <?php echo e($t->transaction->booking->advance_pay); ?> advance given to <?php echo e($t->transaction->booking->driver->name); ?> for Booking references <strong><?php echo e(!empty(Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)) ? Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)->transaction_id : 'n/a'); ?> </strong>  on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php elseif($t->transaction->param_id==18 && $t->transaction->advance_for==22): ?>
									<?php echo e(Hyvikk::get('currency')); ?>  <?php echo e($t->transaction->booking->payment_amount); ?> paid by <?php echo e($t->transaction->booking->customer->name); ?> for Booking on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php elseif($t->transaction->param_id==18): ?>
									<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->amount,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed <?php echo e($t->transaction->type==23 ? "to" : "from"); ?> <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php elseif($t->transaction->param_id==19): ?>
									<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed towards <?php echo e($t->transaction->payroll->driver->name); ?> for the month of <strong><?php echo e(date('F-Y',strtotime($t->transaction->payroll->for_date))); ?>/<?php echo e(date('m-Y',strtotime($t->transaction->payroll->for_date))); ?></strong>  <?php echo e($t->transaction->type==23 ? "to" : "from"); ?> <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php elseif($t->transaction->param_id==20): ?>
									<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed towards <?php echo e($t->transaction->fuel->vendor->name); ?> for <strong><?php echo e($t->transaction->fuel->vehicle_data->license_plate); ?></strong> <?php echo e($t->transaction->type==23 ? "to" : "from"); ?>  <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php else: ?>
									<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($t->transaction->total,1,2)); ?> <?php echo e($t->transaction->pay_type->label); ?>ed <?php echo e($t->transaction->type==23 ? "to" : "from"); ?> <?php echo e($t->transaction->params->label); ?> on <strong><?php echo e(Helper::getCanonicalDate($t->dateof,'default')); ?></strong>
								<?php endif; ?>
							</td>
							<td>
								<?php if(!in_array($t->transaction->param_id,[18,20,26])): ?>
									<?php echo e(bcdiv($t->transaction->total,1,2)); ?>

								<?php else: ?>
									<?php echo e(bcdiv($t->amount,1,2)); ?>

								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
							<th>Invoice ID</th>
							<th>Method</th>
							<th>Type</th>
							<th>Action</th>
							<th>Amount</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
						<th style="float:right">Closing Balance: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($closingBalance,1,2)); ?></th>
						<th style="float:right">Opening Balance: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($openingBalance,1,2)); ?></th>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/statement.blade.php ENDPATH**/ ?>