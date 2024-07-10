<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="remarks" class="form-label required"><strong><?php echo e($user->name); ?>'s remarks :</strong></label>
                <input type="text" class="form-control" required name="remarks[<?php echo e($user->id); ?>]">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="halfday" class="form-label">is Half day ?</strong></label>
                <select name="halfday[<?php echo e($user->id); ?>]" class="form-control halfday" required>
                    <option value="2">Full Leave</option>
                    <option value="3">1st Half Leave</option>
                    <option value="4">2nd Half Leave</option>
                </select>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/leaves/remarks.blade.php ENDPATH**/ ?>