<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item "><a href="<?php echo e(route("bookings.index")); ?>"><?php echo app('translator')->getFromJson('menu.bookings'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.new_booking'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.new_booking'); ?>
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

        <?php echo Form::open(['route' => 'bookings.store','method'=>'post']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('status',0); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('customer_id',__('fleet.selectCustomer'), ['class' => 'form-label']); ?>

              <?php if(Auth::user()->user_type != "C"): ?> <a href="#" data-toggle="modal" data-target="#exampleModal"><?php echo app('translator')->getFromJson('fleet.new_customer'); ?></a> <?php endif; ?>
              <select id="customer_id" name="customer_id" class="form-control" required>
                <option value="">-</option>
                <?php if(Auth::user()->user_type == "C"): ?>
                <option value="<?php echo e(Auth::user()->id); ?>" data-address="<?php echo e(Auth::user()->getMeta('address')); ?>" data-address2="" data-id="<?php echo e(Auth::user()->id); ?>" selected><?php echo e(Auth::user()->name); ?>

                </option>
                <?php else: ?>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($customer->id); ?>" data-address="<?php echo e($customer->getMeta('address')); ?>" data-address2="" data-id="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('pickup',__('fleet.pickup'), ['class' => 'form-label']); ?>

              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                </div>
                <?php echo Form::text('pickup',date("Y-m-d H:i:s"),['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('dropoff',__('fleet.dropoff'), ['class' => 'form-label']); ?>

              <div class='input-group date'>
                <div class="input-group-prepend">
                  <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                </div>
                <?php echo Form::text('dropoff',date("Y-m-d H:i:s"),['class'=>'form-control','required']); ?>

              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']); ?>

              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>" data-driver="<?php echo e($vehicle->driver_id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('driver_id',__('fleet.selectDriver'), ['class' => 'form-label']); ?>


              <select id="driver_id" name="driver_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($driver->id); ?>" ><?php echo e($driver->name); ?> <?php if($driver->getMeta('is_active') != 1): ?>
                ( <?php echo app('translator')->getFromJson('fleet.in_active'); ?> ) <?php endif; ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('travellers',__('fleet.no_travellers'), ['class' => 'form-label']); ?>

              <?php echo Form::number('travellers',1,['class'=>'form-control','min'=>1]); ?>

            </div>
          </div>
        </div>
        <?php if(Auth::user()->user_type == "C"): ?>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('d_pickup',__('fleet.pickup_addr'), ['class' => 'form-label']); ?>

              <select id="d_pickup" name="d_pickup" class="form-control">
                <option value="">-</option>
                <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($address->id); ?>" data-address="<?php echo e($address->address); ?>"><?php echo e($address->address); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <?php echo Form::label('d_dest',__('fleet.dropoff_addr'), ['class' => 'form-label']); ?>

            <select id="d_dest" name="d_dest" class="form-control">
            <option value="">-</option>
            <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($address->id); ?>" data-address="<?php echo e($address->address); ?>"><?php echo e($address->address); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('pickup_addr',__('fleet.pickup_addr'), ['class' => 'form-label']); ?>

              <?php echo Form::text('pickup_addr',null,['class'=>'form-control','required','style'=>'height:100px']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('dest_addr',__('fleet.dropoff_addr'), ['class' => 'form-label']); ?>

              <?php echo Form::text('dest_addr',null,['class'=>'form-control','required','style'=>'height:100px']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('note',__('fleet.note'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('note',null,['class'=>'form-control','placeholder'=>__('fleet.book_note'),'style'=>'height:100px']); ?>

            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="form-group col-md-6">
            <?php echo Form::label('udf1',__('fleet.add_udf'), ['class' => 'col-xs-5 control-label']); ?>

            <div class="row">
              <div class="col-md-8">
                <?php echo Form::text('udf1', null,['class' => 'form-control']); ?>

              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-info add_udf"> <?php echo app('translator')->getFromJson('fleet.add'); ?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="blank"></div>
        <div class="col-md-12">
          <?php echo Form::submit(__('fleet.save_booking'), ['class' => 'btn btn-success']); ?>

        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel"><?php echo app('translator')->getFromJson('fleet.new_customer'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <?php echo Form::open(['route' => 'customers.ajax_store','method'=>'post','id'=>'create_customer_form']); ?>

      <div class="modal-body">
        <div class="alert alert-danger print-error-msg" style="display:none">
          <ul></ul>
        </div>
        <div class="form-group">
          <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']); ?>

          <?php echo Form::text('first_name', null,['class' => 'form-control','required']); ?>

        </div>

        <div class="form-group">
          <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']); ?>

          <?php echo Form::text('last_name', null,['class' => 'form-control','required']); ?>

        </div>
        <div class="form-group">
          <?php echo Form::label('gender', __('fleet.gender') , ['class' => 'form-label']); ?><br>
          <input type="radio" name="gender" class="flat-red gender" value="1" checked> <?php echo app('translator')->getFromJson('fleet.male'); ?><br>

          <input type="radio" name="gender" class="flat-red gender" value="0"> <?php echo app('translator')->getFromJson('fleet.female'); ?>
        </div>

        <div class="form-group">
          <?php echo Form::label('phone',__('fleet.phone'), ['class' => 'form-label']); ?>

          <div class="input-group mb-3">
          <div class="input-group-prepend">
          <span class="input-group-text"><i class="fa fa-phone"></i></span></div>
          <?php echo Form::number('phone', null,['class' => 'form-control','required']); ?>

          </div>
        </div>
        <div class="form-group">
          <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
            <?php echo Form::email('email', null,['class' => 'form-control','required']); ?>

          </div>
        </div>
        <div class="form-group">
          <?php echo Form::label('address', __('fleet.address'), ['class' => 'form-label']); ?>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-address-book-o"></i></span></div>
            <?php echo Form::textarea('address', null,['class' => 'form-control','size'=>'30x2']); ?>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo app('translator')->getFromJson('fleet.save_cust'); ?></button>
      </div>
    </div>
    <?php echo Form::close(); ?>

  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datetimepicker.js')); ?>"></script>

<script type="text/javascript">
  $('#customer_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectCustomer'); ?>"});
  $('#driver_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectDriver'); ?>"});
  $('#vehicle_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
  $('#pickup').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss',sideBySide: true,icons: {
              previous: 'fa fa-arrow-left',
              next: 'fa fa-arrow-right',
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
  }});
  $('#dropoff').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss',sideBySide: true,icons: {
              previous: 'fa fa-arrow-left',
              next: 'fa fa-arrow-right',
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
            }
  });

  $("#create_customer_form").on("submit",function(e){
    $(".print-error-msg").find("ul").html('');
    $(".print-error-msg").hide();
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
        var customers=  $.parseJSON(data);
        if(customers == 0){
          new PNotify({
            title: 'Failed!',
            text: "<?php echo app('translator')->getFromJson('fleet.email_already_taken'); ?>",
            type: 'error'
          });
        }
        else{
          $('#customer_id').empty();
          $.each( customers, function( key, value ) {
            $('#customer_id').append($('<option>', {
              value: value.id,
              text: value.text
            }));
          });
          $('#exampleModal').modal('hide');

          new PNotify({
            title: 'Success!',
            text: '<?php echo app('translator')->getFromJson("fleet.add_customer"); ?>',
            type: 'success'
          });
        }
      },
      error: function(data){
        var errors = $.parseJSON(data.responseText);
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( errors, function( key, value ) {
          $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
      },
      dataType: "html"
    });
    e.preventDefault();
  });

  function get_driver(from_date,to_date){
    $.ajax({
      type: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "<?php echo e(url('admin/get_driver')); ?>",
      data: "req=new&from_date="+from_date+"&to_date="+to_date,
      success: function(data2){
        $("#driver_id").empty();
        $("#driver_id").select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectDriver'); ?>",data:data2.data});
      },
      dataType: "json"
    });
  }

  function get_vehicle(from_date,to_date){
    $.ajax({
      type: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "<?php echo e(url('admin/get_vehicle')); ?>",
      data: "req=new&from_date="+from_date+"&to_date="+to_date,
      success: function(data2){
        $("#vehicle_id").empty();
        $("#vehicle_id").select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>",data:data2.data});
      },
      dataType: "json"
    });
  }

  function prev_address(id){
    $.ajax({
      type: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

      url: "<?php echo e(url('admin/prev-address')); ?>",
      data: "id="+id,
      success: function(data){
        $("#pickup_addr").val(data.pickup_addr);
        $("#dest_addr").val(data.dest_addr);
        if(data.pickup_addr != ""){
          new PNotify({
            title: 'Success!',
            text: "<?php echo app('translator')->getFromJson('fleet.prev_addr'); ?>",
            type: 'success'
          });
        }
      },
      dataType: "json"
    });
  }

  $(document).ready(function() {
    $("#customer_id").on("change",function(){
      var id=$(this).find(":selected").data("id");
      prev_address(id);
    });

    $("#d_pickup").on("change",function(){
      var address=$(this).find(":selected").data("address");
      $("#pickup_addr").val(address);
    });

    $("#d_dest").on("change",function(){
      var address=$(this).find(":selected").data("address");
      $("#dest_addr").val(address);
    });

    $("#pickup").on("dp.change", function (e) {
      var to_date=$('#dropoff').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
      var from_date=e.date.format("YYYY-MM-DD HH:mm:ss");
      get_driver(from_date,to_date);
      get_vehicle(from_date,to_date);
      $('#dropoff').data("DateTimePicker").minDate(e.date);
    });

    $("#dropoff").on("dp.change", function (e) {
      $('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss")
      var from_date=$('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
      var to_date=e.date.format("YYYY-MM-DD HH:mm:ss");

      get_driver(from_date,to_date);
      get_vehicle(from_date,to_date);
    });

    $("#vehicle_id").on("change",function(){
      var driver = $(this).find(":selected").data("driver");
      $("#driver_id").val(driver).change();
    });
  });
</script>
<script type="text/javascript">
  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter field name');
    }

    else{
      $(".blank").append('<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php if(Hyvikk::api('google_api') == "1"): ?>
  <script>
  function initMap() {
    $('#pickup_addr').attr("placeholder","");
    $('#dest_addr').attr("placeholder","");
      // var input = document.getElementById('searchMapInput');
      var pickup_addr = document.getElementById('pickup_addr');
      new google.maps.places.Autocomplete(pickup_addr);

      var dest_addr = document.getElementById('dest_addr');
      new google.maps.places.Autocomplete(dest_addr);

      // autocomplete.addListener('place_changed', function() {
      //     var place = autocomplete.getPlace();
      //     document.getElementById('pickup_addr').innerHTML = place.formatted_address;
      // });
  }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(Hyvikk::api('api_key')); ?>&libraries=places&callback=initMap" async defer></script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/bookings/create.blade.php ENDPATH**/ ?>