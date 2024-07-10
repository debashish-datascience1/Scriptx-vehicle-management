<div class="row">
	<div class="col-md-12 table-responsive">
		<table class="table">
			<thead>
				<th><?php echo app('translator')->getFromJson('fleet.expenseType'); ?></th>
				<th><?php echo app('translator')->getFromJson('fleet.expenseAmount'); ?></th>
				<th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
			</thead>
			<tbody id="hvk">
				<?php
				$i=0;
				$data = unserialize($vehicle->getMeta('purchase_info'));
				?>
				<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<?php
					$i+=$row['exp_amount'];
					?>
					<td><?php echo e($row['exp_name']); ?></td>
					<td><?php echo e(Hyvikk::get('currency')." ". $row['exp_amount']); ?></td>
					<td>
					<?php echo Form::open(['route' =>['acquisition.destroy',$vehicle->id],'method'=>'DELETE','class'=>'form-horizontal']); ?>

					<?php echo Form::hidden("vid",$vehicle->id); ?>

					<?php echo Form::hidden("key",$key); ?>

					<button type="button" class="btn btn-danger del_info" data-vehicle="<?php echo e($vehicle->id); ?>" data-key="<?php echo e($key); ?>">
					<span class="fa fa-times"></span>
					</button>
					<?php echo Form::close(); ?>

					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><strong><?php echo app('translator')->getFromJson('fleet.total'); ?></strong></td>
					<td><strong><?php echo e(Hyvikk::get('currency')." ". $i); ?></strong></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/acquisition/ajax.blade.php ENDPATH**/ ?>