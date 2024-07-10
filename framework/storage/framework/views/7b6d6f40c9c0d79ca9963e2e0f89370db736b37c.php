<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("vehicles.index")); ?>"><?php echo app('translator')->getFromJson('fleet.vehicles'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.driver_logs'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.driver_logs'); ?></h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="driver_logs">
          <thead class="thead-inverse">
            <tr>
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.driver'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_on'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->id); ?></td>
              <td><?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?></td>
              <td><?php echo e($row->driver->name); ?></td>
              <td><?php echo e(date($date_format_setting.' g:i A',strtotime($row->date))); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.driver'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.assigned_on'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
$(document).ready(function() {
  $('#driver_logs tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
  });

  var table = $('#driver_logs').DataTable({
    "language": {
        "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
     },
     columnDefs: [ { orderable: false, targets: [0] } ],
     // individual column search
     "initComplete": function() {
              table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                  // console.log($(this).parent().index());
                    that.search(this.value).draw();
                });
              });
            }
  });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/vehicles/driver_logs.blade.php ENDPATH**/ ?>