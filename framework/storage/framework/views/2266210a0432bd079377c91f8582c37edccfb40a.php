<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.user_report'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.user_report'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.users','method'=>'post','class'=>'form-inline']); ?>

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
            <?php echo Form::label('user', __('fleet.users'), ['class' => 'form-label']); ?>

            <select id="user_id" name="user_id" class="form-control" required>
              <option value=""><?php echo app('translator')->getFromJson('fleet.selectUser'); ?></option>
              <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($user->id); ?>" <?php if($user['id']==$user_id): ?> selected <?php endif; ?>><?php echo e($user->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-users-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php if(isset($result)): ?>
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
              <th><?php echo app('translator')->getFromJson('fleet.book_by'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.journey_status'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
            </tr>
          </thead>

          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->user->name); ?></td>
              <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->pickup_addr); ?></td>
              <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->dest_addr); ?></td>
              <td>
                <?php if($row->pickup != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->pickup))); ?>

                <?php endif; ?>
              </td>
              <td>
                <?php if($row->dropoff != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->dropoff))); ?>

                <?php endif; ?>
              </td>
              <td>
                <?php if($row->status == 1): ?>
                  <span class="text-success">
                  <?php echo app('translator')->getFromJson('fleet.completed'); ?>
                  </span>
                <?php else: ?>
                  <span class="text-warning">
                  <?php echo app('translator')->getFromJson('fleet.not_completed'); ?>
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <?php if($row->receipt == 1): ?>
                <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(($row->tax_total) ? $row->tax_total : $row->total); ?>

                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.book_by'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.journey_status'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#user_id").select2();
	});
</script>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/users.blade.php ENDPATH**/ ?>