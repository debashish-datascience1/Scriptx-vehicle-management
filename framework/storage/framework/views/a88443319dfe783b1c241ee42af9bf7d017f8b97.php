<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
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
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active">Vehicle Documents</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
      <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <?php endif; ?>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"> Manage Vehicle Documents &nbsp;
          <a href="<?php echo e(route("vehicle-docs.create")); ?>" class="btn btn-success">Renew Vehicle Documents</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="myTable" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
                <th>SL#</th>
                <th>Vehicle</th> 
                <th>Date</th> 
                <th>Document / Bank</th> 
                <th>Amount / Vendor</th> 
                <th>Method/Reference</th>
                <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td width="5%"><?php echo e($k+1); ?></td>
                <td>
                  
                  <b><i><?php echo e($row->transaction->transaction_id); ?></i></b>
                  <br>
                  <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <label><?php echo e($row->vehicle->license_plate); ?></label>
                  <br>
                  <?php if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver)): ?>
                    <?php echo e($row->drivervehicle->assigned_driver->name); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <label>On : <?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></label><br>
                  <label>Till : <?php echo e(Helper::getCanonicalDate($row->till,'default')); ?></label><br>
                  <?php ($to = \Carbon\Carbon::now()); ?>

                  <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till)); ?>
  
                  <?php ($diff_in_days = $to->diffInDays($from)); ?>
                  <label><?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($diff_in_days); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?></label>
                </td>
                <td>
                  <label><?php echo e($row->document->label); ?></label>
                  <br>
                  <?php echo e($row->transaction->bank->bank); ?>

                  <br>
                  <?php echo e($row->transaction->bank->account_no); ?>

                </td>
                <td>
                    <?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->amount)); ?>

                    <br>
                    <?php echo e($row->vendor->name); ?>

                </td>
                <td>
                  <?php echo e($row->method_param->label); ?> <br>
                  <?php echo e($row->ddno); ?>

                </td>
                
                <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item mybtn vevent" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
                    
                  </div>
                </div>
                <?php echo Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]); ?>

                <?php echo Form::hidden("id",$row->id); ?>

                <?php echo Form::close(); ?>

                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
                <th>SL#</th>
                <th>Vehicle</th> 
                <th>Date</th> 
                <th>Document</th> 
                <th>Amount</th>
                <th>Status</th>
                <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.view'); ?></h4>
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

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('<?php echo e(url("admin/vehicle-docs/view_event")); ?>/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })



  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
    }
  });
  
  $(function(){
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
  })

    // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicle_docs/index.blade.php ENDPATH**/ ?>