<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .modal-open {margin-left: -250px}
  .custom_padding{
    padding: .3rem !important;
  }
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.vehicles'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.manageVehicles'); ?> &nbsp; <a href="<?php echo e(route('vehicles.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.addNew'); ?></a>
          <button data-toggle="modal" data-target="#import" class="btn btn-warning"><?php echo app('translator')->getFromJson('fleet.import'); ?></button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 25px">
          <thead class="thead-inverse">
            <tr>
              <th>
                <?php if($data->count() > 0): ?>
                <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicleImage'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.type'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.color'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licenseNo'); ?></th>
              
              <th><?php echo app('translator')->getFromJson('fleet.service'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_driver'); ?></th>
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
                <?php if($row->vehicle_image != null): ?>
                <img src="<?php echo e(asset('uploads/'.$row->vehicle_image)); ?>" height="70px" width="70px">
                <?php else: ?>
                <img src="<?php echo e(asset("assets/images/vehicle.jpeg")); ?>" height="70px" width="70px">
                <?php endif; ?>
              </td>
              <td><?php echo e($row->make); ?></td>
              <td><?php echo e($row->model); ?></td>
              <td><?php if($row->type_id): ?><?php echo e($row->types->displayname); ?><?php endif; ?></td>
              <td><?php echo e($row->color); ?></td>
              <td><?php echo e($row->license_plate); ?></td>
              
              <td><?php echo e(($row->in_service)?"YES":"NO"); ?></td>
              <td>
                <?php if(empty($row->driver) || empty($row->driver->assigned_driver)): ?> 
                  <span class="badge badge-danger">Driver Not Assigned</span>
                <?php else: ?>
                  <?php echo e($row->driver->assigned_driver->name); ?>

                <?php endif; ?>
              </td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item openBtn" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2" id="openBtn">
                    <span class="fa fa-eye" aria-hidden="true" style="color: #398439"></span> <?php echo app('translator')->getFromJson('fleet.view_vehicle'); ?>
                    </a>
                    <a class="dropdown-item" href="<?php echo e(url("admin/vehicles/".$row->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                    <?php echo Form::hidden("id",$row->id); ?>

                    <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                    
                  </div>
                </div>
                <?php echo Form::open(['url' => 'admin/vehicles/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>


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
              <th><?php echo app('translator')->getFromJson('fleet.vehicleImage'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.type'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.color'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licenseNo'); ?></th>
              
              <th><?php echo app('translator')->getFromJson('fleet.service'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_driver'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="import" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.importVehicles'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/import-vehicles','method'=>'POST','files'=>true]); ?>

        <div class="form-group">
          <?php echo Form::label('excel',__('fleet.importVehicles'),['class'=>"form-label"]); ?>

          <?php echo Form::file('excel',['class'=>"form-control",'required']); ?>

        </div>
        <div class="form-group">
          <a href="<?php echo e(asset('assets/samples/vehicles.xlsx')); ?>"><?php echo app('translator')->getFromJson('fleet.downloadSampleExcel'); ?></a>
        </div>
        <div class="form-group">
          <h6 class="text-muted"><?php echo app('translator')->getFromJson('fleet.note'); ?>:</h6>
          <ul class="text-muted">
            <li><?php echo app('translator')->getFromJson('fleet.vehicleImportNote1'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.vehicleImportNote2'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.excelNote'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.fileTypeNote'); ?></li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" type="submit"><?php echo app('translator')->getFromJson('fleet.import'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
        <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- Modal -->

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
        <?php echo Form::open(['url'=>'admin/delete-vehicles','method'=>'POST','id'=>'form_delete']); ?>

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
  <div class="modal-dialog" role="document">
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

<!--model 2 -->
<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 104%">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!--model 2 -->
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

  $('.openBtn').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('<?php echo e(url("admin/vehicle/event")); ?>/'+id)
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/vehicle/event")); ?>/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
    });
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
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicles/index.blade.php ENDPATH**/ ?>