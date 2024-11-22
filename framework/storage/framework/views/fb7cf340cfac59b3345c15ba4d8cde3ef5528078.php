<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item "><a href="<?php echo e(route("vehicle-docs.index")); ?>">Vehicle Documents</a></li>
<li class="breadcrumb-item active">Edit Document</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          Edit Vehicle Document
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

        <?php echo Form::open(['route' => ['vehicle-docs.update', $doc->id], 'method'=>'PUT','id'=>'editDocForm','files'=>true]); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('status',1); ?>

        <?php echo Form::hidden('doc_id', $doc->param_id); ?>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.select_vehicle'), ['class' => 'form-label']); ?>

              <?php echo Form::select('vehicle_id',$vehicles,$doc->vehicle_id,['class'=>'form-control','id'=>'vehicle_id','required']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('date','Document Date', ['class' => 'form-label']); ?>

              <?php echo Form::text('date', date('d-m-Y', strtotime($doc->date)), ['class' => 'form-control date', 'id' => 'date', 'required', 'autocomplete' => 'off', 'data-id' => $doc->vehicle_id, 'data-doc' => $doc->param_id]); ?>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vendor','Vendor', ['class' => 'form-label']); ?>

              <?php echo Form::select('vendor',$vendors,$doc->vendor_id,['class'=>'form-control vendor','required','placeholder'=>'Select Vendor']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('bank','Bank Account', ['class' => 'form-label']); ?>

              <?php echo Form::select('bank',$bankAccount,$doc->transaction->bank_id,['class'=>'form-control bank','required','placeholder'=>'Select Bank']); ?>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('amount','Amount', ['class' => 'form-label']); ?>

              <?php echo Form::number('amount', $doc->amount, ['class' => 'form-control amount', 'required', 'step' => '0.01', 'min' => '0']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('method','Payment Method', ['class' => 'form-label']); ?>

              <?php echo Form::select('method',$method,$doc->method,['class'=>'form-control method','required','placeholder'=>'Select Payment Method']); ?>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('ddno','Reference No.', ['class' => 'form-label']); ?>

              <?php echo Form::text('ddno', $doc->ddno, ['class' => 'form-control ddno', 'required']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('remarks','Remarks', ['class' => 'form-label']); ?>

              <?php echo Form::textarea('remarks', $doc->remarks, ['class' => 'form-control remarks', 'rows' => 2]); ?>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::submit('Update Document', ['class' => 'btn btn-warning']); ?>

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
<script src="<?php echo e(asset('assets/jquery-ui/jquery-ui.min.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
   $("body").on("focus",".date",function(){
    var self = $(this);
    $(this).datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
      onSelect: function(date){
        var vid = $(this).data("id");
        var ddoc = $(this).data("doc");
        var dataSet = {_token:"<?php echo e(csrf_token()); ?>",date:date,vehicle_id:vid,doc_id:ddoc}; 
          $.ajax({
              type:"POST",
              url:"<?php echo e(route('vehicle-docs.getNext')); ?>",
              data:dataSet,
              success: function(result){
                if(self.next().length)
                  self.next().remove();
                self.after(result);
              }
          });
      }
    });
   });

   $("#vehicle_id").select2({
     placeholder : 'Please select a vehicle',
   });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicle_docs/edit.blade.php ENDPATH**/ ?>