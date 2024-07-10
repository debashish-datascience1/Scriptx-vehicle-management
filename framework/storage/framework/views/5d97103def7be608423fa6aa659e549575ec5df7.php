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

.custom .nav-link.active {

    background-color: #21bc6c !important;
}

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.api_settings'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.api_settings'); ?>
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

        <div>
          <ul class="nav nav-pills custom">
            <li class="nav-item"><a href="#general" data-toggle="tab" class="nav-link active"> <?php echo app('translator')->getFromJson('menu.general_settings'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#firebase" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.firebase_settings'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#serverkey" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.app_notification'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a href="#maps" data-toggle="tab" class="nav-link"> <?php echo app('translator')->getFromJson('fleet.maps'); ?> <i class="fa"></i></a></li>
          </ul>
        </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tab-content card-body">
            <div class="tab-pane active" id="general">
              <?php echo Form::open(['url' => 'admin/api-settings','files'=>true,'method'=>'post']); ?>


              <div class="row">
                <div class="form-group col-md-3">
                  <h6><?php echo Form::label('api',__('fleet.api'),['class'=>"form-label"]); ?> </h6>
                  <label class="switch">
                  <input type="checkbox" class="api" id="api" name="name[api]" value="1"  <?php if(Hyvikk::api('api') == "1"): ?> checked <?php endif; ?>>
                  <span class="slider round"></span>
                  </label>
                </div>

                <?php if(Hyvikk::api('api') == "1"): ?>
                <div class="form-group col-md-3">
                  <h6><?php echo Form::label('google_api',__('fleet.google_places_api'),['class'=>"form-label"]); ?> <strong class="text-muted">(<a href="https://docs.listingprowp.com/knowledgebase/how-to-get-map-working-with-google-api-key/" target="blank"><?php echo app('translator')->getFromJson('fleet.api_guide'); ?></a>)</strong></h6>
                  <label class="switch">
                  <input type="checkbox" name="name[google_api]" value="1" <?php if(Hyvikk::api('google_api') == "1"): ?> checked <?php endif; ?>>
                  <span class="slider round"></span>
                  </label>
                </div>

                <div class="form-group col-md-3">
                  <h6><?php echo Form::label('anyone_register',__('fleet.anyone_register'),['class'=>"form-label"]); ?> </h6>
                  <label class="switch">
                  <input type="checkbox" name="name[anyone_register]" value="1" <?php if(Hyvikk::api('anyone_register') == "1"): ?> checked <?php endif; ?>>
                  <span class="slider round"></span>
                  </label>
                </div>

                <div class="form-group col-md-3">
                  <h6><?php echo Form::label('driver_review',__('fleet.driver_review'),['class'=>"form-label"]); ?> </h6>
                  <label class="switch">
                  <input type="checkbox" name="name[driver_review]" value="1" <?php if(Hyvikk::api('driver_review') == "1"): ?> checked <?php endif; ?>>
                  <span class="slider round"></span>
                  </label>
                </div>

                <div class="form-group col-md-12">
                  <?php echo Form::label('region_availability',__('fleet.region_availability'),['class'=>"form-label"]); ?>

                  <?php echo Form::text('name[region_availability]',
                  Hyvikk::api('region_availability'),['class'=>"form-control"]); ?>

                </div>

                <div class="form-group col-md-4">
                  <?php echo Form::label('max_trip',__('fleet.max_trip'),['class'=>"form-label"]); ?>

                  <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>
                  <?php echo Form::number('name[max_trip]',Hyvikk::api('max_trip'),['class'=>"form-control trip","required"]); ?>

                  </div>
                </div>

                <div class="form-group col-md-4">
                  <?php echo Form::label('booking',__('fleet.booking'),['class'=>"form-label"]); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar-check-o"></i></span></div>
                    <?php echo Form::number('name[booking]',Hyvikk::api('booking'),['class'=>"form-control",'required']); ?>

                  </div>
                </div>
                <div class="form-group col-md-4">
                  <?php echo Form::label('cancel',__('fleet.cancel'),['class'=>"form-label"]); ?>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar-times-o"></i></span>
                    </div>
                    <?php echo Form::number('name[cancel]',Hyvikk::api('cancel'),['class'=>"form-control"]); ?>

                  </div>
                </div>
                <?php endif; ?>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <input type="submit" class="form-control btn btn-success api_btn" value="<?php echo app('translator')->getFromJson('fleet.save'); ?>" />
                </div>
              </div>

              <?php echo Form::close(); ?>

            </div>

            <div class="tab-pane" id="firebase">
              <?php echo Form::open(['url' => 'admin/firebase-settings','files'=>true,'method'=>'post']); ?>

              <div class="row">
                <div class="form-group col-md-6">
                  <?php echo Form::label('db_url',__('fleet.db_url'),['class'=>"form-label"]); ?>

                  <?php echo Form::text('db_url',
                  Hyvikk::api('db_url'),['class'=>"form-control",'required','placeholder'=>'for example: https://PROJECT.firebaseio.com']); ?>

                </div>
                <div class="form-group col-md-6">
                  <?php echo Form::label('db_secret',__('fleet.db_secret'),['class'=>"form-label"]); ?>


                  <?php echo Form::text('db_secret',
                  Hyvikk::api('db_secret'),['class'=>"form-control",'required']); ?>

                </div>

                <div class="col-md-6">
                  <button type="submit" class="btn btn-success" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.test'); ?></button>
                </div>
                <?php echo Form::close(); ?>

              </div>
            </div>

            <div class="tab-pane" id="serverkey">
              <?php echo Form::open(['url' => 'admin/store-key','files'=>true,'method'=>'post']); ?>

              <div class="row">
                <div class="form-group col-md-12">
                  <?php echo Form::label('server_key',__('fleet.legacy_key'),['class'=>"form-label"]); ?>


                  <?php echo Form::text('server_key',
                  Hyvikk::api('server_key'),['class'=>"form-control",'required']); ?>

                </div>
                <div class="col-md-6">
                  <button type="submit" class="btn btn-success" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.test'); ?></button>
                </div>
                <?php echo Form::close(); ?>

              </div>
            </div>
            <div class="tab-pane" id="maps">
              <?php echo Form::open(['url' => 'admin/store-api','files'=>true,'method'=>'post']); ?>

              <div class="row">
                <div class="form-group col-md-12">
                  <?php echo Form::label('api_key',__('fleet.google_maps'),['class'=>"form-label"]); ?>


                  <?php echo Form::text('api_key',
                  Hyvikk::api('api_key'),['class'=>"form-control",'required']); ?>

                </div>
                <div class="col-md-6">
                  <button type="submit" id="test_api" class="btn btn-success" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.test'); ?></button>
                </div>
                <?php echo Form::close(); ?>

              </div>
              <hr>
              <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                  <div class="form-group">
                    <h6 class="text-danger"> <strong><?php echo app('translator')->getFromJson('fleet.important_Notes'); ?>:</strong></h6>
                    <ol class="text-muted">
                      <li>If you see the message <strong>"Success"</strong>, it means the Google API key is Working.</li>
                      <li>If you don't see the Message, make sure you have enabled the <strong> "Places API, Geocoding API, Maps JavaScript API"</strong> APIs from the "Enabled API" section <a href="https://console.developers.google.com/" target="_blank">Visit here</a></li>
                      <li>You can create a Google API key from this page - <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">General information available here </a> </li>
                      <li><strong>"Error Messages"</strong> information <a href="https://developers.google.com/maps/documentation/javascript/error-messages" target="_blank">available here</a></li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $(".api_btn").on("click",function(){
    // alert($(".trip").val());
    if (($(".trip").val())<1) {
        alert("Maximum Time a Trip can Go(in Days) must be greater than or equal to 1");
        return false;
    }else{
      return true;
    }
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
  <?php if(isset($_GET['tab']) && $_GET['tab']!=""): ?>
  $('.nav-pills a[href="#<?php echo e($_GET['tab']); ?>"]').tab('show')
  <?php endif; ?>
});
</script>
<script type="text/javascript">
  $(window).on('load', function() {

    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
      new PNotify({
        title: 'Success!',
        text: 'Firebase credentials matched.',
        type: 'success',
        delay: 15000
      });
    <?php elseif(isset($_GET['success']) && $_GET['success'] == 0): ?>
      new PNotify({
        title: 'Failed!',
        text: 'Firebase credentials does not matched, Try again!',
        type: 'error',
        delay: 15000
      });
    <?php endif; ?>

    <?php if(isset($_GET['key']) && $_GET['key'] == 1): ?>
      new PNotify({
        title: 'Success!',
        text: 'Legacy server key stored successfully.',
        type: 'success',
        delay: 15000
      });
    <?php elseif(isset($_GET['key']) && $_GET['key'] == 0): ?>
      new PNotify({
        title: 'Failed!',
        text: 'Legacy server key is invalid, Try again!',
        type: 'error',
        delay: 15000
      });
    <?php endif; ?>

    <?php if(isset($_GET['api_key']) && $_GET['api_key'] == 1): ?>

      new PNotify({
        title: 'Success!',
        text: '<?php echo e($_GET["msg"]); ?>',
        type: 'success',
        delay: 15000
      });
    <?php elseif(isset($_GET['api_key']) && $_GET['api_key'] == 0): ?>
      new PNotify({
        title: 'Failed!',
        text: '<?php echo e($_GET["msg"]); ?>',
        type: 'error',
        delay: 5000
      });
    <?php endif; ?>

  });
</script>

<?php if((isset($_GET['test_key']) && $_GET['test_key']!= null) && (isset($_GET['api_key']) && $_GET['api_key'] == 0)): ?>
<script type="text/javascript">
  function initMap() {
    var geocoder = new google.maps.Geocoder();
    geocodeAddress(geocoder);

    // $.get('https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=<?php echo e($_GET['test_key']); ?>',function(data){
    //   console.log("$.get: "+data.status);
    //   if(data.status == "OK"){
    //     alert("$.get: "+data.status);

    //   }
    //   else{
    //     alert("$.get: "+data.error_message);
    //   }
    // });

  }

  function geocodeAddress(geocoder) {
    geocoder.geocode({'location': {lat: 40.714224, lng: -73.961452 }}, function(results, status) {
      // console.log(results);
      // console.log(status);
      if (status === 'OK') {
        // store api key
        $.ajax({
          type: "GET",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

          url: "<?php echo e(url('admin/ajax-api-store/').'/'.$_GET['test_key']); ?>",

          success: function(data){
            // console.log(data);
            new PNotify({
              title: 'Success!',
              text: 'API key successfully saved',
              type: 'success',
              delay: 15000
            });
          },

          dataType: "json"
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($_GET['test_key']); ?>&callback=initMap">
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/utilities/api_settings.blade.php ENDPATH**/ ?>