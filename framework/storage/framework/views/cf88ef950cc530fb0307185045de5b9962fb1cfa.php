<table class="table table-bordered table-striped table-hover" id="fleetOverviewTable">
							<thead>
								<tr>
									<th>Vehicle</th>
									<th>Bookings</th>
									<th>Total KM</th>
									<th>Revenue</th>
									<th>Fuel Usage</th>
									<th>Fuel Cost</th>
									<th>Maintenance</th>
									<th>Tyre Cost</th>
									<th>Net Profit</th>
								</tr>
							</thead>
							<tbody>
								<?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td>
										<?php echo e($vehicle_data['vehicle']->make); ?>-<?php echo e($vehicle_data['vehicle']->model); ?>

										<br>
										<small class="text-muted"><?php echo e($vehicle_data['vehicle']->license_plate); ?></small>
									</td>
									<td><?php echo e($vehicle_data['bookings_count']); ?></td>
									<td><?php echo e(number_format($vehicle_data['total_kms'], 2)); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($vehicle_data['total_revenue'], 2)); ?></td>
									<td><?php echo e(number_format($vehicle_data['fuel_qty'], 2)); ?> <?php echo e(Hyvikk::get('fuel_unit')); ?></td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($vehicle_data['fuel_cost'], 2)); ?></td>
									<td>
										<?php echo e($vehicle_data['work_orders']); ?> orders
										<br>
										<small class="text-muted"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($vehicle_data['maintenance_cost'], 2)); ?></small>
									</td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($vehicle_data['tyre_cost'], 2)); ?></td>
									<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($vehicle_data['net_profit'], 2)); ?></td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
							<tfoot>
								<tr class="table-info">
									<th>Total</th>
									<th><?php echo e(collect($summary)->sum('bookings_count')); ?></th>
									<th><?php echo e(number_format(collect($summary)->sum('total_kms'), 2)); ?> <?php echo e(Hyvikk::get('dis_format')); ?></th>
									<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(collect($summary)->sum('total_revenue'), 2)); ?></th>
									<th><?php echo e(number_format(collect($summary)->sum('fuel_qty'), 2)); ?> <?php echo e(Hyvikk::get('fuel_unit')); ?></th>
									<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(collect($summary)->sum('fuel_cost'), 2)); ?></th>
									<th><?php echo e(collect($summary)->sum('work_orders')); ?> orders</th>
									<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(collect($summary)->sum('tyre_cost'), 2)); ?></th>
									<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format(collect($summary)->sum('net_profit'), 2)); ?></th>
								</tr>
							</tfoot>
						</table>
						<div class="mt-4">
							<?php echo e($pagination->appends(request()->except('page'))->links()); ?>

						</div><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicles/report-partial.blade.php ENDPATH**/ ?>