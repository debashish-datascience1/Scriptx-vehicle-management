<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="remarks" class="form-label required"><strong>Payment Method :</strong></label>
                <?php echo Form::select("method[{$user->id}]",$methods,null,['class'=>'form-control','placeholder'=>'Select Payment Methods','required']); ?>

            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="remarks" class="form-label required"><strong><?php echo e($user->name); ?>'s remarks :</strong></label>
                <input type="text" class="form-control" required name="remarks[<?php echo e($user->id); ?>]">
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/daily_advance/remarks.blade.php ENDPATH**/ ?>