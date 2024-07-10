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
<li class="breadcrumb-item"><a href="{{ route("payroll.index")}}">@lang('fleet.payroll')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addPayroll')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.addPayroll')</h3>
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

        {!! Form::open(['route' => 'payroll.store','files'=>true,'method'=>'post']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('user_id', __('fleet.driverName'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::select('user_id', $data,null,['class' => 'form-control','required','placeholder'=>'Select Driver']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label required']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
              {!! Form::text('date', null,['class' => 'form-control','required','readonly']) !!}
              </div>
            </div>
          </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('salary', __('fleet.salary'), ['class' => 'form-label required']) !!}
                {!! Form::number('salary', null,['class' => 'form-control','required','min'=>1]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('payable_salary', __('fleet.payableSalary'), ['class' => 'form-label required']) !!}
                {!! Form::number('payable_salary', null,['class' => 'form-control','required','min'=>1]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('for_month', __('fleet.forMonth'), ['class' => 'form-label required']) !!}
                {!! Form::select('for_month', $months,null,['class' => 'form-control','required','placeholder'=>'Select Month']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('for_year', __('fleet.forYear'), ['class' => 'form-label required']) !!}
                {!! Form::select('for_year', $years,null,['class' => 'form-control','required','placeholder'=>'Select Year']) !!}
            </div>
        </div>
          
        <div class="col-md-12">
          {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success']) !!}
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
  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

});
</script>
@endsection