
<table class="table table-striped">
    <tr>
        <th>Transaction ID</th>
        <td><?php echo e($row->transaction->transaction_id); ?></td>
    </tr>
    <tr>
        <th>Document</th>
        <td><?php echo e($row->document->label); ?></td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td><?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <label><?php echo e($row->vehicle->license_plate); ?></label></td>
    </tr>
    <tr>
        <th>Driver</th>
        <td>
            <?php if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver)): ?>
                <?php echo e($row->drivervehicle->assigned_driver->name); ?>

            <?php else: ?>
            <span style="color: red"><small><i>Driver not assigned</i></small></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Vendor</th>
        <td>
            <?php echo e($row->vendor->name); ?>

        </td>
    </tr>
    <tr>
        <th>On Date</th>
        <td>
            <?php echo e(Helper::getCanonicalDate($row->date)); ?>

        </td>
    </tr>
    <tr>
        <th>Valid Till</th>
        <td>
            <?php echo e(Helper::getCanonicalDate($row->till)); ?><br>
            <?php ($to = \Carbon\Carbon::now()); ?>

            <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till)); ?>

            <?php ($diff_in_days = $to->diffInDays($from)); ?>
            <label><?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($diff_in_days); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?></label>
        </td>
    </tr>
    <tr>
        <th>Amount</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->amount)); ?></td>
    </tr>
    <tr>
        <th>Method</th>
        <td><?php echo e($row->method_param->label); ?></td>
    </tr>
    <tr>
        <th>Reference No.</th>
        <td><?php echo e($row->ddno); ?></td>
    </tr>
</table>
<?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicle_docs/view_event.blade.php ENDPATH**/ ?>