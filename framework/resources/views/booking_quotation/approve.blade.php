@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item "><a href="{{ route("booking-quotation.index")}}">@lang('fleet.booking_quotes')</a></li>
<li class="breadcrumb-item active">@lang('fleet.approve') @lang('fleet.bookingQuote')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          @lang('fleet.approve') @lang('fleet.bookingQuote')
        </h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
        @endif

        {!! Form::open(['url' => 'admin/add-booking','method'=>'POST']) !!}

        {!! Form::hidden('status',0)!!}
        {!! Form::hidden('id',$data->id)!!}
        {!! Form::hidden('user_id',Auth::id())!!}
        {!! Form::hidden('customer_id',$data->customer_id) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('customer_id',__('fleet.selectCustomer'), ['class' => 'form-label']) !!}
              <select id="customer_id" name="customer_id" class="form-control" disabled>
                <option selected value="{{$data->customer_id}}">{{$data->customer['name']}}</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('pickup',__('fleet.pickup'), ['class' => 'form-label']) !!}
              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                </div>
                {!! Form::text('pickup',$data->pickup,['class'=>'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('dropoff',__('fleet.dropoff'), ['class' => 'form-label']) !!}
              <div class='input-group date'>
                <div class="input-group-prepend">
                  <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                </div>
                {!! Form::text('dropoff',$data->dropoff,['class'=>'form-control','required']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']) !!}
              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vehicles as $vehicle)
                <option @if($vehicle->id==$data->vehicle_id) selected @endif value="{{$vehicle->id}}" data-driver="{{$vehicle->driver_id}}" data-vehicle-type="{{strtolower(str_replace(' ','',$vehicle->types->vehicletype))}}" data-base-mileage="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_base_km')}}" data-base-fare="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_base_fare')}}"
                    data-base_km_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_base_km')}}"
                    data-base_fare_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_base_fare')}}"
                    data-wait_time_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_base_time')}}"
                    data-std_fare_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_std_fare')}}"

                    data-base_km_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_weekend_base_km')}}"
                    data-base_fare_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_weekend_base_fare')}}"
                    data-wait_time_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_weekend_wait_time')}}"
                    data-std_fare_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_weekend_std_fare')}}"

                    data-base_km_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_night_base_km')}}"
                    data-base_fare_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_night_base_fare')}}"
                    data-wait_time_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_night_wait_time')}}"
                    data-std_fare_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$vehicle->types->vehicletype)).'_night_std_fare')}}">{{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('driver_id',__('fleet.selectDriver'), ['class' => 'form-label']) !!}

              <select id="driver_id" name="driver_id" class="form-control" required>
                <option value="">-</option>
                @foreach($drivers as $driver)
                <option value="{{$driver->id}}" @if($driver->id == $data->driver_id) selected @endif>{{$driver->name}} @if($driver->getMeta('is_active') != 1)
                ( @lang('fleet.in_active') ) @endif</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('travellers',__('fleet.no_travellers'), ['class' => 'form-label']) !!}
              {!! Form::number('travellers',$data->travellers,['class'=>'form-control','min'=>1]) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">@lang('fleet.daytype')</label>
              <select id="day" name="day" class="form-control vehicles sum" required>
                <option value="1" @if($data->day == 1) selected @endif>Weekdays</option>
                <option value="2" @if($data->day == 2) selected @endif>Weekend</option>
                <option value="3" @if($data->day == 3) selected @endif>Night</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">@lang('fleet.trip_mileage') ({{Hyvikk::get('dis_format')}})</label>
              {!! Form::number('mileage',$data->mileage,['class'=>'form-control sum','min'=>1,'id'=>'mileage','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">@lang('fleet.waitingtime')</label>
              {!! Form::number('waiting_time',$data->waiting_time,['class'=>'form-control sum','min'=>0,'id'=>'waiting_time','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">@lang('fleet.total') @lang('fleet.amount') </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
                {!! Form::number('total',$data->total,['class'=>'form-control','id'=>'total','required']) !!}
              </div>
            </div>
          </div>
          @php($tax_percent=0)
          @if($data->total_tax_percent != null)
            @php($tax_percent = $data->total_tax_percent)
          @endif
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">@lang('fleet.total_tax') (%) </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text fa fa-percent"></span></div>
                {!! Form::number('total_tax_percent',$data->total_tax_percent,['class'=>'form-control sum','readonly','id'=>'total_tax_charge']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">@lang('fleet.total') @lang('fleet.tax_charge')</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text">{{ Hyvikk::get('currency') }}</span></div>
                {!! Form::number('total_tax_charge_rs',$data->total_tax_charge_rs,['class'=>'form-control sum','readonly','id'=>'total_tax_charge_rs']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">@lang('fleet.total') @lang('fleet.amount') </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
                {!! Form::number('tax_total',$data->tax_total,['class'=>'form-control','id'=>'tax_total','readonly']) !!}
              </div>
            </div>
          </div>
        </div>
        @if(Auth::user()->user_type == "C")
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('d_pickup',__('fleet.pickup_addr'), ['class' => 'form-label']) !!}
              <select id="d_pickup" name="d_pickup" class="form-control">
                <option value="">-</option>
                @foreach($addresses as $address)
                <option value="{{$address->id}}" data-address="{{$address->address}}">{{$address->address}}
                </option>
                @endforeach
              </select>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            {!! Form::label('d_dest',__('fleet.dropoff_addr'), ['class' => 'form-label']) !!}
            <select id="d_dest" name="d_dest" class="form-control">
            <option value="">-</option>
            @foreach($addresses as $address)
            <option value="{{$address->id}}" data-address="{{$address->address}}">{{$address->address}}</option>
            @endforeach
            </select>
            </div>
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('pickup_addr',__('fleet.pickup_addr'), ['class' => 'form-label']) !!}
              {!! Form::text('pickup_addr',$data->pickup_addr,['class'=>'form-control','required','style'=>'height:100px']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('dest_addr',__('fleet.dropoff_addr'), ['class' => 'form-label']) !!}
              {!! Form::text('dest_addr',$data->dest_addr,['class'=>'form-control','required','style'=>'height:100px']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('note',__('fleet.note'), ['class' => 'form-label']) !!}
              {!! Form::textarea('note',$data->note,['class'=>'form-control','placeholder'=>__('fleet.book_note'),'style'=>'height:100px']) !!}
            </div>
          </div>
        </div>
        <div class="col-md-12">
          {!! Form::submit(__('fleet.approve'), ['class' => 'btn btn-info']) !!}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}"></script>

<script type="text/javascript">
  $('#customer_id').select2({placeholder: "@lang('fleet.selectCustomer')"});
  $('#driver_id').select2({placeholder: "@lang('fleet.selectDriver')"});
  $('#vehicle_id').select2({placeholder: "@lang('fleet.selectVehicle')"});
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

  function get_driver(from_date,to_date){
    $.ajax({
      type: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{url('admin/get_driver')}}",
      data: "req=new&from_date="+from_date+"&to_date="+to_date,
      success: function(data2){
        $("#driver_id").empty();
        $("#driver_id").select2({placeholder: "@lang('fleet.selectDriver')",data:data2.data});
      },
      dataType: "json"
    });
  }

  function get_vehicle(from_date,to_date){
    $.ajax({
      type: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{url('admin/get_vehicle')}}",
      data: "req=new&from_date="+from_date+"&to_date="+to_date,
      success: function(data2){
        $("#vehicle_id").empty();
        $("#vehicle_id").select2({placeholder: 'Select Vehicle',data:data2.data});
      },
      dataType: "json"
    });
  }

  $(document).ready(function() {
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
      // get_vehicle(from_date,to_date);
      $('#dropoff').data("DateTimePicker").minDate(e.date);
    });

    $("#dropoff").on("dp.change", function (e) {
      $('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss")
      var from_date=$('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
      var to_date=e.date.format("YYYY-MM-DD HH:mm:ss");

      get_driver(from_date,to_date);
      // get_vehicle(from_date,to_date);
    });

    $("#vehicle_id").on("change",function(){
      var driver = $(this).find(":selected").data("driver");
      $("#driver_id").val(driver).change();
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
<script type="text/javascript" language="javascript">
$("#vehicle_id").on('change',function(){
  $("#mileage").val($("#vehicle_id option:selected").data('base-mileage'));
  $("#waiting_time").val("0");
  $("#total").val($("#vehicle_id option:selected").data('base-fare'));
  $("#day").val("1");
  var tax_charges = (Number('{{ $tax_percent }}') * Number($('#total').val()))/100;
  $('#total_tax_charge_rs').val(tax_charges);
  $('#tax_total').val(Number($('#total').val()) + Number(tax_charges));
});

$(".sum").change(function(){
  // alert($("#base_km_1").val());
  // alert($('.vtype').data('base_km_1'));
  // console.log($("#type").val());
    var day = $("#day").find(":selected").val();
    if(day == 1){
      var base_km = $("#vehicle_id option:selected").data('base_km_1');
      var base_fare = $("#vehicle_id option:selected").data('base_fare_1');
      var wait_time = $("#vehicle_id option:selected").data('wait_time_1');
      var std_fare = $("#vehicle_id option:selected").data('std_fare_1');
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 2){
      var base_km = $("#vehicle_id option:selected").data('base_km_2');
      var base_fare = $("#vehicle_id option:selected").data('base_fare_2');
      var wait_time = $("#vehicle_id option:selected").data('wait_time_2');
      var std_fare = $("#vehicle_id option:selected").data('std_fare_2');
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 3){
      var base_km = $("#vehicle_id option:selected").data('base_km_3');
      var base_fare = $("#vehicle_id option:selected").data('base_fare_3');
      var wait_time = $("#vehicle_id option:selected").data('wait_time_3');
      var std_fare = $("#vehicle_id option:selected").data('std_fare_3');
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }
    $("#total").val(total);
    var tax_charges = (Number('{{ $tax_percent }}') * Number($('#total').val()))/100;
    $('#total_tax_charge_rs').val(tax_charges);
    $('#tax_total').val(Number($('#total').val()) + Number(tax_charges));

  });

  $('#total').on('change',function(){
    var tax_charges = (Number('{{ $tax_percent }}') * Number($('#total').val()))/100;
    $('#total_tax_charge_rs').val(tax_charges);
    $('#tax_total').val(Number($('#total').val()) + Number(tax_charges));
  });
</script>

@if(Hyvikk::api('google_api') == "1")
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
  <script src="https://maps.googleapis.com/maps/api/js?key={{Hyvikk::api('api_key')}}&libraries=places&callback=initMap" async defer></script>
@endif
@endsection