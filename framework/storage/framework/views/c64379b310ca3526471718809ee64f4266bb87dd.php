<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.css')); ?>">
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
  .adjDate,.adjDatePrev,.adjRef,.adjRefPrev,.adjType,.adjTypePrev,.adjBank,.adjBankPrev{margin-top: 10px;}
  .adjDatePrev,.adjTypePrev,.adjMethoPrev,.adjBankPrev{pointer-events: none}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.other_advance'); ?></li>
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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.other_advance'); ?> &nbsp;
          <a href="<?php echo e(route("other-advance.create")); ?>" class="btn btn-success"> Add <?php echo app('translator')->getFromJson('fleet.other_advance'); ?> </a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                
              </th>
              <th>SL#</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                
              </td>
              <td><?php echo e($k+1); ?></td>
              <td><?php echo e($row->driver->name); ?></td>
              <td><?php echo e($row->bank_details->bank); ?>(<?php echo e($row->bank_details->account_no); ?>)</td>
              <td><?php echo e($row->method_param->label); ?></td>
              <td><?php echo e(Helper::properDecimals($row->amount)); ?></td>
              <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
              <td>
                <?php if($row->is_adjusted==1): ?>
                  <span class="badge badge-success">Completed</span>
                <?php elseif($row->is_adjusted==2): ?>
                  <span class="badge badge-primary">In Progress</span>
                <?php elseif($row->is_adjusted==null): ?>
                  <span class="badge badge-danger">Not Yet Done</span>
                <?php endif; ?>
              </td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
                  <?php if($row->is_adjusted==null): ?>
                  <a class="dropdown-item" href="<?php echo e(url("admin/other-advance/".$row->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                  
                  <?php endif; ?>
                  <a class="dropdown-item vadjust" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#adjustModal" title="Adjust"> <span aria-hidden="true" class="fa fa-balance-scale" style="color: #1316b6;"></span> Adjust</a>
                   
                </div>
              </div>
              
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
              <th>SL#</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Date</th>
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
<div id="adjustModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="margin-left: 25%">
    <!-- Modal content-->
    <div class="modal-content"  style="width: 180%">
      <div class="modal-header">
        <h4 class="modal-title">Adjust Other Advance</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo Form::open(['route' => 'other-adjust.store','files'=>true,'method'=>'post']); ?>

      <div class="modal-body">
        
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.js')); ?>"></script>
<script type="text/javascript">
  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('<?php echo e(url("admin/other-advance/view_event")); ?>/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
        $('#viewModal .modal-content').css({width: "100%","margin-left": ""})
      })
  })
  $(".vadjust").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#adjustModal .modal-body").load('<?php echo e(url("admin/other-adjust/adjust")); ?>/'+id,function(res){
        // console.log(res)
        $("#adjustModal").modal({show:true});
      })
  })

  $("body").on("click",".adjustments,.gen",function(){
    var classNames = $(this).attr("class");
    if(classNames.includes("adjustments"))
      $('#viewModal .modal-content').css({width: "200%","margin-left": "-103px"})
    else
      $('#viewModal .modal-content').css({width: "100%","margin-left": ""})
  })

  $("body").on("click","#addmore",function(e){
    let arr = {_token:"<?php echo e(csrf_token()); ?>"};
    var self = $(this);
    $.post("<?php echo e(route('other-adjust.addmore')); ?>",arr).done(function(result){
      console.table(result);
      self.closest("tbody").find(".parent-row:last").after(result)
    });
  })
  $("body").on("click",".remove",function(e){
    if(confirm("Are you sure to remove this ?"))
      $(this).closest(".parent-row").remove()
  })
  $("body").on("focus",".adjDate",function(e){
    $(this).datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
    });
  })

  $("body").on("keyup",".adjAmount",function(){
    var myarr = [];
    var self = $(this);
    $(".adjAmount").each(function(){
      myarr.push($(this).val())
    })
    var postarr = {_token:"<?php echo e(csrf_token()); ?>",value:myarr,id:$("#otherAdvance").val()}
    // console.log(postarr)
    $.post("<?php echo e(route('other-adjust.calculate')); ?>",postarr).done(function(result){
      // console.log(result)
      if(result.status==false){
        self.val('')
        $(".adjAmount").keyup()
      }else{
        $("#span-remain").html(result.remain)
      }
    })
  })
  // $("body").on("change",".adjType",function(){
  //   if($(this).val()==23){
  //     $(this).closest("td").find(".adjBank").prop("required",true).show();
  //   }else{
  //     $(this).closest("td").find(".adjBank").prop("required",false).hide();
  //   }
  // })



  $(document).on("click","#subAdjust",function(e){
    // if($(".adjDate").val()==''){
    //   alert('Date cannot be empty');
    //   return false;
    // }
    $(".adjDate").each(function(){
      if($(this).val()==''){
        alert('Date cannot be empty');
        $(this).focus();
        return false;
      }
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
  function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/other_advance/index.blade.php ENDPATH**/ ?>