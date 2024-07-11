<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.work_orders'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.work_orders'); ?>
        &nbsp;
        <a href="<?php echo e(route('work_order.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.create_workorder'); ?></a></h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
                <?php if($data->count() > 0): ?>
                  <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th></th>
              <th>Date</th>
              <th>Transaction ID</th>
              <th><?php echo app('translator')->getFromJson('fleet.personnel'); ?>/<?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th width="30%"><?php echo app('translator')->getFromJson('fleet.work_order_price'); ?></th>
              
              
              <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                
              </td>
              <td>
                <?php if($row->vehicle['vehicle_image'] != null): ?>
                <img src="<?php echo e(asset('uploads/'.$row->vehicle['vehicle_image'])); ?>" height="70px" width="70px">
                <?php else: ?>
                <img src="<?php echo e(asset("assets/images/vehicle.jpeg")); ?>" height="70px" width="70px">
                <?php endif; ?>
              </td>
              <td>
                <?php echo e(strtoupper($row->vehicle['license_plate'])); ?>

              </td>
              <td>
                <?php echo e(date($date_format_setting,strtotime($row->required_by))); ?><br>
                <strong><?php echo e($row->bill_no); ?></strong>
                <br>
                <?php if($row->bill_image != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$row->bill_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
                <?php endif; ?>
              </td>
              <td>
                <?php echo e($row->trash_id); ?>

                <?php if(!empty($row->category_id)): ?>
                <br>
                <?php echo e(!empty($row->order_head) ? $row->order_head->name : 'n/a'); ?>

                <?php endif; ?>
              </td>
              <td>
                <?php echo e($row->vendor['name']); ?> <br>
                <?php echo e($row->description); ?>

              </td>
              <td> 
                <table class="table table-responsive table-bordered" style="display: table;">
                  <tr>
                    <th>Order Price</th>
                    <td colspan="2">
                      <label for="" style="float: right"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->price); ?></label>
                    </td>
                  </tr>
                  <?php if(!empty($row->parts_fromvendor)): ?>
                  <tr>
                    <th>
                      Parts Price
                      <?php if($row->is_itemno): ?>
                        <a class="badge badge-primary" href='<?php echo e(url("admin/parts-used/".$row->id)); ?>'> Item No.</a>
                      <?php endif; ?>
                    </th>
                    <td colspan="2">
                      <label for="" style="float: right"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->parts_fromvendor->sum('total')); ?></label>
                    </td>
                  </tr>
                  <?php endif; ?>
                  <?php if(!empty($row->parts_fromvendor)): ?>
                  <tr>
                    <th>GST</th>
                    <td style="text-align:right" class="font-italic font-weight-bold"><?php echo e(Hyvikk::get('currency')); ?> 
                    <?php if(!empty($row->parts_fromvendor)): ?>
                      <?php echo e($row->parts_fromvendor->sum('cgst_amt')+$row->parts_fromvendor->sum('sgst_amt')); ?>

                    <?php else: ?>
                        
                    <?php endif; ?> 
                    </td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <th>Total Price</th>
                    <td colspan="2"><label style="float: right;"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->price + $row->parts_fromvendor->sum('grand_total'),1,2)); ?></label></td>
                  </tr>
                  
                </table>
              </td>
              
              <td>
                <?php if($row->status == "Completed"): ?>
                <span class="text-success"><?php echo e($row->status); ?></span>
                <?php elseif($row->status == "Pending"): ?>
                <span class="text-warning"><?php echo e($row->status); ?></span>
                <?php else: ?>
                <?php echo e($row->status); ?>

                <?php endif; ?>
              </td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-gear"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vview" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
                  <a class="dropdown-item" href='<?php echo e(url("admin/parts-used/".$row->id)); ?>'> <span aria-hidden="true" class="fa fa-wrench" style="color: green;"></span> <?php echo app('translator')->getFromJson('fleet.partsUsed'); ?></a>
                  <?php if($row->status == "Completed" && empty($row->category_id)): ?>
                  <a class="dropdown-item vorderhead" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#orderHeadModal"> <span aria-hidden="true" class="fa fa-level-down" style="color: #423091;"></span> Add Order Head</a>
                  <?php endif; ?>
                  <?php if(Helper::isEligible($row->id,28)): ?>
                  <a class="dropdown-item" href='<?php echo e(url("admin/work_order/".$row->id."/edit")); ?>'> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                  <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                  <?php endif; ?>
                </div>
              </div>
              <?php echo Form::open(['url' => 'admin/work_order/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

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
                  
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th>License Plate</th>
              <th><?php echo app('translator')->getFromJson('fleet.created_on'); ?>/<?php echo app('translator')->getFromJson('fleet.required_by'); ?></th>
              <th>Transaction ID</th>
              <th><?php echo app('translator')->getFromJson('fleet.personnel'); ?>/<?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.work_order_price'); ?></th>
              
              
              <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
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
        <?php echo Form::open(['url'=>'admin/delete-work-orders','method'=>'POST','id'=>'form_delete']); ?>

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
<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.view'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<div id="orderHeadModal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Select Order Head</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
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
<!-- Modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('.vview').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/work_order/view_event")); ?>/'+id,function(result){
      $('#myModal2').modal({show:true});
    });
  });
  $('.vorderhead').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#orderHeadModal .modal-body').load('<?php echo e(url("admin/work_order/add_order_head")); ?>/'+id,function(result){
      $('#orderHeadModal').modal({show:true});
    });
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
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/index.blade.php ENDPATH**/ ?>