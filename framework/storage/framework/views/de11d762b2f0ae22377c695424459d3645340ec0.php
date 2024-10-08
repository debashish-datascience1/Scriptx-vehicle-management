<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-striped table-hover" id="myTable1">
      <tr>
        <td align="center" style="font-size:23px;">
          <strong><?php echo e($vehicleData['vehicle']->make); ?>-<?php echo e($vehicleData['vehicle']->model); ?>-<?php echo e($vehicleData['vehicle']->license_plate); ?></strong>
          <?php if(!empty($vehicleData['vehicle']->driver)): ?>
            <br><span><?php echo e(ucwords(strtolower($vehicleData['vehicle']->driver->assigned_driver->name))); ?></span>
          <?php endif; ?>
        </td>
      </tr>
      
      <!-- Bookings -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
            </tr>
            <tr>
              <th>No. of Booking(s)</th>
              <th>Total KM</th>
              <th>Total Fuel</th>
              <th>Total Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if($vehicleData['bookings']->totalbooking != 0): ?>
              <tr>
                <td><?php echo e($vehicleData['bookings']->totalbooking); ?> bookings</td>
                <td><?php echo e($vehicleData['bookings']->totalkms); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
                <td><?php echo e($vehicleData['bookings']->totalfuel); ?> <?php echo e(Hyvikk::get('fuel_unit')); ?></td>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($vehicleData['bookings']->totalprice); ?></td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </tr>
      
      <!-- Fuel -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
            </tr>
            <tr>
              <th>Fuel Type</th>
              <th>No. of Refuel(s)</th>
              <th>Quantity</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($vehicleData['fuels'])): ?>
              <?php $__currentLoopData = $vehicleData['fuels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$fs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($k); ?></td>
                  <td><?php echo e(count($fs->id)); ?> time(s)</td>
                  <td><?php echo e(array_sum($fs->ltr)); ?> <?php echo e($k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'); ?></td>
                  <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals(array_sum($fs->total))); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </tr>
      
      <!-- Driver Advance -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($vehicleData['advances']->details)): ?>
              <tr>
                <td>
                  <table class="table tabl-bordered table-striped">
                    <thead>
                      <th>#</th>
                      <th>Head</th>
                      <th>No. of Time(s)</th>
                      <th>Amount</th>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $vehicleData['advances']->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$det): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($k+1); ?></td>
                          <td><?php echo e($det->label); ?></td>
                          <td><?php echo e($det->times); ?></td>
                          <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)); ?></td>
                        </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <th colspan="3" style="text-align:right;">Total</th>
                        <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(!empty($vehicleData['advances']->amount) ? Helper::properDecimals(array_sum($vehicleData['advances']->amount)) : Helper::properDecimals(0)); ?></th>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </tr>
      
      <!-- Work Order -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
            </tr>
            <tr>
              <th>No. of Work Order(s)</th>
              <th>GST</th>
              <th>Total</th>
              <th>No. of Vendors</th>
              <th>Status</th>
              <th>Parts Used</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($vehicleData['wo']->count) && $vehicleData['wo']->count!=0): ?>
              <tr>
                <td><?php echo e($vehicleData['wo']->count); ?></td>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th>CGST</th>
                      <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($vehicleData['wo']->cgst)); ?></td>
                    </tr>
                    <tr>
                      <th>SGST</th>
                      <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($vehicleData['wo']->sgst)); ?></td>
                    </tr>
                  </table>
                </td>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($vehicleData['wo']->grand_total)); ?></td>
                <td><?php echo e($vehicleData['wo']->vendors); ?></td>
                <td>
                  <table class="table table-striped">
                    <?php $__currentLoopData = $vehicleData['wo']->status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <th><?php echo e($k); ?></th>
                        <td><?php echo e(count($s)); ?></td>
                      </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </table>
                </td>
                <td>
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Part</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($vehicleData['partsUsed'])): ?>
                        <?php $__currentLoopData = $vehicleData['partsUsed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($pu->part->title); ?></td>
                            <td><?php echo e($pu->qty); ?></td>
                            <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($pu->total)); ?></td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="3" align='center' style="color: red">No Parts Used...</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="6" align='center' style="color: red">No Records Found...</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </tr>
    </table>
  </div>
</div><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicles/partials/vehicle_report.blade.php ENDPATH**/ ?>