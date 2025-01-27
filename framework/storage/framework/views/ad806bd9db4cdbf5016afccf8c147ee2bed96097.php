<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.fuel_management'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.fuel_management'); ?>
        &nbsp;
        <a href="<?php echo e(route('fuel_manage.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.add_fuel_management'); ?></a>
        <button onclick="window.print()" class="btn btn-secondary ml-2">
            <i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?>
        </button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.quantity'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.remarks'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $fuel_managements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $management): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($management->date); ?></td>
              <td>
                <?php echo e($management->vehicle->make); ?> 
                <?php echo e($management->vehicle->model); ?> 
                (<?php echo e($management->vehicle->license_plate); ?>)
              </td>
              <td><?php echo e(number_format($management->quantity, 2)); ?></td>
              <td><?php echo e($management->remark); ?></td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="<?php echo e(route('fuel_manage.edit', $management->id)); ?>">
                      <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?>
                    </a>
                    <?php echo Form::open(['url' => 'admin/fuel_manage/'.$management->id, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'form_'.$management->id]); ?>

                    <a class="dropdown-item" data-id="<?php echo e($management->id); ?>" data-toggle="modal" data-target="#myModal">
                      <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?>
                    </a>
                    <?php echo Form::close(); ?>

                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
        <?php echo e($fuel_managements->links()); ?>

      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });
</script>
<?php $__env->stopSection(); ?>

<style>
@media  print {
    body * {
        visibility: hidden;
    }
    #data_table, #data_table * {
        visibility: visible !important;
    }
    #data_table {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn, .card-header .btn, .pagination, #data_table th:last-child, #data_table td:last-child {
        display: none !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
}
</style>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/fuel_manage/index.blade.php ENDPATH**/ ?>