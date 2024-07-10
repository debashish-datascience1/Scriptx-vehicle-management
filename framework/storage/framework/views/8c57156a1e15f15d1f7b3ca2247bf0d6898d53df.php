<table class="table table-striped">
    <tr>
        <th style="width:150px;">Bank</th>
        <td><strong><?php echo e($deposit->bank->bank); ?></strong></td>
    </tr>
    <?php if(!empty($deposit->refer_bank)): ?>
    <tr>
        <th>From Bank</th>
        <td><strong><?php echo e($deposit->referBank->bank); ?></strong></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Date</th>
        <td><?php echo e(Helper::getCanonicalDate($deposit->date,'default')); ?></td>
    </tr>
    <tr>
        <th>Amount</th>
        <td><?php echo e(Helper::properDecimals($deposit->amount)); ?></td>
    </tr>
    <tr>
        <th style="width: 50px;">Remarks</th>
        <td><?php echo e($deposit->remarks); ?></td>
    </tr>
</table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bank_account/deposit_view.blade.php ENDPATH**/ ?>