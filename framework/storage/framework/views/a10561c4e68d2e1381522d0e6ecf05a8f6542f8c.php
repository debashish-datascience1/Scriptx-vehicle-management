<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?></li>
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
        <?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?>
        &nbsp;
        <a href="<?php echo e(route('service-reminder.create')); ?>" class="btn btn-success" style="margin-bottom: 5px"><?php echo app('translator')->getFromJson('fleet.add_service_reminder'); ?></a> &nbsp;
        <a href="<?php echo e(route('service-item.create')); ?>" class="btn btn-success" style="margin-bottom: 5px"><?php echo app('translator')->getFromJson('fleet.add_service_item'); ?></a></h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              
              <th>SL#</th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.service_item'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.start_date'); ?> / <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> </th>
              <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.date'); ?>)</th>
              <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.meter'); ?>)</th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $service_reminder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($k+1); ?></td>
              <td>
                  <?php if($reminder->vehicle['vehicle_image'] != null): ?>
                    <img src="<?php echo e(asset('uploads/'.$reminder->vehicle['vehicle_image'])); ?>" height="70px" width="70px">
                  <?php else: ?>
                    <img src="<?php echo e(asset("assets/images/vehicle.jpeg")); ?>" height="70px" width="70px">
                  <?php endif; ?>
              </td>
              <td>
                <strong><?php echo e($reminder->vehicle->license_plate); ?></strong>
              </td>
              <td>
                <?php echo e($reminder->services['description']); ?>

                <br>
                <?php echo app('translator')->getFromJson('fleet.interval'); ?>: <?php echo e($reminder->services->overdue_time); ?> <?php echo e($reminder->services->overdue_unit); ?>

                <?php if($reminder->services->overdue_meter != null): ?>
                <?php echo app('translator')->getFromJson('fleet.or'); ?> <?php echo e($reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                <?php endif; ?>
              </td>
              <td>
                <?php echo app('translator')->getFromJson('fleet.start_date'); ?>: <?php echo e(date($date_format_setting,strtotime($reminder->last_date))); ?>

                <br>
                <?php echo app('translator')->getFromJson('fleet.last_performed'); ?> <?php echo app('translator')->getFromJson('fleet.meter'); ?>: <?php echo e($reminder->last_meter); ?>

              </td>
              <td>
                <?php ($interval = substr($reminder->services->overdue_unit,0,-3)); ?>
                <?php if($reminder->services->overdue_time != null): ?>
                  <?php ($int = $reminder->services->overdue_time.$interval); ?>
                <?php else: ?>
                  <?php ($int = Hyvikk::get('time_interval')."day"); ?>
                <?php endif; ?>
                  
                <?php if($reminder->last_date != 'N/D'): ?>
                 <?php ($date = date('Y-m-d', strtotime($int, strtotime($reminder->last_date)))); ?> 
                <?php else: ?>
                 <?php ($date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))))); ?> 
                <?php endif; ?>
                
                <?php echo e(date($date_format_setting,strtotime($date))); ?>

                <br>
                <?php ($to = \Carbon\Carbon::now()); ?>

                <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $date)); ?>

                <?php ($diff_in_days = $to->diffInDays($from)); ?>
                <?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($diff_in_days); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?>
              </td>
              <td>
                <?php if($reminder->services->overdue_meter != null): ?>
                  <?php if($reminder->last_meter == 0): ?>
                    <?php echo e($reminder->vehicle->int_mileage + $reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                  <?php else: ?>
                    <?php echo e($reminder->last_meter + $reminder->services->overdue_meter); ?> <?php echo e(Hyvikk::get('dis_format')); ?>

                  <?php endif; ?>
                <?php endif; ?>
              </td>
              <td>
                <?php echo Form::open(['url' => 'admin/service-reminder/'.$reminder->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$reminder->id]); ?>


                <?php echo Form::hidden("id",$reminder->id); ?>

                  <button type="button" class="btn btn-danger" data-id="<?php echo e($reminder->id); ?>" data-toggle="modal" data-target="#myModal" title="<?php echo app('translator')->getFromJson('fleet.delete'); ?>">
                  <span class="fa fa-times" aria-hidden="true"></span>
                  </button>
                <?php echo Form::close(); ?>

              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>
                <?php if($service_reminder->count() > 0): ?>
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
                <?php endif; ?>
              </th>
              <th></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.service_item'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.start_date'); ?> / <?php echo app('translator')->getFromJson('fleet.last_performed'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.date'); ?>)</th>
              <th><?php echo app('translator')->getFromJson('fleet.next_due'); ?> (<?php echo app('translator')->getFromJson('fleet.meter'); ?>)</th>
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
        <?php echo Form::open(['url'=>'admin/delete-reminders','method'=>'POST','id'=>'form_delete']); ?>

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
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/service_reminder/index.blade.php ENDPATH**/ ?>