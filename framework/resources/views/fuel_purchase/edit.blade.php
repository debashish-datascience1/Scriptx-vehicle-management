@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('fuel_purchase.index') }}">@lang('fleet.fuel_purchase')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_fuel_purchase')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_fuel_purchase')</h3>
      </div>

      {!! Form::open(['route' => ['fuel_purchase.update', $fuel_purchase->id], 'method' => 'PUT']) !!}
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
              {!! Form::date('date', $fuel_purchase->date, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('vendor_id', __('fleet.vendor'), ['class' => 'form-label']) !!}
              {!! Form::select('vendor_id', $vendors, $fuel_purchase->vendor_id, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('fuel_type_id', __('fleet.fuel_type'), ['class' => 'form-label']) !!}
              {!! Form::select('fuel_type_id', $data['oil'], $fuel_purchase->fuel_type_id, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('quantity', __('fleet.quantity'), ['class' => 'form-label']) !!}
              {!! Form::number('quantity', $fuel_purchase->quantity, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label']) !!}
              {!! Form::number('amount', $fuel_purchase->amount, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
            </div>
          </div>
        </div>

        <div class="form-group">
            {!! Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label']) !!}
            {!! Form::textarea('remarks', $fuel_purchase->remarks, ['class' => 'form-control', 'rows' => 3]) !!} 
       </div>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-info btn-block']) !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $('#vendor_id, #fuel_type_id').select2();
});
</script>
@endsection