
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding">Numbered Parts Used<i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Bill No </th>
					<td>
						<strong><?php echo e($row->bill_no); ?></strong>
						<?php if($row->bill_image != null): ?>
							<a href="<?php echo e(asset('uploads/'.$row->bill_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
						<?php endif; ?>
					</td>
				</tr>
                <tr>
					<th>Date </th>
					<td><?php echo e(Helper::getCanonicalDate($row->required_by,'default')); ?></td>
				</tr>
				<tr>
					<th>Vehicle</th>
					<td><?php echo e($row->vehicle->make); ?>-<?php echo e($row->vehicle->model); ?>-<?php echo e($row->vehicle->license_plate); ?></td>
				</tr>
                <tr>
					<th>Vendor</th>
					<td><?php echo e($row->vendor->name); ?></td>
				</tr>
                <tr>
					<th>Price</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->price)); ?></td>
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
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->price+$row->cgst_amt+$row->sgst_amt)); ?></td>
				</tr>
                <?php endif; ?>
				<tr>
					<th>Description</th>
					<td><?php echo e($row->description); ?></td>
				</tr>
                <tr>
					<th>Meter</th>
					<td><?php echo e($row->meter); ?></td>
				</tr>
                <tr>
					<th>Note</th>
					<td><?php echo e($row->note); ?></td>
				</tr>
                <tr>
					<th>Status</th>
					<td><?php echo e($row->status); ?></td>
				</tr>
				
			</table>
		</div>
		<div class="tab-pane" id="prev-tab">
			<table class="table table-striped">
				<?php if(count($row->part_numbers)>0): ?>
					<?php $__currentLoopData = $row->part_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e(Helper::getFullPartName($item->part->id)); ?></td>
							<td><?php echo e($item->slno); ?></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php else: ?>
					<tr>
						<td class="text-center" style="color: red">No item numbers defined.. </td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/work_orders/view_event.blade.php ENDPATH**/ ?>