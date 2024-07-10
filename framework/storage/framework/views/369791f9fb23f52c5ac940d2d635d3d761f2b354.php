<?php if($isSalaryPaid): ?>
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <h5 class="text-danger">Salary is already paid for selected month. Cannot change attendance if salary is paid for selected month</h5>
        </div>
    </div>
<?php endif; ?>
<div class="row">
    <?php $__currentLoopData = $leaveList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $id = !empty($leave) ? $leave->id : null;
            $is_present = !empty($leave) ? $leave->is_present : null;
            $remarks = !empty($leave) ? $leave->remarks : null;
            $style = $isSalaryPaid ? "pointer-events:none;" : "";
            $isReadOnly = $isSalaryPaid ? "readonly" : "";
        ?>
    <div class="col-md-3">
        <div class="form-group">
        <div class="row">
            <div class="col-md-2 text-center">
                <h4><?php echo e($key+1); ?></h4>
                <input type="hidden" name="dates[]" value="<?php echo e($key+1); ?>">
            </div>
            <div class="col-md-10">
            <?php echo Form::select("attendance[]",Helper::leaveTypes(),$is_present,['class' => 'form-control attendance','id'=>'attendance','placeholder'=>'Select','style'=>$style,$isReadOnly]); ?>

            </div>
            <div class="col-md-12 mt-2">
            <?php echo Form::textarea("remarks[]",$remarks,['class' => 'form-control remarks','id'=>'remarks','placeholder'=>'Remarks...']); ?>

            </div>
        </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-12">
        <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-success','id'=>'btnsubmit']); ?>

    </div>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bulk_leave/getMonths_ajax.blade.php ENDPATH**/ ?>