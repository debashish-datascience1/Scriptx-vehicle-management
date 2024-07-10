<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?></h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.notification'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.remaining_days'); ?></th>
            </tr>
          </thead>
          <tbody>

          <?php
          $user = App\Model\User::find(Auth::id());
          ?>

          <?php if($type = "service-reminder"): ?>
          <?php ($type = "App\Notifications\ServiceReminderNotification"); ?>
          <?php endif; ?>
          <?php $__currentLoopData = $user->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($notification->type==$type): ?>
          <?php ($notification->markAsRead()); ?>
          <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          <?php $__currentLoopData = $reminder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <?php $__currentLoopData = $user->notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <?php if($data->id == $notification->data['vid']): ?>

          <?php if($notification->type == $type): ?>

          <?php ($to = \Carbon\Carbon::now()); ?>

          <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $notification->data['date'])); ?>

          <?php ($diff_in_days = $to->diffInDays($from)); ?>

            <tr>
              <td>
              <?php if($data->vehicle['vehicle_image'] != null): ?>
                <img src="<?php echo e(asset('uploads/'.$data->vehicle['vehicle_image'])); ?>" height="70px" width="70px">
              <?php else: ?>
                <img src="<?php echo e(asset("assets/images/vehicle.jpeg")); ?>" height="70px" width="70px">
              <?php endif; ?>
              </td>
              <td>
              <?php echo e($data->vehicle->year); ?> <?php echo e($data->vehicle->make); ?> <?php echo e($data->vehicle->model); ?>

              <br>
              <?php echo app('translator')->getFromJson('fleet.vin'); ?>:<?php echo e($data->vehicle->vin); ?>

              <br>
              <?php echo app('translator')->getFromJson('fleet.plate'); ?>:<?php echo e($data->vehicle->license_plate); ?>

              </td>
              <td>
              <?php echo e($notification->data['msg']); ?>

              </td>
              <td>
              <?php if(strtotime($notification->data['date'])>strtotime("now")): ?>
                <?php echo e($diff_in_days); ?>

              <?php else: ?>
                <span class="text-danger">Expired</span>
              <?php endif; ?>
              </td>
            </tr>
          <?php endif; ?>
          <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/notifications/service_reminder.blade.php ENDPATH**/ ?>