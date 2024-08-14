<table class="table table-responsive display" id="myTable" style="padding-bottom: 35px; width: 100%">
    <thead class="thead-inverse">
      <tr>
        <th>SL#</th>
        <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
        <th>Pickup Address</th>
        <th>Dropoff Address</th>
        <th>Pickup Date</th>
        <th>Dropoff Date</th>
        <th>Advance to Driver</th>
        <th><?php echo app('translator')->getFromJson('fleet.booking_status'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
      </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <tr>
          <td><?php echo e($k+1); ?></td>
          <td><?php echo e($row->customer->name); ?></td>
          <td>
            <?php if($row->vehicle_id): ?>
            <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?>

            <?php endif; ?>
          </td>
          <td><?php echo str_replace(",", ",<br>", $row->pickup_addr); ?></td>
          <td><?php echo str_replace(",", ",<br>", $row->dest_addr); ?></td>
          <td><?php echo !empty($row->pickup) ? Helper::str_replace_first(" ","<br>",Helper::getCanonicalDateTime($row->pickup,'default')) : '-'; ?></td>
          <td><?php echo !empty($row->dropoff) ? Helper::str_replace_first(" ","<br>",Helper::getCanonicalDateTime($row->dropoff,'default')) : '-'; ?></td>
          <td>
            <?php if($row->advance_pay != null): ?>
              <i class="fa fa-inr"></i> <?php echo e($row->advance_pay); ?>

            <?php else: ?>
              <span class="badge badge-danger">N/A</span>
            <?php endif; ?>
          </td>
          <td>
            <strong><?php echo e($row->invoice_id); ?></strong><br>
            <?php if($row->ride_status!='Completed'): ?>
              <span class="text-warning"><?php echo e($row->ride_status); ?></span>
            <?php else: ?>
              <span class="text-success"><?php echo e($row->ride_status); ?></span>
            <?php endif; ?>
          </td>
          <td style="width: 10% !important">
          <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="fa fa-gear"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu custom" role="menu">
                      <a class="dropdown-item vbook" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"  style="cursor:pointer;"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.viewBookingDetails'); ?></a>
                      <a href="print_booking_new/<?php echo e($row->id); ?>" class="dropdown-item" data-id="<?php echo e($row->id); ?>" style="cursor:pointer;" target="_blank"> <span aria-hidden="true" class="fa fa-print" style="color: #1114b4;"></span> Print</a>
                      
                      <?php if($row->ride_status == 'Completed'): ?>
                        <a class="dropdown-item" href="<?php echo e(url('admin/bookings/'.$row->id.'/edit')); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                        <a class="dropdown-item vRoute" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#modalRoute" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-plus" aria-hidden="true" style="color: #0d9c00"></span> Add Route</a>
                      <?php else: ?>
                        <?php if($row->status==0 && $row->ride_status != "Cancelled" && !empty($row->transid) && $row->inc_rows<2 && Helper::isEligible($row->id,18)): ?>
                          <a class="dropdown-item" href="<?php echo e(url('admin/bookings/'.$row->id.'/edit')); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                          <a class="dropdown-item vtype" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal" style="cursor:pointer;"> <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39;"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                          <a class="dropdown-item vDriverAdvanceLater" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#modalDriverAdvanceLater" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-inr" aria-hidden="true" style="color: #0d9c00"></span> Late Driver Advance</a>
                          <?php if($row->receipt != 1): ?>
                            
                          <?php endif; ?>
                        <?php endif; ?>
                        <?php if($row->vehicle_id != null): ?>
                          <?php if($row->status==0 && $row->receipt != 1): ?>
                            <?php if(Auth::user()->user_type != "C" && $row->ride_status != "Cancelled"): ?>
                              <a class="dropdown-item vcomplete" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#modalComplete" data-backdrop='static' data-keyboard='false' style="cursor:pointer;"> <span class="fa fa-check" aria-hidden="true" style="color: #0d9c00;"></span> Mark as Complete</a>
                              <a class="dropdown-item vRoute" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#modalRoute" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-plus" aria-hidden="true" style="color: #0d9c00"></span> Add Route</a>
                            <?php endif; ?>
                          <?php elseif($row->receipt == 1): ?>
                            
                            
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </div>
          <?php echo Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]); ?>

          <?php echo Form::hidden("id",$row->id); ?>

          <?php echo Form::close(); ?>

          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
      <tfoot>
        <tr>
            <th>SL#</th>
            <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
            <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
            <th>Pickup Address</th>
            <th>Dropoff Address</th>
            <th>Pickup Date</th>
            <th>Dropoff Date</th>
            <th>Advance to Driver</th>
            <th><?php echo app('translator')->getFromJson('fleet.booking_status'); ?></th>
            <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
        </tr>
      </tfoot>
</table><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/global_search/bookings.blade.php ENDPATH**/ ?>