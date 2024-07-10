<select id="vehicle_id" name="vehicle_id" class="form-control" required>
    <option value="">-</option>
    <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($vehicle['id']}" data-driver="{{$vehicle['data_driver']); ?>" data-mileage="<?php echo e($vehicle['data_mileage']); ?>"><?php echo e($vehicle['text']); ?>

    </option>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bookings/vehicle_call.blade.php ENDPATH**/ ?>