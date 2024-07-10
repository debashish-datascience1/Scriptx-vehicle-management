
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">
                <input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo e($purchase->id); ?>">
                <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo e($vehicle->id); ?>">
                <small>Vehicle :</small> <?php echo e($vehicle->license_plate); ?> <br>
                <small>For Month : <?php echo e(date('m/Y',strtotime($date))); ?> <i>(<?php echo e(date('F-Y',strtotime($date))); ?>)</i></small>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label required']); ?>

                            <?php echo Form::text('date',Helper::indianDateFormat($date),['class'=>'form-control','readonly','required','id'=>'date']); ?>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']); ?>

                            <?php echo Form::text('amount',$purchase->emi_amount,['class'=>'form-control','readonly','required','id'=>'amount']); ?>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('pay_date', __('fleet.pay_date'), ['class' => 'form-label required']); ?>

                            <?php echo Form::text('pay_date',null,['class'=>'form-control','readonly','id'=>'pay_date']); ?>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('bank', __('fleet.bank'), ['class' => 'form-label required']); ?>

                            <?php echo Form::select('bank_id',Helper::getBanks(),null,['class'=>'form-control','placeholder'=>'Select Bank','id'=>'bank']); ?>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('method', __('fleet.method'), ['class' => 'form-label required']); ?>

                            <?php echo Form::select('method',Helper::getMethods(),null,['class'=>'form-control','placeholder'=>'Select Method','id'=>'method']); ?>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <?php echo Form::label('reference_no', __('fleet.reference_no'), ['class' => 'form-label required']); ?>

                            <?php echo Form::text('reference_no',null,['class'=>'form-control','required','id'=>'reference_no','placeholder'=>'Enter Reference No.']); ?>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <?php echo Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label required']); ?>

                            <?php echo Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','placeholder'=>'Remarks if any']); ?>

                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?php echo Form::submit('Confirm',['class'=>'btn btn-success','id'=>'payEmi']); ?></td>
        </tr>
    </table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/emi/pay_modal.blade.php ENDPATH**/ ?>