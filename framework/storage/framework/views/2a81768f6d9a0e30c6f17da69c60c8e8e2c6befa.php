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
<li class="breadcrumb-item"><a href="<?php echo e(route("work-order-category.index")); ?>">Manage Order Head</a></li>
<li class="breadcrumb-item active">Add Order Head</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add Order Head</h3>
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

        <?php echo Form::open(['route' => 'work-order-category.store','files'=>true,'method'=>'post']); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('name', __('fleet.name'), ['class' => 'form-label required','autofocus']); ?>

              <?php echo Form::text('name',null,['class' => 'form-control','required','placeholder'=>'Enter Order Head Name..']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('status', __('fleet.status'), ['class' => 'form-label required']); ?>

              <?php echo Form::select('status',$workOrderCategory->statusOptions(),null,['class' => 'form-control','required','placeholder'=>'Select Status']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('description', __('fleet.description'), ['class' => 'form-label required']); ?>

              <?php echo Form::textarea('description',null,['class' => 'form-control','placeholder'=>'Select Description','style'=>'height:100px;resize:none;']); ?>

            </div>
          </div>
          <div class="col-md-12">
          <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

          </div>
        </div>
        <?php echo Form::close(); ?>

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
    var date = new Date();
    date.setDate(date.getDate()-1);
    $('#date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        // startDate: date,
        endDate: '+1d'
    });

    $(".drivers").select2();

    $("#present_type").change(function(){
        if($(this).val()==1 || $(this).val()=='')
            $("#driver_id").attr('disabled',true);
        else
            $("#driver_id").attr('disabled',false);
    })

    $("#driver_id").on("change",function(){
        var darr = $(this).val();
        console.log(darr)
        $('.remarks').load('<?php echo e(url("admin/leave/get_remarks")); ?>/'+darr,function(result){
            // console.log(result);
        })
    })

    

    $("#savebtn").click(function(){
        if($("#date").val()==""){
            alert('Date field can\'t be empty.');
            return false;
        }
            
    })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/work_order_categories/create.blade.php ENDPATH**/ ?>