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
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.drivers'); ?></li>
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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.drivers'); ?> &nbsp;
          <a href="<?php echo e(route("drivers.create")); ?>" class="btn btn-success"> <?php echo app('translator')->getFromJson('fleet.addDriver'); ?> </a>
          <button data-toggle="modal" data-target="#import" class="btn btn-warning"><?php echo app('translator')->getFromJson('fleet.import'); ?></button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                <?php if($data->count() > 0): ?>
                <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.driverImage'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.name'); ?></th>
              
              <th><?php echo app('translator')->getFromJson('fleet.is_active'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.phone'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.start_date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
              </td>
              <td><?php echo e($row->id); ?></td>
              <td>
                <?php if($row->getMeta('driver_image') != null): ?>
                <?php if(starts_with($row->getMeta('driver_image'),'http')): ?>
                <?php ($src = $row->getMeta('driver_image')); ?>
                <?php else: ?>
                <?php ($src=asset('uploads/'.$row->getMeta('driver_image'))); ?>
                <?php endif; ?>
                <img src="<?php echo e($src); ?>" height="70px" width="70px">
                <?php else: ?>
                <img src="<?php echo e(asset("assets/images/no-user.jpg")); ?>" height="70px" width="70px">
                <?php endif; ?>
              </td>
              <td><?php echo e($row->name); ?></td>
              
              <td>
                <?php if($row->getMeta('is_active')): ?>
                  <a href="<?php echo e(url("admin/drivers/disable/".$row->id)); ?>" class="badge badge-success">YES</a>
                <?php else: ?>
                  <a href="<?php echo e(url("admin/drivers/enable/".$row->id)); ?>" class="badge badge-danger">NO</a>
                <?php endif; ?>
              </td>
              <td><?php echo e($row->getMeta('phone')); ?></td>
              <td><?php if($row->vehicle_id != null && $row->getMeta('is_active')==1 && !empty($row->driver_vehicle->vehicle)): ?> 
              <?php echo e($row->driver_vehicle->vehicle->make); ?>-<?php echo e($row->driver_vehicle->vehicle->model); ?>-<?php echo e($row->driver_vehicle->vehicle->license_plate); ?> 
              <?php endif; ?>
              </td>
              <td><?php echo e(date($date_format_setting,strtotime($row->getMeta('start_date')))); ?></td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vevent" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="View Driver Details"><i class="fa fa-eye" aria-hidden="true" style="color:#398439;"></i>View Driver Details </a>
                  
                  <a class="dropdown-item" href="<?php echo e(url("admin/drivers/".$row->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                  <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                  <?php if($row->getMeta('is_active')): ?>
                  <a class="dropdown-item" href="<?php echo e(url("admin/drivers/disable/".$row->id)); ?>" class="mybtn" data-toggle="tooltip"  title="<?php echo app('translator')->getFromJson('fleet.disable_driver'); ?>"><span class="fa fa-times" aria-hidden="true" style="color: #5cb85c;"></span> <?php echo app('translator')->getFromJson('fleet.disable_driver'); ?></a>
                  <?php else: ?>
                  <a class="dropdown-item" href="<?php echo e(url("admin/drivers/enable/".$row->id)); ?>" class="mybtn" data-toggle="tooltip"  title="<?php echo app('translator')->getFromJson('fleet.enable_driver'); ?>"><span class="fa fa-check" aria-hidden="true" style="color: #5cb85c;"></span> <?php echo app('translator')->getFromJson('fleet.enable_driver'); ?></a>
                  <?php endif; ?>
                </div>
              </div>
              <?php echo Form::open(['url' => 'admin/drivers/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

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
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.driverImage'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.name'); ?></th>
              
              <th><?php echo app('translator')->getFromJson('fleet.is_active'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.phone'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.start_date'); ?></th>
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
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.importDrivers'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/import-drivers','method'=>'POST','files'=>true]); ?>

        <div class="form-group">
          <?php echo Form::label('excel',__('fleet.importDrivers'),['class'=>"form-label"]); ?>

          <?php echo Form::file('excel',['class'=>"form-control",'required']); ?>

        </div>
        <div class="form-group">
          <a href="<?php echo e(asset('assets/samples/drivers.xlsx')); ?>"><?php echo app('translator')->getFromJson('fleet.downloadSampleExcel'); ?></a>
        </div>
        <div class="form-group">
          <h6 class="text-muted"><?php echo app('translator')->getFromJson('fleet.note'); ?>:</h6>
          <ul class="text-muted">
            <li><?php echo app('translator')->getFromJson('fleet.driverImportNote1'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.driverImportNote2'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.driverImportNote3'); ?></li>
            <li><?php echo app('translator')->getFromJson('fleet.excelNote'); ?></li>
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
        <?php echo Form::open(['url'=>'admin/delete-drivers','method'=>'POST','id'=>'form_delete']); ?>

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
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Driver Details</h4>
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
<!-- Modal -->

<!-- Modal -->
<div id="changepass" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.change_password'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>url('admin/change_password'),'id'=>'changepass_form']); ?>

        <form id="change" action="<?php echo e(url('admin/change_password')); ?>" method="POST">
      <?php echo Form::hidden('driver_id',"",['id'=>'driver_id']); ?>

       <div class="form-group">
        <?php echo Form::label('passwd',__('fleet.password'),['class'=>"form-label"]); ?>

        <div class="input-group mb-3">
         <div class="input-group-prepend">
          <span class="input-group-text"><i class="fa fa-lock"></i></span></div>
        <?php echo Form::password('passwd',['class'=>"form-control",'id'=>'passwd','required']); ?>

        </div>
      </div>
      <div class="modal-footer">
        <button id="password" class="btn btn-info" type="submit" ><?php echo app('translator')->getFromJson('fleet.change_password'); ?></button>
      </form>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">

  $(function(){
    $(".vevent").on("click",function(){
      var id = $(this).data("id");
      $("#viewModal .modal-body").load('<?php echo e(url("admin/drivers/event")); ?>/'+id,function(result){
        $("#viewModal").modal({show:true})
      })
    })
  })

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#changepass').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#driver_id").val(id);
  });

  $("#changepass_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
       new PNotify({
            title: 'Success!',
            text: "<?php echo app('translator')->getFromJson('fleet.passwordChanged'); ?>",
            type: 'info'
        });
      },
      dataType: "html"
    });
    $('#changepass').modal("hide");
    e.preventDefault();
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/drivers/index.blade.php ENDPATH**/ ?>