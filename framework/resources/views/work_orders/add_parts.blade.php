@if ($count==0)
<div class="fullPartsRow" data-stock='{{ $partsWithStock }}'>
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']) !!}
                {!! Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('is_own',"Own Stock ?", ['class' => 'form-label']) !!}
                {!! Form::select('is_own[]',[2=>'No',1=>'Yes'],null,['class'=>'form-control is_own','id'=>'is_own','required']) !!}
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                {!! Form::label('qty',"Quantity", ['class' => 'form-label']) !!}
                {!! Form::text('qty[]',null,['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']) !!}
                {!! Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']) !!}               
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('manual_tyre_numbers', 'Enter Tyre Numbers', ['class' => 'form-label']) !!}
                {!! Form::text('manual_tyre_numbers[]', null, ['class' => 'form-control manual_tyre_numbers', 'id' => 'manual_tyre_numbers', 'placeholder' => 'Enter comma-separated numbers']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']) !!}
                {!! Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('total_cost',"Amount", ['class' => 'form-label']) !!}
                {!! Form::text('total_cost[]',null,['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Total Amount','disabled']) !!}
            </div>
        </div>
        <div class="col-md-1 btn-vertical-align">
            <div class="form-group">
                <button type="button" class="btn btn-primary addmore" id="addmore">
                    <span class="fa fa-plus"></span> Add More
                </button>
            </div>
        </div>
        <div class="col-md-1 btn-vertical-align">
            <div class="form-group">
                <button type="button" class="btn btn-danger remove" id="remove">
                    <span class="fa fa-minus"></span> Remove
                </button>
            </div>
        </div>
    </div>
    <div class="row gst-row">
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                {!! Form::text('cgst[]',null,['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                {!! Form::text('cgst_amt[]',null,['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                {!! Form::text('sgst[]',null,['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                {!! Form::text('sgst_amt[]',null,['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']) !!}
                {!! Form::text('after_gst[]',null,['class'=>'form-control after_gst','id'=>'after_gst','readonly']) !!}
            </div>
        </div>
    </div>
</div>
@else
<div class="fullPartsRow" data-stock='{{ $partsWithStock }}'>
    <hr>
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']) !!}
                {!! Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('is_own',"Own Stock ?", ['class' => 'form-label']) !!}
                {!! Form::select('is_own[]',[2=>'No',1=>'Yes'],null,['class'=>'form-control is_own','id'=>'is_own','required']) !!}
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                {!! Form::label('qty',"Quantity", ['class' => 'form-label']) !!}
                {!! Form::text('qty[]',null,['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']) !!}
                {!! Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']) !!}                
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('manual_tyre_numbers', 'Enter Tyre Numbers', ['class' => 'form-label']) !!}
                {!! Form::text('manual_tyre_numbers[]', null, ['class' => 'form-control manual_tyre_numbers', 'id' => 'manual_tyre_numbers', 'placeholder' => 'Enter comma-separated numbers']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']) !!}
                {!! Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('total_cost',"Amount", ['class' => 'form-label']) !!}
                {!! Form::text('total_cost[]',null,['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Parts Price','disabled']) !!}
            </div>
        </div>
        <div class="col-md-2 btn-vertical-align">
            <div class="form-group">
                <button type="button" class="btn btn-danger remove" id="remove">
                    <span class="fa fa-minus"></span> Remove
                </button>
            </div>
        </div>
    </div>
    <div class="row gst-row">
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                {!! Form::text('cgst[]',null,['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                {!! Form::text('cgst_amt[]',null,['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                {!! Form::text('sgst[]',null,['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                {!! Form::text('sgst_amt[]',null,['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']) !!}
                {!! Form::text('after_gst[]',null,['class'=>'form-control after_gst','id'=>'after_gst','readonly']) !!}
            </div>
        </div>
    </div>
</div>
@endif

<script>
    $(document).ready(function() {
        var stockData = JSON.parse($('.fullPartsRow').attr('data-stock'));

        $(document).on('change', '.parts_id', function() {
            var row = $(this).closest('.row');
            updatePartDetails(row);
        });

        $(document).on('change', '.is_own', function() {
            var row = $(this).closest('.row');
            updateQuantityStatus(row);
        });
        
        $(document).on('input', '.qty, .unit_cost, .cgst, .sgst, .manual_tyre_numbers', function() {
            var row = $(this).closest('.row');
            calculateAmount(row);
        });

        function updatePartDetails(row) {
            var partId = row.find('.parts_id').val();
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            var unitCostInput = row.find('.unit_cost');
            var qtyInput = row.find('.qty');
            var totalCostInput = row.find('.total_cost');
            
            console.log('Selected Part ID:', partId);
            console.log('Stock Data:', stockData);
            
            // Reset fields when changing parts
            unitCostInput.val('');
            qtyInput.val('');
            totalCostInput.val('');
            manualTyreNumbers.val('');
            
            if (partId && partId !== 'add_new') {
                $.ajax({
                    url: '{{ route("get.tyre.numbers") }}',
                    type: 'GET',
                    data: { part_id: partId },
                    success: function(data) {
                        console.log('Received tyre numbers:', data);
                        tyreNumbersSelect.empty();
                        tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                        $.each(data, function(key, value) {
                            tyreNumbersSelect.append('<option value="' + value + '">' + value + '</option>');
                        });
                        updateQuantityStatus(row);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            } else {
                tyreNumbersSelect.empty();
                updateQuantityStatus(row);
            }
        }

        function updateQuantityStatus(row) {
            var partId = row.find('.parts_id').val();
            var isOwn = row.find('.is_own').val();
            var qtyInput = row.find('.qty');
            var unitCostInput = row.find('.unit_cost');
            var totalCostInput = row.find('.total_cost');
            var gstRow = row.next('.gst-row');
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            
            if (partId && partId !== 'add_new') {
                var stock = stockData[partId] !== undefined ? parseInt(stockData[partId]) : 0;
                console.log('Stock for part ' + partId + ':', stock);
                
                if (isOwn == '1') { // Yes for Own Stock
                    if (stock <= 0) {
                        qtyInput.val('Out of Stock');
                        qtyInput.prop('disabled', true);
                        tyreNumbersSelect.prop('disabled', true);
                    } else {
                        qtyInput.val('');
                        qtyInput.prop('disabled', false);
                        qtyInput.attr('data-max-stock', stock);
                        tyreNumbersSelect.prop('disabled', false);
                    }
                    unitCostInput.val('').prop('disabled', true);
                    totalCostInput.val('0').prop('disabled', true);
                    gstRow.find('input').val('').prop('disabled', true);
                    manualTyreNumbers.val('').prop('disabled', true);
                } else { // No for Own Stock
                    qtyInput.val('');
                    qtyInput.prop('disabled', false);
                    qtyInput.removeAttr('data-max-stock');
                    unitCostInput.val('').prop('disabled', false);
                    totalCostInput.prop('disabled', true);
                    gstRow.find('input').prop('disabled', false);
                    tyreNumbersSelect.val('').prop('disabled', true);
                    manualTyreNumbers.prop('disabled', false);
                }
            } else {
                qtyInput.val('');
                qtyInput.prop('disabled', false);
                qtyInput.removeAttr('data-max-stock');
                unitCostInput.val('').prop('disabled', false);
                totalCostInput.prop('disabled', true);
                gstRow.find('input').prop('disabled', false);
                tyreNumbersSelect.val('').prop('disabled', true);
                manualTyreNumbers.val('').prop('disabled', false);
            }

            qtyInput.off('input').on('input', function() {
                validateTyreNumbers(row);
            });
            
            tyreNumbersSelect.off('change').on('change', function() {
                validateTyreNumbers(row);
            });

            manualTyreNumbers.off('input').on('input', function() {
                validateTyreNumbers(row);
            });

            calculateAmount(row);
        }

        function validateTyreNumbers(row) {
            var qty = parseInt(row.find('.qty').val()) || 0;
            var isOwn = row.find('.is_own').val();
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            
            if (isOwn == '1') {
                var selectedOptions = tyreNumbersSelect.val() ? tyreNumbersSelect.val().length : 0;
                if (selectedOptions !== qty) {
                    tyreNumbersSelect.addClass('is-invalid');
                    tyreNumbersSelect.next('.invalid-feedback').remove();
                    tyreNumbersSelect.after('<div class="invalid-feedback">Please select exactly ' + qty + ' tyre number(s).</div>');
                } else {
                    tyreNumbersSelect.removeClass('is-invalid');
                    tyreNumbersSelect.next('.invalid-feedback').remove();
                }
            } else {
                var enteredNumbers = manualTyreNumbers.val().split(',').filter(n => n.trim() !== '').length;
                if (enteredNumbers !== qty) {
                    manualTyreNumbers.addClass('is-invalid');
                    manualTyreNumbers.next('.invalid-feedback').remove();
                    manualTyreNumbers.after('<div class="invalid-feedback">Please enter exactly ' + qty + ' comma-separated tyre number(s).</div>');
                } else {
                    manualTyreNumbers.removeClass('is-invalid');
                    manualTyreNumbers.next('.invalid-feedback').remove();
                }
            }
        }

        function calculateAmount(row) {
            var isOwn = row.find('.is_own').val();
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var unitCost = parseFloat(row.find('.unit_cost').val()) || 0;
            var totalCost = row.find('.total_cost');
            var gstRow = row.next('.gst-row');

            if (isOwn == '1') { // Yes for Own Stock
                totalCost.val('0');
                gstRow.find('input').val('').prop('disabled', true);
            } else {
                var amount = qty * unitCost;
                totalCost.val(amount.toFixed(2));

                var cgst = parseFloat(gstRow.find('.cgst').val()) || 0;
                var sgst = parseFloat(gstRow.find('.sgst').val()) || 0;

                var cgstAmount = (amount * cgst / 100).toFixed(2);
                var sgstAmount = (amount * sgst / 100).toFixed(2);
                var afterGstAmount = (amount + parseFloat(cgstAmount) + parseFloat(sgstAmount)).toFixed(2);

                gstRow.find('.cgst_amt').val(cgstAmount);
                gstRow.find('.sgst_amt').val(sgstAmount);
                gstRow.find('.after_gst').val(afterGstAmount);
            }
        }
        
        // Validate quantity and tyre numbers on form submission
        $('form').on('submit', function(e) {
            var isValid = true;
            $('.qty').each(function() {
                var row = $(this).closest('.row');
                var isOwn = row.find('.is_own').val();
                var qty = parseInt($(this).val());
                var maxStock = parseInt($(this).attr('data-max-stock'));
                
                if (isOwn == '1' && !isNaN(maxStock) && qty > maxStock) {
                    alert('Input quantity (' + qty + ') is greater than the available stock (' + maxStock + ') for one or more parts.');
                    isValid = false;
                    return false;
                }
                
                validateTyreNumbers(row);
                if (row.find('.is-invalid').length > 0) {
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please correct the errors before submitting.');
            }
        });
    });
</script>