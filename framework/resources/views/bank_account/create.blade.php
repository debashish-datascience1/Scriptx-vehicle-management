@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("bank-account.index")}}">@lang('fleet.bankAccount')</a></li>
<li class="breadcrumb-item active">Add @lang('fleet.bankAccount')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add @lang('fleet.bankAccount')</h3>
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

        {!! Form::open(['route' => 'bank-account.store','files'=>true,'method'=>'post']) !!}
        <div class="row toprow">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('bank', __('fleet.bank'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::text('bank',null,['class' => 'form-control bank','placeholder'=>'Enter Bank Name...','required','id'=>'bank']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('account_no', __('fleet.account_no'), ['class' => 'form-label']) !!}
              {!! Form::text('account_no', null,['class' => 'form-control','placeholder'=>'Enter Account No..']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('ifsc_code', __('fleet.ifsc_code'), ['class' => 'form-label required']) !!}
              {!! Form::text('ifsc_code', null,['class' => 'form-control','placeholder'=>'Enter IFSC Code...']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('branch', __('fleet.branch'), ['class' => 'form-label']) !!}
              {!! Form::text('branch', null,['class' => 'form-control','placeholder'=>'Enter Branch..']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('account_holder', __('fleet.holder'), ['class' => 'form-label required']) !!}
              {!! Form::text('account_holder', null,['class' => 'form-control','required','placeholder'=>'Enter Account Holder...']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('starting_amount', __('fleet.starting_amount'), ['class' => 'form-label required']) !!}
              {!! Form::text('starting_amount', null,['class' => 'form-control','required','placeholder'=>'Enter Starting Amount...']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('mobile', __('fleet.mobile'), ['class' => 'form-label required']) !!}
              {!! Form::text('mobile', null,['class' => 'form-control','required','placeholder'=>'Enter Mobile Number..']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('email', __('fleet.email'), ['class' => 'form-label required']) !!}
              {!! Form::email('email', null,['class' => 'form-control','placeholder'=>'Enter Email Address..']) !!}
            </div>
          </div>
          {{-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('status', "Status", ['class' => 'form-label required']) !!}
              {!! Form::select('status',['1'=>'Active','2'=>'Inactive'], null,['class' => 'form-control','required','readonly','pointer-events:none;']) !!}
            </div>
          </div> --}}
          <div class="col-md-12">
                <div class="form-group">
                {!! Form::label('address', __('fleet.address'), ['class' => 'form-label required']) !!}
                {!! Form::textarea('address', null,['class' => 'form-control','style'=>'resize:none;height:120px;','placeholder'=>'Enter Address..']) !!}
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
        // console.log(darr)
        $('.remarks').load('{{url("admin/bank-account/get_remarks")}}/'+darr,function(result){
            console.log(result);
        })
    })
//   $("#sub").click(function(){
//     if($("#date").val()=="" || $("#date").val()==null){
//       alert("Date cannot be empty");
//       $("#date").css('border','1px solid red').focus();
//       return false;
//     }else{
//       $("#date").css('border','');
//     }
//   });
  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

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