
<table class="table table-striped adjustTable">
    <thead>
        <th><?php echo e($adv->driver->name); ?></th>
        <th><?php echo e(Helper::getCanonicalDate($adv->date,'default')); ?></th>
        <th>Total : <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($adv->amount)); ?></th>
        <th colspan="2">
            Remaining : <span id="span-remain"><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($remaining)); ?></span>
            <?php if($remaining==0): ?>
            &nbsp;&nbsp;&nbsp;<span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
            <?php endif; ?>
        </th>
        <th><input type="hidden" name="remainingAmt" id="remainingAmt" value="<?php echo e($remaining); ?>"></th>
        <th></th>
        <th><input type="hidden" name="otherAdvance" id="otherAdvance" value="<?php echo e($adv->id); ?>"></th>
    </thead>
    <tbody>
            <tr>
                <th style="width: 18%">Head & Date</th>
                <th style="width: 18%">Method & Reference No.</th>
                <th style="width: 19%">Amount & Payment Type</th>
                <th colspan="4">Remarks</th>
                <th></th>
            </tr>
        <?php if(count($others)>0): ?>
            <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="parent-row">
                <td>
                    <?php echo Form::text("adjHeadPrev[]",$oth->head,['class'=>'form-control','placeholder'=>'e.g. Food','readonly']); ?>

                    <input type="text" name="adjDatePrev[]" class="form-control adjDatePrev" readonly placeholder="Date.." value="<?php echo e($oth->date); ?>">
                </td>
                <td>
                    <?php echo Form::select('adjMethodPrev[]',$method,$oth->method,['class'=>'form-control adjMethodPrev','readonly','placeholder'=>'Select']); ?>

                    <?php echo Form::text("adjRefPrev[]",$oth->ref_no,['class'=>'form-control adjRefPrev','placeholder'=>'Reference No.']); ?>

                </td>
                <td>
                    <input type="text"  name="adjAmountPrev[]" class="form-control adjAmount" placeholder="Enter Amount.." readonly  onkeypress='return isNumber(event,this)' value="<?php echo e($oth->amount); ?>">
                    <?php echo Form::select('adjTypePrev[]',$type,$oth->type,['class'=>'form-control adjTypePrev','readonly','placeholder'=>'Select']); ?>

                    <?php if($oth->bank_id!=''): ?>
                    <strong><?php echo e($oth->bank_details->bank); ?><br> (<?php echo e($oth->bank_details->account_no); ?>)</strong>
                    <?php endif; ?>
                </td>
                <td colspan="4">
                    <textarea class="form-control adjRemarksPrev" name="adjRemarksPrev[]" id="adjRemarks[]" style="resize:none;height:85px;" placeholder="Remarks if any"><?php echo e($oth->remarks); ?></textarea>
                </td>
                <td>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        
        <?php if($remaining!=0): ?>
        <tr class="parent-row">
            <td>
                <?php echo Form::text("adjHead[]",null,['class'=>'form-control','placeholder'=>'e.g. Food','required']); ?>

                <input type="text" name="adjDate[]" class="form-control adjDate" readonly placeholder="Date.." required>
            </td>    
            <td>
                <?php echo Form::select('adjMethod[]',$method,null,['class'=>'form-control adjMethod','required','placeholder'=>'Select']); ?>

                <?php echo Form::text("adjRef[]",null,['class'=>'form-control adjRef','placeholder'=>'Reference No.']); ?>

            </td>
            <td>
                <input type="text"  name="adjAmount[]" class="form-control adjAmount" placeholder="Enter Amount.." required  onkeypress='return isNumber(event,this)'>
                <?php echo Form::select('adjType[]',$type,null,['class'=>'form-control adjType','required','placeholder'=>'Select']); ?>

                <?php echo Form::select('adjBank[]',$bank,null,['class'=>'form-control adjBank','required','placeholder'=>'Select']); ?>

            </td>
            <td colspan="4">
                
                <textarea class="form-control adjRemarks" name="adjRemarks[]" id="adjRemarks[]" style="resize:none;height:85px;" placeholder="Remarks if any"></textarea>
            </td>
            <td>
                <button type="button" class="btn btn-primary addmore" id="addmore"><span class="fa fa-plus"></span> Add More</button>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <?php echo Form::submit("Submit",['class'=>'btn btn-success',"id"=>'subAdjust','name'=>'subAdjust']); ?>

            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/other_advance/other_adjust.blade.php ENDPATH**/ ?>