<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Part(s)</th>
      <th>Unit Cost</th>
      <th>Quantity</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td><?php echo e($dat->parts_zero->item); ?> <?php echo e($dat->parts_zero->category->name); ?> (<?php echo e($dat->parts_zero->manufacturer_details->name); ?>)</td>
      <td><?php echo e(Hyvikk::get('currency')." ". $dat->unit_cost); ?></td>
      <td><?php echo e($dat->quantity); ?></td>
      <td><?php echo e(Hyvikk::get('currency')." ". $dat->total); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <th colspan="2"></th>
      <th>Total</th>
      <th><?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->sub_total)); ?></th>
    </tr>
    <?php if($row->is_gst==1 && !empty($row->cgst) && !empty($row->sgst)): ?>
    <tr style="font-size: 14px;font-weight: 600;">
      <td style="text-align: center;">CGST <br> SGST</td>
      <td><?php echo e($row->cgst); ?> % <br> <?php echo e($row->sgst); ?> %</td>
      <td><?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->cgst_amt)); ?> <br> <?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->sgst_amt)); ?></td>
      <td style="vertical-align: middle;font-size: 16px;">
      <?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->cgst_amt + $row->sgst_amt)); ?>

      </td>
    <tr>
      <th colspan="2"></th>
      <th>Grand Total</th>
      <th><?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($row->grand_total)); ?></th>
    </tr>
    <?php endif; ?>
  </tbody>
</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/parts_invoice/view_event.blade.php ENDPATH**/ ?>