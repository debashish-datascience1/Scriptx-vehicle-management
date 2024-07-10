<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("daily-advance.index")); ?>"><?php echo app('translator')->getFromJson('fleet.salary_advance'); ?></a></li>
<li class="breadcrumb-item active">Add <?php echo app('translator')->getFromJson('fleet.salary_advance'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add <?php echo app('translator')->getFromJson('fleet.salary_advance'); ?></h3>
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

        <?php echo Form::open(['route' => 'daily-advance.store','files'=>true,'method'=>'post']); ?>

        <div class="row toprow">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('driver_id', __('fleet.driver'), ['class' => 'form-label required','autofocus']); ?>

              <?php echo Form::select('driver_id[]',$driver,null,['class' => 'form-control drivers','required','id'=>'driver_id','multiple'=>'multiple']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

              <?php echo Form::text('date', null,['class' => 'form-control','readonly','required']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']); ?>

              <?php echo Form::text('amount', null,['class' => 'form-control','required','onkeypress'=>'return isNumber(event)']); ?>

              <small><strong>CASH : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purse)); ?></strong></small>
            </div>
          </div>
        </div>
        <div class="remarks"></div>
        <div class="row">
            <div class="col-md-12">
            <?php echo Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']); ?>

            </div>
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#driver_id").select2();
  $("#driver_id").on("change",function(){
        var darr = $(this).val();
        console.log(darr)
        $('.remarks').load('<?php echo e(url("admin/daily-advance/get_remarks")); ?>/'+darr,function(result){
            console.log(result);
        })
    })
  $("#sub").click(function(){
    if($("#date").val()=="" || $("#date").val()==null){
      alert("Date cannot be empty");
      $("#date").css('border','1px solid red').focus();
      return false;
    }else{
      $("#date").css('border','');
    }
  });
  $('#date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });

  $("#date").on('changeDate',function(event){
    var drivers = $("#driver_id").val();
    var date = event.format();
    var params = {_token:"<?php echo e(csrf_token()); ?>",drivers :drivers ,date:date} 
    var posting = $.post("<?php echo e(route('daily-advance.ispaychecked')); ?>",params);
    posting.done(function(data){
      // console.log(data);
      
      $.each(data,function(i,e){
        // console.log(e);
        // console.log(e.paycheck);
        if(e.paycheck){
          // $(".toprow").append("<label style='color:red'>"+e.message+"</label>");
          // $("#sub").prop('readonly',true);
          alert(e.message);
          location.reload()
        }
      })
    })
  })

});
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/daily_advance/create.blade.php ENDPATH**/ ?>