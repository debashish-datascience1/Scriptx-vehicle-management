<div role="tabpanel" style="margin-bottom: 10px;">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"><i class="fa fa-"></i> General Information </a>
        </li>

        <li class="nav-item"><a href="#history-tab" data-toggle="tab" class="nav-link custom_padding"> <i class="fa fa-history"></i> History</a>
        </li>
    </ul>

    <div class="tab-content">
    <!-- General Information Tab-->
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <tr>
                    <th style="width: 145px;">Bank Name</th>
                    <td><?php echo e($bankAccount->bank); ?></td>
                </tr>
                <tr>
                    <th>Account No.</th>
                    <td>
                        <?php echo e($bankAccount->account_no); ?>

                    </td>
                </tr>
                <tr>
                    <th>IFSC Code</th>
                    <td>
                        <?php echo e($bankAccount->ifsc_code); ?>

                    </td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td>
                        <?php echo e($bankAccount->branch); ?>

                    </td>
                </tr>
                <tr>
                    <th>Account Holder</th>
                    <td>
                        <?php echo e($bankAccount->account_holder); ?>

                    </td>
                </tr>
                <tr>
                    <th>Starting Amount</th>
                    <td>
                        <?php echo e($bankAccount->starting_amount); ?>

                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        <?php echo e($bankAccount->address); ?>

                    </td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>
                        <?php echo e($bankAccount->mobile); ?>

                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <?php echo e($bankAccount->email); ?>

                    </td>
                </tr>
                
            </table>
        </div>
        <div class="tab-pane active" id="history-tab">
            <table class="table table-striped">
                <tr>
                    <th>Bank</th>
                    <th>Refer Bank</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($h->bank->bank); ?></td>
                    <td><?php echo e(!empty($h->refer_bank) ? $h->referBank->bank : '-'); ?></td>
                    <td><?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($h->amount)); ?></td>
                    <td><?php echo e(Helper::getCanonicalDate($h->date)); ?></td>
                    <td><?php echo e(!empty($h->remarks) ? Helper::limitText($h->remarks,40) : '-'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bank_account/view_event.blade.php ENDPATH**/ ?>