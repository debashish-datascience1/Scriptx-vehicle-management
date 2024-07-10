
<div role="tabpanel">
	
	<?php if($adv->adjust_advance): ?>
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active gen"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>
        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding adjustments"> Adjustment <i class="fa"></i></a>
        </li>
    </ul>
	<?php endif; ?>
	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver</th>
					<td><?php echo e($adv->driver->name); ?></td>
				</tr>
				<tr>
					<th>Bank</th>
					<td><?php echo e($adv->bank_details->bank); ?>(<?php echo e($adv->bank_details->account_no); ?>)</td>
				</tr>
				<tr>
					<th>Method</th>
					<td><?php echo e($adv->method_param->label); ?></td>
				</tr>
				<tr>
					<th>Amount</th>
					<td><?php echo e(bcdiv($adv->amount,1,2)); ?></td>
				</tr>
				<tr>
					<th>Date</th>
					<td><?php echo e(Helper::getCanonicalDate($adv->date,'default')); ?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						<?php if($adv->is_adjusted==1): ?>
							<span class="badge badge-success">Completed</span>
						<?php elseif($adv->is_adjusted==2): ?>
							<span class="badge badge-primary">In Progress</span>
						<?php elseif($adv->is_adjusted==null): ?>
							<span class="badge badge-danger">Not Yet Done</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td><?php echo e($adv->remarks); ?></td>
				</tr>
				<tr>
					<th>Created On</th>
					<td><?php echo e(Helper::getCanonicalDateTime($adv->created_at,'default')); ?></td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<?php if($adv->adjust_advance): ?>
		<div class="tab-pane" id="prev-tab" >
			<table class="table table-striped">
				<tr>
					<th>Head</th>
					<th>Amount</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Bank</th>
                    <th>Ref. No</th>
                    <th>Date</th>
                    <th>Remarks</th>
				</tr>
                <?php $__currentLoopData = $adv->adjust_advance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($adj->head); ?></td>
                        <td><?php echo e(bcdiv($adj->amount,1,2)); ?></td>
                        <td><?php echo e($adj->method_param->label); ?></td>
                        <td><?php echo e($adj->payment_type->label); ?></td>
                        <td>
							<?php if(!empty($adj->bank_details)): ?>
							<?php echo e($adj->bank_details->bank); ?><br>
							<?php echo e($adj->bank_details->account_no); ?>

							<?php endif; ?>
						</td>
						<td><?php echo e($adj->ref_no); ?></td>
						<td><?php echo e(Helper::getCanonicalDate($adj->date,'default')); ?></td>
						<td><?php echo e($adj->remarks); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			
		</div>
		<?php endif; ?>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/other_advance/view_event.blade.php ENDPATH**/ ?>