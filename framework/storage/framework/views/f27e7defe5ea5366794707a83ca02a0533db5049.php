<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
  .btn-vertical-align{margin-top: 32px;}
  .description{height: 100px;resize: none;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("work_order.index")); ?>"><?php echo app('translator')->getFromJson('fleet.work_orders'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_order'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.create_workorder'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php if(count($errors) > 0): ?>
          <div class="alert alert-danger">
            <ul>
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php echo Form::open(['route' => 'work_order.store','method'=>'post','files'=>true]); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('type','Created'); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('bill_image', "Bill/bill", ['class' => 'form-label']); ?>

              <?php echo Form::file('bill_image',['class' => 'form-control bill','accept'=>'.pdf,.doc,.png,.jpg,.jpeg,.doc,.docx']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.vehicle'), ['class' => 'form-label']); ?>

              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo Form::label('required_by', "Date", ['class' => 'form-label']); ?>

              <div class="input-group date">
              <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></div>
              <?php echo Form::text('required_by',null,['class'=>'form-control','required','readonly']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('meter',Hyvikk::get('dis_format')." ".__('fleet.reading'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
              <?php echo Form::number('meter',null,['class'=>'form-control']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',null,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('bill_no',"Bill No", ['class' => 'form-label']); ?>

              <?php echo Form::text('bill_no',null,['class'=>'form-control bill_no','id'=>'bill_no','placeholder'=>'Enter Bill No...']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vendor->id); ?>"> <?php echo e($vendor->name); ?> </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <div class="form-group">
              <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

              <?php echo Form::select('status',["Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control','required']); ?>

            </div>

            <div class="row">
              <div class="col-6">
                <div class="form-group">
                <?php echo Form::label('order_head',__('fleet.order_head'), ['class' => 'form-label']); ?>

                <?php echo Form::select('category_id',$orderHeads,null,['class' => 'form-control','required','placeholder'=>'Select Order Head']); ?>

                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <?php echo Form::label('price',__('fleet.work_order_price'), ['class' => 'form-label']); ?>

                  <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <?php echo Form::text('price',null,['class'=>'form-control','onkeypress'=>'return isNumber(event,this)','required']); ?>

                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <?php echo Form::label('description',__('fleet.description'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('description',null,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']); ?>

            </div>
          </div>
        </div>
        <hr>
        <div class="row" style="margin-bottom: 25px;">
          <div class="col-md-2"> 
            <div class="form-group"> 
              <?php echo Form::label('is_addParts',"Add Parts ?", ['class' => 'form-label']); ?>

              <?php echo Form::select('is_addParts',[2=>'No',1=>'Yes'],null,['class'=>'form-control','id'=>'is_addParts']); ?>

            </div>
          </div>
        </div>
        <div class="parts-div">
        </div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <?php echo Form::label('billable_parts',"Billable Parts : ", ['class' => 'form-label billable_parts']); ?>

            <?php echo e(Hyvikk::get('currency')); ?> <?php echo Form::label('parts_value',"0.00", ['class' => 'form-label parts_value']); ?>

          </div>
          <div class="col-md-4">
            <?php echo Form::label('nonbillable_parts',"Non-Billable Part : ", ['class' => 'form-label nonbillable_parts']); ?>

            <?php echo e(Hyvikk::get('currency')); ?> <?php echo Form::label('non_wo_value',"0.00", ['class' => 'form-label non_wo_value']); ?>

          </div>
          <div class="col-md-4">
            <?php echo Form::label('workorder_price',"Total Work Order Price : ", ['class' => 'form-label workorder_price']); ?>

            <?php echo e(Hyvikk::get('currency')); ?> <?php echo Form::label('wo_value',"0.00", ['class' => 'form-label wo_value']); ?>

          </div>
        </div>
        <div class="row">
          <div class="parts col-md-12"></div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <?php echo Form::submit(__('fleet.add_order'), ['class' => 'btn btn-success','id'=>'addwork']); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="dropAdd" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 211%;margin-left: -155px;">
      <div class="modal-header">
        <h4 class="modal-title">Add Part</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
// Check Number and Decimal
function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
}
function isNumberOnly(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function removeAddMore(self){
    if(confirm('Are you sure ?')){
      self.closest(".addedmore").remove();
      $("#cgst").trigger('keyup')
    }
  }

function getPartsPrices(){
  const billable = [];
  const nonbillable = [];
  const total = [];
  var woprice=  $("#price").val();
  $(".parts_id").each(function(){
    var owndiv = $(this).closest(".fullPartsRow");
    var isown = owndiv.find('.is_own').val();
    if(isown==1){
      var total_cost = owndiv.find(".total_cost").val();
      nonbillable.push(total_cost);
    }else{
      var total_cost = owndiv.find(".after_gst").val();
      billable.push(total_cost);
    }
    total.push(total_cost);
  })
  var data = {_token:"<?php echo e(csrf_token()); ?>",billable:billable,nonbillable:nonbillable,price:woprice,total:total};
  console.log(data);
  $.post("<?php echo e(route('work_order.othercalc')); ?>",data).done(function(result){
    console.log(result);
    $(".parts_value").html(result.billable);
    $(".non_wo_value").html(result.nonbillable);
    $(".wo_value").html(result.grandtotal);
  })
  // return data;
}
$(document).ready(function() {
  $('#vehicle_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
  $('#vendor_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.select_vendor'); ?>"});
  $('#select_part').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectPart'); ?>"});
  $('#required_by').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#created_on').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });

  $("#vendor_id").change(function(){
    console.log($(this).val())
    if($(this).val()==7){
      $("#is_gst").val(2);
      $("#is_gst").trigger('change')
    }else{
      $("#is_gst").val(1);
      $("#is_gst").trigger('change')
    }
  })

  $("#is_addParts").change(function(){
    var is_add = $(this).val();
    var div_count = $('.fullPartsRow').length;
    var data = {_token:"<?php echo e(csrf_token()); ?>",count:div_count};
    if(is_add==1){
      $.post("<?php echo e(route('work_order.add_parts')); ?>",data).done(function(result){
        $(".parts-div").append(result);
      })
    }else{
      $(".parts-div").html('');
    }
  })

  $("body").on("change",".is_own",function(){
    var is_own = $(this).val();
    if(is_own==1){
      // $(this).closest(".fullPartsRow").find(".gst-row").hide();
      $(this).closest(".fullPartsRow").find("#cgst").prop({"required":false,"readonly":true});
      $(this).closest(".fullPartsRow").find("#sgst").prop({"required":false,"readonly":true});
    }else{
      // $(this).closest(".fullPartsRow").find(".gst-row").show();
      $(this).closest(".fullPartsRow").find("#cgst").prop({"required":true,"readonly":false});
      $(this).closest(".fullPartsRow").find("#sgst").prop({"required":true,"readonly":false});
    }
    getPartsPrices();
  })

  $("body").on("change",".parts_id",function(){
    if($(this).val()=='add_new'){
        $("#dropAdd .modal-body").load("<?php echo e(route('work_order.new_part')); ?>",function(data){
          $("#dropAdd .modal-body").html(data);
          $("#dropAdd").modal('show');
        })
    }
  })

  $("body").on("click",".addmore",function(){
    var div_count = $('.fullPartsRow').length;
    var data = {_token:"<?php echo e(csrf_token()); ?>",count:div_count};
    $.post("<?php echo e(route('work_order.add_parts')); ?>",data).done(function(result){
      $(".parts-div").append(result);
    })
  })

  $("body").on("click",".remove",function(){
    if(confirm("Are you sure ?")){
      $(this).closest(".fullPartsRow").remove();
      var div_count = $(".fullPartsRow").length;
      div_count==0 ? $("#is_addParts").val(2) : $("#is_addParts").val(1);
      getPartsPrices();
    }
  })

  $("body").on("click","#savebtn",function(e){
    e.preventDefault();
    var blankTest = /\S/;
    var item = $("#item").val();
    var category_id = $("#category_id").val();
    var manufacturer = $("#manufacturer").val();
    var unit = $("#unit").val();
    var min_stock = $("#min_stock").val();
    var description = $("#description").val();

    if(!blankTest.test(item)){
      alert("Item cannot be empty");
      $("#item").focus();
      return false;
    }
    if(!blankTest.test(category_id)){
      alert("Please select category");
      $("#category_id").focus();
      return false;
    }
    if(!blankTest.test(manufacturer)){
      alert("Please select manufacturer");
      $("#manufacturer").focus();
      return false;
    }
    if(!blankTest.test(unit)){
      alert("Please select unit");
      $("#unit").focus();
      return false;
    }
    
    var data = {_token:"<?php echo e(csrf_token()); ?>",item:item,category_id:category_id,manufacturer:manufacturer,unit:unit,min_stock:min_stock,description:description};
    $.post("<?php echo e(route('work_order.new_part')); ?>",data).done(function(result){
      $("#dropAdd").modal('hide');
      $(".parts_id").each(function(){
        var prev = $(this).val();
        var selecthtml = $(this);
        $(this).html(result);
        if(prev=="add_new")
          selecthtml.val('');
        else
          selecthtml.val(prev)
      })
      console.log(result)
    })
  })

  $("#addwork").click(function(){
    var blankTest = /\S/;
    // var status = $("#status").val();
    // $("#required_by").prop({'required':false,'readonly':false})
    var required_by = $("#required_by").val();
    // console.log(typeof required_by)
    if(!blankTest.test(required_by)){
      alert("Date cannot be empty");
      $("#required_by").focus();
      // $("#required_by").prop({'required':true,'readonly':true})
      return false;
    }

    $(".parts_id").each(function(){
      if($(this).val()=='add_new'){
        alert("Select a valid part");
        $(this).focus();
        return false;
      }
    })
  })

  $("body").on("keyup",".qty,.unit_cost,.cgst,.sgst,#price",function(){
    var ownDiv = $(this).closest(".fullPartsRow");
    var qty = ownDiv.find(".qty").val();
    var unit_cost = ownDiv.find(".unit_cost").val();
    var cgst = ownDiv.find(".cgst").val();
    var sgst = ownDiv.find(".sgst").val();
    var is_own = ownDiv.find(".is_own").val();
    if(qty != "" && unit_cost!=""){
      var data = {_token:"<?php echo e(csrf_token()); ?>",is_own:is_own,qty:qty,unit_cost:unit_cost,cgst:cgst,sgst:sgst};
      $.post("<?php echo e(route('work_order.wo_calcgst')); ?>",data).done(function(result){
        // console.log(result);
        ownDiv.find(".total_cost").val(result.total);
        if(is_own!=1){
          ownDiv.find(".cgst_amt").val(result.cgst_amt);
          ownDiv.find(".sgst_amt").val(result.sgst_amt);
          ownDiv.find(".after_gst").val(result.grand_total);
          getPartsPrices();
        }
      })
    }
  })

  $("#is_gst").change(function(){
    var is_gst = $("#is_gst").val();
    var cgst = $("#cgst")
    var sgst = $("#sgst")
    // var cgst_amt = $("#cgst_amt")
    // var sgst_amt = $("#sgst_amt")
    if(is_gst==1){
      cgst.prop('readonly',false).prop('required',true)
      sgst.prop('readonly',false).prop('required',true)
    }else{
      cgst.prop('readonly',true).prop('required',false).val('')
      sgst.prop('readonly',true).prop('required',false).val('')
      $("#sgst_amt").val('')
      $("#cgst_amt").val('')
      $("#total_amount").val('');
    }
    getPartsPrices();
  })

  // $("#price").keyup(function(){
  //   console.log($(this).val())
  //   var price = $(this).val();
  //   $("#total_amount").val(parseFloat(price).toFixed(2));
  // })
  // $(document).on('keyup','.calc,.cprc,#price,#cgst,#sgst',function(){

  //   var calcArray = $('.calc').map(function(){
  //     return $(this).val()
  //   }).get();
  //   var cprcArray = $('.cprc').map(function(){
  //     return $(this).val()
  //   }).get();
  //   var price = $("#price").val();
  //   var cgst = $("#cgst").val();
  //   // var cgst_amt = $("#cgst_amt").val();
  //   var sgst = $("#sgst").val();
  //   // var sgst_amt = $("#sgst_amt").val();
  //   console.log(calcArray);
  //   console.log(cprcArray);
  //   // return false;
  //   var sendData = {_token:"<?php echo e(csrf_token()); ?>",price:price,calcArray:calcArray,cprcArray:cprcArray,cgst:cgst,sgst:sgst};
  //   $.post("<?php echo e(route('work_order.wo_gstcalculate')); ?>",sendData).done(function(data){
  //     // console.log(data)
  //     console.table(data)
  //     // if(!isNaN(data.total) && data.total!=0){
  //     //   $(".smallfuel").show()
  //     //   $(".fueltot").html(data.total)
  //     // }else{
  //     //   $(".smallfuel").hide()
  //     //   $(".fueltot").html('')
  //     // }

  //     if(!isNaN(data.cgstval) && data.cgstval!=0){
  //       $("#cgst_amt").val(data.cgstval)
  //     }else{
  //       $("#cgst_amt").val('')
  //     }

  //     if(!isNaN(data.sgstval) && data.sgstval!=0){
  //       $("#sgst_amt").val(data.sgstval)
  //     }else{
  //       $("#sgst_amt").val('')
  //     }

  //     if(!isNaN(data.grandtotal) && data.grandtotal!=0){
  //       $("#total_amount").val(data.grandtotal)
  //     }else{
  //       $("#total_amount").val('')
  //     }

        
  //   })
  // })

  //Flat green color scheme for iCheck
  // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
  //   checkboxClass: 'icheckbox_flat-green',
  //   radioClass   : 'iradio_flat-green'
  // });

  // $('.attach').on('click',function(){

  //   var field = $('#select_part').val();
  //   if(field == "" || field == null){
  //     alert('Select Part');
  //   }
  //   else{
  //     var qty=$('#select_part option:selected').attr('qty');
  //     var title=$('#select_part option:selected').attr('title');
  //     var price=$('#select_part option:selected').attr('price');
  //     // alert($('#select_part option:selected').attr('title'));
  //     // alert($('#select_part option:selected').attr('qty'));
  //     $(".parts").append('<div class="row col-md-12 addedmore"><div class="col-md-4">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.selectPart'); ?></label> <select  class="form-control" disabled>  <option value="'+field+'" selected >'+title+'</option> </select> </div></div> <div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.qty'); ?></label> <input type="number" name="parts['+field+']" min="1" value="1" class="form-control calc" max='+qty+' required> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></label> <input type="number" value="'+price+'" name="unit_cost['+field+']" class="form-control cprc" required> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label"><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></label> <input type="number" value="'+price+'" class="form-control total_cost" disabled id="'+field+'"> </div></div> <div class="col-md-2"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="removeAddMore($(this));">Remove</button> </div></div></div>');

  //     $('.calc,.cprc').on('change',function(){
  //       // alert(field)
  //       // alert($(this).val()*price);
  //       var qt = $(this).closest(".addedmore").find(".calc").val();
  //       var pc = $(this).closest(".addedmore").find(".cprc").val();
  //       $(this).closest(".addedmore").find('#'+field).val(qt*pc);
  //     });
  //     $('#select_part').val('').change();
  //   }
  // });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/work_orders/create.blade.php ENDPATH**/ ?>