@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
<style>
  .description{resize: none;height: 120px;}
  .item-row {margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("parts-sell.index")}}">@lang('menu.partSell')</a></li>
<li class="breadcrumb-item active">@lang('fleet.sellParts')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.sellParts')</h3>
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

          {!! Form::open(['route' => 'parts-sell.store','method'=>'post','files'=>true, 'id'=>'sellForm']) !!}
          
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('sell_to', __('fleet.sellTo'), ['class' => 'form-label']) !!}
                  {!! Form::text('sell_to', null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter name']) !!}
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
                  {!! Form::text('date', date('Y-m-d'), ['class' => 'form-control datepicker', 'required']) !!}
                </div>
              </div>
            </div>

            <div id="item-rows">
              <div class="item-row">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      {!! Form::label('item[]', __('fleet.item'), ['class' => 'form-label']) !!}
                      {!! Form::select('item[]', $items, null, ['class' => 'form-control item', 'required', 'placeholder' => 'Select Part']) !!}
                    </div> 
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      {!! Form::label('quantity[]', __('fleet.quantity'), ['class' => 'form-label']) !!}
                      {!! Form::number('quantity[]', null, ['class' => 'form-control quantity', 'required', 'min' => '1', 'placeholder' => 'Enter quantity']) !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      {!! Form::label('tyre_numbers[]', __('fleet.selltyreNumbers'), ['class' => 'form-label']) !!}
                      {!! Form::text('tyre_numbers[]', null, ['class' => 'form-control tyre_numbers', 'placeholder' => 'Enter comma-separated tyre numbers']) !!}
                      <input type="hidden" name="tyre_numbers_grouped[]" class="tyre_numbers_grouped">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      {!! Form::label('amount[]', __('fleet.amount'), ['class' => 'form-label']) !!}
                      {!! Form::number('amount[]', null, ['class' => 'form-control amount', 'required', 'step' => '0.01', 'min' => '0', 'placeholder' => 'Enter amount']) !!}
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      {!! Form::label('total[]', __('fleet.total'), ['class' => 'form-label']) !!}
                      {!! Form::text('total[]', null, ['class' => 'form-control total', 'readonly']) !!}
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <label class="form-label">&nbsp;</label>
                      <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <button type="button" id="add-item" class="btn btn-info">Add More</button>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-offset-8 col-md-4">
                <div class="form-group">
                  {!! Form::label('grand_total', __('fleet.sumtotal'), ['class' => 'form-label']) !!}
                  {!! Form::text('grand_total', null, ['class' => 'form-control', 'readonly', 'id' => 'grand_total']) !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                {!! Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success', 'id' => 'savebtn']) !!}
              </div>
            </div> 
        </div>      
  {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: new Date()
    });

        function updatePlaceholder(row) {
        var quantity = parseInt(row.find('.quantity').val());
        var tyreNumbersField = row.find('.tyre_numbers');
        if (!tyreNumbersField.prop('disabled')) {
            if (quantity > 0) {
                tyreNumbersField.attr('placeholder', 'Enter ' + quantity + ' comma-separated tyre numbers');
            } else {
                tyreNumbersField.attr('placeholder', 'Enter comma-separated tyre numbers');
            }
        }
    }
    function updateGroupedTyreNumbers(row) {
      var tyreNumbers = row.find('.tyre_numbers').val();
      row.find('.tyre_numbers_grouped').val(tyreNumbers);
    }

    function calculateTotal() {
        var grandTotal = 0;
        $('.item-row').each(function() {
            var quantity = parseInt($(this).find('.quantity').val()) || 0;
            var amount = parseFloat($(this).find('.amount').val()) || 0;
            var total = quantity * amount;
            $(this).find('.total').val(total.toFixed(2));
            grandTotal += total;
        });
        $('#grand_total').val(grandTotal.toFixed(2));
    }

    $(document).on('change', '.quantity, .amount', function() {
    var row = $(this).closest('.item-row');
    updatePlaceholder(row);
    calculateTotal();
    updateGroupedTyreNumbers(row);
    });

    $('#add-item').click(function() {
        var newRow = $('.item-row:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#item-rows').append(newRow);
        updatePlaceholder();
    });

    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        } else {
            alert('You cannot remove the last item.');
        }
    });

        $(document).on('change', '.item', function() {
        var itemId = $(this).val();
        var row = $(this).closest('.item-row');
        var tyreNumbersField = row.find('.tyre_numbers');

        $.ajax({
            url: '{{ route("get.category.info") }}',
            type: 'GET',
            data: { item_id: itemId },
            success: function(response) {
                if (response.category_name.toLowerCase().includes('tyre')) {
                    tyreNumbersField.prop('disabled', false);
                    tyreNumbersField.attr('placeholder', 'Enter comma-separated tyre numbers');
                    updatePlaceholder(row);
                } else {
                    tyreNumbersField.prop('disabled', true);
                    tyreNumbersField.val('');
                    tyreNumbersField.attr('placeholder', 'Not applicable for this item');
                }
            },
            error: function() {
                console.log('Error fetching category info');
            }
        });
    });
    $('#sellForm').on('submit', function(e) {
    var isValid = true;
    $('.item-row').each(function() {
        var row = $(this);
        var quantity = parseInt(row.find('.quantity').val());
        var tyreNumbers = row.find('.tyre_numbers').val().split(',').filter(function(item) {
            return item.trim() !== '';
        });
        var isTyreNumbersDisabled = row.find('.tyre_numbers').prop('disabled');

        updateGroupedTyreNumbers(row);

        if (!isTyreNumbersDisabled && tyreNumbers.length !== quantity) {
            isValid = false;
            return false;  // breaks the loop
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('The number of tyre numbers must match the quantity for each tyre item.');
    }
  });
});
</script>
@endsection