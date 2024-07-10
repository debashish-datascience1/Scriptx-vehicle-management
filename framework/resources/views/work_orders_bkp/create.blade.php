@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("work_order.index")}}">@lang('fleet.work_orders') </a></li>
<li class="breadcrumb-item active">@lang('fleet.add_order')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          @lang('fleet.create_workorder')
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

        {!! Form::open(['route' => 'work_order.store','method'=>'post']) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        {!! Form::hidden('type','Created')!!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.vehicle'), ['class' => 'form-label']) !!}
              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vehicles as $vehicle)
                <option value="{{$vehicle->id}}">{{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              {!! Form::label('required_by', __('fleet.required_by'), ['class' => 'form-label']) !!}
              <div class="input-group date">
              <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></div>
              {!! Form::text('required_by',null,['class'=>'form-control','required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('meter',Hyvikk::get('dis_format')." ".__('fleet.reading'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">{{Hyvikk::get('dis_format')}}</span></div>
              {!! Form::number('meter',null,['class'=>'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('note',__('fleet.note'), ['class' => 'form-label']) !!}
              {!! Form::textarea('note',null,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vendors as $vendor)
                <option value="{{$vendor->id}}"> {{$vendor->name}} </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              {!! Form::label('status',__('fleet.status'), ['class' => 'form-label']) !!}
              {!! Form::select('status',["Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control','required']) !!}
            </div>

            <div class="form-group">
              {!! Form::label('price',__('fleet.work_order_price'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
              {!! Form::text('price',null,['class'=>'form-control','onkeypress'=>'return isNumber(event,this)','required']) !!}
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('description',__('fleet.description'), ['class' => 'form-label']) !!}
              {!! Form::textarea('description',null,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']) !!}
            </div>
          </div>
        </div>
        <hr>
        <div class="row" style="margin-bottom: 25px;">
          <div class="col-md-6"> 
            <div class="form-group"> 
              {{-- <label class="form-label">@lang('fleet.selectPart')</label> 
              <select id="select_part" class="form-control" name="part_list"> 
                <option></option>
                @foreach($parts as $part) 
                <option value="{{ $part->id }}" title="{{ $part->title }}" qty="{{ $part->stock }}" price="{{ $part->unit_cost }}">{{ $part->title }}
                </option> 
                @endforeach 
              </select>  --}}
              {!! Form::label('part_list',__('fleet.selectPart'), ['class' => 'form-label']) !!}
              {!! Form::select('part_list',$parts,null,['class'=>'form-control','placeholder'=>'Attach Parts']) !!}
            </div>
          </div>
          <div class="col-md-6" style="margin-top: 30px">
            <button type="button" class="btn btn-warning attach">@lang('fleet.attachPart')</button>
          </div>
        </div>
        <div class="row">
          <div class="parts col-md-12"></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card card-solid">
              <div class="card-header">
                <h3 class="card-title">
                  GST
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('Is GST?',__('fleet.isGst'), ['class' => 'form-label']) !!}
                        {!! Form::select('is_gst',$is_gst,null,['class'=>'form-control','id'=>'is_gst','placeholder'=>'Select','required']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('cgst',null,['class'=>'form-control','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('cgst_amt',null,['class'=>'form-control','id'=>'cgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('sgst',null,['class'=>'form-control','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('sgst_amt',null,['class'=>'form-control','id'=>'sgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('total_amount',__('fleet.total_amount'), ['class' => 'form-label']) !!}
                        {!! Form::text('total_amount',null,['class'=>'form-control','id'=>'total_amount','readonly']) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.add_order'), ['class' => 'btn btn-success']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
// Check Number and Decimal
function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
}
$(document).ready(function() {
  $('#vehicle_id').select2({placeholder: "@lang('fleet.selectVehicle')"});
  $('#vendor_id').select2({placeholder: "@lang('fleet.select_vendor')"});
  $('#select_part').select2({placeholder: "@lang('fleet.selectPart')"});
  $('#required_by').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
  $('#created_on').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $("#is_gst").change(function(){
    var is_gst = $("#is_gst").val();
    var cgst = $("#cgst")
    var sgst = $("#sgst")
    // var cgst_amt = $("#cgst_amt")
    // var sgst_amt = $("#sgst_amt")
    if(is_gst==1){
      cgst.prop('readonly',false).prop('required',true)
      sgst.prop('readonly',false).prop('required',true)
    }else{
      cgst.prop('readonly',true).prop('required',false).val('')
      sgst.prop('readonly',true).prop('required',false).val('')
      $("#sgst_amt").val('')
      $("#cgst_amt").val('')
      $("#total_amount").val('');
    }
  })

  // $("#price").keyup(function(){
  //   console.log($(this).val())
  //   var price = $(this).val();
  //   $("#total_amount").val(parseFloat(price).toFixed(2));
  // })
  $(document).on('keyup','#price,#cgst,#sgst',function(){
    var price = $("#price").val();
    var cgst = $("#cgst").val();
    // var cgst_amt = $("#cgst_amt").val();
    var sgst = $("#sgst").val();
    // var sgst_amt = $("#sgst_amt").val();
    
    var sendData = {_token:"{{csrf_token()}}",price:price,cgst:cgst,sgst:sgst};
    $.post("{{route('work_order.wo_gstcalculate')}}",sendData).done(function(data){
      // console.log(data)
      console.table(data)
      // if(!isNaN(data.total) && data.total!=0){
      //   $(".smallfuel").show()
      //   $(".fueltot").html(data.total)
      // }else{
      //   $(".smallfuel").hide()
      //   $(".fueltot").html('')
      // }

      if(!isNaN(data.cgstval) && data.cgstval!=0){
        $("#cgst_amt").val(data.cgstval)
      }else{
        $("#cgst_amt").val('')
      }

      if(!isNaN(data.sgstval) && data.sgstval!=0){
        $("#sgst_amt").val(data.sgstval)
      }else{
        $("#sgst_amt").val('')
      }

      if(!isNaN(data.grandtotal) && data.grandtotal!=0){
        $("#total_amount").val(data.grandtotal)
      }else{
        $("#total_amount").val('')
      }

        
    })
  })

  //Flat green color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $('.attach').on('click',function(){

    var field = $('#select_part').val();
    if(field == "" || field == null){
      alert('Select Part');
    }
    else{
      var qty=$('#select_part option:selected').attr('qty');
      var title=$('#select_part option:selected').attr('title');
      var price=$('#select_part option:selected').attr('price');
      // alert($('#select_part option:selected').attr('title'));
      // alert($('#select_part option:selected').attr('qty'));
      $(".parts").append('<div class="row col-md-12"><div class="col-md-4">  <div class="form-group"> <label class="form-label">@lang('fleet.selectPart')</label> <select  class="form-control" disabled>  <option value="'+field+'" selected >'+title+'</option> </select> </div></div> <div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.qty')</label> <input type="number" name="parts['+field+']" min="1" value="1" class="form-control calc" max='+qty+' required> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.unit_cost')</label> <input type="number" value="'+price+'" class="form-control" disabled> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.total_cost')</label> <input type="number" value="'+price+'" class="form-control total_cost" disabled id="'+field+'"> </div></div> <div class="col-md-2"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('.calc').on('change',function(){
        // alert($(this).val()*price);
        $('#'+field).val($(this).val()*price);
      });
      $('#select_part').val('').change();
    }
  });

});
</script>
@endsection