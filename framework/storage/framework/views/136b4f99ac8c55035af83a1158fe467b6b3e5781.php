<table class="table table-striped">
    <tr>
        <th colspan="4"  style="font-size: 25px;text-align:center"><?php echo e(ucwords($bulk->name)); ?></th>
    </tr>
    <tr>
        <th colspan="2">
            <?php echo Form::label('bank',"Bank :", ['class' => 'form-label']); ?>

            <br>
            <?php echo e($bulk->bank->bank); ?>

        </th>
        <th>
            <?php echo Form::label('amount', "Amount :", ['class' => 'form-label']); ?>

            <br>
            <?php echo e(Hyvikk::get('currency')); ?><?php echo e(Helper::properDecimals($bulk->amount)); ?>

        </th>
        <th>
            <?php echo Form::label('date', "Date :", ['class' => 'form-label']); ?>

            <br>
            <?php echo e(Helper::getCanonicalDate($bulk->date,'default')); ?>

        </th>
    </tr>
    <tr>
        <th>Transaction</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Remarks</th>
    </tr>
    <?php $__currentLoopData = $bulk_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($h->trash->transaction_id); ?></td>
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($h->amount); ?></td>
        <td><?php echo e($faults[$h->fault]); ?></td>
        <td><?php echo e(!empty($h->comment) ? Helper::limitText($h->comment,400) : '-'); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/bank_account/bulk_viewevent.blade.php ENDPATH**/ ?>