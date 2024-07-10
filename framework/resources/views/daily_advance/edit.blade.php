@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("daily-advance.index")}}">@lang('fleet.daily_advance')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_daily_advance')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.edit_daily_advance')</h3>
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

        {!! Form::model($dailyAdvance,['route' => ['daily-advance.update',$dailyAdvance->id],'files'=>true,'method'=>'PATCH']) !!}
        {!! Form::hidden('id',$dailyAdvance->id) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('driver_id', 'Driver', ['class' => 'form-label required']) !!}
              {!! Form::select('driver_id',$drivers,$dailyAdvance->driver_id,['class' => 'form-control drivers','required','id'=>'driver_id','readonly','style'=>'pointer-events:none']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', 'Date', ['class' => 'form-label required']) !!}
              {!! Form::text('date',Helper::indianDateFormat($dailyAdvance->date),['class' => 'form-control','id'=>'date','required','readonly']) !!}
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']) !!}
                {!! Form::text('amount', $dailyAdvance->amount,['class' => 'form-control','required','onkeypress'=>'return isNumber(event)']) !!}
              </div>
          </div>
          <div class="col-md-12">
            <div class="form-group remarks">
              {!! Form::label('remarks', 'Remarks', ['class' => 'form-label required']) !!}
              {!! Form::textarea('remarks',$dailyAdvance->remarks,['class' => 'form-control remarks','style'=>'resize:none;height:100px;']) !!}
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
</script>
@endsection