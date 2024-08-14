<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.css')); ?>">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
  #remarks{resize: none;height: 120px;max-height: 120px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("other-advance.index")); ?>"><?php echo app('translator')->getFromJson('fleet.other_advance'); ?></a></li>
<li class="breadcrumb-item active">Add <?php echo app('translator')->getFromJson('fleet.other_advance'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add <?php echo app('translator')->getFromJson('fleet.other_advance'); ?></h3>
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

        <?php echo Form::open(['route' => 'other-advance.store','files'=>true,'method'=>'post']); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('driver_id', "Drivers", ['class' => 'form-label required','autofocus']); ?>

              <?php echo Form::select('driver_id',$drivers,null,['class' => 'form-control','required','placeholder'=>'Select Drivers']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('bank', 'Banks', ['class' => 'form-label required']); ?>

              <?php echo Form::select('bank',$bank,null,['class' => 'form-control','required','id'=>'bank','placeholder'=>'Select Bank']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('method', 'Payment Method', ['class' => 'form-label required']); ?>

              <?php echo Form::select('method',$method,null,['class' => 'form-control','required','id'=>'method','placeholder'=>'Select Method']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('ref_no', 'Ref. No.', ['class' => 'form-label required']); ?>

              <?php echo Form::text('ref_no',null,['class' => 'form-control','id'=>'ref_no','placeholder'=>'Reference No.']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('date', 'Date', ['class' => 'form-label required']); ?>

              <?php echo Form::text('date',null,['class' => 'form-control','id'=>'date','required','readonly']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('amount', 'Amount', ['class' => 'form-label required']); ?>

              <?php echo Form::text('amount',null,['class' => 'form-control','id'=>'amount','required','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::label('remarks', 'Remarks', ['class' => 'form-label required']); ?>

              <?php echo Form::textarea('remarks',null,['class' => 'form-control','id'=>'remarks','placeholder'=>'Enter Remarks']); ?>

            </div>
          </div>
          <div class="col-md-12">
          <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

          </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.js')); ?>"></script>

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
$(document).ready(function() {
  $("#driver_id").select2();
  $("#date").datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
    });

    $("#savebtn").click(function(){
      var blankTest = /\S/;
      var date = $("#date").val();
      if(!blankTest.test(date)){
        alert("Date cannot be empty");
        $("#date").focus();
        return false;
      }
    })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/other_advance/create.blade.php ENDPATH**/ ?>