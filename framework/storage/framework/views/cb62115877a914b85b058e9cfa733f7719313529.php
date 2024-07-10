<table class="table table-striped">
    <tr>
        <th>Vendor</th>
        <td>
            <?php echo e($vendor->name); ?>

            <?php if($vendor->photo!=""): ?>
            <a href="<?php echo e(asset("uploads/".$vendor->photo)); ?>" class="col-xs-3 control-label" target="_blank" >(View)</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Type</th>
        <td><?php echo e($vendor->type); ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?php echo e($vendor->phone); ?></td>
    </tr>
    <tr>
        <th>Address 1</th>
        <td><?php echo e($vendor->address1); ?></td>
    </tr>
    <tr>
        <th>Address 2</th>
        <td><?php echo e($vendor->address2); ?></td>
    </tr>
    <tr>
        <th>City</th>
        <td><?php echo e($vendor->city); ?></td>
    </tr>
    <tr>
        <th>Postal Code</th>
        <td><?php echo e($vendor->postal_code); ?></td>
    </tr>
    <tr>
        <th>Country</th>
        <td><?php echo e($vendor->country); ?></td>
    </tr>
    <tr>
        <th>State/Province</th>
        <td><?php echo e($vendor->province); ?></td>
    </tr>
    <tr>
        <th>Note</th>
        <td><?php echo e($vendor->note); ?></td>
    </tr>
    <tr>
        <th>Opening Balance</th>
        <td><?php echo e(bcdiv($vendor->opening_balance,1,2)); ?></td>
    </tr>
    <tr>
        <th>Opening Details</th>
        <td><?php echo e($vendor->opening_comment); ?></td>
    </tr>
</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/vendors/view_event.blade.php ENDPATH**/ ?>