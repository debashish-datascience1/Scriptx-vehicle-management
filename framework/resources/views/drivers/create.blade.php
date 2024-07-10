@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  .select2-selection{
    height: 38px !important;
  }
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("drivers.index")}}">@lang('fleet.drivers')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addDriver')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.addDriver')</h3>
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

        {!! Form::open(['route' => 'drivers.store','files'=>true,'method'=>'post']) !!}
        {!! Form::hidden('is_active',0) !!}
        {!! Form::hidden('is_available',0) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::text('first_name', null,['class' => 'form-control','required','autofocus']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('middle_name', __('fleet.middlename'), ['class' => 'form-label']) !!}
              {!! Form::text('middle_name', null,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']) !!}
              {!! Form::text('last_name', null,['class' => 'form-control','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.assign_vehicle'), ['class' => 'form-label']) !!}

              <select id="vehicle_id" name="vehicle_id" class="form-control" >
                <option value="">@lang('fleet.selectVehicle')</option>
                @foreach($vehicles as $vehicle)
                <option value="{{$vehicle->id}}">{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('address', __('fleet.address'), ['class' => 'form-label required']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                </div>
                {!! Form::text('address', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          {{-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('email', __('fleet.email'), ['class' => 'form-label required']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                {!! Form::email('email', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div> --}}
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}
              <div class="input-group">
                <div class="input-group-prepend">
                {!! Form::select('phone_code',$phone_code,"+91",['class' => 'form-control code','style'=>'width:80px']) !!}
                </div>
                {!! Form::number('phone', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('adphone', __('fleet.additionalPhone'), ['class' => 'form-label required']) !!}
              <div class="input-group">
                <div class="input-group-prepend">
                {!! Form::select('adphone_code',$phone_code,"+91",['class' => 'form-control code','style'=>'width:80px']) !!}
                </div>
                {!! Form::number('adphone', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('emp_id', __('fleet.employee_id'), ['class' => 'form-label']) !!}
              {!! Form::text('emp_id', null,['class' => 'form-control']) !!}
            </div>
          </div>
          {{-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('contract_number', __('fleet.contract'), ['class' => 'form-label']) !!}
              {!! Form::text('contract_number', null,['class' => 'form-control']) !!}
            </div>
          </div> --}}
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('license_number', __('fleet.licenseNumber'), ['class' => 'form-label required']) !!}
              {!! Form::text('license_number', null,['class' => 'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('issue_date', __('fleet.issueDate'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                {!! Form::text('issue_date', null,['class' => 'form-control','readonly']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('exp_date', __('fleet.expirationDate'), ['class' => 'form-label required']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                {!! Form::text('exp_date', null,['class' => 'form-control','readonly']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              {!! Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                {!! Form::text('start_date', null,['class' => 'form-control','readonly']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              {!! Form::label('end_date', __('fleet.leave_date'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                {!! Form::text('end_date', null,['class' => 'form-control','readonly']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('salary', __('fleet.driverSalary'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-inr"></i></span></div>
                {!! Form::text('salary', null,['class' => 'form-control','onkeypress'=>'return isNumber(event)']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('bank', __('fleet.bank'), ['class' => 'form-label']) !!}
              {!! Form::text('bank', null,['class' => 'form-control bank','placeholder'=>'Enter Bank Name','id'=>'bank']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('account_no', "Bank Account No.", ['class' => 'form-label']) !!}
              {!! Form::text('account_no', null,['class' => 'form-control account_no','placeholder'=>'Enter Bank Account No.','id'=>'account_no']) !!}
            </div>
          </div>
        </div>
        {{-- <div class="row">
          
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                {!! Form::password('password', ['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
        </div> --}}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('gender', __('fleet.gender') , ['class' => 'form-label']) !!}<br>
              <input type="radio" name="gender" class="flat-red gender" value="1" checked> @lang('fleet.male')<br>

              <input type="radio" name="gender" class="flat-red gender" value="0"> @lang('fleet.female')
            </div>

            <div class="form-group">
              {!! Form::label('driver_image', __('fleet.driverImage'), ['class' => 'form-label']) !!}

              {!! Form::file('driver_image',null,['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('documents', __('fleet.documents'), ['class' => 'form-label']) !!}
              {!! Form::file('documents',null,['class' => 'form-control']) !!}
            </div>


            <div class="form-group">
              {!! Form::label('license_image', __('fleet.licenseImage'), ['class' => 'form-label']) !!}
              {!! Form::file('license_image',null,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('econtact', __('fleet.emergency_details'), ['class' => 'form-label']) !!}
              {!! Form::textarea('econtact',null,['class' => 'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="col-md-12">
          {!! Form::submit(__('fleet.saveDriver'), ['class' => 'btn btn-success']) !!}
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
  $('.code').select2();
  $('#vehicle_id').select2();
  $("#first_name").focus();
  $('#end_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#exp_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#issue_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#start_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
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