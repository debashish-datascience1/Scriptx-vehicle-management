<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection('extra_css'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
  <style type="text/css">
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
  </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.booking_quotes'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> <?php echo app('translator')->getFromJson('fleet.manageBookingQuotations'); ?> &nbsp;
          <a href="<?php echo e(route("booking-quotation.create")); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.add_quote'); ?></a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive display" id="data_table" style="padding-bottom: 35px; width: 100%">
            <thead class="thead-inverse">
              <tr>
                <th>
                  <?php if($data->count() > 0): ?>
                  <input type="checkbox" id="chk_all">
                  <?php endif; ?>
                </th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.passengers'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.approve'); ?>/<?php echo app('translator')->getFromJson('fleet.reject'); ?> </th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td>
                  <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
                </td>
                <td style="width: 10% !important"><?php echo e($row->customer['name']); ?></td>
                <td style="width: 10% !important"><?php echo e($row->vehicle['make']); ?> - <?php echo e($row->vehicle['model']); ?> - <?php echo e($row->vehicle['license_plate']); ?></td>
                <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->pickup_addr); ?></td>
                <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->dest_addr); ?></td>
                <td style="width: 10% !important">
                <?php if($row->pickup != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->pickup))); ?>

                <?php endif; ?>
                </td>
                <td style="width: 10% !important">
                <?php if($row->dropoff != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->dropoff))); ?>

                <?php endif; ?>
                </td>
                <td style="width: 10% !important"><?php echo e($row->travellers); ?></td>
                <td style="width: 10% !important">

                <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(($row->tax_total) ? $row->tax_total : $row->total); ?>


                </td>
                <td>
                  <a href="<?php echo e(url('admin/booking-quotation/approve/'.$row->id)); ?>" class="btn btn-success" title="<?php echo app('translator')->getFromJson('fleet.approve'); ?>"><i class="fa fa-check"></i></a> &nbsp;
                  <a class="btn btn-danger" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#rejectModal" href="" title="<?php echo app('translator')->getFromJson('fleet.reject'); ?>"><i class="fa fa-times"></i> </a>
                </td>
                <td style="width: 10% !important">
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="<?php echo e(url('admin/booking-quotation/invoice/'.$row->id)); ?>"> <span aria-hidden="true" class="fa fa-list" style="color: #31b0d5;"></span> <?php echo app('translator')->getFromJson('fleet.receipt'); ?></a>
                    <a class="dropdown-item" href="<?php echo e(url('admin/booking-quotation/'.$row->id.'/edit')); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                    <a class="dropdown-item vtype" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal" > <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                  </div>
                </div>
                <?php echo Form::open(['url' => 'admin/booking-quotation/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]); ?>

                <?php echo Form::hidden("id",$row->id); ?>

                <?php echo Form::close(); ?>

                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
              <tr>
                <th>
                <?php if($data->count() > 0): ?>
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
                <?php endif; ?>
                </th>
                <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.passengers'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.approve'); ?>/<?php echo app('translator')->getFromJson('fleet.reject'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/delete-quotes','method'=>'POST','id'=>'form_delete']); ?>

        <div id="bulk_hidden"></div>
        <p><?php echo app('translator')->getFromJson('fleet.confirm_bulk_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
        <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>


<div id="rejectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.reject'); ?> <?php echo app('translator')->getFromJson('fleet.bookingQuote'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_reject'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn2" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.reject'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("script"); ?>

<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  <?php if(Session::get('msg')): ?>
    new PNotify({
        title: 'Success!',
        text: '<?php echo e(Session::get('msg')); ?>',
        type: 'success'
      });
  <?php endif; ?>

  $(document).ready(function() {
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $("#del_btn2").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#rejectModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn2").attr("data-submit",id);
  });
</script>

<script type="text/javascript" language="javascript">
  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "<?php echo app('translator')->getFromJson('fleet.delete_error'); ?>",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/booking_quotation/index.blade.php ENDPATH**/ ?>