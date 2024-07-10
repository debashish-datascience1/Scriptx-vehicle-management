<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item "><a href="<?php echo e(route("bookings.index")); ?>"><?php echo app('translator')->getFromJson('menu.bookings'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_booking'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.edit_booking'); ?>
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
        <div class="alert alert-info hide fade in alert-dismissable" id="msg_driver">
          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          Your current driver is not available in the chosen times. Available driver  has been selected.
        </div>
        <div class="alert alert-info hide fade in alert-dismissable" id="msg_vehicle">
          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          Your current vehicle is not available in the chosen times. Available vehicle has been selected.
        </div>
        
        <?php echo Form::open(['route' => ['bookings.update',$data->id],'method'=>'PATCH','id'=>'bookingForm']); ?>

        <?php echo Form::hidden('user_id',Auth::user()->id); ?>

        <?php echo Form::hidden('status',0); ?>

        <?php echo Form::hidden('id',$data->id); ?>

        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('customer_id',__('fleet.selectCustomer'), ['class' => 'form-label']); ?>

              <select id="customer_id" disabled="" name="customer_id" class="form-control xxhvk" required onchange="return checkTop()">
                <option selected value="<?php echo e($data->customer['id']); ?>"><?php echo e($data->customer['name']); ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('pickup',__('fleet.pickup'), ['class' => 'form-label']); ?>

              <div class='input-group date' id='from_date'>
                <div class="input-group-prepend">
                <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                </div>
                <?php echo Form::text('pickup',$data->pickup,['class'=>'form-control','required','onchange'=>'return checkTop()']); ?>

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
                <?php echo Form::text('dropoff',$data->dropoff,['class'=>'form-control','required','readonly','onchange'=>'return checkTop()']); ?>

              </div>
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']); ?>

              <select id="vehicle_id" name="vehicle_id" class="form-control" required onchange="return checkTop()">
              <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vehicle->id); ?>" <?php if($vehicle->id==$data->vehicle_id): ?> selected <?php endif; ?>> <?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <input type="hidden" name="ddriver" id="ddriver" value="<?php echo e($data->driver_id); ?>">
              <input type="hidden" name="dvehicle" id="dvehicle" value="<?php echo e($data->vehicle_id); ?>">
              <input type="hidden" name="dmileage" id="dmileage" value="<?php echo e($data->getMeta('mileage')); ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('vehicle_id',__('fleet.selectDriver'), ['class' => 'form-label']); ?>

              <select id="driver_id" name="driver_id" class="form-control" required onchange="return checkTop()">
                <option value="">-</option>
                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($driver->id); ?>" <?php if($driver->id == $data->driver_id): ?> selected <?php endif; ?>><?php echo e($driver->name); ?><?php if($driver->getMeta('is_active') != 1): ?>
                ( <?php echo app('translator')->getFromJson('fleet.in_active'); ?> ) <?php endif; ?></option>
                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('material',__('fleet.material'), ['class' => 'form-label']); ?>

              <?php echo Form::text('material',$data->material,['class'=>'form-control','onchange'=>'return checkTop()']); ?>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('initial_km',__('fleet.initial_km'), ['class' => 'form-label']); ?>

              <?php echo Form::number('initial_km',$data->initial_km,['class'=>'form-control','min'=>1,'onchange'=>'return checkTop()']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('pickup_addr',__('fleet.pickup_addr'), ['class' => 'form-label']); ?>

              <?php echo Form::text('pickup_addr',$data->pickup_addr,['class'=>'form-control','required','onchange'=>'return checkTop()']); ?>

              <input type="hidden" name="picklat" value="" id="picklat">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('dest_addr',__('fleet.dropoff_addr'), ['class' => 'form-label']); ?>

              <?php echo Form::text('dest_addr',$data->dest_addr,['class'=>'form-control','required']); ?>

            </div>
          </div>
        </div>
        
        <div class="blank"></div>
        <div class="col-md-12 btnDiv " style="display: none;">
          <input type="button" value="Update" class="btn btn-primary update" id="update">
        </div>
        <hr>
        <div class="overview-booking">
            
              <div class="card card-default">
                <div class="card-header">Booking Summary</div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label class="form-label">Customer Name :</label>
                      <span id="customerName"><?php echo e($data->customer->name); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Driver Name :</label>
                      <span id="driverName"><?php echo e($data->driver->name); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Vehicle :</label>
                      <span id="vehicleName"><?php echo e($data->vehicle->make); ?> - <?php echo e($data->vehicle->model); ?> - <?php echo e($data->vehicle->license_plate); ?></span>
                      <input type="hidden" id="bookblob">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label class="form-label">Pick-up Address :</label>
                      <span id="pickupAddress"><?php echo e($data->pickup_addr); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Drop-off Address :</label>
                      <span id="dropoffAddress"><?php echo e($data->dest_addr); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Distance :</label>
                      <span id="distance_span"><?php echo e($data->getMeta('distance')); ?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label class="form-label">Pick-up Date :</label>
                      <span id="pickupDate"><?php echo e(Helper::getCanonicalDateTime($data->pickup)); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Drop-off Date :</label>
                      <span id="dropoffDate"><?php echo e(Helper::getCanonicalDateTime($data->dropoff)); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Duration :</label>
                      <span id="duration_span"><?php echo e($data->getMeta('duration_map')); ?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label class="form-label">Material :</label>
                      <span id="material_span"><?php echo e($data->material); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Initial KM :</label>
                      <span id="initialKm"><?php echo e($data->initial_km); ?></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Mileage :</label>
                      <span id="mileage_span"><?php echo e($data->mileage); ?> </span>km/ltr
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                    <input type="hidden" name="_blob" id="_blob" value="">
                      <label class="form-label">Petrol Required :</label>
                      <span class="pet-required"><?php echo e($data->pet_required); ?></span> ltr
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Petrol per Liter :</label>
                        <?php echo Form::number('petrol_perltr',$data->perltr,['class'=>'form-control petrol_perltr','min'=>1,'style'=>'width:25%;display:inline','step'=>'0.01']); ?>

                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Total Petrol Price :</label>
                      Rs. <span class="petrol-price"><?php echo e($data->petrol_price); ?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label class="form-label">Load Type :</label>
                      <?php echo Form::select('load_set',$loadsetting,$data->loadtype,['placeholder'=>'Select Load','class'=>'form-control load_set','style'=>'width:45%;display:inline']); ?>

                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Load :</label>
                      <?php echo Form::number('load_price',$data->loadprice,['class'=>'form-control load_price','min'=>1,'style'=>'width:23%;display:inline','min'=>1,'required','placeholder'=>'Price']); ?>

                      x
                        <?php echo Form::number('load_qty',$data->loadqty,['class'=>'form-control load_qty','min'=>1,'style'=>'width:27%;display:inline','min'=>1,'required','placeholder'=>'Quantity']); ?>

                        <span class="load-type-label"></span>
                    </div>
                    <div class="col-md-4 form-group">
                      <label class="form-label">Total Product Price :</label>
                      Rs. <span class="total-price"><?php echo e($data->total_price); ?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group"></div>
                    <div class="col-md-4 form-group"></div>
                    <div class="col-md-12 form-group">
                      <div class="card card-default">
                        <div class="card-body">
                        <table class="table table-bordered">
                          <tr>
                            <td>
                              <label class="form-label">Party Name :</label>
                            </td>
                            <td>
                            <?php echo Form::text('party_name',$data->party_name,['class'=>'form-control','style'=>'display:inline','required','placeholder'=>'Party Name...']); ?>

                            </td>
                            <td>
                              <label class="form-label">Narration :</label>
                            </td>
                            <td>
                            <?php echo Form::text('narration',$data->narration,['class'=>'form-control','style'=>'display:inline','required','placeholder'=>'Narration..']); ?>

                            </td>
                          </tr>
                          <tr>
                            <td>
                              <label class="form-label">Total Rs. (Round):</label>
                            </td>
                            <td>
                            <?php echo Form::number('total_pay',$data->total_price,['class'=>'form-control total_pay','min'=>1,'style'=>'display:inline','min'=>1,'required','placeholder'=>'Total Payment..','readonly']); ?>

                            </td>
                            <td>
                              <label class="form-label">Advance to Driver :</label>
                            </td>
                            <td>
                            <?php echo Form::number('advance_pay',$data->advance_pay,['class'=>'form-control advance_pay','min'=>1,'style'=>'display:inline','min'=>1,'required','placeholder'=>'Price']); ?>

                            </td>
                          </tr>
                          <tr>
                            <td>
                              <label class="form-label">Payment Method :</label>
                            </td>
                            <td>
                            <?php echo Form::select('payment_type',$payment_type,$payment_method,['class'=>'form-control payment_type','id'=>'payment_type','style'=>'pointer-events:none;','placeholder'=>'Select Payment Method']); ?>

                            </td>
                            <td>
                              <label class="form-label">Payment Amount :</label>
                            </td>
                            <td>
                            <?php echo Form::text('payment_amount',$payment_amount,['class'=>'form-control','id'=>'payment_amount','style'=>'display:inline','onkeypress'=>'return isNumber(event)','placeholder'=>'Price cannot be changed']); ?>

                            </td>
                          </tr>
                          <tr>
                              <td>
                                <label class="form-label">Challan :</label>
                              </td>
                              <td>
                              <?php echo Form::file('challan',['class'=>'form-control challan','accept'=>'.jpg,.jpeg,.png,.gif,.pdf']); ?>

                              <?php if($data->challan != null): ?>
                              <a href="<?php echo e(asset('uploads/'.$data->challan)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
                              <?php endif; ?>
                              </td>
                            <td><label class="form-label">Remarks :</label></td>
                            <td>
                            <?php echo Form::textarea('remarks',$remarks,['class'=>'form-control','placeholder'=>'Remarks about the payment..','style'=>'resize:none;height:100px;']); ?>

                            </td>
                          </tr>
                          <tr>
                            <td colspan="4" align="center">
                              <?php echo Form::submit(__('fleet.save_booking'),['id'=>'booking_sub','class'=>'btn btn-success']); ?>

                            </td>
                          </tr>
                        </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>


<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin:0;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datetimepicker.js')); ?>"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(Hyvikk::api('api_key')); ?>&libraries=places"></script>
<script type="text/javascript">

  function totalPay(){
    var petprice = $(".petrol-price").html();
    var tot_price = $(".total-price").html();
    var total_pay = Math.round(parseFloat(petprice) + parseFloat(tot_price));
    //total price is considered because petrol expenses are given by the employer which will get added to the company expenses.
    $(".total_pay").val(tot_price);
  }

  function getvals(){
    var loadprice = $(".load_price").val();
    var loadqty  = $(".load_qty").val();
    var perltr  = $(".petrol_perltr").val();
    var loadtype  = $(".load_set").val();
    var distance_span  = $("#distance_span").html();
    var duration_span  = $("#duration_span").html();
    var mileage_span  = $("#mileage_span").html();
    var pet_required  = $(".pet-required").html();
    var petrol_price  = $(".petrol-price").html();
    var total_price  = $(".total-price").html();
    var advance_pay  = $(".advance_pay").val();
    var total_pay  = $(".total_pay").val();
  

    var some = {'_token':"<?php echo e(csrf_token()); ?>",'loadprice':loadprice,'loadqty':loadqty,'perltr':perltr,'loadtype':loadtype,'distance':distance_span,'duration':duration_span,'mileage':mileage_span,'pet_required':pet_required,'petrol_price':petrol_price,'total_price':total_price,'advance_pay':advance_pay,'total_pay':total_pay};
    // console.log(some);
    // return false;
    $.ajax({
      url:"<?php echo e(route('bookings.getblob')); ?>",
      type:"post",
      data:some,
      success:function(respond){
        console.log(respond);
        $("#_blob").val(respond);
        $('#bookingForm').submit();
      }
    })

  }

  $(".load_price,.load_qty").keyup(function(){
    var loadprice = $(".load_price").val();
    var loadqty  = $(".load_qty").val();
    var totalprice = (loadprice * loadqty).toFixed(2);
    $(".total-price").html(totalprice);
    totalPay();
  })

  $(".petrol_perltr").keyup(function(){
    var petreq = parseFloat($(".pet-required").html());
    var perltr = $(this).val();
    var petprice = (petreq * perltr).toFixed(2);
    $(".petrol-price").html(petprice)
    // if($(".total-price").html(totalprice)!="")
    //   totalprice();
  })

  $(".load_set").change(function(){
    let selectedVal = $(this).val();
    let labelText = {1:"trips",2:"bags",3:"quintals"}
    // console.log(selectedVal);
    // console.log(labelText[selectedVal])
    $(".load-type-label").html(labelText[selectedVal]);
  })

  $(document).ready(function() {
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  });




  // // Google Maps API
  //   var input = document.getElementById('pickup_addr');
  //   var autocomplete = new google.maps.places.Autocomplete(input);
  

  

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
        console.log(data);
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

  function getRequired(vehicleid){
    $.ajax({
      type: "POST",
      url: "<?php echo e(url('admin/bookings/get_required')); ?>",
      data:{_token:"<?php echo e(csrf_token()); ?>",id:vehicleid},
      success: function(data){
        // console.log(data);
        $("#ddriver").val(data.driver);
        $("#dvehicle").val(data.vehicle);
        $("#dmileage").val(data.mileage);
      }
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
      // prev_address(id);
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
      getRequired($(this).val());
    });
  });
