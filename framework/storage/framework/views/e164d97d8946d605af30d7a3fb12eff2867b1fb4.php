<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("parts.index")); ?>"><?php echo app('translator')->getFromJson('menu.manageParts'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.addParts'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.addParts'); ?></h3>
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

        <?php echo Form::open(['route' => 'parts.store','method'=>'post','files'=>true]); ?>

        <?php echo Form::hidden("user_id",Auth::user()->id); ?>

        <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('billno', __('fleet.billno'), ['class' => 'form-label']); ?>

              <?php echo Form::text('billno', null,['class' => 'form-control billno']); ?>

            </div>

          </div>
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']); ?>

              <select id="vendor_id" name="vendor_id" class="form-control vendor_id" >
                <option value="">-</option>
                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                
                <option value="<?php echo e($vendor->id); ?>"><?php echo e($vendor->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>

        </div>
       

          <div class="" id="parts_form">

          </div>      
        
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

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


function parts_form(){
      
		$.post('<?php echo e(route('parts.get_parts_form')); ?>',{_token:'<?php echo e(csrf_token()); ?>'}, function(data){
         // $('.more').remove();
          $('#parts_form').html(data);         
		});
}

parts_form();



  $("#vendor_id").select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.select_vendor'); ?>"});
  

  $('#dateofpurchase').datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
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
// $(function(){
//   $("body").on("click","#savebtn",function(){
//     //e.preventDefault();
//    var blankTest = /\S/;
//    var billno = $('.billno').val();
//    var vendor_id = $('.vendor_id').val();
//    var title = $('.title');
//    var number = $('.number');
//    var category_id = $('.category_id');
//    var manufacturer = $('.manufacturer');
//    var unit_cost = $('.unit_cost');
//    var stock = $('.stock');
//    $(".more").remove();
//    var returnval = true;
//    console.log(billno);
//    console.log(title.val());

//    if(!blankTest.test(billno)){
//     $('.billno').css('border','1px solid red').focus();
//     alert("Bill No cannot be empty");
//     return false;
//    }else{ 
//      $('.billno').css('border',''); 
//      }

//      if(!blankTest.test(vendor_id)){
//         $('.vendor_id').css('border','1px solid red').focus();
//         alert("Vendor name cannot be empty");
//         return false;
//       }else{ 
//         $('.vendor_id').css('border',''); 
//       }

//    if(title.length>0){
//     $('.title').each(function(){
//       console.log($(this).val());
//       if(!blankTest.test($(this).val())){
//         $(this).css('border','1px solid red').focus();
//         alert("Title cannot be empty");
//         //i.preventDefault();
//         returnval =  false;
//       }else{ $(this).css('border',''); }
//       })
//       if(returnval===false) return returnval;
//    }

//    if(number.length>0){
//     $('.number').each(function(){
//       if(!blankTest.test($(this).val())){
//         $(this).css('border','1px solid red').focus();
//         alert("Number cannot be empty");
//         returnval =  false;
//     }else{ $(this).css('border',''); }
//     })
//     if(returnval===false) return returnval;
//    }

//    if(category_id.length>0){
//     $('.category_id').each(function(e){
//       if(!blankTest.test($(this).val())){
//       $(this).css('border','1px solid red').focus();
//       alert("Category cannot be empty");
//       returnval =  false;
//     }else{ $(this).css('border',''); }
//     })
//     if(returnval===false) return returnval;
//    }

//    if(manufacturer.length>0){
//     $('.manufacturer').each(function(e){
//       if(!blankTest.test($(this).val())){
//       $(this).css('border','1px solid red').focus();
//       alert("manufacturer cannot be empty");
//       returnval =  false;
//     }else{ $(this).css('border',''); }
//     })
//     if(returnval===false) return returnval;
//    }
//    if(unit_cost.length>0){
//     $('.unit_cost').each(function(e){
//       if(!blankTest.test($(this).val())){
//       $(this).css('border','1px solid red').focus();
//       alert("Unit cost cannot be empty");
//       returnval =  false;
//     }else{ $(this).css('border',''); }
//     })
//     if(returnval===false) return returnval;
//    }
//    if(stock.length>0){
//     $('.stock').each(function(e){
//       if(!blankTest.test($(this).val())){
//       $(this).css('border','1px solid red').focus();
//       alert("Stock cannot be empty");
//       returnval =  false;
//     }else{ $(this).css('border',''); }
//     })
//     if(returnval===false) return returnval;
//    }
//   // return false;
//  })
// })
 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/parts/create.blade.php ENDPATH**/ ?>