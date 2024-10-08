<?php if(isset($summary)): ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card card-info">
				<div class="card-header">
					<h3 class="card-title">
						<?php if(isset($all_vehicles)): ?>
							Fleet Overview Report
						<?php else: ?>
							Vehicle Overview Report
						<?php endif; ?>
					</h3>
				</div>

				<div class="card-body table-responsive">
					<?php if(isset($all_vehicles)): ?>
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

						</div>
					<?php else: ?>
					<div class="card-body table-responsive">
						<table class="table table-bordered table-striped table-hover"  id="myTable1">
							
							<tr>
								<td align="center" style="font-size:23px;">
									<strong><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></strong>
									<?php if(!empty($vehicle->driver)): ?>
										<br><span><?php echo e(ucwords(strtolower($vehicle->driver->assigned_driver->name))); ?></span>
									<?php endif; ?>
									<?php if(!empty($vehicle->driver)): ?>
										<h6><?php echo e(Helper::getCanonicalDate($from_date)); ?> - <?php echo e(Helper::getCanonicalDate($to_date)); ?></h6>
									<?php endif; ?>
								</td>
							</tr>
							
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
										</tr>
										<tr>
											<th>No. of Booking(s)</th>
											<th>Total KM</th>
											<th>Total Fuel</th>
											<th>Total Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php if($book->totalbooking!=0 && !empty($book->totalbooking)): ?>
										<tr>
											<td><?php echo e($book->totalbooking); ?> bookings</td>
											<td><?php echo e($book->totalkms); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
											<td><?php echo e($book->totalfuel); ?> <?php echo e(Hyvikk::get('fuel_unit')); ?></td>
											<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($book->totalprice); ?></td>
										</tr>
										<?php else: ?>
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
										</tr>
										<tr>
											<th>Fuel Type</th>
											<th>No. of Refuel(s)</th>
											<th>Quantity</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($fuels)): ?>
										<?php $__currentLoopData = $fuels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$fs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<tr>
											<td><?php echo e($k); ?></td>
											<td><?php echo e(count($fs->id)); ?> time(s)</td>
											<td><?php echo e(array_sum($fs->ltr)); ?> <?php echo e($k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'); ?></td>
											<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals(array_sum($fs->total))); ?></td>
										</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php else: ?>
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($advances->details)): ?>
										
										<tr>
											
											<td>
												<table class="table tabl-bordered table-striped">
													<thead>
														<th>#</th>
														<th>Head</th>
														<th>No. of Time(s)</th>
														<th>Amount</th>
													</thead>
													<tbody>
														<?php $__currentLoopData = $advances->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$det): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<tr>
															<td><?php echo e($k+1); ?></td>
															<td><?php echo e($det->label); ?></td>
															<td><?php echo e($det->times); ?></td>
															<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)); ?></td>
														</tr>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<tr>
															<th colspan="3" style="text-align:right;">Total</th>
															<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($advances->amount) ? Helper::properDecimals(array_sum($advances->amount)) : Helper::properDecimals(0)); ?></th>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										
										<?php else: ?>
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
										</tr>
										<tr>
											<th>No. of Work Order(s)</th>
											<th>GST</th>
											<th>Total</th>
											<th>No. of Vendors</th>
											<th>Status</th>
											<th>Parts Used</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($wo->count) && $wo->count!=0): ?>
										
										<tr>
											<td><?php echo e($wo->count); ?></td>
											<td>
												<table class="table table-striped">
													<tr>
														<th>CGST</th>
														<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->cgst)); ?></td>
													</tr>
													<tr>
														<th>SGST</th>
														<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->sgst)); ?></td>
													</tr>
												</table>
											</td>
											<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($wo->grand_total)); ?></td>
											<td><?php echo e($wo->vendors); ?></td>
											<td>
												<table class="table table-striped">
													<?php $__currentLoopData = $wo->status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<th><?php echo e($k); ?></th>
														<td><?php echo e(count($s)); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												</table>
											</td>
											<td>
												<table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>Part</th>
															<th>Quantity</th>
															<th>Amount</th>
														</tr>
													</thead>
													<tbody>
														<?php if(empty($partsUsed)): ?>
														<?php $__currentLoopData = $partsUsed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<tr>
															<td><?php echo e($pu->part->title); ?></td>
															<td><?php echo e($pu->qty); ?></td>
															<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($pu->total)); ?></td>
														</tr>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<?php else: ?>
														<tr>
															<td colspan="3" align='center' style="color: red">No Parts Used...</td>
														</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</td>
										</tr>
										
										<?php else: ?>
										<tr>
											<td colspan="6" align='center' style="color: red">No Records Found...</td>
										</tr>
										<?php endif; ?>
								</tbody>
							</table>
						</tr>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicles/vehicles_overview_partial.blade.php ENDPATH**/ ?>