</script>
<script type="text/javascript">

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php if(Hyvikk::api('google_api') == "1"): ?>
  <script>
// Get Latitude and Longitude when page loads
  $(function(){
      var addr = $("#pickup_addr").val();
      $.ajax({
        url:"<?php echo e(url('admin/bookings/get_lat')); ?>",
        type:"POST",
        data:{_token:"<?php echo e(csrf_token()); ?>",addr:addr},
        success:function(respond){
          console.log(respond);
          if(respond['status']=='OK'){
            var pickcord ={'lat':respond.results[0].geometry.location.lat,'long':respond.results[0].geometry.location.lng};
            $("#picklat").val(btoa(JSON.stringify(pickcord)));
          }else{
            alert("Couldn't determine the latitude/longitude of pickup address. Please type it again..")
            $("#pickup_addr").val('');
            $("#dropoff_addr").val('');
            return false;
          }
        }
      })
  })

  function initialize() {
      var options = {
        componentRestrictions: {
          country: "IN"
        }
      };

      var pickInput = document.getElementById('pickup_addr');
      var pickAutocomplete = new google.maps.places.Autocomplete(pickInput, options);
      
      var dropInput = document.getElementById('dest_addr');
      var dropAutocomplete = new google.maps.places.Autocomplete(dropInput, options);

      // var placep = pickAutocomplete.getPlace(); 
      // var picklatlong = {'lat':placep.geometry.location.lat(),'long':placep.geometry.location.lng()}

      google.maps.event.addListener(pickAutocomplete,'place_changed', function() {
          var placep = pickAutocomplete.getPlace(); 
          var picklatlong = {'lat':placep.geometry.location.lat(),'long':placep.geometry.location.lng()}
          // console.log(picklatlong)
          $("#picklat").val(btoa(JSON.stringify(picklatlong)));
          
      });
      google.maps.event.addListener(dropAutocomplete,'place_changed', function() {
          var placed = dropAutocomplete.getPlace();
          var droplatlong = {'lat':placed.geometry.location.lat(),'long':placed.geometry.location.lng()}
          var pick = (JSON.parse(atob($("#picklat").val())))
          var drop = droplatlong;
          console.log(pick);
          console.log(drop);

          var from = new google.maps.LatLng(pick.lat, pick.long);
          var fromName = $("#pickup_addr").val();
          var destName = $("#dest_addr").val();
          var dest = new google.maps.LatLng(drop.lat, drop.long);

          var service = new google.maps.DistanceMatrixService();
          
          service.getDistanceMatrix(
          {
              origins: [from],
              destinations: [dest],
              travelMode: 'DRIVING',
          }, callback);

      function callback(response, status) {
          if (status == 'OK') {
              var origins = response.originAddresses;
              var destinations = response.destinationAddresses;


              $(".overview-booking").fadeIn('slow').show();
              // $(".btnDiv").fadeIn('slow').show();
              // console.log(response);
              
              $("#customerName").html($("#customer_id option:selected").text());
              $("#driverName").html($("#driver_id option:selected").text());
              $("#vehicleName").html($("#vehicle_id option:selected").text());
              $("#pickupAddress").html($("#pickup_addr").val());
              $("#dropoffAddress").html($("#dest_addr").val());
              $("#distance_span").html(response.rows[0].elements[0].distance.text)
              $("#material_span").html($("#material").val())
              $("#initialKm").html($("#initial_km").val())
              var distan = (response.rows[0].elements[0].distance.value/1000).toFixed(1);
              var mileage = $("#vehicle_id option:selected").data('mileage');
              if(mileage=="" || mileage==null){
                var mileage = $("#dmileage").val();
                var driver_id = $("#ddriver").val();
              }
              $("#mileage_span").html(mileage);
              // console.warn(mileage);
              var respDuration = response.rows[0].elements[0].duration;
              // ajax time
              var data = {_token:"<?php echo e(csrf_token()); ?>",currTime : $("#dropoff").val(),duration:respDuration.text};
              var posting = $.post("<?php echo e(route('bookings.dropofftime')); ?>",data)
              posting.done(function(data){
                // console.table(data);
                $("#pickup").val(data.start)
                $("#dropoff").val(data.end)
                $("#pickupDate").html(data.start_sum);
                $("#dropoffDate").html(data.drop_sum);
              })
              $("#duration_span").html(respDuration.text)
              // console.log(dropDate);
              
              // console.warn(distan);
              // console.warn(mileage);
              
              var petrolreq  = (distan/mileage).toFixed(2);
              // console.log(petrolreq);
              $(".pet-required").html(petrolreq)
          }
      }
      });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
    $(function(){
      $("#booking_sub").click(function(e){
        e.preventDefault();
        // Validate everything including the spans
        var blankTest = /\S/;
        var custid = $("#customer_id").val();
        var pickup = $("#pickup").val();
        var dropoff = $("#dropoff").val();
        var vehicle_id = $("#vehicle_id").val();
        var driver_id = $("#driver_id").val();
        var material = $("#material").val();
        var initial_km = $("#initial_km").val();
        var pickup_addr = $("#pickup_addr").val();
        var dest_addr = $("#dest_addr").val();
        var petrol_perltr = $("#petrol_perltr").val();
        var load_set = $("#load_set").val();
        var load_price = $("#load_price").val();
        var load_qty = $("#load_qty").val();
        var dmileage = $("#dmileage").val();
        var petrol_perltr = $(".petrol_perltr").val();
        var load_set = $(".load_set").val();
        var load_price = $(".load_price").val();
        var load_qty = $(".load_qty").val();
        var total_pay = $(".total_pay").val();
        
        if(!blankTest.test(custid)){
          alert("Please select customer..");
          $("#customer_id").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(pickup)){
          alert("Please select pickup date");
          $("#pickup").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(dropoff)){
          alert("Please select dropoff date");
          $("#dropoff").focus();
          checkTop();
          return false;
        }

        if(!blankTest.test(vehicle_id)){
          alert("Please select vehicle");
          $("#vehicle_id").focus();
          checkTop();
          return false;
        }else if(!blankTest.test(dmileage)){
          alert("Please select vehicle again...");
          $("#vehicle_id").focus();
          checkTop();
          return false;
        }

        if(!blankTest.test(driver_id)){
          alert("Please select a driver");
          $("#driver_id").focus();
          checkTop();
          return false;
        }else if((!blankTest.test($("#ddriver").val()) && driver_id==null) || driver_id==null){
          alert("Please select driver..");
          $("#driver_id").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(material)){
          alert("Please enter material");
          $("#material").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(initial_km)){
          alert("Please enter Initial Kilometers...");
          $("#initial_km").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(dest_addr)){
          alert("Please enter Destination Address....");
          $("#dest_addr").focus();
          checkTop();
          return false;
        }
        if(!blankTest.test(dmileage)){
          alert("Cannot determine Vehicle Mileage. Select Vehicle again and if the problem persists.. assign mileage to this perticular vehicle");
          checkTop();
          return false;
        }

        if(!blankTest.test(petrol_perltr)){
          alert("Please enter price of diesel per liter");
          $(".petrol_perltr").focus();
          return false;
        }
        if(!blankTest.test(load_set)){
          alert("Please select load type");
          $(".load_set").focus();
          return false;
        }
        if(!blankTest.test(load_price)){
          alert("Please enter price of per load..");
          $(".load_price").focus();
          return false;
        }
        if(!blankTest.test(load_qty)){
          alert("Please enter load quantity..");
          $(".load_qty").focus();
          return false;
        }

        getvals();
      })
    })

    function checkTop(){
      $("#dest_addr").val('');
    }

    function checkFields(){
      var blankTest = /\S/;
      var label = ['customer_id','pickup','dropoff','vehicle_id','driver_id','material','initial_km','pickup_addr','dest_addr','petrol_perltr','load_price','load_qty'];
        $.each(label,function(key,value){
          var input = $("#"+value).val();
          if(!blankTest(input)){
            return value;
          }
        })
        return true;
    }
  </script>
  
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bookings/edit.blade.php ENDPATH**/ ?>