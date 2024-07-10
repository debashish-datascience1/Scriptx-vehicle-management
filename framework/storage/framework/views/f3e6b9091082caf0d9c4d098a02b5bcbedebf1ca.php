<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.email_notification'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">

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
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.email_notification'); ?>
        </h3>
      </div>
      <div class="card-body">
        <?php echo Form::open(['url' => 'admin/enable-mail','method'=>'post']); ?>

        <div class=" card card-body">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <h4>  <?php echo app('translator')->getFromJson('menu.email_notification'); ?><span id="change" class="text-muted">
              <?php if(Hyvikk::email_msg('email')==1): ?>
                (<?php echo app('translator')->getFromJson('fleet.enable'); ?>)
              <?php else: ?>
                (<?php echo app('translator')->getFromJson('fleet.disable'); ?>)
              <?php endif; ?>
            </span></h4>
          </div>
          <div class="col-md-3 col-sm-12">
            <label class="switch">
              <input type="checkbox" name="email" value="1" id="email" <?php if(Hyvikk::email_msg('email')==1): ?> checked <?php endif; ?>>
              <span class="slider round"></span>
            </label>
          </div>
          <div class="col-md-3 col-sm-12">
            <button type="submit" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.update'); ?></button>
          </div>
        </div>
      </div>
        <?php echo Form::close(); ?>

        <hr>
        <?php echo Form::open(['url' => 'admin/email-settings','method'=>'post']); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('users', __('fleet.select_user'), ['class' => 'form-label']); ?>

              <select class="form-control" required name="users[]" multiple style="height: 200px;">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php if(in_array($user->id,$selected_users)): ?> selected <?php endif; ?>><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('chk',__('fleet.selectNotification'), ['class' => 'form-label']); ?> <br>
              <input type="checkbox" name="chk[]" value="1" class="flat-red form-control" <?php if(in_array(1,$options)): ?> checked <?php endif; ?>>&nbsp; Registration Notification<br>
              <input type="checkbox" name="chk[]" value="2" class="flat-red form-control" <?php if(in_array(2,$options)): ?> checked <?php endif; ?>>&nbsp; Insurance Notification <br>
              <input type="checkbox" name="chk[]" value="3" class="flat-red form-control" <?php if(in_array(3,$options)): ?> checked <?php endif; ?>>&nbsp; Vehicle Licence Notification <br>
              <input type="checkbox" name="chk[]" value="4" class="flat-red form-control" <?php if(in_array(4,$options)): ?> checked <?php endif; ?>>&nbsp; Driving Licence Notification <br>
              <input type="checkbox" name="chk[]" value="5" class="flat-red form-control" <?php if(in_array(5,$options)): ?> checked <?php endif; ?>>&nbsp; Service Reminder Notification<br>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="form-group">
              <input type="submit" class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.save'); ?>" id="save"/>
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
  $(document).ready(function() {
  //Flat green color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

    $("#save").on("click",function(){
      if (($("input[name*='chk']:checked").length)<=0) {
          alert("You must select at least 1 Notification");
          return false;
      }else{
        return true;
      }
    });

    $('#email').change(function () {
      if($('#email').is(":checked")){
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
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/utilities/send_email.blade.php ENDPATH**/ ?>