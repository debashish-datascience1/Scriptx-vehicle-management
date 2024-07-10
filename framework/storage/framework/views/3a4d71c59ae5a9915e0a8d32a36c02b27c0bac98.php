
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding"> Attendance History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver Name</th>
					<td><?php echo e($leave->driver->name); ?></td>
				</tr>

				<tr>
					<th>Date</th>
					<td>
						<?php echo e(Helper::getCanonicalDate($leave->date)); ?>

					</td>
				</tr>

				<tr>
					<th>Status</th>
					<td>
						<?php if($leave->is_present==1): ?>
                            <span class="badge badge-success">Present</span>
                        <?php elseif($leave->is_present==2): ?>
                            <span class="badge badge-danger">Absent</span>
						<?php elseif($leave->is_present==3): ?>
							<span class="badge badge-info">1st Half Leave</span>
						<?php elseif($leave->is_present==4): ?>
							<span class="badge badge-primary">2nd Half Leave</span>
                        <?php elseif($leave->is_present==null): ?>
                            <span class="badge badge-warning">N/A</span>
                        <?php endif; ?>
					</td>
				</tr>

				<tr>
					<th>Remarks</th>
					<td>
						<?php echo e($leave->remarks!="" ? $leave->remarks : "N/A"); ?>

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
					<th>Status</th>
                    <th>Remarks</th>
				</tr>
                <?php $__currentLoopData = $historys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th><?php echo e(Helper::getCanonicalDate($hist->date)); ?></th>
                        <td>
                            <?php if($hist->is_present==1): ?>
                                <span class="badge badge-success">Present</span>
                            <?php elseif($hist->is_present==2): ?>
                                <span class="badge badge-danger">Absent</span>
                            <?php elseif($hist->is_present==null): ?>
                                <span class="badge badge-warning">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($hist->remarks); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			
		</div>
</div><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/leaves/view_event.blade.php ENDPATH**/ ?>