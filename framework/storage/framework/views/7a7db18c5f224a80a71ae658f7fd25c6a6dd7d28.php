<?php if(empty($user->getMeta('salary')) || $user->getMeta('salary')==null): ?>
    <span style="color: red;font-style:italic">Edit the salary of driver <strong><?php echo e($user->name); ?></strong> to <a href="<?php echo e(url('admin/drivers/'.$user->id.'/edit/')); ?>">proceed...</a></span>
<?php else: ?>
    <div class="container">
        
        <?php echo Form::open(['route'=>'payroll.store']); ?>

        <div class="row">
            
                
                
            
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('drivers', 'Driver Name', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('drivers',$user->name, ['class' => 'form-control','required','readonly']); ?>

                    <input type="hidden" name="driver_id" id="driver_id" value="<?php echo e($user->id); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('salary', 'Salary', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('salary',$user->getMeta('salary'), ['class' => 'form-control','required','readonly','onkeypress'=>'return isNumber(event)']); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('month', 'For Month?', ['class' => 'form-label required']); ?>

                    <?php echo Form::select('month',Helper::getMonths() ,null, ['class' => 'form-control','required','placeholder'=>'Select Month']); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('year', 'For Year?', ['class' => 'form-label required']); ?>

                    <?php echo Form::select('year',Helper::getYear() ,null, ['class' => 'form-control','required','placeholder'=>'Select Year']); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('working_days', 'Working Days', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('working_days',null, ['class' => 'form-control','required','readonly','id'=>'working_days','placeholder'=>'days..']); ?>

                    <input type="checkbox" name="manual" id="manual" > 
                    <label for="manual">Manual</label>

                </div>
               
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('advance_salary', 'Advance to Salary ?', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('advance_salary', null, ['class' => 'form-control','required','readonly','id'=>'advance_salary']); ?>

                    <small id="payable_sal" style="color: red;"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('payable_salary', 'Payable Salary!', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('payable_salary', null, ['class' => 'form-control','required','readonly','id'=>'payable_salary']); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::hidden('remaining',base64_encode($remaining),['id'=>'remaining']); ?>

                    <?php echo Form::label('cash', 'Cash', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('cash', Helper::properDecimals($remaining), ['class' => 'form-control','required','readonly','id'=>'cash']); ?>

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo Form::label('payroll_remarks', 'Remarks ?', ['class' => 'form-label']); ?>

                    <?php echo Form::textarea('payroll_remarks', null, ['class' => 'form-control','required','id'=>'payroll_remarks','style'=>'height:75px;resize:none;']); ?>

                </div>
            </div>
        </div>
        <div class="row expenditure_div">
            
        </div>
        <div class="row">
            <div class="col-md-4">
                <input type="submit" value="Add Payroll" id="payroll"  class="btn btn-success">
            </div>
        </div>
        <?php echo Form::close(); ?>

    </div>
<?php endif; ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/payroll/pay.blade.php ENDPATH**/ ?>