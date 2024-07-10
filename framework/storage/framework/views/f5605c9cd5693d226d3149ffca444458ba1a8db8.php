

    <?php if($isAlreayPaid): ?> 
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: green"> <i class="fa fa-check"></i>   Salary of <?php echo e($userData->name); ?> for the month of <?php echo e($imonth); ?> is already paid</span>
            </div>
        </div> 
    <?php elseif(!$isLeaveChecked): ?>
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: red"><i>Please provide leave details of <?php echo e($userData->name); ?> for the month of <?php echo e($imonth); ?> to proceed. You can add leave details <a href="<?php echo e(route("bulk_leave.create")); ?>" target="_blank">here</a></i></span>
            </div>
        </div> 
    <?php elseif(!empty($yetToComplete)): ?>
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: red"><i><b>Mark as Complete</b> <?php echo e($yetToComplete->count()); ?> bookings of <?php echo e($userData->name); ?> for the month of <?php echo e($imonth); ?> to proceed. You can complete them <a href="<?php echo e(route("bookings.index")); ?>" target="_blank">here</a></i></span>
            </div>
        </div>
        <div class="col-md-12 p-3">
            <div class="form-group">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $yetToComplete; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($k+1); ?></td>
                            <td><?php echo e(!empty(Helper::getTransaction($y->id,18)) ? Helper::getTransaction($y->id,18)->transaction_id : '-'); ?></td>
                            <td><?php echo e(Helper::getCanonicalDateTime($y->pickup,'default')); ?></td>
                            <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($y->getMeta('advance_pay'),1,2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
            <?php if(count($advanceFromBooking)>0 || $salary_advance>0): ?>
            <div class="col-md-12">
                <div class="form-group">
                <span style="color: green"><i>Driver doesn't have any booking with driver advance that is yet to be <b>Mark as Complete</b> for the month of <?php echo e($imonth); ?></i></span>
                </div>
            </div>
            <?php endif; ?>
            <?php if(count($advanceFromBooking)>0): ?>
            <div class="col-md-12 p-3">
                <div class="form-group">
                    <h6><strong> Booking (Advances to Driver) [<?php echo e($imonth); ?>]</strong></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $advanceFromBooking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($k+1); ?></td>
                                <td><?php echo e(!empty(Helper::getTransaction($y->id,18)) ? Helper::getTransaction($y->id,18)->transaction_id : '-'); ?></td>
                                <td><?php echo e(Helper::getCanonicalDateTime($y->pickup,'default')); ?></td>
                                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($y->advanceToDriver->value); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            <?php if($salary_advance>0): ?>
            <div class="col-md-12 p-3">
                <div class="form-group">
                    <h6><strong> Salary Advance (<?php echo e($imonth); ?>)</strong></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $salary_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($k+1); ?></td>
                                <td><?php echo e(Helper::getTransaction($y->id,25)->transaction_id); ?></td>
                                <td><?php echo e(Helper::getCanonicalDate($y->date,'default')); ?></td>
                                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($y->amount); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            <?php if(count($advanceFromBooking)==0 && $salary_advance==0): ?>
            <div class="col-md-12">
                <div class="form-group">
                <span style="color: green"><i>Driver didn't get any advances from <b>Bookings</b> for the month of <?php echo e($imonth); ?></i></span>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/payroll/expenses.blade.php ENDPATH**/ ?>