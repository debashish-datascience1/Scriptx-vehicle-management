<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("customers.index")); ?>"><?php echo app('translator')->getFromJson('fleet.customers'); ?></a></li>
<li class="breadcrumb-item active"> <?php echo app('translator')->getFromJson('fleet.edit_customer'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.edit_customer'); ?>
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

        <?php echo Form::open(['route' => ['customers.update',$data->id],'method'=>'PATCH']); ?>

        <?php echo Form::hidden('id',$data->id); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('company_name', __('fleet.companyName'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-home"></i></span>
                </div>
                  <?php echo Form::text('company_name', $data->name,['class' => 'form-control','required']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('company_gst', __('fleet.companyGst'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-certificate"></i></span>
                </div>
                  <?php echo Form::text('company_gst', $data->gstin,['class' => 'form-control']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('phone',__('fleet.phone'), ['class' => 'form-label']); ?>

              <div class=" input-group mb-3">
                <div class="input-group-prepend date">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <?php echo Form::number('phone', $data->getMeta('mobno'),['class' => 'form-control','required']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <?php echo Form::email('email', $data->email,['class' => 'form-control']); ?>

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('address', __('fleet.address'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                </div>
                <?php echo Form::textarea('address', $data->getMeta('address'),['class' => 'form-control','size'=>'30x2']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <?php echo Form::label('opening_balance',__('fleet.opening_balance'), ['class' => 'form-label']); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                    </div>
                    <?php echo Form::text('opening_balance', $data->getMeta('opening_balance'),['class' => 'form-control opening_balance','id'=>'opening_balance','placeholer'=>'Enter Opening Balance','onkeypress'=>'return isNumberNegative(event,this)']); ?>

                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <?php echo Form::label('opening_remarks',"Opening Remarks", ['class' => 'form-label']); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text-o"></i></span>
                    </div>
                    <?php echo Form::textarea('opening_remarks', $data->getMeta('opening_remarks'),['class' => 'form-control','size'=>'30x2','style'=>'resize:none;height:100px;']); ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']); ?>

        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
  function isNumberNegative(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      // console.log(charCode);
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.//CHECK ONLY '-' Sign is allowed
          ((charCode < 48 && charCode != 45) || charCode > 57))
          return false;
          return true;
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/customers/edit.blade.php ENDPATH**/ ?>