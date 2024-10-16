<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.loan_take'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.loan_take'); ?>
        &nbsp;
        <a href="<?php echo e(route('loan-take.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.create_loan_take'); ?></a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.from'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.remaining_amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $loanTakes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loanTake): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($loanTake->date); ?></td>
              <td><?php echo e($loanTake->from); ?></td>
              <td><?php echo e(number_format($loanTake->amount, 2)); ?></td>
              <td><?php echo e(number_format($loanTake->remaining_amount, 2)); ?></td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="<?php echo e(route('loan-take.show', $loanTake->id)); ?>">
                      <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.details'); ?>
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('loan-take.edit', $loanTake->id)); ?>">
                      <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?>
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('loan-take.return', $loanTake->id)); ?>">
                      <span aria-hidden="true" class="fa fa-undo" style="color: #3c8dbc;"></span> <?php echo app('translator')->getFromJson('fleet.return'); ?>
                    </a>
                    <?php echo Form::open(['url' => 'admin/loan-take/'.$loanTake->id, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'form_'.$loanTake->id]); ?>

                    <a class="dropdown-item" data-id="<?php echo e($loanTake->id); ?>" data-toggle="modal" data-target="#myModal">
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
        <?php echo e($loanTakes->links()); ?>

      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/loan_take/index.blade.php ENDPATH**/ ?>