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
<li class="breadcrumb-item"><a href="{{ route("vehicle-emi.index")}}">@lang('fleet.vehicle_emi')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle_emi')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.edit_vehicle_emi')</h3>
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

        {!! Form::model($emiModel,['route' => ['vehicle-emi.update',$emiModel->id],'files'=>true,'method'=>'PATCH']) !!}
        {!! Form::hidden('id',$emiModel->id) !!}
            <div class="row">
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('date', __('fleet.duedate'), ['class' => 'form-label required']) !!}
                      {!! Form::text('date',Helper::indianDateFormat($emiModel->date),['class'=>'form-control','readonly','id'=>'date']) !!}
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']) !!}
                      {!! Form::text('amount',$emiModel->emi_amount,['class'=>'form-control','readonly','id'=>'amount']) !!}
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('pay_date', __('fleet.pay_date'), ['class' => 'form-label required']) !!}
                      {!! Form::text('pay_date',Helper::indianDateFormat($emiModel->pay_date),['class'=>'form-control','readonly','id'=>'pay_date']) !!}
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('bank', __('fleet.bank'), ['class' => 'form-label required']) !!}
                      {!! Form::select('bank_id',Helper::getBanks(),$emiModel->bank_id,['class'=>'form-control','placeholder'=>'Select Bank','id'=>'bank','required']) !!}
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('method', __('fleet.method'), ['class' => 'form-label required']) !!}
                      {!! Form::select('method',Helper::getMethods(),$emiModel->transaction->getRefNo->payment_method,['class'=>'form-control','placeholder'=>'Select Method','id'=>'method','required']) !!}
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      {!! Form::label('reference_no', __('fleet.reference_no'), ['class' => 'form-label required']) !!}
                      {!! Form::text('reference_no',null,['class'=>'form-control','required','id'=>'reference_no','placeholder'=>'Enter Reference No.']) !!}
                  </div>
              </div>
              <div class="col-12">
                  <div class="form-group">
                      {!! Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label required']) !!}
                      {!! Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','placeholder'=>'Remarks if any']) !!}
                  </div>
              </div>
              <div class="col-md-12">
                {!! Form::submit('Update', ['class' => 'btn btn-warning','id'=>'sub']) !!}
              </div>
          </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#pay_date').datepicker({
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