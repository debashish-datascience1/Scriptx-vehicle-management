<?php if($count==0): ?>
<div class="fullPartsRow" data-stock='<?php echo e($partsWithStock); ?>'>
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']); ?>

                <?php echo Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']); ?>

            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <?php echo Form::label('is_own',"Own Stock ?", ['class' => 'form-label']); ?>

                <?php echo Form::select('is_own[]',[2=>'No',1=>'Yes'],null,['class'=>'form-control is_own','id'=>'is_own','required']); ?>

            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <?php echo Form::label('qty',"Quantity", ['class' => 'form-label']); ?>

                <?php echo Form::text('qty[]',null,['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']); ?>               
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']); ?>

                <?php echo Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('total_cost',"Amount", ['class' => 'form-label']); ?>

                <?php echo Form::text('total_cost[]',null,['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Total Amount','disabled']); ?>

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
                <?php echo Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']); ?>

                <?php echo Form::text('cgst[]',null,['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']); ?>

                <?php echo Form::text('cgst_amt[]',null,['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']); ?>

                <?php echo Form::text('sgst[]',null,['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']); ?>

                <?php echo Form::text('sgst_amt[]',null,['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']); ?>

                <?php echo Form::text('after_gst[]',null,['class'=>'form-control after_gst','id'=>'after_gst','readonly']); ?>

            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="fullPartsRow" data-stock='<?php echo e($partsWithStock); ?>'>
    <hr>
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']); ?>

                <?php echo Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']); ?>

            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <?php echo Form::label('is_own',"Own Stock ?", ['class' => 'form-label']); ?>

                <?php echo Form::select('is_own[]',[2=>'No',1=>'Yes'],null,['class'=>'form-control is_own','id'=>'is_own','required']); ?>

            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <?php echo Form::label('qty',"Quantity", ['class' => 'form-label']); ?>

                <?php echo Form::text('qty[]',null,['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']); ?>                
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']); ?>

                <?php echo Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','onkeypress'=>'return isNumber(event,this)']); ?>

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('total_cost',"Amount", ['class' => 'form-label']); ?>

                <?php echo Form::text('total_cost[]',null,['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Parts Price','disabled']); ?>

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
                <?php echo Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']); ?>

                <?php echo Form::text('cgst[]',null,['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']); ?>

                <?php echo Form::text('cgst_amt[]',null,['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']); ?>

                <?php echo Form::text('sgst[]',null,['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']); ?>

                <?php echo Form::text('sgst_amt[]',null,['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']); ?>

                <?php echo Form::text('after_gst[]',null,['class'=>'form-control after_gst','id'=>'after_gst','readonly']); ?>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>
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
    
    $(document).on('input', '.qty, .unit_cost, .cgst, .sgst', function() {
        var row = $(this).closest('.row');
        calculateAmount(row);
    });

    function updatePartDetails(row) {
        var partId = row.find('.parts_id').val();
        var tyreNumbersSelect = row.find('.tyre_numbers');
        var unitCostInput = row.find('.unit_cost');
        var qtyInput = row.find('.qty');
        var totalCostInput = row.find('.total_cost');
        
        console.log('Selected Part ID:', partId);
        console.log('Stock Data:', stockData);
        
        // Reset fields when changing parts
        unitCostInput.val('');
        qtyInput.val('');
        totalCostInput.val('');
        
        if (partId && partId !== 'add_new') {
            $.ajax({
                url: '<?php echo e(route("get.edit.tyre.numbers")); ?>',
                type: 'GET',
                data: { part_id: partId },
                success: function(data) {
                    console.log('Received tyre numbers:', data);
                    tyreNumbersSelect.empty();
                    tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                    $.each(data, function(key, value) {
                        tyreNumbersSelect.append('<option value="' + value + '">' + value + '</option>');
                    });
                    tyreNumbersSelect.prop('required', true);
                    updateQuantityStatus(row);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        } else {
            tyreNumbersSelect.empty();
            tyreNumbersSelect.prop('required', false);
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
    
    if (partId && partId !== 'add_new') {
        var stock = stockData[partId] !== undefined ? parseInt(stockData[partId]) : 0;
        console.log('Stock for part ' + partId + ':', stock);
        
        if (isOwn == '1') { // Yes for Own Stock
            if (stock <= 0) {
                qtyInput.val('Out of Stock');
                qtyInput.prop('disabled', true);
                tyreNumbersSelect.val('').prop('disabled', true);
            } else {
                qtyInput.val('');
                qtyInput.prop('disabled', false);
                qtyInput.attr('data-max-stock', stock);
                tyreNumbersSelect.prop('disabled', false);
            }
            unitCostInput.val('');
            totalCostInput.val('0');
            gstRow.find('input').val('').prop('disabled', true);
        } else { // No for Own Stock
            qtyInput.val('');
            qtyInput.prop('disabled', false);
            qtyInput.removeAttr('data-max-stock');
            unitCostInput.val('');
            gstRow.find('input').prop('disabled', false);
            tyreNumbersSelect.prop('disabled', false);
        }
    } else {
        qtyInput.val('');
        qtyInput.prop('disabled', false);
        qtyInput.removeAttr('data-max-stock');
        unitCostInput.val('');
        gstRow.find('input').prop('disabled', false);
        tyreNumbersSelect.prop('disabled', false);
    }

    // New code for handling quantity input and tyre number selection
    qtyInput.off('input').on('input', function() {
        var qty = parseInt($(this).val()) || 0;
        tyreNumbersSelect.attr('data-qty', qty);
        validateTyreNumbersSelection(tyreNumbersSelect);
    });
    
    tyreNumbersSelect.off('change').on('change', function() {
        validateTyreNumbersSelection($(this));
    });

    calculateAmount(row);
}

    function validateTyreNumbersSelection(select) {
        var qty = parseInt(select.attr('data-qty')) || 0;
        var selectedOptions = select.val() ? select.val().length : 0;
        
        if (selectedOptions !== qty) {
            select.addClass('is-invalid');
            select.next('.invalid-feedback').remove();
            select.after('<div class="invalid-feedback">Please select exactly ' + qty + ' tyre number(s).</div>');
        } else {
            select.removeClass('is-invalid');
            select.next('.invalid-feedback').remove();
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
    
    // Validate quantity on form submission
    $('form').on('submit', function(e) {
        var isValid = true;
        $('.qty').each(function() {
            var row = $(this).closest('.row');
            var isOwn = row.find('.is_own').val();
            var qty = parseInt($(this).val());
            var maxStock = parseInt($(this).attr('data-max-stock'));
            
            if (isOwn == '1' && !isNaN(maxStock) && qty > maxStock) { // Only check if Own Stock
                alert('Input quantity (' + qty + ') is greater than the available stock (' + maxStock + ') for one or more parts.');
                isValid = false;
                return false;  // Break the loop
            }
        });
        if (!isValid) {
            e.preventDefault();  // Prevent form submission
        }
    });
    $('form').on('submit', function(e) {
        var isValid = true;
        $('.tyre_numbers').each(function() {
            var qty = parseInt($(this).attr('data-qty')) || 0;
            var selectedOptions = $(this).val() ? $(this).val().length : 0;
            
            if (selectedOptions !== qty) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">Please select exactly ' + qty + ' tyre number(s).</div>');
            }
        });
        
        if (!isValid) {
            e.preventDefault();  // Prevent form submission
            alert('Please ensure the number of selected tyre numbers matches the quantity for each part.');
        }
    });
});
</script>
<?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/add_parts_edit.blade.php ENDPATH**/ ?>