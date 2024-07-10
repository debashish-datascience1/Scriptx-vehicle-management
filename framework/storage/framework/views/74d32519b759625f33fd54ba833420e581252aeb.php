<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Transaction Report</li>
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
    .border-refund{border:2px solid #02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .badge-deposit{background: #b000bb;color:#fff;}
    .badge-revised{background: #da107f;color:#fff;}
    .badge-liability{background: #004e5c;color:#fff;}
    .badge-renewdocs{background: #2944ca;color:#fff;}
    .badge-view{font-size: 16px;}
    .badge-vehicleDoc{background: tomato;color: #fff;}
    .badge-otherAdv{background:darkcyan;color: #fff;}
    .badge-advanceRefund{background: deeppink;color: #fff;}
    .badge-vehicle-downpayment{background: darkgoldenrod;color: #fff;}
    .badge-vehicle-purchase{background: darkgray;color: #fff;}
    .badge-vehicle-emi{background: darkslateblue;color: #fff;}
    .badge-viwevent{background: #0091bd;color:#fff!important;cursor: pointer;}
    .btn-search{transition: .7s}
    .btn-search:hover{background:#9c27b0;border:1px solid white}

    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
  </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Transaction Report
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.transaction','method'=>'post','class'=>'form-inline']); ?>

        <div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
            <?php echo Form::label('from', 'Head', ['class' => 'form-label']); ?>

            <?php echo Form::select('from',$from,$request['from'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Head']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('payment', 'Payment', ['class' => 'form-label']); ?>

              <?php echo Form::select('payment_type',$payment_type,$request['payment_type'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Type']); ?>

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
					<div class="col-md-4 mt-2">
            <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
            <button type="submit" formaction="<?php echo e(url('admin/print-transaction-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
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
          <?php echo app('translator')->getFromJson('fleet.report'); ?>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover"  id="myTable">
          <thead>
            <tr>
              <th width="9%">SL#</th>
              <th>Date</th>
              <th>Head</th>
              <th>Towards</th>
              <th>Method</th>
              <th>Payment Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
             <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
            <tr>
              <td><?php echo e($k+1); ?></td>
              <td>
                <?php echo e(Helper::getCanonicalDate($row->dateof,'default')); ?>

              </td>
              <td>
                <?php if($row->param_id==18): ?>
                  <a class="badge badge-success where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                  
                  <br>
                  <?php if($row->advance_for==21): ?>
                  <a class="badge badge-warning advance_for" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#advanceForModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><?php echo e($row->advancefor->label); ?></a>
                  
                  <?php endif; ?>
                <?php elseif($row->param_id==19): ?>
                  <a class="badge badge-info where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==20): ?>
                  <a class="badge badge-fuel where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==25): ?>
                  <a class="badge badge-driver-adv where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==26): ?>
                  <a class="badge badge-parts where_from" data-id="<?php echo e($row->id); ?>" data-partsw=<?php echo e($row->id); ?> data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==27): ?>
                  <a class="badge badge-refund where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==28): ?>
                  <a class="badge badge-info where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==29): ?>
                  <a class="badge badge-starting-amt where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==30): ?>
                  <a class="badge badge-starting-amt where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                  <?php elseif($row->param_id==30): ?>
                  <a class="badge badge-deposit where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==31): ?>
                  <a class="badge badge-revised where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==32): ?>
                  <a class="badge badge-liability where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==35): ?>
                  <a class="badge badge-renewdocs where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a><br>
                  <span class="badge badge-vehicleDoc"><?php echo e($row->vehicle_document->document->label); ?></span>
                <?php elseif($row->param_id==43): ?>
                  <a class="badge badge-otherAdv where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==44): ?>
                  <a class="badge badge-advanceRefund where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                <?php elseif($row->param_id==49): ?>
                  <a class="badge badge-vehicle-downpayment where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a><br>
                  <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                <?php elseif($row->param_id==50): ?>
                  <a class="badge badge-vehicle-emi where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a><br>
                  <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                <?php else: ?><?php echo e(dd($row->param_id)); ?>

               <?php endif; ?></td>
              <td>
                <strong>(<?php echo e($row->transaction_id); ?>)</strong>
                <?php if(!empty($row->org)): ?>
                    <br><?php echo e($row->org); ?>

                <?php endif; ?>
              </td>
              <td><?php echo e($row->incExp->method->label); ?></td>
              <td> 
                <?php if($row->type==23): ?>
                  <span class="badge badge-success"><?php echo e($row->pay_type->label); ?></span>
                <?php elseif($row->type==24): ?>
                    <span class="badge badge-danger"><?php echo e($row->pay_type->label); ?></span>
                <?php endif; ?>
            </td>
              <td><?php echo e(bcdiv($row->total,1,2)); ?></td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
          </tbody>
           <tfoot>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              <th>Head</th>
              <th>Transaction ID</th>
              <th>Method</th>
              <th>Payment Type</th>
              <th>Amount</th>
            </tr>
          </tfoot> 
        </table>
        <br>
        <table class="table">
          <tr> <th style="float:right">Grand Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($sumoftotal,1,2)); ?></th>
          </tr>
      </table>
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
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/reportDebitCredit.blade.php ENDPATH**/ ?>