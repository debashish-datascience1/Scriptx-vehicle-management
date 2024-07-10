<table class="table table-striped">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
        <th></th>
        <th></th>
        <th></th>
         <?php if($remaining->remaining!=0): ?>
        <th></th>
        <th></th>
        <th></th>
        <?php endif; ?>
        <th></th>
        <th></th>
        <th>Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($transaction->total,2,'.','')); ?></th>
        <th></th>
        <th>
            Remaining : <span id="span-remain"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($remaining->remaining,2,'.','')); ?></span>
            <?php if($remaining->remaining==0): ?>
            &nbsp;&nbsp;&nbsp;<span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
            <?php endif; ?>
        </th>
        <th><input type="hidden" name="remainingAmt" id="remainingAmt" value="<?php echo e($remaining->remaining); ?>"></th>
        
        <th>
            <input type="hidden" name="trans_id" value="<?php echo e($transaction->id); ?>">
        </th>
    </tr>
</table>

<table class="table table-striped adjustTable">
    <thead>
        <th style="width: 18%">Date</th>
        <th style="width: 18%">Method</th>
        <th style="width: 19%">Amount</th>
        <th>Remarks</th>
        <th></th>
    </thead>
    <tbody>
        <?php if(count($incomes)>0): ?>
            <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="parent-row">
                <td>
                    <input type="text" name="adjDatePrev[]" class="form-control adjDatePrev" readonly value="<?php echo e(date("Y-m-d",strtotime($income->date))); ?>">
                </td>
                <td>
                    <?php echo Form::select('adjTypePrev[]',$method,$income->payment_method,['class'=>'form-control adjTypePrev']); ?>

                </td>
                <td>
                    <input type="text"  name="adjAmountPrev[]" class="form-control adjAmountPrev"  value="<?php echo e($income->amount); ?>" <?php echo e(count($incomes)==1 && $income->amount==0 ? '' : 'readonly'); ?> required>
                </td>
                <td>
                    <?php echo e($income->remarks); ?>

                </td>
                <td><input type="hidden" name="income_id[]" class="income_id" value="<?php echo e($income->id); ?>"></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <?php if(($remaining->remaining>0 && count($incomes)!=1) || ($remaining->remaining>0 && $incomes[0]->amount!=0)): ?>
        <tr class="parent-row">    
            <td>
                <input type="text" name="adjDate[]" class="form-control adjDate" readonly placeholder="Choose Date.." required>
            </td>
            <td>
                <?php echo Form::select('adjType[]',$method,null,['class'=>'form-control adjType','required']); ?>

            </td>
            <td>
                <input type="text"  name="adjAmount[]" class="form-control adjAmount" placeholder="Enter Amount.." required>
            </td>
            <td>
                <input type="text"  name="adjRemarks[]" class="form-control adjRemarks" placeholder="Enter Remarks..">
            </td>
            <td>
                <button class="btn btn-primary addmore" id="addmore"><span class="fa fa-plus"></span> Add More</button>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/transactions/adjust.blade.php ENDPATH**/ ?>