<table class="table table-striped">
    <tr>
      <th>Item</th>
      <td><?php echo e($row->item); ?></td>
    </tr>
    <tr>
      <th>Unit</th>
      <td><?php echo e($row->unit_details->name); ?></td>
    </tr>
      <tr>
        <th>Category</th>
        <td><?php echo e($row->manufacturer_details->name); ?></td>
      </tr>
      <tr>
        <th>Stock</th>
        <td><?php echo e($row->stock); ?></td>
      </tr>
      <tr>
        <th>Min Stock</th>
        <td><?php echo e($row->min_stock); ?></td>
      </tr>
      <tr>
        <th>Description</th>
        <td><?php echo e($row->description); ?></td>
      </tr>
      <tr>
        <th>Created On</th>
        <td><?php echo e(Helper::getCanonicalDateTime($row->description,'default')); ?></td>
      </tr>
</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/parts/view_event.blade.php ENDPATH**/ ?>