<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.frontend_settings'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .nav-link {
    padding: .5rem !important;
  }

  .custom .nav-link.active {

      background-color: #21bc6c !important;
  }

  /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.frontend_settings'); ?>
        </h3>
      </div>
      <?php echo Form::open(['url' => 'admin/frontend-settings','method'=>'post']); ?>

      <div class="card-body">
        <div class="row">
          <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger">
              <ul>
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <h4>  <?php echo app('translator')->getFromJson('fleet.frontend_settings'); ?><span id="change" class="text-muted">
              <?php if(Hyvikk::frontend('enable')==1): ?>
                (<?php echo app('translator')->getFromJson('fleet.enable'); ?>)
              <?php else: ?>
                (<?php echo app('translator')->getFromJson('fleet.disable'); ?>)
              <?php endif; ?>
            </span></h4>
          </div>
          <div class="col-md-3 col-sm-12">
            <label class="switch">
              <input type="checkbox" name="enable" value="1" id="enable" <?php if(Hyvikk::frontend('enable')==1): ?> checked <?php endif; ?>>
              <span class="slider round"></span>
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::label('about', __('fleet.about_us'), ['class' => 'form-label']); ?>

              <textarea name="about" class="form-control" rows="3" required><?php echo e(Hyvikk::frontend('about_us')); ?></textarea>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('customer_support',__('fleet.customer_support'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <?php echo Form::number('customer_support', Hyvikk::frontend('customer_support') ,['class' => 'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('phone',__('fleet.contact_number'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <?php echo Form::number('phone', Hyvikk::frontend('contact_phone') ,['class' => 'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('email', __('fleet.contact_email'), ['class' => 'form-label']); ?>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <?php echo Form::email('email',  Hyvikk::frontend('contact_email') ,['class' => 'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::label('about_description', __('About Vehicle Manager Description'), ['class' => 'form-label']); ?>

              <textarea name="about_description" class="form-control" rows="3" required><?php echo e(Hyvikk::frontend('about_description')); ?></textarea>
            </div>
          </div>
            <div class="col-md-6">
              <div class="form-group">
              <?php echo Form::label('about_title',__('About Vehicle Manager Title'), ['class' => 'form-label']); ?>

              <?php echo Form::text('about_title', Hyvikk::frontend('about_title') ,['class' => 'form-control','required']); ?>

            </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
              <?php echo Form::label('language',__('fleet.language'),['class'=>"form-label"]); ?>

                  <select id='language' name='language' class="form-control" required>
                  <option value="en" <?php if(Hyvikk::frontend('language')=="en"): ?> selected <?php endif; ?>> English</option>
                  </select>
              </div>
            </div>

          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('faq_link',__('fleet.faq_link'), ['class' => 'form-label']); ?>

              <?php echo Form::text('faq_link', Hyvikk::frontend('faq_link') ,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('cities',__('fleet.cities_serving'), ['class' => 'form-label']); ?>

              <?php echo Form::number('cities', Hyvikk::frontend('cities') ,['class' => 'form-control','required','min'=>0]); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vehicles',__('fleet.vehicles_serving'), ['class' => 'form-label']); ?>

              <?php echo Form::number('vehicles', Hyvikk::frontend('vehicles') ,['class' => 'form-control','required','min'=>0]); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('cancellation',__('fleet.cancellation_link'), ['class' => 'form-label']); ?>

              <?php echo Form::text('cancellation', Hyvikk::frontend('cancellation') ,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('terms',__('fleet.terms'), ['class' => 'form-label']); ?>

              <?php echo Form::text('terms', Hyvikk::frontend('terms') ,['class' => 'form-control']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('privacy_policy',__('fleet.privacy_policy'), ['class' => 'form-label']); ?>

              <?php echo Form::text('privacy_policy', Hyvikk::frontend('privacy_policy') ,['class' => 'form-control']); ?>

            </div>
          </div>
        </div>
        <hr>
       
      <div class="card-footer">
        <div class="row">
          <div class="form-group">
            <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.save'); ?>"/>
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
<script type="text/javascript">
  //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

    $('#enable').change(function () {
      if($('#enable').is(":checked")){
        // alert("checked");
        $("#change").empty();
        $("#change").append(" (<?php echo app('translator')->getFromJson('fleet.enable'); ?>)");

      }
      else{
        // alert("unchecked");
        $("#change").empty();
        $("#change").append(" (<?php echo app('translator')->getFromJson('fleet.disable'); ?>)");
      }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/utilities/frontend.blade.php ENDPATH**/ ?>