
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active advgeneral"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>
     <?php if(count($advanced)>0): ?>
        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding advbook">Booking Advances <i class="fa"></i></a>
        </li>
     <?php endif; ?>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver</th>
					<td>
						
						<?php if(!empty($payroll->user_id)): ?>
							<?php echo e($payroll->driver->name); ?>

						<?php else: ?>
							<span class="badge badge-danger">No Driver Found</span>
						<?php endif; ?>
					</td>
					<th>Vehicle</th>
					<td>
						<?php if(!empty($payroll->driver_vehicle)): ?>
							<?php echo e($payroll->driver_vehicle->vehicle->make); ?>-<?php echo e($payroll->driver_vehicle->vehicle->model); ?>-<?php echo e($payroll->driver_vehicle->vehicle->license_plate); ?>

						<?php else: ?>
							<span class="badge badge-danger">No Vehicle Assigned</span>
						<?php endif; ?>
						
					</td>
				</tr>
                <tr>
					<th>For Month</th>
					<td>
						<?php $month = $payroll->for_month<10 ? "0".$payroll->for_month:$payroll->for_month;  ?>
                        <?php echo e(date("F-Y",strtotime($payroll->for_year."-".$month."-01"))); ?>

					</td>
					<th>Working Days</th>
					<td>
						<?php echo e($payroll->working_days!='' ? $payroll->working_days :'0'); ?> days
					</td>
				</tr>
                <tr>
					<th>Monthly Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->salary,1,2)); ?></td>
					<th>Absent Days</th>
					<td>
						<?php echo e($payroll->absent_days!='' ? $payroll->absent_days :'0'); ?> days
					</td>
				</tr>
				<tr>
					<th>Total Payable Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->total_payable_salary,1,2)); ?></td>
					<th>Carried Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->carried_salary,1,2)); ?></td>
				</tr>
				<tr>
					<th>Paid Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->payable_salary,1,2)); ?></td>
					<th>Remaining Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->remaining_salary,1,2)); ?></td>
				</tr>
				<tr>
					<th>Salary Advance</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->advance_salary,1,2)); ?></td>
					<th>Booking Advance</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->advance_driver,1,2)); ?></td>
				</tr>
				<tr>
					<th>Deducted Salary</th>
					<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->deduct_salary,1,2)); ?></td>
					<th>Remarks</th>
					<td>
						<?php echo e($payroll->payroll_remarks); ?>

					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

        <?php if(!empty($advanced[0])): ?>
		<!--tab2-->
		<div class="tab-pane" id="prev-tab" >
            

            <table class="table table-striped">
			<?php if(isset($advanced) || !empty($advanced)): ?>
				<?php $__currentLoopData = $advheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<th><?php echo e($val->label); ?></th>
						<?php $__currentLoopData = $advanced; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php $__currentLoopData = $advance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($adv->param_id==$val->id): ?>
									<td><?php echo e($adv->value); ?></td>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php else: ?>
				<tr>
					<th colspan="2" style="color: red;">No advances were given..</th>
				</tr>
			<?php endif; ?>
            </table>
		</div>
		<?php else: ?>
		<div class="tab-pane" id="prev-tab" >
			<table>
				<tr>
					<td colspan="2" style="color: red;"><i>No <strong>Advances</strong> were given to driver during bookings....</i></td>
				</tr>
			</table>
		</div>
        <?php endif; ?>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/payroll/view_event.blade.php ENDPATH**/ ?>