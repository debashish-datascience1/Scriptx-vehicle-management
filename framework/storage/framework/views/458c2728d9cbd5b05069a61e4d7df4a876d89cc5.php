<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
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
  .badge-viwevent{background: #0091bd;color:#fff!important;cursor: pointer;}
  .btn-search{transition: .7s}
  .btn-search:hover{background:#9c27b0;border:1px solid white}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.transactions'); ?></li>
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
        <h3 class="card-title">Bank <?php echo app('translator')->getFromJson('fleet.transactions'); ?> &nbsp;
          
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                <?php if($transactions->count() > 0): ?>
                <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th>Transaction ID</th>
              <th style="width: 125px;">Date</th>
              <th>From</th>
              <th>Method</th>
              <th>Previous</th>
              <th>Total</th>
              <th>Remaining</th>
              <th>Grand Total</th>
              
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'></td>
              <td><a class="mybtn vevent badge badge-view" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"> <?php echo e($row->transaction_id); ?></a></td>
              
              <td><?php echo e(!empty($row->rem->date) ? Helper::getCanonicalDate($row->rem->date) : Helper::getCanonicalDate($row->rem->created_at)); ?></td>
              
              <td>
                <?php if($row->param_id==18): ?>
                  <a class="badge badge-success where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                  
                  <br>
                  <?php if($row->advance_for==21): ?>
                  <a class="badge badge-warning advance_for" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#advanceForModal" title="<?php echo e(!empty($tr->params) ? $tr->params->label : 'N/A'); ?>"><?php echo e($row->advancefor->label); ?></a>
                  
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
                <?php else: ?><?php echo e(dd($row->param_id)); ?>

               <?php endif; ?>
               
                <br>
               
               <label for="bank"><?php echo e(empty($row->bank) ? 'N/A' : $row->bank->bank); ?></label>
              </td>
              <td><?php echo e($row->pay_method->label); ?></td>
              <td><?php echo e(number_format($row->prev,2,'.','')); ?></td>
              <td>
                <?php echo e($row->total); ?><br>
                <?php if($row->type==23): ?>
                    <span class="badge badge-success"><?php echo e($row->pay_type->label); ?></span>
                <?php elseif($row->type==24): ?>
                    <span class="badge badge-danger"><?php echo e($row->pay_type->label); ?></span>
                <?php endif; ?>
              </td>
              <td><?php echo e(number_format($row->rem->remaining,2,'.','')); ?></td>
              <td><?php echo e(number_format($row->grandtotal,2,'.','')); ?></td>
              
              
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>
                <?php if($transactions->count() > 0): ?>
                
                <?php endif; ?>
              </th>
              <th>Transaction ID</th>
              <th>Date</th>
              <th>From</th>
              
              <th>Method</th>
              <th>Previous</th>
              <th>Total</th>
              
              <th>Remaining</th>
              <th>Grand Total</th>
              
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
<!-- Modal -->
<div id="adjustModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 43%">
    <!-- Modal content-->
    <form action="<?php echo e(route('accounting.store')); ?>" method="POST">
    <?php echo e(csrf_field()); ?>

      <div class="modal-content" style="width: 150%">
        <div class="modal-header">
          <h4 class="modal-title">Adjusting Transaction</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading...
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success adjustSubmit" value="Submit">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
          </button>
        </div>
      </div>
    </form>
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
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $('body').on("click",".adjustSubmit",function(){
    console.log($(".adjDate").val());
    var ret = true;
    $(".adjDate").each(function(index,element){
      if($(this).val()==""){
        console.log($(this).val());
        alert("Date field can't be empty.");
        $(this).focus();
        ret = false;
      }
    })
    return ret;
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

  // $(function(){
  //   var adj = $(".vadjust").val();
  //   $(".vadjust").each(function(i,e){
  //     var datval = $(this).data("show");
  //     if(datval==null && datval==0)

  //   })
  // })

  $('body').on("keyup",".adjAmount",function(){
    var r = $("#remainingAmt").val();
    var typed = $(this).val();
    if(Number(typed)>Number(r))
      $(this).val('').focus();
    // console.log(typeof r);
    // console.log(typeof typed);
    var sumTotal = 0;
    $(".adjAmount").each(function(i,e){
      var vals = $(this).val();
      sumTotal+=$(this).val()=="" ? 0 : Number(vals);
    })
    if(sumTotal>r)
      $(".adjAmount").val('');
  })

  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('<?php echo e(url("admin/accounting/view_bank_event")); ?>/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })

  $(".vadjust").click(function(){
    var id = $(this).data("id");
    var datval = atob($(this).data("rem"));
      
    // console.log(datval)
      $("#adjustModal .modal-body").load('<?php echo e(url("admin/accounting/adjust")); ?>/'+id,function(res){
        // console.log(res)
        if(datval==null || datval==0)
          $(".adjustSubmit").hide();
        else $(".adjustSubmit").show();
        $("#adjustModal").modal({show:true});
        
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
  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }

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
  $("body").on("focus",".adjDate",function(){
    $(this).datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  })

  $("body").on("click",".addmore",function(){
    var count = $(".parent-row").length;
    console.log(count);
    if(count<=5){
      $(".adjustTable").last().append("<tr class='parent-row'><td><input type='text' name='adjDate[]' class='form-control adjDate' readonly=' placeholder='Choose Date..' required></td><td><select class='form-control adjType' name='adjType[]' required><option value='16'>Cash</option><option value='17'>DD</option></select></td><td><input type='text' name='adjAmount[]' class='form-control adjAmount' placeholder='Enter Amount..' required></td><td><input type='text' name='adjRemarks[]' class='form-control adjRemarks' placeholder='Enter Remarks..' required></td><td><button class='btn btn-danger remove'><span class='fa fa-minus'></span>&nbsp;Remove</button></td></tr>");
    }else{
      alert("Can't add more than 5 rows.");
      return false;
    }
  })

  $("body").on("click",".remove",function(){
    // alert('remove')
    $(this).closest(".parent-row").remove();
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/index-bank.blade.php ENDPATH**/ ?>