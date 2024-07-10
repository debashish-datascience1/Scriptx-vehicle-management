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
  /* .viewModal{color:#fff} */

  .nav-tabs-custom>.nav-tabs>li.active{border-top-color:#00a65a !important;}

/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.custom .nav-link.active {

    background-color: #21bc6c !important;

}
</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.payroll'); ?></li>
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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.payroll'); ?> &nbsp;
          
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
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Salary</th>
              
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
                <?php echo e($row->name); ?>

              </td>
              
              <td>
                <?php if(!empty($row->driver_vehicle)): ?>
                  <?php echo e($row->driver_vehicle->vehicle->make); ?>-<?php echo e($row->driver_vehicle->vehicle->model); ?>-<?php echo e($row->driver_vehicle->vehicle->license_plate); ?>

                <?php else: ?>
                  <span class="badge badge-danger">Driver Not Assigned</span>
                <?php endif; ?>

              </td>
              <td><span class="fa fa-inr"> <?php echo e(number_format($row->salary)); ?></span></td>
              <td>
              <div class="btn-group">
                <a class="vpay btn btn-success" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#viewModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>" style="color: #fff">Pay</a>
                
                
                  
                
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
              <th><?php echo app('translator')->getFromJson('fleet.name'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.salary'); ?></th>
              
              
              
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
        
      </div>
      <div class="modal-footer">
        
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
  <div class="modal-dialog" style="max-width:50%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pay</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">

  $(".vpay").click(function(){
      var id = $(this).data('id');
      $("#viewModal .modal-body").load('<?php echo e(url("admin/payroll/single_pay")); ?>/'+id,function(result){
         $("#viewModal").modal({show:true})
      })
  })

  $('body').on('click','#payroll',function(e){
    var blankTest = /\S/;
    var remain = atob($("#remaining").val());
    var paysal = $("#payable_salary").val();
    var dump = {_token:'<?php echo e(csrf_token()); ?>',remain:remain,paysal:paysal};
    if(!blankTest.test(paysal)){
      alert("Payable Salary Cannot be empty. Select month, year and try again..")
      return false;
    }else{
      // console.log(remain);
      // console.log(paysal);
      // console.log(dump);
      if(confirm('Are you sure ?')){
        var posting = $.post('<?php echo e(url("admin/payroll/purse")); ?>',dump);
        posting.done(function(data){
          // console.log(data)
          if(data.can==false)
            return false;
        })
      }
      // console.log(data);
      // return false;
    }

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
    console.log($( "input[name='ids[]']:checked" ).length);
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
  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }

  $(document).on('change','#month,#year',function(){
    $("#working_days").val('');
    $("#absent_days").val('');
    $("#advance_salary").val('');
    $("#advance_driver").val('');
    $("#total_payable_salary").val('');
    $("#payable_salary").val('');
    $("#carried_salary").val('');
  })

  $(document).on('change keyup','#month,#year,#working_days',function(){
    var month = $("#month").val();
    var year = $("#year").val();
    var working_days = $("#working_days").val();
    var total_days = $("#working_days").data("totaldays");
    var driver_id = $("#driver_id").val();
    if(month=='' || month==null){
      alert('Please select Month');
      return false;
    }

    if(year=='' || year==null){
      alert('Please select Year');
      return false;
    }

    if(working_days>total_days && $("#manual").is(":checked")){
      alert('Working days cannot be more than number of days in this month');
      $("#working_days").val(0);
      working_days = 0;
      return false;
    }

    var data = {_token:"<?php echo e(csrf_token()); ?>",month:month,year:year,driver_id:driver_id,working_days:working_days};
    var posting = $.post('<?php echo e(url("admin/payroll/getWorkingDays")); ?>',data);
    posting.done(function(resp){
      console.log(resp);
      $("#working_days").val(resp.presentDays);
      $("#working_days").attr("data-totaldays",resp.totalMonthDays);
      // $("#working_days").data("totaldays",resp.totalMonthDays);
      $("#absent_days").val(resp.absentDays);
      $("#advance_salary").val(resp.salary_advance);
      $("#advance_driver").val(resp.bookingAdvance);
      $(".expenditure_div").html(resp.view);
      $("#deduct_sal").html("Deduct Salary <span class='fa fa-inr'></span>"+resp.deduct_amount);
      $("#deduct_salary").val(resp.deduct_amount);
      $("#payable_sal").html("Payable Salary <span class='fa fa-inr'></span>"+resp.payable_salary);
      $("#advancedriver_sal").html("Advance to Driver <span class='fa fa-inr'></span>"+resp.bookingAdvance);
      $("#payable_salary").val(resp.payable_salary);
      $("#total_payable_salary").val(resp.total_payable_salary);
      $("#carried_salary").val(resp.carried_salary);
      $("#payable_actual").val(resp.payable_salary);
      // if(resp.isLeaveChecked==true){
      //   // $(".manual_workingdays").hide();
      //   $("#working_days").prop('readonly',true);
      // }else{
      //   // $(".manual_workingdays").show();
      //   $("#working_days").prop('readonly',false);
      //   $("#absent_days").val($("#working_days").data("totaldays"));
      //   // $("#working_days").trigger('keyup');
      // }
      console.log(resp.isLeaveChecked,resp.yetToComplete,resp.isAlreayPaid)
      if(resp.isLeaveChecked && resp.yetToComplete==null && resp.isAlreayPaid==false){
        console.log('satisfies')
        $("#working_days").prop('readonly',true);
        $("#payable_salary").prop('readonly',false);
        $("#payroll").prop("disabled",false);
      }else{
        $("#working_days").prop('readonly',false);
        $("#payable_salary").prop('readonly',true);
        $("#payroll").prop("disabled",true);
      }
    })
  })

  $(document).on('keyup','#payable_salary',function(){
    var total = $("#total_payable_salary").val();
    var payable = $("#payable_salary").val();
    var data = {_token:"<?php echo e(csrf_token()); ?>",total:total,payable:payable}
    $.post("<?php echo e(route('payroll.payabletype')); ?>",data).done(function(result){
      if(result.ismore)
        $("#payable_salary").val('');
    });
  })


  
  

  

  // $(document).on('click','#manual',function(){
  //   if($("#manual").is(":checked"))
  //     $("#working_days").prop('readonly',false);
  //   else
  //     $("#working_days").prop('readonly',true);
  // })
  
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/payroll/index.blade.php ENDPATH**/ ?>