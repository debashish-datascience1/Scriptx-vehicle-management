<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.incomeCategories'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
      <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <?php endif; ?>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.incomeCategories'); ?> &nbsp;
          <a href="<?php echo e(route('incomecategories.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.addNew'); ?></a>
          <button data-toggle="modal" data-target="#import" class="btn btn-warning"><?php echo app('translator')->getFromJson('fleet.import'); ?></button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.name'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.created'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->id); ?></td>
              <td><?php echo e($row->name); ?></td>
              <td><?php echo e(date($date_format_setting,strtotime($row->created_at))); ?></td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item" href="<?php echo e(url("admin/incomecategories/".$row->id."/edit")); ?>"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                  <?php echo Form::hidden("id",$row->id); ?>

                  <?php if($row->type == 'u'): ?>
                  <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"> <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                  <?php endif; ?>
                </div>
              </div>
              <?php echo Form::open(['url' => 'admin/incomecategories/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

              <?php echo Form::hidden("id",$row->id); ?>

              <?php echo Form::close(); ?>

              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th><?php echo app('translator')->getFromJson('fleet.name'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.created'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="import" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.importIncome'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/import-income','method'=>'POST','files'=>true]); ?>

        <div class="form-group">
          <?php echo Form::label('excel',__('fleet.importIncome'),['class'=>"form-label"]); ?>

          <?php echo Form::file('excel',['class'=>"form-control",'required']); ?>

        </div>
        <div class="form-group">
          <a href="<?php echo e(asset('assets/samples/income.xlsx')); ?>"><?php echo app('translator')->getFromJson('fleet.downloadSampleExcel'); ?></a>
        </div>
        <div class="form-group">
          <h6 class="text-muted"><?php echo app('translator')->getFromJson('fleet.note'); ?>:</h6>
          <ul class="text-muted">
            <li><?php echo app('translator')->getFromJson('fleet.fileTypeNote'); ?></li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" type="submit"><?php echo app('translator')->getFromJson('fleet.import'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
        <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
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
<!-- Modal -->
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/income/cats.blade.php ENDPATH**/ ?>