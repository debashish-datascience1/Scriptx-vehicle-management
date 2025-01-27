@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('fuel_purchase.index') }}">@lang('fleet.fuel_purchase')</a></li>
<li class="breadcrumb-item active">@lang('fleet.add_fuel_purchase')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.add_fuel_purchase')</h3>
      </div>

      {!! Form::open(['route' => 'fuel_purchase.store', 'method' => 'post']) !!}
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
              {!! Form::date('date', date('Y-m-d'), ['class' => 'form-control', 'required']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('vendor_id', __('fleet.vendor'), ['class' => 'form-label']) !!}
              {!! Form::select('vendor_id', $vendors, null, ['class' => 'form-control', 'placeholder' => 'Select Vendor', 'required']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('fuel_type_id', __('fleet.fuel_type'), ['class' => 'form-label']) !!}
              {!! Form::select('fuel_type_id', $data['oil'], null, ['class' => 'form-control', 'placeholder' => 'Select Fuel Type', 'required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('quantity', __('fleet.quantity'), ['class' => 'form-label']) !!}
              {!! Form::number('quantity', null, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label']) !!}
              {!! Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
            </div>
          </div>
        </div>

        <div class="form-group">
            {!! Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label']) !!}
            {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
      </div>

      <div class="card-footer">
        <div class="row">
            <div class="col-md-12">
            {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-info btn-block']) !!}
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