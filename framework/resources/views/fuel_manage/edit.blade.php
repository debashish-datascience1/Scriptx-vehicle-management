@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('fuel_manage.index') }}">@lang('fleet.fuel_management')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_fuel_management')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_fuel_management')</h3>
      </div>

      {!! Form::open(['route' => ['fuel_manage.update', $fuel_management->id], 'method' => 'PUT', 'id' => 'fuel-management-form']) !!}
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
              {!! Form::date('date', $fuel_management->date, ['class' => 'form-control', 'required' => true]) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('stock', __('fleet.stock'), ['class' => 'form-label']) !!}
              {!! Form::select('stock', ['own' => 'Own Stock'], 'own', ['class' => 'form-control', 'placeholder' => __('fleet.select_stock'), 'required' => true, 'id' => 'stock-select']) !!}
              <small id="available-stock-info" class="form-text text-muted mt-1"></small>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'form-label']) !!}
              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vehicles as $vehicle)
                <option value="{{$vehicle->id}}" {{ $fuel_management->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                  {{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}
                </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('quantity', __('fleet.quantity'), ['class' => 'form-label']) !!}
              {!! Form::number('quantity', $fuel_management->quantity, ['class' => 'form-control', 'step' => '0.01', 'required' => true, 'id' => 'quantity-input']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('remark', __('fleet.remarks'), ['class' => 'form-label']) !!}
              {!! Form::textarea('remark', $fuel_management->remark, ['class' => 'form-control', 'rows' => 3]) !!}
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer">
        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-info']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
   var availableStock = 0;
   var initialQuantity = parseFloat('{{ $fuel_management->quantity }}');

   $('#stock-select').on('change', function() {
    var selectedStock = $(this).val();
    
    if (selectedStock === 'own') {
        $.ajax({
            url: '{{ route("fuel_manage.get_available_stock") }}',
            method: 'GET',
            data: { stock: selectedStock },
            success: function(response) {
                availableStock = parseFloat(response.available_stock);
                
                $('#available-stock-info')
                    .html('<strong>Available Stock:</strong> ' + availableStock.toFixed(2) + ' Liters')
                    .css('color', availableStock > 0 ? 'green' : 'red');
                
                $('#quantity-input')
                    .attr('max', availableStock + initialQuantity)
                    .attr('min', '0');
            },
            error: function() {
                $('#available-stock-info')
                    .text('Error fetching available stock')
                    .css('color', 'red');
            }
        });
    } else {
        $('#available-stock-info').text('');
    }
   }).trigger('change');

   $('#quantity-input').on('input', function() {
    var enteredQuantity = parseFloat($(this).val()) || 0;
    
    if ($('#stock-select').val() === 'own') {
        var newAvailableStock = availableStock + initialQuantity - enteredQuantity;
        
        $('#available-stock-info')
            .html('<strong>Available Stock:</strong> ' + newAvailableStock.toFixed(2) + ' Liters')
            .css('color', newAvailableStock >= 0 ? 'green' : 'red');
        
        // Prevent entering more than available stock plus initial quantity
        if (enteredQuantity > (availableStock + initialQuantity)) {
            $(this).val(availableStock + initialQuantity);
            newAvailableStock = 0;
            $('#available-stock-info')
                .html('<strong>Available Stock:</strong> 0.00 Liters')
                .css('color', 'red');
        }
    }
   });
});
</script>
@endsection