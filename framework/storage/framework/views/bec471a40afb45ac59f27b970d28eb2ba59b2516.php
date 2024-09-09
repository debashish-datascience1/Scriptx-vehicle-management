
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding advgeneral active"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link advsalary custom_padding"> Advance History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver Name</th>
					<td><?php echo e($advance->driver->name); ?></td>
				</tr>

				<tr>
					<th>Date</th>
					<td>
						<?php echo e(Helper::getCanonicalDate($advance->date,'default')); ?>

					</td>
				</tr>

                <tr>
					<th>Amount <span class="fa fa-inr"></span></th>
					<td>
						<?php echo e(Hyvikk::get('currency')); ?> <?php echo e($advance->amount); ?>

					</td>
				</tr>

				<tr>
					<th>Payroll </th>
					<td>
						<?php if($advance->payroll_check==1): ?>
                            <span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
                        <?php else: ?>
                            <span class="badge badge-danger"><i class="fa fa-times"></i> Not Adjusted</span>
                        <?php endif; ?>
					</td>
				</tr>

				<tr>
					<th>Remarks</th>
					<td>
						<?php echo e($advance->remarks!="" ? $advance->remarks : "N/A"); ?>

					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<div class="tab-pane" id="prev-tab" >
			<table class="table table-striped">
				<tr>
					<th>Date</th>
					<th>Amount</th>
					<th>Payroll</th>
                    <th>Remarks</th>
				</tr>
                <?php $__currentLoopData = $historys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th><?php echo e(Helper::getCanonicalDate($hist->date,'default')); ?></th>
                        <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($hist->amount,1,2)); ?></th>
                        <td>
                            <?php if($hist->payroll_check==1): ?>
                                <span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><i class="fa fa-times"></i> Not Adjusted</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($hist->remarks!="" ? $hist->remarks : "N/A"); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			
		</div>
</div><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/daily_advance/view_event.blade.php ENDPATH**/ ?>