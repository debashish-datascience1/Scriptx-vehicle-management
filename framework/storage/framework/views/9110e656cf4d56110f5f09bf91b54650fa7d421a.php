<?php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')?>
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
				<h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.stock_report'); ?>
        </h3>
			</div>

			<div class="card-body">
				<?php echo Form::open(['route' => 'reports.stock','method'=>'post','class'=>'form-block']); ?>

				<div class="row newrow">
					<div class="col-md-6">
                        <div class="form-group">
                            <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

                            <?php echo Form::select("vendor_id",$vendors,null,['class'=>'form-control vendor_id','id'=>'vendor_id','placeholder'=>'Select Vendor','required']); ?>

                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']); ?>

							</div>
						</div>
					</div>
				</div>	
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
						<button type="submit" formaction="<?php echo e(url('admin/print-stock-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
					</div>
				</div>
				<?php echo Form::close(); ?>

			</div>
		</div>
	</div>
</div>
<?php if(isset($invoices)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    Parts Invoices
                </h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Vendor</th>
                            <th>Bill No</th>
                            <th>Date of Purchase</th>
							<th>Parts</th>
                            <th>Tyre Numbers</th>
                            <th>Sub Total</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($k+1); ?></td>
                            <td><?php echo e($invoice->vendor->name); ?></td>
                            <td><?php echo e($invoice->billno); ?></td>
                            <td><?php echo e($invoice->date_of_purchase); ?></td>
							<td>
                                <?php $__currentLoopData = $invoice->partsDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($detail->parts_zero): ?>
                                        <?php echo e($detail->parts_zero->item ?? 'N/A'); ?> 
                                        <?php echo e($detail->parts_zero->category->name ?? 'N/A'); ?> 
                                        (<?php echo e($detail->parts_zero->manufacturer_details->name ?? 'N/A'); ?>)
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                    <br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
							<td>
								<?php $__currentLoopData = $invoice->partsDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php
										$partsModel = App\Model\PartsModel::find($dat->parts_id);
										$tyre_numbers = $partsModel ? $partsModel->tyres_used : '';
										$numbers_array = explode(',', $tyre_numbers);
										$formatted_numbers = [];

										foreach (array_chunk($numbers_array, 4) as $chunk) {
											$formatted_numbers[] = implode(', ', $chunk);
										}

										echo nl2br(implode("\n", $formatted_numbers));
									?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</td>
                            <td><?php echo e($invoice->sub_total); ?></td>
                            <td><?php echo e($invoice->grand_total); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>SL#</th>
                            <th>Vendor</th>
                            <th>Bill No</th>
                            <th>Date of Purchase</th>
							<th>Parts</th>
                            <th>Tyre Numbers</th>
                            <th>Sub Total</th>
                            <th>Grand Total</th>
                        </tr>
                    </tfoot>
                </table>
                <br>
            
                <table class="table">
                    <tr>
                        <th style="float:right">Total Sub Total: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total_sub_total); ?></th>
                        <th style="float:right">Total Grand Total: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total_grand_total); ?></th>
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
		// $("#vehicle_id,#customer_id").select2();
        $("#vendor_id").select2();

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/stock.blade.php ENDPATH**/ ?>