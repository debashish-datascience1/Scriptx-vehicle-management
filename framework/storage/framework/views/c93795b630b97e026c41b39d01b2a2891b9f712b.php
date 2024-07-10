
		<?php $__currentLoopData = $getdet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<table class="table table-striped">
				<tr>
					<th>Transaction ID</th>
					<td><?php echo e($trans->transaction_id); ?></td>
				</tr>
                <tr>
					<th>Operation</th>
					<td>
						<?php echo e($trans->params->label); ?>

					</td>
				</tr>
                <tr>
                    
                    <th>Method</th>
                    
					<td>
						<?php echo e($income->method->label); ?>

					</td>
                    
				</tr>
				<tr>
					<th>Type</th>
					<td>
                        <?php if($trans->type==23): ?>
                            <span class="badge badge-success"><?php echo e($trans->pay_type->label); ?></span>
                        <?php elseif($trans->type==24): ?>
                            <span class="badge badge-danger"><?php echo e($trans->pay_type->label); ?></span>
                        <?php endif; ?>
					</td>
				</tr>
                <tr>
					<th>Previous</th>
					<td>
						<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($trans->prev,2,'.','')); ?>

					</td>
				</tr>
                <tr>
					<th>Total</th>
					<td>
						<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($trans->total,2,'.','')); ?>

					</td>
				</tr>
                <tr>
					<th>Grand Total</th>
					<td>
						<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($trans->grandtotal,2,'.','')); ?>

					</td>
				</tr>
				<?php if($trans->advance_for==22): ?>
				<tr>
					<th>Advance ? </th>
					<td>
						<?php echo e(Hyvikk::get('currency')); ?> <?php echo e(empty($trans->income_expense) ? 0 : number_format($trans->income_expense->amount,2,'.','')); ?>

					</td>
				</tr>
				<?php endif; ?>
                <tr>
					<th>Date</th>
					<td>
						<?php echo e(Helper::getCanonicalDateTime($income->date)); ?>

					</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td>
						<?php echo e($income->remarks); ?>

					</td>
				</tr>
			</table>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		<!--tab1-->
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/view_event.blade.php ENDPATH**/ ?>