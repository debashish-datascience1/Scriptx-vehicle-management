

		
			<table class="table table-striped">
				<tr>
					<th>Driver ID</th>
					<td><strong><?php echo e(strtoupper($driver->getMeta('emp_id'))); ?></strong></td>
				</tr>
				<tr>
					<th>Driver</th>
					<td><strong><?php echo e(ucwords($driver->name)); ?></strong></td>
				</tr>
				<tr>
					<th>License</th>
					<td><?php echo e(ucwords($driver->getMeta('license_number'))); ?></td>
				</tr>
				<tr>
					<th>Vehicle</th>
					<td>
						<?php if(!empty($vehicle)): ?>
							<?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?>

						<?php else: ?>
							<span class="badge badge-danger">N/A</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>Gender</th>
					<td>
						<?php if($driver->getMeta('gender')==1): ?>
							Male
						<?php elseif($driver->getMeta('gender')==0): ?>
							Female
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>Salary</th>
					<td><?php echo e(($driver->getMeta('salary'))); ?></td>
				</tr>
				<tr>
					<th>Phone</th>
					<td><?php echo e($driver->getMeta('phone_code')); ?> <?php echo e($driver->getMeta('phone')); ?></td>
				</tr>
				<tr>
					<th>Email</th>
					<td><?php echo e($driver->getMeta('email')); ?></td>
				</tr>
				<tr>
					<th>Address</th>
					<td><?php echo e($driver->getMeta('address')); ?></td>
				</tr>
			</table>
		<?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/drivers/view_event.blade.php ENDPATH**/ ?>