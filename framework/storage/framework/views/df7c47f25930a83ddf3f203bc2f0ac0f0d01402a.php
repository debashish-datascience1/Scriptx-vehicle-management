<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<style>
  .description{resize: none;height: 120px;}
</style>
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

          
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('item', __('fleet.item'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('item', null,['class' => 'form-control item','required','placeholder'=>'Enter Item Name']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('category_id',__('fleet.category'), ['class' => 'form-label']); ?>

                  <?php echo Form::select("category_id",$categories,null,['class'=>'form-control category_id','id'=>'category_id','placeholder'=>'Select Category','required']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('manufacturer',__('fleet.manufacturer'), ['class' => 'form-label']); ?>

                  <?php echo Form::select("manufacturer",$manufacturers,null,['class'=>'form-control manufacturer','id'=>'manufacturer','placeholder'=>'Select Vendor','required']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('unit',__('fleet.unit'), ['class' => 'form-label']); ?>

                  <?php echo Form::select("unit",$units,null,['class'=>'form-control unit','id'=>'unit','placeholder'=>'Select Unit','required']); ?>

                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('min_stock', __('fleet.min_stock'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('min_stock', null,['class' => 'form-control min_stock','placeholder'=>'Enter Minimum Stock','onkeypress'=>'return isNumber(event)']); ?>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('description', __('fleet.description'), ['class' => 'form-label']); ?>

                  <?php echo Form::textarea('description', null,['class' => 'form-control description','placeholder'=>'Item/Part description..']); ?>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <?php echo Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

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
function select_type(){
    var type=$("#type option:selected").text();
    if(type=="Add New"){
      $("#nothing").empty();
      $("#nothing").html('<?php echo Form::text('type',null,['class' => 'form-control','required']); ?>');
    }
  }
// Check Number and Decimal
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/parts/create.blade.php ENDPATH**/ ?>