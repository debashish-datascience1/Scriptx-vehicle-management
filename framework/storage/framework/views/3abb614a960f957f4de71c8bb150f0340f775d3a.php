<table class="table" id="myTable">
    <thead class="thead-inverse">
      <tr>
        <th>SL#</th>
        <th>Vehicle</th>
        <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.fuelType'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
        <th>CGST</th>
        <th>SGST</th>
        <th>Total</th>
        <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e($k+1); ?></td>
        <td>
          
          <strong><?php echo e(date('d-M-Y',strtotime($row->vehicle_data['year']))); ?></strong><br>
          <a href="<?php echo e(url("admin/vehicles/".$row->vehicle_id."/edit")); ?>"  target="_blank">
          
          <?php echo e($row->vehicle_data['make']); ?>-<?php echo e($row->vehicle_data['model']); ?>

          </a>
          <br>
          <?php if($row->vehicle_data['vehicle_image'] != null): ?>
            <a href="<?php echo e(asset('uploads/'.$row->vehicle_data['vehicle_image'])); ?>" target="_blank" class="badge badge-danger"><?php echo e(strtoupper($row->vehicle_data['license_plate'])); ?></a>
          <?php else: ?>
            <a href="#" target="_blank" class="badge badge-danger"><?php echo e(strtoupper($row->vehicle_data['license_plate'])); ?></a>
          <?php endif; ?>
          <br>
        </td>
        <td>
          <strong><?php echo e(!empty(Helper::getTransaction($row->id,20)) ? Helper::getTransaction($row->id,20)->transaction_id : ''); ?></strong>
          <br>
          <?php echo e(Helper::getCanonicalDate($row->date,'default')); ?>

          <br>
          <?php echo e($row->province); ?>

        </td>
        <td>
          <?php if($row->fuel_details!=''): ?>
            <?php echo e($row->fuel_details->fuel_name); ?>

          <?php else: ?>
            <small style="color:red">specify fuel type</small>
          <?php endif; ?>
        </td>
        <td> <?php echo e($row->qty); ?> <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?> <?php echo app('translator')->getFromJson('fleet.gal'); ?> <?php else: ?> Liter <?php endif; ?> </td>
        <td>
          <?php ($total = $row->qty * $row->cost_per_unit); ?>
          <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total); ?>

          <br>
          <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->cost_per_unit); ?>/ <?php echo e(Hyvikk::get('fuel_unit')); ?>

        </td>
        <td>
          <?php if(!empty($row->cgst)): ?>
          <?php echo e($row->cgst); ?> %
          <br>
          <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->cgst_amt); ?>

          <?php endif; ?>
        </td>
        <td>
          <?php if(!empty($row->sgst)): ?>
          <?php echo e($row->sgst); ?> %
          <br>
          <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->sgst_amt); ?>

          <?php endif; ?>
        </td>
        <td>
          <?php if(!empty($row->grand_total)): ?>
          <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->grand_total); ?>

          <?php endif; ?>
        </td>
        
        
        <td>
        <div class="btn-group">
          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            <span class="fa fa-gear"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu custom" role="menu">
            <a class="dropdown-item vview" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
            <?php if(Helper::isEligible($row->id,20)): ?>
            <a class="dropdown-item" href="<?php echo e(url("admin/fuel/".$row->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
            <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
            <?php endif; ?>
          </div>
        </div>
        <?php echo Form::open(['url' => 'admin/fuel/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

        <?php echo Form::hidden("id",$row->id); ?>

        <?php echo Form::close(); ?>

        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
      <tr>
          <th>SL#</th>
          <th>Vehicle</th>
          <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
          <th><?php echo app('translator')->getFromJson('fleet.fuelType'); ?></th>
          <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
          <th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
          <th>CGST</th>
          <th>SGST</th>
          <th>Total</th>
          <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
      </tr>
    </tfoot>
  </table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/reports/global_search/fuel.blade.php ENDPATH**/ ?>