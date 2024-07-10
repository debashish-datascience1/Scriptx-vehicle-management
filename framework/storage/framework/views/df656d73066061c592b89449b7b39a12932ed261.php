<table class="table table-striped" >
    
  <thead class="thead-inverse">
      <tr>
        <td colspan="6">
          <?php echo Form::open(['method'=>'post','class'=>'form-inline']); ?>

          <input type="hidden" name="customer_id" value="<?php echo e($customer_select); ?>">
          <input type="hidden" name="vehicle_id" value="<?php echo e($vehicle_select); ?>">
          <input type="hidden" name="month" value="<?php echo e($month_select); ?>">
          <input type="hidden" name="year" value="<?php echo e($year_select); ?>">  
        
          <button  type="submit" formaction="<?php echo e(url('admin/print-booking-modal-report')); ?>" class="btn btn-danger" style="margin-left:665px;"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
          <?php echo Form::close(); ?> 
        </td>
      </tr>
      <tr>
        <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
        <th>Advance</th>
        <th>Payment Amount</th>
        <th>Total Amount</th>
        <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e($row->customer->name); ?></td>
        <td>
          <?php if($row->vehicle_id != null): ?>
          <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?>

          <?php endif; ?>
          </td>
        <td><?php if($row->advance_pay != null): ?> 
          <i class="fa fa-inr"></i> <?php echo e($row->advance_pay); ?> 
        <?php else: ?>
          <span class="badge badge-danger">N/A</span>
        <?php endif; ?></td>
        <td><?php if($row->payment_amount != null): ?>
          <i class="fa fa-inr"></i> <?php echo e($row->payment_amount); ?>

        <?php else: ?>
          <span class="badge badge-danger">N/A</span>
        <?php endif; ?></td>
        <td><?php echo e($row->total_price); ?></td>
        <td><?php if($row->status==0): ?><span style="color:orange;"><?php echo app('translator')->getFromJson('fleet.journey_not_ended'); ?> <?php else: ?> <span style="color:green;"><?php echo app('translator')->getFromJson('fleet.journey_ended'); ?> <?php endif; ?></span></td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/view_booking_details.blade.php ENDPATH**/ ?>