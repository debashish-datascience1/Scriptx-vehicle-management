<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">All Parts Sold</h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.sellTo'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.details'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction_id => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($event->first()->sell_to); ?></td>
                <td><?php echo e($event->first()->date_of_sell); ?></td>
                <td>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th><?php echo app('translator')->getFromJson('fleet.item'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.quantity'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.selltyreNumbers'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($items[$row->item] ?? $row->item); ?></td>
                          <td><?php echo e($row->quantity); ?></td>
                          <td><?php echo e(Hyvikk::get('currency') . " " . $row->amount); ?></td>
                          <td><?php echo e(Hyvikk::get('currency') . " " . $row->total); ?></td>
                          <td>
                            <?php
                              $tyres = $row->tyre_numbers;
                              if (!empty($tyres)) {
                                  $numbers_array = explode(',', $tyres);
                                  $formatted_numbers = [];

                                  foreach (array_chunk($numbers_array, 4) as $chunk) {
                                      $formatted_numbers[] = implode(', ', $chunk);
                                  }

                                  $output = nl2br(implode("\n", $formatted_numbers));
                              } else {
                                  $output = 'N/A';
                              }

                              echo $output;
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </td>
                <td><?php echo e(Hyvikk::get('currency') . " " . $event->sum('total')); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $(document).ready(function() {
    window.print();
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/parts_sell/print_all.blade.php ENDPATH**/ ?>