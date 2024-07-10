
<table class="table table-striped">
    
    <tr>
        <th>Vendor</th>
        <td><?php echo e($row->vendor->name); ?></td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td><?php echo e($row->vehicle_data->make); ?>-<?php echo e($row->vehicle_data->model); ?>-<?php echo e($row->vehicle_data->license_plate); ?></td>
    </tr>
    <tr>
        <th>Date </th>
        <td><?php echo e(Helper::getCanonicalDate($row->date)); ?></td>
    </tr>
    <tr>
        <th>Fuel </th>
        <td><?php echo e($row->fuel_details->fuel_name); ?></td>
    </tr>
    <tr>
        <th>Quantity</th>
        <td><?php echo e($row->qty); ?></td>
    </tr>
    <tr>
        <th>Per Unit</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->cost_per_unit)); ?></td>
    </tr>
    <tr>
        <th>Fuel Price</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->qty*$row->cost_per_unit)); ?></td>
    </tr>
    <?php if(!empty($row->cgst)): ?>
    <tr>
        <th>Is GST ?</th>
        <td>
            <?php if($row->is_gst==1): ?>
            <span class="badge badge-success">Yes</span>
            <?php else: ?>
            <span class="badge badge-danger">No</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php if(!empty($row->cgst)): ?>
    <tr>
        <th>CGST Rate</th>
        <td><?php echo e($row->cgst); ?> %</td>
    </tr>
    <tr>
        <th>CGST Amount</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->cgst_amt)); ?></td>
    </tr>
    <?php endif; ?>
    <?php if(!empty($row->sgst)): ?>
    <tr>
        <th>SGST Rate</th>
        <td><?php echo e($row->sgst); ?> %</td>
    </tr>
    <tr>
        <th>SGST Amount</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->sgst_amt)); ?></td>
    </tr>
    <?php endif; ?>
    <?php if(!empty($row->cgst) || !empty($row->sgst)): ?>
    <tr>
        <th>GST Amount</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->cgst_amt+$row->sgst_amt)); ?></td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals(($row->qty*$row->cost_per_unit)+$row->cgst_amt+$row->sgst_amt)); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Reference</th>
        <td><?php echo e($row->reference); ?></td>
    </tr>
    <tr>
        <th>Start Meter</th>
        <td><?php echo e($row->start_meter); ?></td>
    </tr>
    <tr>
        <th>Province</th>
        <td><?php echo e($row->province); ?></td>
    </tr>
    <tr>
        <th>Note</th>
        <td><?php echo e($row->note); ?></td>
    </tr>
    <tr>
        <th>Complete Fill Up</th>
        <td>
            <?php if($row->complete==1): ?>
            <span class="badge badge-success">Yes</span>
            <?php else: ?>
            <span class="badge badge-danger">No</span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>is Paid ?</th>
        <td>
            <?php if(!empty(Helper::getTransaction($row->id,20)) && Helper::getTransaction($row->id,20)->is_completed==1): ?>
                <span class="badge badge-success">Yes</span>
            <?php elseif(!empty(Helper::getTransaction($row->id,20)) && Helper::getTransaction($row->id,20)->is_completed==null): ?>
                <span class="badge badge-danger">No</span>
            <?php else: ?>
                <span class="badge badge-warning">In Progress</span>
            <?php endif; ?>
        </td>
    </tr>
</table>
		<?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/fuel/view_event.blade.php ENDPATH**/ ?>