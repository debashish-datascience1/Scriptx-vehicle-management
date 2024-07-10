<table class="table table-striped">
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
		<td><?php echo e($booking->customer->name); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
		<?php if($booking->vehicle_id != null): ?>
		<td><?php echo e($booking->vehicle->make); ?> - <?php echo e($booking->vehicle->model); ?> - <?php echo e($booking->vehicle->license_plate); ?></td>
		<?php endif; ?>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.travellers'); ?></th>
		<td><?php echo e($booking->travellers); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.note'); ?></th>
		<td><?php echo e($booking->note); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
		<td><?php echo e(date(Hyvikk::get('date_format').' g:i A',strtotime($booking->pickup))); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
		<td><?php echo e(date(Hyvikk::get('date_format').' g:i A',strtotime($booking->dropoff))); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
		<td><?php echo e($booking->pickup_addr); ?></td>
	</tr>
	<tr>
		<th><?php echo app('translator')->getFromJson('fleet.dest_addr'); ?></th>
		<td><?php echo e($booking->dest_addr); ?></td>
	</tr>
</table><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bookings/event.blade.php ENDPATH**/ ?>