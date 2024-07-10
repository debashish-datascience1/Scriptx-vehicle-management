<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.expense'); ?> <?php echo app('translator')->getFromJson('fleet.report'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.expense'); ?> <?php echo app('translator')->getFromJson('fleet.report'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['url' => 'admin/reports/expense','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('year', __('fleet.year1'), ['class' => 'form-label']); ?>

            <?php echo Form::select('year', $years, $year_select,['class'=>'form-control']);; ?>

          </div>

          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('month', __('fleet.month'), ['class' => 'form-label']); ?>

            <?php echo Form::selectMonth('month',$month_select,['class'=>'form-control']);; ?>

          </div>

          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

            <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 250px">
              <option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
              <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vehicle['id']); ?>" <?php if($vehicle['id']==$vehicle_id): ?> selected <?php endif; ?>><?php echo e($vehicle['make']); ?>-<?php echo e($vehicle['model']); ?>-<?php echo e($vehicle['license_plate']); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-expense')); ?>" class="btn btn-danger" style="margin-right: 10px"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.report'); ?>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover"  id="myTable">
          <thead>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licensePlate'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.expenseType'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.note'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->vehicle->make); ?></td>
              <td><?php echo e($row->vehicle->model); ?></td>
              <td><?php echo e($row->vehicle->license_plate); ?></td>
              <td>
              <?php if($row->type == "s"): ?>
              <?php echo e($row->service->description); ?>

              <?php else: ?>
              <?php echo e($row->category->name); ?>

              <?php endif; ?>
              </td>
              <td><?php echo e(date($date_format_setting,strtotime($row->date))); ?></td>
              <td>
              <?php echo e(Hyvikk::get('currency')); ?>

              <?php echo e($row->amount); ?></td>
              <td><?php echo e($row->comment); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licensePlate'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.expenseType'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.note'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $("#vehicle_id").select2();
		$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
          dom: 'Bfrtip',
          buttons: [{
               extend: 'collection',
                  text: 'Export',
                  buttons: [
                      'copy',
                      'excel',
                      'csv',
                      'pdf',
                  ]}
          ],
          "language": {
                   "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
                },
          // individual column search
          "initComplete": function() {
                  myTable.columns().every(function () {
                    var that = this;
                    $('input', this.footer()).on('keyup change', function () {
                        that.search(this.value).draw();
                    });
                  });
                }
      });
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/expense.blade.php ENDPATH**/ ?>