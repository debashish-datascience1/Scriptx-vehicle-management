<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("work_order.index")); ?>"><?php echo app('translator')->getFromJson('fleet.work_orders'); ?> </a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.partsUsed'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style>
  .item-put{cursor: pointer;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
      $(".data_table").DataTable();
      $(".item-put").click(function(){
        var id = $(this).data("id");
        // console.log(id);
        $("#myModal2 .modal-body").load('<?php echo e(url("admin/work_order/itemno_get")); ?>/'+id,function(result){
          $('#myModal2').modal({show:true});
        })
      })
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        Own Parts Used
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.part'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.part_price'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $order->part_fromown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->workorder->vehicle->license_plate); ?></td>
              <td><?php echo $row->workorder->description; ?></td>
              <td>
                <?php if(!empty($row->part) && !empty($row->part->category) && $row->part->category->is_itemno==1): ?>
                  <a class="item-put" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"><strong><?php echo e(Helper::getFullPartName($row->part->id)); ?></strong></a>
                <?php else: ?>
                  <?php echo e(Helper::getFullPartName($row->part->id)); ?>

                <?php endif; ?>
              </td>
              <td><?php echo e($row->qty); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->price); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->total); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->grand_total); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        Vendor Parts Used
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.description'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.part'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.part_price'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cgst'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cgst_amt'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.sgst'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.sgst_amt'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total_cost'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $__currentLoopData = $order->parts_fromvendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->workorder->vehicle->license_plate); ?></td>
              <td><?php echo $row->workorder->description; ?></td>
              <td>
                <?php if(!empty($row->part) && !empty($row->part->category) && $row->part->category->is_itemno==1): ?>
                  <a class="item-put" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"><strong><?php echo e(Helper::getFullPartName($row->part->id)); ?></strong></a>
                <?php else: ?>
                  <?php echo e(Helper::getFullPartName($row->part->id)); ?>

                <?php endif; ?>
              </td>
              <td><?php echo e($row->qty); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->price); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->total); ?></td>
              <td><?php echo e($row->cgst); ?> %</td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->cgst_amt); ?></td>
              <td><?php echo e($row->sgst); ?> %</td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->sgst_amt); ?></td>
              <td><?php echo e(Hyvikk::get('currency')." ". $row->grand_total); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Item No.</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading..
        </div>
        
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/parts_used.blade.php ENDPATH**/ ?>