<?php if($count==0): ?>
<div class="fullPartsRow">
    <div class="row parts-row">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']); ?>

                <?php echo Form::select('parts_id[]',$options,null,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']); ?>

            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'placeholder' => 'Select Tyre Number', 'required']); ?>

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
                <?php echo Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']); ?>

                <?php echo Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','required','onkeypress'=>'return isNumber(event,this)']); ?>

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
<div class="fullPartsRow">
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
                <?php echo Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']); ?>

                <?php echo Form::select('tyre_numbers[]', [], null, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers', 'placeholder' => 'Select Tyre Number', 'required']); ?>

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
                <?php echo Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']); ?>

                <?php echo Form::text('unit_cost[]',null,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','required','onkeypress'=>'return isNumber(event,this)']); ?>

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
    $(document).on('change', '.parts_id', function() {
        var partId = $(this).val();
        var tyreNumbersSelect = $(this).closest('.row').find('.tyre_numbers');
        
        console.log('Selected Part ID:', partId);
        
        if (partId) {
            $.ajax({
                url: '<?php echo e(route("get.tyre.numbers")); ?>',
                type: 'GET',
                data: { part_id: partId },
                success: function(data) {
                    console.log('Received data:', data);
                    tyreNumbersSelect.empty();
                    tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                    $.each(data, function(key, value) {
                        tyreNumbersSelect.append('<option value="' + value + '">' + value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        } else {
            tyreNumbersSelect.empty();
            tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
        }
    });
});
</script><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/add_parts.blade.php ENDPATH**/ ?>