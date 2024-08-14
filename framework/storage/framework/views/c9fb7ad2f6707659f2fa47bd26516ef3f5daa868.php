<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Customer Payment Report</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">

<style type="text/css">
.form-label{display:block !important;}
    .mybtn1
    {
     padding-top: 4px;
      padding-right: 8px;
      padding-bottom: 4px;
      padding-left: 8px;
    }
  
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
    .where_from,.advance_for{cursor: pointer;}
    .where_from{color:#fff!important; }
    .border-refund{border:2px solid #s02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .form-label{display:block !important;}
    .fullsize{width: 100% !important;}
	  .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	  .dateShow{padding-right: 13px}
  </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Customer Payment Report
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.customerPayment','method'=>'post','class'=>'form-block']); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('customer_id', 'Customers', ['class' => 'form-label']); ?>

              <?php echo Form::select('customer_id',$customers,$request['customer_id'] ?? null,['class'=>'form-control','id'=>'customer_id','placeholder'=>'Select Customer']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

              <?php echo Form::text('from_date',isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,['class'=>'form-control','readonly']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

              <?php echo Form::text('to_date',isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,['class'=>'form-control','readonly']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <button type="submit" class="btn btn-info gen_report"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
            <button type="submit" formaction="<?php echo e(url('admin/print-customer-payment')); ?>" formtarget="_blank"  class="btn btn-danger print_report"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
          </div>
        </div>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php if(isset($result)): ?>
<div class="container-fluid">
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
                <th>Date</th>
                <th>Ref. No.</th>
                <th>Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody>
               <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
              <tr>
                <td><?php echo e($k+1); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
                <td><?php echo e($row->transaction_id); ?></td>
                <td>
                  <?php if($row->param_id==18): ?>
                    
                    <?php if($row->is_bulk!=1): ?>    
                      Freight of <?php echo e(Hyvikk::get('currency')); ?><?php echo e($row->booking->total_price); ?> containing <?php echo e($row->booking->material); ?>(<?php echo e($row->booking->loadqty); ?> <?php echo e(Helper::getParamFromID($row->booking->loadtype)->label); ?>) transported by <strong><?php echo e($row->booking->vehicle->license_plate); ?></strong>(<?php echo e($row->booking->driver->name); ?>) on <?php echo e(Helper::getCanonicalDateTime($row->booking->pickup,'default')); ?> for <?php echo e($row->booking->distance); ?> from <?php echo e($row->booking->pickup_addr); ?> to <?php echo e($row->booking->dest_addr); ?> in <?php echo e($row->booking->duration_map); ?>

                    <?php endif; ?>
                    <?php if($row->is_bulk==1): ?> 
                      
                      Bulk Paid towards Booking
                    <?php endif; ?>
                  <?php else: ?>
                    <?php echo e(dd($row)); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <?php if($row->is_bulk!=1): ?>
                      <?php echo e(bcdiv($row->total,1,2)); ?>

                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($row->is_bulk==1): ?>
                     <?php echo e(bcdiv($row->total,1,2)); ?>

                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td><?php echo e(bcdiv($row->new_total,1,2)); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
            </tbody>
             <tfoot>
              <tr>
                <th>SL#</th>
                <th>Date</th>
                <th>Ref. No.</th>
                <th>Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
              </tr>
            </tfoot> 
          </table>
          <br>
          <table class="table">
            <tr> <th style="float:right">Opening Balance : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($opening_balance,1,2)); ?></th>
            </tr>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>


<!-- Modal -->
<div id="whereModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <div class="modal-content">
          Loading....
        </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div id="advanceForModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Advance Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            Loading...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
            </button>
          </div>
        </div>
    </div>
  </div>
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


  $(".where_from").on("click",function(){
    var id = $(this).data("id");
    var partsw = $(this).data("partsw");
    console.log(id);
    console.log(partsw);
    $("#whereModal .modal-content").load('<?php echo e(url("admin/accounting/where_from")); ?>/'+id,function(res){
      typeof partsw!="undefined" ? $(this).css('width','160%') : $(this).css('width','');
      $("#whereModal").modal({show:true})
    })
  })

  $(".advance_for").on("click",function(){
    var id = $(this).data("id");
    $("#advanceForModal .modal-body").load('<?php echo e(url("admin/accounting/advance_for")); ?>/'+id,function(res){
      $("#advanceForModal").modal({show:true})
    })
  })

  $(".gen_report,.print_report").on("click",function(){
    var blankTest = /\S/
    var customer_id = $("#customer_id").val();
    if(!blankTest.test(customer_id)){
      alert("Please choose a customer");
      $("#customer_id").focus();
      return false;
    }
  })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/customerPayment.blade.php ENDPATH**/ ?>