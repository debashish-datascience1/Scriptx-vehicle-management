<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('fuel_manage.index')); ?>"><?php echo app('translator')->getFromJson('fleet.fuel_management'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_fuel_management'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.edit_fuel_management'); ?></h3>
      </div>

      <?php echo Form::open(['route' => ['fuel_manage.update', $fuel_management->id], 'method' => 'PUT', 'id' => 'fuel-management-form']); ?>

      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

              <?php echo Form::date('date', $fuel_management->date, ['class' => 'form-control', 'required' => true]); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('stock', __('fleet.stock'), ['class' => 'form-label']); ?>

              <?php echo Form::select('stock', ['own' => 'Own Stock'], 'own', ['class' => 'form-control', 'placeholder' => __('fleet.select_stock'), 'required' => true, 'id' => 'stock-select']); ?>

              <small id="available-stock-info" class="form-text text-muted mt-1"></small>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'form-label']); ?>

              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vehicle->id); ?>" <?php echo e($fuel_management->vehicle_id == $vehicle->id ? 'selected' : ''); ?>>
                  <?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('quantity', __('fleet.quantity'), ['class' => 'form-label']); ?>

              <?php echo Form::number('quantity', $fuel_management->quantity, ['class' => 'form-control', 'step' => '0.01', 'required' => true, 'id' => 'quantity-input']); ?>

            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <?php echo Form::label('remark', __('fleet.remarks'), ['class' => 'form-label']); ?>

              <?php echo Form::textarea('remark', $fuel_management->remark, ['class' => 'form-control', 'rows' => 3]); ?>

            </div>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-info']); ?>

      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
$(document).ready(function() {
   var availableStock = 0;
   var initialQuantity = parseFloat('<?php echo e($fuel_management->quantity); ?>');

   $('#stock-select').on('change', function() {
    var selectedStock = $(this).val();
    
    if (selectedStock === 'own') {
        $.ajax({
            url: '<?php echo e(route("fuel_manage.get_available_stock")); ?>',
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/fuel_manage/edit.blade.php ENDPATH**/ ?>