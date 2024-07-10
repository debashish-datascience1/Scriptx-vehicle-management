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
              <th><?php echo app('translator')->getFromJson('fleet.created_on'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.required_by'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.personnel'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.work_order_price'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.parts'); ?> <?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
              </td>
              <td>
                <?php if($row->vehicle['vehicle_image'] != null): ?>
                <img src="<?php echo e(asset('uploads/'.$row->vehicle['vehicle_image'])); ?>" height="70px" width="70px">
                <?php else: ?>
                <img src="<?php echo e(asset("assets/images/vehicle.jpeg")); ?>" height="70px" width="70px">
                <?php endif; ?>
              </td>
              <td>
                <?php echo e($row->vehicle['year']); ?>

                <?php echo e($row->vehicle['make']); ?> - <?php echo e($row->vehicle['model']); ?>

                <br>
                <b> <?php echo app('translator')->getFromJson('fleet.vin'); ?>: </b><?php echo e($row->vehicle['vin']); ?>

                <br>
                <b> <?php echo app('translator')->getFromJson('fleet.plate'); ?>:</b> <?php echo e($row->vehicle['license_plate']); ?>

              </td>
              <td>
                <?php echo e(date($date_format_setting,strtotime($row->created_at))); ?>

              </td>
              <td>
                <?php echo e(date($date_format_setting,strtotime($row->required_by))); ?>

              </td>
              <td>
                <?php echo e($row->vendor['name']); ?>

              </td>
              <td><?php echo e($row->description); ?></td>
              <td> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->price); ?></td>
              <td> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->parts->sum('total')); ?></td>
              <td> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->price + $row->parts->sum('total')); ?></td>
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
                  <a class="dropdown-item" href='<?php echo e(url("admin/parts-used/".$row->id)); ?>'> <span aria-hidden="true" class="fa fa-wrench" style="color: green;"></span> <?php echo app('translator')->getFromJson('fleet.partsUsed'); ?></a>
                  <a class="dropdown-item" href='<?php echo e(url("admin/work_order/".$row->id."/edit")); ?>'> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                  <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
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
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th></th>
              <th><?php echo app('translator')->getFromJson('fleet.created_on'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.required_by'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.personnel'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.work_order_price'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.parts'); ?> <?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></th>
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
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/work_orders/index.blade.php ENDPATH**/ ?>