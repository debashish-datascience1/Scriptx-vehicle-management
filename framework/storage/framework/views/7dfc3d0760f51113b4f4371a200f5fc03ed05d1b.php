<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("leave.index")); ?>"><?php echo app('translator')->getFromJson('fleet.leave'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.editLeave'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.editLeave'); ?></h3>
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

        <?php echo Form::model($leave,['route' => ['leave.update',$leave->id],'files'=>true,'method'=>'PATCH']); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('drivers', 'Driver Name', ['class' => 'form-label required']); ?>

              <?php echo Form::select('driver_id',$drivers,$leave->driver_id,['class' => 'form-control drivers','required','id'=>'driver_id','readonly']); ?>

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
                <?php echo Form::label('is_present','Is Present?', ['class' => 'form-label required']); ?>

                <?php echo Form::select('is_present',Helper::getLeaveTypes(),$leave->is_present,['class' => 'form-control remarks','required','placeholder'=>'Select Status']); ?>

              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group remarks">
              <?php echo Form::label('remarks', 'Remark', ['class' => 'form-label required']); ?>

              <?php echo Form::text('remarks',$leave->remarks,['class' => 'form-control remarks']); ?>

            </div>
          </div>
            <div class="col-md-12">
            <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

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
    // $("#driver_id").attr('disabled',true);
    // var date = new Date();
    // date.setDate(date.getDate()-1);
    // $('#date').datepicker({
    //     autoclose: true,
    //     format: 'yyyy-mm-dd',
    //     startDate: date,
    //     endDate: '+0d'
    // });

    // $(".drivers").select2();

    // $("#present_type").change(function(){
    //     if($(this).val()==1 || $(this).val()=='')
    //         $("#driver_id").attr('disabled',true);
    //     else
    //         $("#driver_id").attr('disabled',false);
    // })

    // $("#driver_id").on("change",function(){
    //     var darr = $(this).val();
    //     console.log(darr)
    //     $('.remarks').load('<?php echo e(url("admin/leave/get_remarks")); ?>/'+darr,function(result){
    //         console.log(result);
    //     })
    // })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/leaves/edit.blade.php ENDPATH**/ ?>