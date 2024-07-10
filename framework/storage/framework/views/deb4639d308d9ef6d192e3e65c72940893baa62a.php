
<div role="tabpanel">
    <ul class="nav nav-pills" style="margin-bottom: 10px;">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#address-tab" data-toggle="tab" class="nav-link custom_padding"> <?php echo app('translator')->getFromJson('fleet.vehicleDocs'); ?> <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#acq-tab" data-toggle="tab" class="nav-link custom_padding"> <?php echo app('translator')->getFromJson('fleet.purchase_info'); ?> <i class="fa"></i></a>
        </li>

        
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
					<td><?php echo e($vehicle->make); ?></td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
					<td>
						<?php echo e($vehicle->model); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.type'); ?></th>
					<td>
						<?php echo e($vehicle->types['displayname']); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.year'); ?></th>
					<td>
						<?php echo e($vehicle->year); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.average'); ?> (<?php echo app('translator')->getFromJson('fleet.mpg'); ?>)</th>
					<td>
						<?php echo e($vehicle->average); ?> km/L
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.average'); ?> (<?php echo app('translator')->getFromJson('fleet.tpl'); ?>)</th>
					<td>
						<?php echo e($vehicle->time_average); ?> hour(s)/L
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.intMileage'); ?> (<?php echo e(Hyvikk::get('dis_format')); ?>)</th>
					<td>
						<?php echo e($vehicle->int_mileage); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.vehicleImage'); ?></th>
					<td>
						<?php if($vehicle->vehicle_image != null): ?>
			            <a href="<?php echo e(asset('uploads/'.$vehicle->vehicle_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
			            <?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.engine'); ?></th>
					<td>
						<?php echo e($vehicle->engine_type); ?>

					</td>
				</tr>

				

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.color'); ?></th>
					<td>
						<?php echo e($vehicle->color); ?>

					</td>
				</tr>

				

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.engine_no'); ?></th>
					<td>
						<?php echo e($vehicle->engine_no); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.chassis_no'); ?></th>
					<td>
						<?php echo e($vehicle->chassis_no); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.licenseNo'); ?></th>
					<td>
						<?php echo e($vehicle->license_plate); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.lic_exp_date'); ?></th>
					<td>
						<?php if($vehicle->lic_exp_date): ?>
						<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->lic_exp_date))); ?>

						<?php endif; ?>
					</td>
				</tr>

				

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.assigned_driver'); ?></th>
					<td>
						<?php if($vehicle->getMeta('driver_id')): ?>
							<?php echo e(ucwords($vehicle->driver->assigned_driver->name)); ?>

						<?php else: ?>
							<span class="badge badge-warning"><?php echo app('translator')->getFromJson('fleet.notAssigned'); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>Vehicle Owner</th>
					<td>
						<?php if($vehicle->getMeta('owner_name')): ?>
							<?php echo e(ucwords($vehicle->owner_name)); ?>

						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>Owner Phone Number</th>
					<td>
						<?php if($vehicle->getMeta('owner_name')): ?>
							<?php echo e(ucwords($vehicle->owner_number)); ?>

						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>RC Number</th>
					<td>
						<?php if($vehicle->getMeta('owner_name')): ?>
							<?php echo e(ucwords($vehicle->rc_number)); ?>

						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>RC Image</th>
					<td>
						<?php if($vehicle->rc_image != null): ?>
			            <a href="<?php echo e(asset('uploads/'.$vehicle->rc_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
			            <?php endif; ?>
					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<div class="tab-pane" id="address-tab" >
			<table class="table table-striped">
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
					<td>
					<?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->types['displayname']); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.insuranceNumber'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('ins_number')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.inc_doc'); ?></th>
					<td>
					<?php if($vehicle->getMeta('documents') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('documents'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.inc_expirationDate'); ?></th>
					<td>
						<?php if($vehicle->getMeta('ins_exp_date')): ?>
							<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('ins_exp_date')))); ?>

						<?php endif; ?>
					</td>
				</tr>
				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.fitnessTax'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('fitness_tax')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.fitnessDocuments'); ?></th>
					<td>
					<?php if($vehicle->getMeta('fitness_taxdocs') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('fitness_taxdocs'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.fitnessExpirationDate'); ?></th>
					<td>
						<?php if($vehicle->getMeta('fitness_expdate')): ?>
							<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('fitness_expdate')))); ?>

						<?php endif; ?>
					</td>
				</tr>
				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.roadTax'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('road_tax')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.roadTaxDocuments'); ?></th>
					<td>
					<?php if($vehicle->getMeta('road_docs') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('road_docs'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.roadTaxExpDate'); ?></th>
					<td>
						<?php if($vehicle->getMeta('road_expdate')): ?>
							<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('road_expdate')))); ?>

						<?php endif; ?>
					</td>
				</tr>

				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.permitNumber'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('permit_number')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.permitDocuments'); ?></th>
					<td>
					<?php if($vehicle->getMeta('permit_docs') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('permit_docs'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.permitExpDate'); ?></th>
					<td>
						<?php if($vehicle->getMeta('permit_expdate')): ?>
							<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('permit_expdate')))); ?>

						<?php endif; ?>
					</td>
				</tr>
				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.pollutionNumber'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('pollution_tax')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.pollutionDocuments'); ?></th>
					<td>
					<?php if($vehicle->getMeta('pollution_docs') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('pollution_docs'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.pollutionExpDate'); ?></th>
					<td>
						<?php if($vehicle->getMeta('pollution_expdate')): ?>
							<?php echo e(date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('pollution_expdate')))); ?>

						<?php endif; ?>
					</td>
				</tr>
				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.fastTagNumber'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('fast_tag')); ?>

					</td>
				</tr>

				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.fastTagDoc'); ?></th>
					<td>
					<?php if($vehicle->getMeta('fasttag_docs') != null): ?>
					<a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('fasttag_docs'))); ?>" target="_blank">
					View
					</a>
					<?php endif; ?>
					</td>
				</tr>

				
				<tr>
					<th><?php echo app('translator')->getFromJson('fleet.gpsNumber'); ?></th>
					<td>
					<?php echo e($vehicle->getMeta('gps_number')); ?>

					</td>
				</tr>
			</table>
			
		</div>
		<!--tab2-->

		
		<div class="tab-pane " id="acq-tab" >
			<table class="table table-striped">
				<?php if(!empty($purch_info)): ?>
				<tr>
					<th>Vehicle</th>
					<td><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></td>
				</tr>
				<tr>
					<th>Purchase Date</th>
					<td><?php echo e(Helper::getCanonicalDate($purch_info->purchase_date)); ?></td>
				</tr>
				<tr>
					<th>Loan Date</th>
					<td><?php echo e(Helper::getCanonicalDate($purch_info->loan_date)); ?></td>
				</tr>
				<tr>
					<th>Loan Account</th>
					<td><?php echo e($purch_info->loan_account); ?></td>
				</tr>
				<tr>
					<th>Vehicle Cost</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purch_info->vehicle_cost)); ?></td>
				</tr>
				<tr>
					<th>Loan Amount</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purch_info->loan_amount)); ?></td>
				</tr>
				<tr>
					<th>Down Payment</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purch_info->amount_paid)); ?></td>
				</tr>
				<tr>
					<th>Bank</th>
					<td><strong><?php echo e($purch_info->bank_name); ?></strong></td>
				</tr>
				<tr>
					<th>EMI Amount</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purch_info->emi_amount)); ?></td>
				</tr>
				<tr>
					<th>EMI Date</th>
					<td><?php echo e(Helper::getCanonicalDate($purch_info->emi_date)); ?></td>
				</tr>
				<tr>
					<th>Loan Duration</th>
					<td><?php echo e($purch_info->loan_duration); ?></td>
				</tr>
				<tr>
					<th>GST Amount</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($purch_info->gst_amount)); ?></td>
				</tr>
				<?php else: ?>
				<tr>
					<th class="text-center" style="text-align:left">No Purchase details found...</th>
				</tr>
				<?php endif; ?>
			</table>
		</div>

		<!--tab3-->

		<!-- tab4 -->
		<div class="tab-pane " id="reviews" >
			<div class="card card-default">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
						<?php $__currentLoopData = $vehicle->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<a href="<?php echo e(url('admin/view-vehicle-review/'.$r->id)); ?>" class="btn btn-success" style="margin-bottom: 5px;" title="View Review"><?php echo app('translator')->getFromJson('fleet.reg_no'); ?>: <?php echo e($r->reg_no); ?></a>
							&nbsp; <a href="<?php echo e(url('admin/print-vehicle-review/'.$r->id)); ?>" class="btn btn-danger" target="_blank" title="<?php echo app('translator')->getFromJson('fleet.print'); ?>" style="margin-bottom: 5px;"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></a>
							<br>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- tab4 -->
	</div>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/vehicles/view_event.blade.php ENDPATH**/ ?>