<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("parts-invoice.index")); ?>">Manage <?php echo app('translator')->getFromJson('fleet.parts_inv'); ?></a></li>
<li class="breadcrumb-item active">Add <?php echo app('translator')->getFromJson('fleet.parts_inv'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Add <?php echo app('translator')->getFromJson('fleet.parts_inv'); ?></h3>
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

          <?php echo Form::open(['route' => 'parts-invoice.store','method'=>'post','files'=>true]); ?>

          <?php echo Form::hidden("user_id",Auth::user()->id); ?>

          <div class="row">
             <div class="col-md-8"></div>
             <div class="col-md-4">
             <div class="form-group">
              <?php echo Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']); ?>

              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
                <?php echo Form::text('dateofpurchase',date("d-m-Y"),['class'=>'form-control dateofpurchase','required']); ?>

              </div>
            </div>
             </div>
          </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('invoice', "Bill/Invoice", ['class' => 'form-label']); ?>

                  <?php echo Form::file('invoice',['class' => 'form-control invoice','accept'=>'.pdf,.doc,.png,.jpg,.jpeg,.gif']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('billno', __('fleet.billno'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('billno', null,['class' => 'form-control billno','required','placeholer'=>'e.g PAT-15']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

                  <?php echo Form::select("vendor_id",$vendors,null,['class'=>'form-control vendor_id','id'=>'vendor_id','placeholder'=>'Select Vendor','required']); ?>

                </div>
              </div>
            </div>
        

          <div class="" id="parts_form">
            <div class="row cal_div">
              <div class="col-md-12">
                <div class="form-group">
                  <?php echo Form::label('item', __('fleet.item'), ['class' => 'form-label']); ?>

                  <?php echo Form::select('item[]',$items, null,['class' => 'form-control item','required','placeholder'=>'Select Part']); ?>

                </div> 
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span> </div>
                    <?php echo Form::text('unit_cost[]', null,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']); ?>

                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('stock[]', null,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('tyre_number', __('fleet.tyre_number'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('tyre_number[]', null, ['class' => 'form-control tyre_number', 'disabled' => 'disabled', 'placeholder' => 'Enter comma-separated tyre numbers']); ?>

                </div>
              </div>
              <div class="col-md-4">  
                <div class="form-group">   
                  <?php echo Form::label('total', __('fleet.total'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('total[]', null,['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']); ?>

                </div>
              </div>
            </div>
          </div>
          <div class="row more_less"></div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('subtotal', __('fleet.sumtotal'), ['class' => 'form-label']); ?>

              <?php echo Form::text('subtotal', null,['class' => 'form-control subtotal','readonly','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
          </div>
          <!-- <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('cash_payment', __('fleet.cash_payment'), ['class' => 'form-label']); ?>

              <?php echo Form::text('cash_payment', null,['class' => 'form-control cash_payment','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('cheque_draft', __('fleet.cheque_draft'), ['class' => 'form-label']); ?>

              <?php echo Form::text('cheque_draft', null,['class' => 'form-control cheque_draft']); ?>

            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
                  <?php echo Form::label('cheque_draft_amount', __('fleet.cheque_draft_amount'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('cheque_draft_amount', null,['class' => 'form-control cheque_draft_amount','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('cheque_draft_date',__('fleet.cheque_draft_date'), ['class' => 'form-label']); ?>

              <?php echo Form::text('cheque_draft_date',null,['class'=>'form-control cheque_draft_date']); ?>

            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']); ?>

              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
                <?php echo Form::text('dateofpurchase',date("d-m-Y"),['class'=>'form-control dateofpurchase','required']); ?>

              </div>
            </div>
          </div> -->
          <div class="col-md-12">
            <div class="text-right">
              <button class="btn btn-primary addmore" type="button" id="button_addform" name="button"><?php echo e(__('Add More')); ?></button>
            </div>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card card-solid">
              <div class="card-header">
                <h3 class="card-title">
                  GST
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo Form::label('Is GST?',__('fleet.isGst'), ['class' => 'form-label']); ?>

                        <?php echo Form::select('is_gst',$is_gst,null,['class'=>'form-control','id'=>'is_gst','placeholder'=>'Select','required']); ?>

                      </div>
                      <div class="col-md-6">
                        <?php echo Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']); ?>

                        <?php echo Form::text('cgst',null,['class'=>'form-control','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('cgst_amt',null,['class'=>'form-control','id'=>'cgst_amt','readonly']); ?>

                      </div>
                      <div class="col-md-6">
                        <?php echo Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']); ?>

                        <?php echo Form::text('sgst',null,['class'=>'form-control','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('sgst_amt',null,['class'=>'form-control','id'=>'sgst_amt','readonly']); ?>

                      </div>
                      <div class="col-md-6">
                        <?php echo Form::label('total_amount',__('fleet.total_amount'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('total_amount',null,['class'=>'form-control','id'=>'total_amount','readonly']); ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
              <?php echo Form::submit(__('fleet.savePartInv'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

            </div>
        </div> 
    </div>      
        
<?php echo Form::close(); ?>


      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datetimepicker.js')); ?>"></script>
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

$("document").ready(function(){
  //add more
  // $('#button_addform').click(function(){
  //   $.post('<?php echo e(url("admin/parts-invoice/getparts_form")); ?>',{_token:"<?php echo e(csrf_token()); ?>"},function(result){
  //     // console.log(result)
  //     $(".more_less").append(result);
  //     $(".item:last").select2();
  //   });
  // });

  $("body").on("click",".remove",function(){
    if(confirm("Are you sure ?"))
      $(this).closest(".addmore_cont").remove();
      $(".unit_cost:first").keyup();
      $("#cgst").keyup()
      updateSubtotal();

  })

  $("#vendor_id").select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.select_vendor'); ?>"});
  $(".item").select2();

    $(".dateofpurchase,.cheque_draft_date").datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
      }});

    //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
    $("body").on("keyup", ".unit_cost, .stock", function() {
  var stock = $(this).closest('.cal_div').find('.stock').val();
  var unit_cost = $(this).closest('.cal_div').find('.unit_cost').val();

  if (stock && unit_cost && !isNaN(stock) && !isNaN(unit_cost)) {
    var total = parseFloat(stock) * parseFloat(unit_cost);
    $(this).closest('.cal_div').find('.total').val(parseFloat(total).toFixed(2));

    updateSubtotal();
  }
});

$("body").on("keyup", ".total", function() {
  var stock = $(this).closest('.cal_div').find('.stock').val();
  var total = $(this).val();

  if (stock && total && !isNaN(stock) && !isNaN(total)) {
    var ucost = parseFloat(total) / parseFloat(stock);
    $(this).closest('.cal_div').find('.unit_cost').val(parseFloat(ucost).toFixed(2));

    updateSubtotal();
  }
});

function updateSubtotal() {
  var sumtotal = 0;
  $(".total").each(function(i, e) {
    var thisval = $(this).val();
    if (thisval && !isNaN(thisval)) {
      sumtotal += parseFloat(thisval);
    }
  });
  $(".subtotal").val(sumtotal.toFixed(2));
  $("#cgst").keyup();
}
// $("body").on("keyup", ".stock", function() {
//         var stock = parseInt($(this).val());
//         var tyreNumberField = $(this).closest('.cal_div').find('.tyre_number');
        
//         if (stock && !isNaN(stock) && stock > 0) {
//             tyreNumberField.prop('disabled', false);
//             tyreNumberField.attr('placeholder', 'Enter tyre numbers, comma-separated');
//         } else {
//             tyreNumberField.prop('disabled', true);
//             tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
//         }
//     });
//     $("#savebtn").click(function(e) {
//         var isValid = true;
        
//         $(".stock").each(function() {
//             var stock = parseInt($(this).val());
//             var tyreNumbers = $(this).closest('.cal_div').find('.tyre_number').val();
            
//             if (stock > 0) {
//                 var tyreNumbersArray = tyreNumbers.split(',').map(item => item.trim()).filter(item => item !== '');
                
//                 if (tyreNumbersArray.length !== stock) {
//                     alert('The number of tyre numbers (' + tyreNumbersArray.length + ') does not match the current stock (' + stock + '). Please adjust either the stock or the tyre numbers.');
//                     isValid = false;
//                     return false;
//                 }
//             }
//         });
    
//         if (!isValid) {
//             e.preventDefault();
//         }
//     });

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
    })

    $(document).on('keyup','#cgst,#sgst',function(){
      var price = $(".subtotal").val();
      var cgst = $("#cgst").val();
      // var cgst_amt = $("#cgst_amt").val();
      var sgst = $("#sgst").val();
      // var sgst_amt = $("#sgst_amt").val();
      
      var sendData = {_token:"<?php echo e(csrf_token()); ?>",price:price,cgst:cgst,sgst:sgst};
      $.post("<?php echo e(route('parts-invoice.pi_gstcalculate')); ?>",sendData).done(function(data){
        // console.log(data)
        console.table(data)
       
        if(!isNaN(data.cgstval) && data.cgstval!=0){
          $("#cgst_amt").val(data.cgstval)
        }else{
          $("#cgst_amt").val('')
        }

        if(!isNaN(data.sgstval) && data.sgstval!=0){
          $("#sgst_amt").val(data.sgstval)
        }else{
          $("#sgst_amt").val('')
        }

        if(!isNaN(data.grandtotal) && data.grandtotal!=0){
          $("#total_amount").val(data.grandtotal)
        }else{
          $("#total_amount").val('')
        }

          
      })
    })
})
// $("body").on("change", ".item", function() {
//     var itemId = $(this).val();
//     var tyreNumberField = $(this).closest('.cal_div').find('.tyre_number');
//     var stockField = $(this).closest('.cal_div').find('.stock');

//     $.ajax({
//         url: '<?php echo e(route("get.category.info")); ?>',
//         type: 'GET',
//         data: { item_id: itemId },
//         success: function(response) {
//             if (response.category_name.toLowerCase().includes('tyre')) {
//                 tyreNumberField.prop('disabled', false);
//                 stockField.on('keyup', function() {
//                     var stock = parseInt($(this).val());
//                     if (stock && !isNaN(stock) && stock > 0) {
//                         tyreNumberField.attr('placeholder', 'Enter ' + stock + ' tyre numbers, comma-separated');
//                     } else {
//                         tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
//                     }
//                 });
//             } else {
//                 tyreNumberField.prop('disabled', true);
//                 tyreNumberField.val('');
//                 tyreNumberField.attr('placeholder', 'Not applicable for this item');
//             }
//         },
//         error: function() {
//             console.log('Error fetching category info');
//         }
//     });
// });
// $("#savebtn").click(function(e) {
//     var isValid = true;
    
//     $(".stock").each(function() {
//         var stock = parseInt($(this).val());
//         var tyreNumbers = $(this).closest('.cal_div').find('.tyre_number');
        
//         if (!tyreNumbers.prop('disabled') && stock > 0) {
//             var tyreNumbersArray = tyreNumbers.val().split(',').map(item => item.trim()).filter(item => item !== '');
            
//             if (tyreNumbersArray.length !== stock) {
//                 alert('The number of tyre numbers (' + tyreNumbersArray.length + ') does not match the current stock (' + stock + '). Please adjust either the stock or the tyre numbers.');
//                 isValid = false;
//                 return false;
//             }
//         }
//     });

//     if (!isValid) {
//         e.preventDefault();
//     }
// });
// $("body").on("change", ".item", function() {
//     var itemId = $(this).val();
//     var calDiv = $(this).closest('.cal_div');
//     var tyreNumberField = calDiv.find('.tyre_number');
//     var stockField = calDiv.find('.stock');

//     $.ajax({
//         url: '<?php echo e(route("get.category.info")); ?>',
//         type: 'GET',
//         data: { item_id: itemId },
//         success: function(response) {
//             if (response.category_name.toLowerCase().includes('tyre')) {
//                 tyreNumberField.prop('disabled', false);
//                 updateTyreNumberPlaceholder(stockField, tyreNumberField);
//             } else {
//                 tyreNumberField.prop('disabled', true);
//                 tyreNumberField.val('');
//                 tyreNumberField.attr('placeholder', 'Not applicable for this item');
//             }
//         },
//         error: function() {
//             console.log('Error fetching category info');
//         }
//     });
// });

// $("body").on("keyup", ".stock", function() {
//     var calDiv = $(this).closest('.cal_div');
//     var tyreNumberField = calDiv.find('.tyre_number');
    
//     if (!tyreNumberField.prop('disabled')) {
//         updateTyreNumberPlaceholder($(this), tyreNumberField);
//     }
// });

// function updateTyreNumberPlaceholder(stockField, tyreNumberField) {
//     var stock = parseInt(stockField.val());
//     if (stock && !isNaN(stock) && stock > 0) {
//         tyreNumberField.attr('placeholder', 'Enter ' + stock + ' tyre numbers, comma-separated');
//     } else {
//         tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
//     }
// }

// $("#savebtn").click(function(e) {
//     var isValid = true;
    
//     $(".cal_div").each(function() {
//         var stockField = $(this).find('.stock');
//         var tyreNumberField = $(this).find('.tyre_number');
//         var itemField = $(this).find('.item');
//         var stock = parseInt(stockField.val());
//         var itemId = itemField.val();
        
//         if (!tyreNumberField.prop('disabled') && stock > 0) {
//             var tyreNumbersArray = tyreNumberField.val().split(',').map(item => item.trim()).filter(item => item !== '');
            
//             if (tyreNumbersArray.length !== stock) {
//                 alert('The number of tyre numbers (' + tyreNumbersArray.length + ') does not match the current stock (' + stock + ') for one of the items. Please adjust either the stock or the tyre numbers.');
//                 isValid = false;
//                 return false;
//             }
            
//             // Add this line to include tyre numbers in the form submission
//             tyreNumberField.attr('name', 'tyre_number[' + itemId + ']');
//         }
//     });

//     if (!isValid) {
//         e.preventDefault();
//     }
// });

// $('#button_addform').click(function(){
//     $.post('<?php echo e(url("admin/parts-invoice/getparts_form")); ?>', {_token:"<?php echo e(csrf_token()); ?>"}, function(result){
//         $(".more_less").append(result);
//         $(".item:last").select2().trigger('change');  // Trigger change event after initializing Select2
//     });
// });

$("body").on("change", ".item", function() {
    var itemId = $(this).val();
    var calDiv = $(this).closest('.cal_div');
    var tyreNumberField = calDiv.find('.tyre_number');
    var stockField = calDiv.find('.stock');

    $.ajax({
        url: '<?php echo e(route("get.category.info")); ?>',
        type: 'GET',
        data: { item_id: itemId },
        success: function(response) {
            if (response.category_name.toLowerCase().includes('tyre')) {
                tyreNumberField.prop('disabled', false);
                updateTyreNumberPlaceholder(stockField, tyreNumberField);
            } else {
                tyreNumberField.prop('disabled', true);
                tyreNumberField.val('');
                tyreNumberField.attr('placeholder', 'Not applicable for this item');
            }
        },
        error: function() {
            console.log('Error fetching category info');
        }
    });
});

$("body").on("keyup", ".stock", function() {
    var calDiv = $(this).closest('.cal_div');
    var tyreNumberField = calDiv.find('.tyre_number');
    
    if (!tyreNumberField.prop('disabled')) {
        updateTyreNumberPlaceholder($(this), tyreNumberField);
    }
});

function updateTyreNumberPlaceholder(stockField, tyreNumberField) {
    var stock = parseInt(stockField.val());
    if (stock && !isNaN(stock) && stock > 0) {
        tyreNumberField.attr('placeholder', 'Enter ' + stock + ' tyre numbers, comma-separated');
    } else {
        tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
    }
}

$("#savebtn").click(function(e) {
    var isValid = true;
    
    $(".cal_div").each(function() {
        var stockField = $(this).find('.stock');
        var tyreNumberField = $(this).find('.tyre_number');
        var itemField = $(this).find('.item');
        var stock = parseInt(stockField.val());
        var itemId = itemField.val();
        
        if (!tyreNumberField.prop('disabled') && stock > 0) {
            var tyreNumbersArray = tyreNumberField.val().split(',').map(item => item.trim()).filter(item => item !== '');
            
            if (tyreNumbersArray.length !== stock) {
                alert('The number of tyre numbers (' + tyreNumbersArray.length + ') does not match the current stock (' + stock + ') for one of the items. Please adjust either the stock or the tyre numbers.');
                isValid = false;
                return false;
            }
            
            // Add this line to include tyre numbers in the form submission
            tyreNumberField.attr('name', 'tyre_number[' + itemId + ']');
        }
    });

    if (!isValid) {
        e.preventDefault();
    }
});

$('#button_addform').click(function(){
    $.post('<?php echo e(url("admin/parts-invoice/getparts_form")); ?>', {_token:"<?php echo e(csrf_token()); ?>"}, function(result){
        $(".more_less").append(result);
        $(".more_less .cal_div:last").find(".item").select2().trigger('change');  // Initialize Select2 for the new item only
    });
});

 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/parts_invoice/create.blade.php ENDPATH**/ ?>