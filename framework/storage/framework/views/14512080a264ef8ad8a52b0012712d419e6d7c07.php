 
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.manageBulkPay'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
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
</style>
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
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.manageBulkPay'); ?>
        &nbsp;
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
              
              </th>
              <th>Date</th>
              <th>Bank</th>
              <th>Transaction ID</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $bulkpays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
              </td>
              <td><?php echo e($row->date); ?></td>
              <td>
                <span class="badge badge-info"><?php echo e($row->bank->bank); ?></span><br>
              </td>
              <td>
                <?php $__currentLoopData = $row->trash; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $tr = $tr->transaction; ?>
                  <?php if($tr->param_id==18): ?>
                    <a class="badge badge-success where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    
                    <br>
                      <?php if($tr->advance_for==21): ?>
                      <a class="badge badge-warning advance_for" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#advanceForModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e($tr->transaction_id); ?></a>
                      <?php endif; ?>
                    <?php elseif($tr->param_id==19): ?>
                    <a class="badge badge-info where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==20): ?>
                    <a class="badge badge-fuel where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==25): ?>
                    <a class="badge badge-driver-adv where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==26): ?>
                    <a class="badge badge-parts where_from" data-id="<?php echo e($tr->id); ?>" data-partsw=<?php echo e($tr->id); ?> data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==27): ?>
                    <a class="badge badge-refund where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==28): ?>
                    <a class="badge badge-info where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($tr->param_id==29): ?>
                    <a class="badge badge-starting-amt where_from" data-id="<?php echo e($tr->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($tr->params) ? $tr->transaction_id : 'N/A'); ?></a>
                    <?php elseif($row->param_id==30): ?>
                    <a class="badge badge-deposit where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                    <?php elseif($row->param_id==31): ?>
                    <a class="badge badge-revised where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                    <?php elseif($row->param_id==32): ?>
                    <a class="badge badge-liability where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                    <?php else: ?><?php echo e(dd($tr->param_id)); ?>

                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($row->amount,2,'.','')); ?></td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
                 
                  
                  <?php echo Form::hidden("id",$row->id); ?>

                  
                </div>
              </div>
                <?php echo Form::open(['url' => 'admin/bank-account/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

                <?php echo Form::hidden("id",$row->id); ?>

                <?php echo Form::close(); ?>

              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>
              
              </th>
              <th>Date</th>
              <th>Bank</th>
              <th>Transaction ID</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<!-- Modal -->

<!-- Modal -->

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
<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 155%">
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

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
 $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('<?php echo e(url("admin/bank-account/bulk_pay/view_event")); ?>/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
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
  $(function(){
      $(document).on('click','.advbook',function(){
          $("#viewModal .modal-content").css('width','150%');
      })
      $(document).on('click','.advgeneral',function(){
          $("#viewModal .modal-content").css('width','100%');
      })

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
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bank_account/manage_bulkpay.blade.php ENDPATH**/ ?>