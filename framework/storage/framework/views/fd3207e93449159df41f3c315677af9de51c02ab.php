<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.fuelTypeReport'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
  .fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
  .DetailsChk{cursor: pointer;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.fuelTypeReport'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.fuel-type','method'=>'post','class'=>'form-inline']); ?>

        <div class="row newrow">
					<div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']); ?>

              <?php echo Form::select('vendor',$vendors,$request['vendor'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Fuel Vendor']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']); ?>

              <?php echo Form::select('fuel_type',$fuel_types,$request['fuel_type'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Fuel Type']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

              &nbsp;
              <?php echo Form::text('from_date',$request['from_date'] ?? null,['class'=>'form-control fullsize','readonly']); ?>

            </div>
          </div>
					<div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

              &nbsp;
              <?php echo Form::text('to_date',$request['to_date'] ?? null,['class'=>'form-control fullsize','readonly']); ?>

            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-4">
            <button type="submit" class="btn btn-info"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
            <button type="submit" formaction="<?php echo e(url('admin/print-fuel-vendor-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
              <th>Vendor</th>
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th><span class="fa fa-inr"></span> Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($k+1); ?></td>
              <td><?php echo e($row->vendor->name); ?></td>
              <td><?php echo e($row->fuel_details->fuel_name); ?></td>
              <td><?php echo e($row->qty); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->total_cost)); ?></td>
              <td><a class="DetailsChk" data-id="<?php echo e($row->vendor_name); ?>"  data-fuel="<?php echo e($row->fuel_type); ?>" data-toggle="modal" data-target="#FuelDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green"></span> Details</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Vendor</th>
              <th>Fuel Type</th>
              <th>Quantity</th>
              <th>Amount</th>
              <th style="pointer-events: none"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal view-->
<div id="FuelDetailsModal" class="modal fade" role="dialog">
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
    $('#from_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    //details of fuel showing
    $(".DetailsChk").on("click",function(){
      var id = $(this).data("id");
      var fuel = $(this).data("fuel");
      var from = $("#from_date").val();
      var to = $("#to_date").val();
      // var arr = {id:id,fuel:fuel};
      var arr = [id,fuel,from,to];
      $("#FuelDetailsModal .modal-body").load('<?php echo e(url("admin/reports/view_fuel_details")); ?>/'+arr,function(res){
        $("#FuelDetailsModal").modal({show:true})
      })
      
    })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/fuel/report.blade.php ENDPATH**/ ?>