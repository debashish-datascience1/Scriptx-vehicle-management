<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/bootstrap-datetimepicker.min.css')); ?>">
<style>
  .description{resize: none;height: 120px;}
  .item-row {margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("parts-sell.index")); ?>"><?php echo app('translator')->getFromJson('menu.partSell'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.editPartsSale'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.editPartsSale'); ?></h3>
      </div>

      <div class="card-body">
          <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger">
              <ul>
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php echo Form::model($data, ['route' => ['parts-sell.update', $data->id], 'method' => 'PATCH', 'id' => 'sellForm']); ?>

          
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <?php echo Form::label('sell_to', __('fleet.sellTo'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('sell_to', null, ['class' => 'form-control', 'required']); ?>

                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('date', $data->date_of_sell, ['class' => 'form-control datepicker', 'required']); ?>

                </div>
              </div>
            </div>

            <div id="item-rows">
              <?php $__currentLoopData = $allParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item-row">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <?php echo Form::label('item[]', __('fleet.item'), ['class' => 'form-label']); ?>

                        <?php echo Form::select('item[]', $items, $item->item, ['class' => 'form-control item', 'required']); ?>

                      </div> 
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <?php echo Form::label('quantity[]', __('fleet.quantity'), ['class' => 'form-label']); ?>

                        <?php echo Form::number('quantity[]', $item->quantity, ['class' => 'form-control quantity', 'required', 'min' => '1']); ?>

                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <?php echo Form::label('tyre_numbers[]', __('fleet.selltyreNumbers'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('tyre_numbers[]', $item->tyre_numbers, ['class' => 'form-control tyre_numbers', 'placeholder' => 'Enter comma-separated tyre numbers']); ?>

                        <input type="hidden" name="tyre_numbers_grouped[]" class="tyre_numbers_grouped" value="<?php echo e($item->tyre_numbers); ?>">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <?php echo Form::label('amount[]', __('fleet.amount'), ['class' => 'form-label']); ?>

                        <?php echo Form::number('amount[]', $item->amount, ['class' => 'form-control amount', 'required', 'step' => '0.01', 'min' => '0']); ?>

                      </div>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <?php echo Form::label('total[]', __('fleet.total'), ['class' => 'form-label']); ?>

                        <?php echo Form::text('total[]', $item->total, ['class' => 'form-control total', 'readonly']); ?>

                      </div>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger remove-item" style="<?php echo e($index == 0 ? 'display:none;' : ''); ?>">Remove</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="row">
              <div class="col-md-12">
                <button type="button" id="add-item" class="btn btn-info">Add More</button>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-offset-8 col-md-4">
                <div class="form-group">
                  <?php echo Form::label('grand_total', __('fleet.sumtotal'), ['class' => 'form-label']); ?>

                  <?php echo Form::text('grand_total', $data->grand_total, ['class' => 'form-control', 'readonly', 'id' => 'grand_total']); ?>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-success']); ?>

              </div>
            </div> 
        </div>      
  <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datetimepicker.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: new Date()
    });

    // function updatePlaceholder(row) {
    //     var quantity = parseInt(row.find('.quantity').val());
    //     var tyreNumbersField = row.find('.tyre_numbers');
    //     if (quantity > 0) {
    //         tyreNumbersField.attr('placeholder', 'Enter ' + quantity + ' comma-separated tyre numbers');
    //     } else {
    //         tyreNumbersField.attr('placeholder', 'Enter comma-separated tyre numbers');
    //     }
    // }
      function updatePlaceholder(row) {
          var quantity = parseInt(row.find('.quantity').val());
          var tyreNumbersField = row.find('.tyre_numbers');
          var isTyreItem = !tyreNumbersField.prop('disabled');
          if (isTyreItem && quantity > 0) {
              tyreNumbersField.attr('placeholder', 'Enter ' + quantity + ' comma-separated tyre numbers');
          } else if (isTyreItem) {
              tyreNumbersField.attr('placeholder', 'Enter comma-separated tyre numbers');
          } else {
              tyreNumbersField.attr('placeholder', 'Not applicable for this item');
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

    // $('#add-item').click(function() {
    //     var newRow = $('.item-row:first').clone();
    //     newRow.find('input').val('');
    //     newRow.find('select').val('');
    //     newRow.find('.remove-item').show();
    //     $('#item-rows').append(newRow);
    //     updatePlaceholder(newRow);
    // });
    // Initialize tyre number fields for existing items
    $('.item-row').each(function() {
        var row = $(this);
        var itemId = row.find('.item').val();
        var tyreNumbersField = row.find('.tyre_numbers');

        $.ajax({
            url: '<?php echo e(route("get.category.info")); ?>',
            type: 'GET',
            data: { item_id: itemId },
            success: function(response) {
                if (response.category_name.toLowerCase().includes('tyre')) {
                    tyreNumbersField.prop('disabled', false);
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
    $('#add-item').click(function() {
      var newRow = $('.item-row:first').clone();
      newRow.find('input').val('');
      newRow.find('select').val('');
      newRow.find('.remove-item').show();
      newRow.find('.tyre_numbers').prop('disabled', true).attr('placeholder', 'Not applicable for this item');
      $('#item-rows').append(newRow);
      updatePlaceholder(newRow);
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
            url: '<?php echo e(route("get.category.info")); ?>',
            type: 'GET',
            data: { item_id: itemId },
            success: function(response) {
                if (response.category_name.toLowerCase().includes('tyre')) {
                    tyreNumbersField.prop('disabled', false);
                    tyreNumbersField.attr('placeholder', 'Enter comma-separated tyre numbers');
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

    // Initialize placeholders and calculations
    $('.item-row').each(function() {
        updatePlaceholder($(this));
    });
    calculateTotal();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/parts_sell/edit.blade.php ENDPATH**/ ?>