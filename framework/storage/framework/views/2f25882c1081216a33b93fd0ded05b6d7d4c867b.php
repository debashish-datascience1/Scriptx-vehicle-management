<table class="table table-striped">
    <tr>
        <th>Name</th>
        <td><?php echo e($customer->name); ?></td>
    </tr>
    <?php if(!empty($customer->gstin)): ?>
    <tr>
        <th>GSTIN</th>
        <td><?php echo e($customer->gstin); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Email</th>
        <td><?php echo e($customer->email); ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?php echo e($customer->getMeta('mobno')); ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?php echo e($customer->address); ?></td>
    </tr>
    <tr>
        <th>Opening Balance</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($customer->opening_balance,1,2)); ?></td>
    </tr>
    <tr>
        <th>Opening Remarks</th>
        <td><?php echo e($customer->opening_remarks); ?></td>
    </tr>
</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/customers/view_event.blade.php ENDPATH**/ ?>