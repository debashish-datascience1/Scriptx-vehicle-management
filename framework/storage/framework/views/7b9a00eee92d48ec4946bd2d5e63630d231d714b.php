<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("bank-account.index")); ?>"><?php echo app('translator')->getFromJson('fleet.bankAccount'); ?></a></li>
<li class="breadcrumb-item active">Edit <?php echo app('translator')->getFromJson('fleet.bankAccount'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Edit <?php echo app('translator')->getFromJson('fleet.bankAccount'); ?></h3>
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

        <?php echo Form::model($deposit,['route' => ['bank-account.deposit_save',$deposit->id],'files'=>true,'method'=>'PATCH']); ?>

        <div class="row toprow">
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo Form::label('bank','Bank',['class' => 'form-label']); ?>

                    <?php echo Form::select('bank',$banks,$bankSelect,['class'=>'form-control','id'=>'bank','placeholder'=>'Select Bank','required']); ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo Form::label('is_self','Is Self ?',['class' => 'form-label']); ?>

                    <?php echo Form::select('is_self',$is_self,!empty($deposit->refer_bank) ? 1 : null,['class'=>'form-control','id'=>'is_self','placeholder'=>'Is Self']); ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo Form::label('amount','Amount',['class' => 'form-label']); ?>

                    <?php echo Form::text('amount',null,['class'=>'form-control','id'=>'amount','placeholder'=>'Enter Amount','required']); ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo Form::label('date','Date',['class' => 'form-label']); ?>

                    <?php echo Form::text('date',Helper::indianDateFormat($deposit->date),['class'=>'form-control','id'=>'date','required','readonly']); ?>

                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <?php echo Form::label('remarks','Remarks',['class' => 'form-label']); ?>

                    <?php echo Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','style'=>'height:100px;resize:none;']); ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']); ?>

                </div>
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
        // console.log(darr)
        $('.remarks').load('<?php echo e(url("admin/bank-account/get_remarks")); ?>/'+darr,function(result){
            console.log(result);
        })
    })
//   $("#sub").click(function(){
//     if($("#date").val()=="" || $("#date").val()==null){
//       alert("Date cannot be empty");
//       $("#date").css('border','1px solid red').focus();
//       return false;
//     }else{
//       $("#date").css('border','');
//     }
//   });
  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bank_account/deposit_edit.blade.php ENDPATH**/ ?>