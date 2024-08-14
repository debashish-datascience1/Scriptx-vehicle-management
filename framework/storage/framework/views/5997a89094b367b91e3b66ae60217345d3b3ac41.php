<?php if($count==0): ?>
<div class="fullPartsRow" data-stock='<?php echo e($partsWithStock); ?>'>
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']); ?>

                <?php echo Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
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

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']); ?>

                <input type="hidden" name="tyre_numbers_grouped[]" class="tyre_numbers_grouped">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('manual_tyre_numbers', 'Enter Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::text('manual_tyre_numbers[]', null, ['class' => 'form-control manual_tyre_numbers', 'id' => 'manual_tyre_numbers', 'placeholder' => 'Enter comma-separated numbers']); ?>

                <input type="hidden" name="manual_tyre_numbers_grouped[]" class="manual_tyre_numbers_grouped">
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
        <div class="col-md-2">
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

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'multiple' => 'multiple', 'placeholder' => 'Select Tyre Numbers']); ?>

            <input type="hidden" name="tyre_numbers_grouped[]" class="tyre_numbers_grouped">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('manual_tyre_numbers', 'Enter Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::text('manual_tyre_numbers[]', null, ['class' => 'form-control manual_tyre_numbers', 'id' => 'manual_tyre_numbers', 'placeholder' => 'Enter comma-separated numbers']); ?>

                <input type="hidden" name="manual_tyre_numbers_grouped[]" class="manual_tyre_numbers_grouped">
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
        
        $(document).on('input', '.qty, .unit_cost, .cgst, .sgst, .manual_tyre_numbers', function() {
            var row = $(this).closest('.row');
            calculateAmount(row);
        });

        function generateUniqueId() {
            return 'id_' + Math.random().toString(36).substr(2, 9);
        }

      
        function updatePartDetails(row) {
            var partId = row.find('.parts_id').val();
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            
            console.log('Selected Part ID:', partId);

            if (!row.attr('id')) {
                row.attr('id', generateUniqueId());
            }
            
            // Reset fields when changing parts
            row.find('.unit_cost, .qty, .total_cost').val('');
            manualTyreNumbers.val('');
            tyreNumbersSelect.empty();
    
            if (partId && partId !== 'add_new') {
                $.ajax({
                    url: '<?php echo e(route("get.part.category")); ?>',
                    type: 'GET',
                    data: { part_id: partId },
                    success: function(categoryData) {
                        console.log('Category Data:', categoryData);
                        
                        row.data('is-tyre', categoryData.is_tyre);
                        
                        updateQuantityStatus(row);
                        
                        if (categoryData.is_tyre) {
                            $.ajax({
                                url: '<?php echo e(route("get.tyre.numbers")); ?>',
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

                                    tyreNumbersSelect.off('change').on('change', function() {
                                        updateGroupedTyreNumbers(row);
                                        validateTyreNumbers(row);
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX Error:', status, error);
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        row.data('is-tyre', false);
                        updateQuantityStatus(row);
                    }
                });
            } else {
                row.data('is-tyre', false);
                updateQuantityStatus(row);
            }
        }

        function updateGroupedTyreNumbers(row) {
        var selectedTyreNumbers = row.find('.tyre_numbers').val() || [];
        var groupedTyreNumbers = selectedTyreNumbers.join(',');
        row.find('.tyre_numbers_grouped').val(groupedTyreNumbers);

        var manualTyreNumbers = row.find('.manual_tyre_numbers').val();
        row.find('.manual_tyre_numbers_grouped').val(manualTyreNumbers);
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
            var isTyre = row.data('is-tyre');
            
            if (partId && partId !== 'add_new') {
                var stock = stockData[partId] !== undefined ? parseInt(stockData[partId]) : 0;
                console.log('Stock for part ' + partId + ':', stock);
                
                if (isTyre) {
                    if (isOwn == '1') { // Yes for Own Stock
                        if (stock <= 0) {
                            qtyInput.val('Out of Stock').prop('disabled', true);
                            tyreNumbersSelect.prop('disabled', true);
                        } else {
                            qtyInput.val('').prop('disabled', false).attr('data-max-stock', stock);
                            tyreNumbersSelect.prop('disabled', false);
                        }
                        unitCostInput.val('').prop('disabled', true);
                        totalCostInput.val('0').prop('disabled', true);
                        gstRow.find('input').val('').prop('disabled', true);
                        manualTyreNumbers.val('').prop('disabled', true);
                    } else { // No for Own Stock
                        qtyInput.val('').prop('disabled', false).removeAttr('data-max-stock');
                        unitCostInput.val('').prop('disabled', false);
                        totalCostInput.prop('disabled', true);
                        gstRow.find('input').prop('disabled', false);
                        tyreNumbersSelect.val('').prop('disabled', true);
                        manualTyreNumbers.prop('disabled', false);
                    }
                } else {
                    // Not a tyre part
                    if (isOwn == '1') { // Yes for Own Stock
                        if (stock <= 0) {
                            qtyInput.val('Out of Stock').prop('disabled', true);
                        } else {
                            qtyInput.val('').prop('disabled', false).attr('data-max-stock', stock);
                        }
                        unitCostInput.val('').prop('disabled', true);
                        totalCostInput.val('0').prop('disabled', true);
                        gstRow.find('input').val('').prop('disabled', true);
                    } else { // No for Own Stock
                        qtyInput.val('').prop('disabled', false).removeAttr('data-max-stock');
                        unitCostInput.val('').prop('disabled', false);
                        totalCostInput.prop('disabled', true);
                        gstRow.find('input').prop('disabled', false);
                    }
                    tyreNumbersSelect.val('').prop('disabled', true);
                    manualTyreNumbers.val('').prop('disabled', true);
                }
            } else {
                qtyInput.val('').prop('disabled', false).removeAttr('data-max-stock');
                unitCostInput.val('').prop('disabled', false);
                totalCostInput.prop('disabled', true);
                gstRow.find('input').prop('disabled', false);
                tyreNumbersSelect.val('').prop('disabled', true);
                manualTyreNumbers.val('').prop('disabled', true);
            }

            // Update event listeners
            qtyInput.off('input').on('input', function() {
                if (isTyre) validateTyreNumbers(row);
                calculateAmount(row);
            });
            
            tyreNumbersSelect.off('change').on('change', function() {
                if (isTyre && isOwn == '1') validateTyreNumbers(row);
            });

            manualTyreNumbers.off('input').on('input', function() {
                if (isTyre && isOwn != '1') validateTyreNumbers(row);
                calculateAmount(row);
            });

            calculateAmount(row);
        }

        function validateTyreNumbers(row) {
            var isTyre = row.data('is-tyre');
            if (!isTyre) return;

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
                manualTyreNumbers.removeClass('is-invalid').next('.invalid-feedback').remove();
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
                tyreNumbersSelect.removeClass('is-invalid').next('.invalid-feedback').remove();
            }
            updateGroupedTyreNumbers(row);

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
                updateGroupedTyreNumbers(row);
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please correct the errors before submitting.');
            }
        });
    });
</script><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/add_parts.blade.php ENDPATH**/ ?>