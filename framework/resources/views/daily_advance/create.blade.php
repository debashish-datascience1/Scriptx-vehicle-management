@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("daily-advance.index")}}">@lang('fleet.salary_advance')</a></li>
<li class="breadcrumb-item active">Add @lang('fleet.salary_advance')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add @lang('fleet.salary_advance')</h3>
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

        {!! Form::open(['route' => 'daily-advance.store','files'=>true,'method'=>'post']) !!}
        <div class="row toprow">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('driver_id', __('fleet.driver'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::select('driver_id[]',$driver,null,['class' => 'form-control drivers','required','id'=>'driver_id','multiple'=>'multiple']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
              {!! Form::text('date', null,['class' => 'form-control','readonly','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']) !!}
              {!! Form::text('amount', null,['class' => 'form-control','required','onkeypress'=>'return isNumber(event)']) !!}
              <small><strong>CASH : {{Hyvikk::get('currency')}} {{Helper::properDecimals($purse)}}</strong></small>
            </div>
          </div>
        </div>
        <div class="remarks"></div>
        <div class="row">
            <div class="col-md-12">
            {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']) !!}
            </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#driver_id").select2();
  $("#driver_id").on("change",function(){
        var darr = $(this).val();
        console.log(darr)
        $('.remarks').load('{{url("admin/daily-advance/get_remarks")}}/'+darr,function(result){
            console.log(result);
        })
    })
  $("#sub").click(function(){
    if($("#date").val()=="" || $("#date").val()==null){
      alert("Date cannot be empty");
      $("#date").css('border','1px solid red').focus();
      return false;
    }else{
      $("#date").css('border','');
    }
  });
  $('#date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });

  $("#date").on('changeDate',function(event){
    var drivers = $("#driver_id").val();
    var date = event.format();
    var params = {_token:"{{csrf_token()}}",drivers :drivers ,date:date} 
    var posting = $.post("{{route('daily-advance.ispaychecked')}}",params);
    posting.done(function(data){
      // console.log(data);
      
      $.each(data,function(i,e){
        // console.log(e);
        // console.log(e.paycheck);
        if(e.paycheck){
          // $(".toprow").append("<label style='color:red'>"+e.message+"</label>");
          // $("#sub").prop('readonly',true);
          alert(e.message);
          location.reload()
        }
      })
    })
  })

});
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
@endsection