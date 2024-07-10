<table class="table table-striped" >
    
	<thead class="thead-inverse">
		<tr>
		  <td colspan="6">
			<?php echo Form::open(['method'=>'post','class'=>'form-inline']); ?>

			<input type="hidden" name="param_id" value="<?php echo e($param->id); ?>">
			<input type="hidden" name="vehicle_id" value="<?php echo e($vehicle->id); ?>">
			<input type="hidden" name="from_date" value="<?php echo e($from_date); ?>">
			<input type="hidden" name="to_date" value="<?php echo e($to_date); ?>">  
		  
			<button  type="submit" formaction="<?php echo e(url('admin/print-vehicle-head-advance-report')); ?>" formtarget="_blank" class="btn btn-danger" style="margin-left:665px;"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
			<?php echo Form::close(); ?> 
		  </td>
		</tr>
		<tr>
		  <th>SL#</th>
		  <th>Date</th>
		  <th>Vehicle</th>
		  <th>Head</th>
		  <th>Amount</th>
		</tr>
	  </thead>
	  <tbody>
		<?php $__currentLoopData = $advanceDriver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
		  <td><?php echo e($k+1); ?></td>
		  <td><?php echo e(Helper::getCanonicalDateTime($row->booking->pickup,'default')); ?></td>
		  <td><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<strong><?php echo e($vehicle->license_plate); ?></strong></td>
		  <td><?php echo e($row->param_name->label); ?></td>
		  <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->value,1,2)); ?></td>
		</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<th colspan="3"></th>
			<th>Grand Total</th>
			<th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($advanceDriver->sum('value'),1,2)); ?></th>
		</tr>
	  </tbody>
	</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/vehicleheadadvance.blade.php ENDPATH**/ ?>