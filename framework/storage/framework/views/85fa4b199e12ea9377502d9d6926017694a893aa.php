<?php
    use App\Model\VehicleDocs;
?>
<?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="row">
    
    <div class="col-md-12">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td align="center" style="font-size:23px;">
                            <strong><?php echo e($v->make); ?>-<?php echo e($v->model); ?>-<?php echo e($v->license_plate); ?></strong>
                            <?php if(!empty($v->driver) && !empty($v->driver->assigned_driver)): ?>
                            <br><span><?php echo e(ucwords(strtolower($v->driver->assigned_driver->name))); ?></span>
                            <?php else: ?>
                            <br>
                            <span style="font-size:15px;font-style:italic">Driver not assigned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Documents</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Bank & Method</th>
                                        <th width="25%">Reference No.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $docparams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php

                                    
                                    $docName = $docparamArray[$doc->id];
                                    //checking last day
                                    if(!empty($v->getMeta($docName[2]))){
                                            $expirationDateSet = true;
                                    
                                        //  checking if amount and duration is set
                                        //36-insurance,37-fitness,38-roadtax,39-permit,40-pollution
                                        //index 0-duration,1-amount,2-expiration date
                                        if(!empty($v->getMeta($docName[0])) && !empty($v->getMeta($docName[1]))){
                                            $durationUnitSet = true;
                                            //check how many days document have to expire
                                            $daysLeft = Helper::renewLastday($v->getMeta($docName[2]));
                                            if($daysLeft<=0){
                                                $lastDay = true;
                                                $vehicleDoc = VehicleDocs::where(['vehicle_id'=>$v->id,'param_id'=>$doc->id])->orderBy('id','DESC');
                                                if($vehicleDoc->exists()){
                                                    $vehicleTillDate = $vehicleDoc->first()->till;
                                                    $daysLeft = Helper::renewLastday($vehicleTillDate);
                                                    $lastDay = $daysLeft<=0 ? true :false;
                                                }
                                            }else{
                                                $lastDay = false;
                                            }
                                        }else{
                                            $durationUnitSet = false;
                                        }
                                    }else{
                                        $expirationDateSet = false;
                                    }
                                    
                                    
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo Form::label("$doc->label","$doc->label", ['class' => 'form-label']); ?>

                                            
                                        </td>
                                        <?php if($expirationDateSet): ?>
                                        <?php if($durationUnitSet): ?>
                                        <?php if($lastDay): ?>
                                        <td>
                                            <?php echo Form::text("date[$v->id][$doc->id]",null,['class' => 'form-control date','required','readonly','data-id'=>$v->id,'data-doc'=>$doc->id]); ?>

                                            
                                        </td>
                                        <td>
                                            <?php echo Form::text("amount[$v->id][$doc->id]",null,['class' => 'form-control amount','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Enter Renewal Amount']); ?>

                                            <?php echo Form::select("vendor[$v->id][$doc->id]",$vendors,null,['class' => 'form-control vendor','required','placeholder'=>'Select Vendor','style'=>'margin-top:10px;']); ?>

                                        </td>
                                        <td>
                                            
                                            <?php echo Form::select("bank[$v->id][$doc->id]",$bankAccount,null,['class' => 'form-control bank','required','placeholder'=>'Select Bank','style'=>'margin-top:10px;']); ?>

                                            <?php echo Form::select("method[$v->id][$doc->id]",$method,null,['class' => 'form-control method','required','placeholder'=>'Select Method','style'=>'margin-top:10px;']); ?>

                                        </td>
                                        <td>
                                            <?php echo Form::text("ddno[$v->id][$doc->id]",null,['class' => 'form-control ddno','required','style'=>'margin-top: 9px;','placeholder'=>'Reference No.']); ?>

                                            <?php echo Form::textarea("remarks[$v->id][$doc->id]",null,['class' => 'form-control remarks','style'=>'height:100px;resize:none;margin-top:7px;','placeholder'=>'Remarks (if any)']); ?>

                                        </td>
                                        <td>
                                            <input type="button" value="Renew" class="btn btn-success renew_btn">
                                            <input type="hidden" name="hiddenInput" class="singleVehicleId" value="<?php echo e($v->id); ?>">
                                        </td>
                                        <?php else: ?>
                                        
                                         <td colspan="5" align="center">
                                            <?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($daysLeft); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?>
                                         </td>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        
                                         <td colspan="5" align="center">
                                            
                                            <label for=""><?php echo e($doc->label); ?> duration and unit is not set</label> <a href="../vehicles/<?php echo e($v->id); ?>/edit?tab=insurance" target="_blank">click here</a> to set
                                         </td>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        
                                         <td colspan="5" align="center">
                                            
                                            <label for=""><?php echo e($doc->label); ?> expiration date is not set</label> <a href="../vehicles/<?php echo e($v->id); ?>/edit?tab=insurance" target="_blank">click here</a> to set
                                         </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicle_docs/renew_list.blade.php ENDPATH**/ ?>