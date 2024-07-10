<table>
    <thead>
     <tr>
        <th><strong>SL#</strong></th>
        <th><strong>Date</strong></th>
        <th><strong>Vendor</strong></th>
        <th><strong>Vehicle</strong></th>
        <th><strong>Fuel</strong></th>
        <th><strong>Rate</strong></th>
        <th><strong>Qty(ltr)</strong></th>
        <th><strong>Amount</strong></th>
     </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($k+1); ?></td>
                <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
                
                <td>
                  <?php if(!empty($row->vendor)): ?>
                    <?php echo e($row->vendor->name); ?>

                  <?php else: ?>
                    <span class='badge badge-danger'><?php echo e($row-id); ?>Unnamed Vendor</span>
                  <?php endif; ?>
                </td>
                
                <td><?php echo e($row->vehicle_data->make); ?>-<?php echo e($row->vehicle_data->model); ?>-<strong><?php echo e(strtoupper($row->vehicle_data->license_plate)); ?></strong></td>
                <td>
                    <?php if(!empty($row->fuel_details)): ?>
                      <?php echo e($row->fuel_details->fuel_name); ?>

                    <?php else: ?>
                      <span class='badge badge-danger'>Unnamed Fuel</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($row->cost_per_unit); ?></td>
                <td><?php echo e($row->qty); ?></td>
                <td><?php echo e(bcdiv($row->qty * $row->cost_per_unit,1,2)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <th colspan="5"></th>
            <th>Total</th>
            <th><?php echo e(bcdiv($fuelQtySum,1,2)); ?> ltr</th>
            <th nowrap><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($fuelTotal,1,2)); ?></th>
        </tr>
    </tbody>
  </table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/exports/fuel.blade.php ENDPATH**/ ?>