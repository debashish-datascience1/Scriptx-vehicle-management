<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.manageParts'); ?></li>
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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.manageParts'); ?>
          <a href="<?php echo e(route('parts.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.addParts'); ?></a>
        </h3>
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
              
              <th><?php echo app('translator')->getFromJson('fleet.billno'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vendor'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cash_payment'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft_amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft_date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.subtotal'); ?></th>             
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
            
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
              </td>
              <td> <?php echo e($row->billno); ?>

              </td>
              <td><?php echo e($row->vendor->name); ?>

              <br>
              (<?php echo e($row->vendor->type); ?>)
              <br>
              <?php echo e($row->vendor->phone); ?>

              </td>
              <td> <?php echo e($row->cash_amount); ?>

              </td>
              <td> <?php echo e($row->chq_draft_number); ?>

              </td>

              <td> <?php echo e($row->chq_draft_amount); ?>

              </td>
              <td>
                <?php if($row->chq_draft_date!="1970-01-01"): ?>

                <?php echo e($row->chq_draft_date); ?>

                <?php endif; ?>
              </td>
              <td><?php echo e($row->sub_total); ?></td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vview" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#PartsDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green"></span> View</a>
                  
                  
                  
                  
                </div>
              </div>
              <?php echo Form::open(['url' => 'admin/parts/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

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
              <th><?php echo app('translator')->getFromJson('fleet.billno'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vendor'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cash_payment'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft_amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cheque_draft_date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.subtotal'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal view-->
<div id="PartsDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:158%">
      <div class="modal-header" style="border-bottom:none;">
        <h4 class="modal-title">Parts Details</h4>
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


<!-- Modal -->
<div id="stockModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="part_title"></span> : <?php echo app('translator')->getFromJson('fleet.addStock'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo Form::open(['method'=>'POST','url'=>'admin/add-stock']); ?>

      <?php echo Form::hidden('part_id',null,['id'=>'part_id']); ?>

      <div class="modal-body">
        <div class="form-group">
          <?php echo Form::label('stock', __('fleet.qty'), ['class' => 'form-label']); ?>

          <?php echo Form::number('stock', 1,['class' => 'form-control','min'=>1]); ?>

        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-info" type="submit" data-submit=""><?php echo app('translator')->getFromJson('fleet.addStock'); ?></button>
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
        <?php echo Form::open(['url'=>'admin/delete-parts','method'=>'POST','id'=>'form_delete']); ?>

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

  $('#stockModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#part_id").val(id);
    $("#part_title").html(e.relatedTarget.dataset.title);
  });
  $(".vview").on("click",function(){
    var id = $(this).data("id");
    $("#PartsDetailsModal .modal-body").load('<?php echo e(url("admin/parts/view_event")); ?>/'+id,function(res){
      $("#PartsDetailsModal").modal({show:true})
    })
    
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/parts/index.blade.php ENDPATH**/ ?>