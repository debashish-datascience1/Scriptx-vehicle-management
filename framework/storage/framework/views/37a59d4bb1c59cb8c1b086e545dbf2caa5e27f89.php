<div class="modal-header">
    <h4 class="modal-title"><?php echo e(ucwords($transaction->params->label)); ?> Details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    
    <?php if($transaction->param_id==18): ?>
        <div role="tabpanel" style="margin-bottom: 10px;">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"> General Information <i class="fa"></i></a>
                </li>

                <li class="nav-item"><a href="#load-tab" data-toggle="tab" class="nav-link custom_padding"> Load Details <i class="fa"></i></a>
                </li>

                <li class="nav-item"><a href="#journey-tab" data-toggle="tab" class="nav-link custom_padding"> Journey Details <i class="fa"></i></a>
                </li>
            </ul>

            <div class="tab-content">
            <!-- General Information Tab-->
                <div class="tab-pane active" id="info-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Customer </th>
                            <td><?php echo e($data->customer->name); ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle </th>
                            <td><?php echo e($data->vehicle->make); ?> - <?php echo e($data->vehicle->model); ?> - <?php echo e($data->vehicle->license_plate); ?></td>
                        </tr>
                        <tr>
                            <th>Driver </th>
                            <td><?php echo e($data->driver->name); ?></td>
                        </tr>
                        <tr>
                            <th>Pickup Address</th>
                            <td><?php echo e($data->pickup_addr); ?></td>
                        </tr>
                        <tr>
                            <th>Pickup Date & Time</th>
                            <td><?php echo e(Helper::getCanonicalDate($data->pickup,'default')); ?> <?php echo e(date("g:i:s A",strtotime($data->pickup))); ?></td>
                        </tr>
                        <tr>
                            <th>Dropoff Address</th>
                            <td><?php echo e($data->dest_addr); ?></td>
                        </tr>
                        <tr>
                            <th>Dropoff Date & Time</th>
                            <td><?php echo e(Helper::getCanonicalDate($data->dropoff,'default')); ?> <?php echo e(date("g:i:s A",strtotime($data->dropoff))); ?></td>
                        </tr>
                        <tr>
                            <th>Party Name</th>
                            <td><?php echo e($data->getMeta('party_name')); ?></td>
                        </tr>
                        <tr>
                            <th>Narration</th>
                            <td><?php echo e($data->getMeta('narration')); ?></td>
                        </tr>
                        <?php if($data->status==1): ?>
                        <tr>
                            <th>Booking Status</th>
                            <td><span class="badge badge-success">Completed</span></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <!-- Load Tab-->
                <div class="tab-pane" id="load-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Load Price</th>
                            <td><span class="fa fa-inr"></span> <?php echo e($data->getMeta('loadprice')); ?> per <?php echo e($params->label=='Quantity' ? 'Quintals' : $params->label); ?></td>
                        </tr>
                        <tr>
                            <th>Load Quantity</th>
                            <td><?php echo e($data->getMeta('loadqty')); ?> <?php echo e($params->label=='Quantity' ? 'Quintals' : $params->label); ?></td>
                        </tr>
                        <tr>
                            <th>Fuel Per Litre</th>
                            <td><span class="fa fa-inr"></span> <?php echo e($data->getMeta('perltr')); ?></td>
                        </tr>
                        <tr>
                            <th>Material</th>
                            <td><?php echo e($data->getMeta('material')); ?></td>
                        </tr>
                    </table>
                </div>
                <!-- Journey Tab-->
                <div class="tab-pane" id="journey-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Initial KM. on Vehicle</th>
                            <td><?php echo e($data->getMeta('initial_km')); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
                        </tr>
                        <tr>
                            <th>Distance</th>
                            <td><?php echo e($data->getMeta('distance')); ?></td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td><?php echo e($data->getMeta('duration_map')); ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle Mileage</th>
                            <td><?php echo e($data->getMeta('mileage')); ?> km/ltr</td>
                        </tr>
                        <tr>
                            <th>Fuel Required(ltr)</th>
                            <td><?php echo e($data->getMeta('pet_required')); ?> litre</td>
                        </tr>
                        <tr>
                            <th>Fuel Per Litre</th>
                            <td> <span class="fa fa-inr"></span> <?php echo e($data->getMeta('perltr')); ?></td>
                        </tr>
                        <tr>
                            <th>Total Fuel Price</th>
                            <td><span class="fa fa-inr"></span>  <?php echo e($data->getMeta('petrol_price')); ?></td>
                        </tr>
                        <tr>
                            <th>Total Freight Price</th>
                            <td><span class="fa fa-inr"></span>  <?php echo e($data->getMeta('total_price')); ?></td>
                        </tr>
                        <?php if($data->getMeta('old_frieght_price')): ?>
                        <tr>
                            <th>Old Freight Price</th>
                            <td>
                                <span class="fa fa-inr"></span>  <?php echo e($data->getMeta('old_frieght_price')); ?>

                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if(!empty($from_transa)): ?>
                        <tr>
                            <th>Non Payable Amount</th>
                            <td>
                                <span class="fa fa-inr"></span>  <?php echo e($from_transa->total); ?><br>
                                <i>Ref. ID.</i><span class="badge badge-info"><?php echo e($from_transa->transaction_id); ?></span>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Advance to Driver</th>
                            <td>
                                <?php if($data->getMeta('advance_pay')!=''): ?>
                                    <span class="fa fa-inr"></span> <?php echo e($data->getMeta('advance_pay')); ?>

                                <?php else: ?>
                                    <span class="badge badge-warning"><i>No Advance was given...</i></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Ride Status</th>
                            <td>
                                <?php $status_arr = ['upcoming'=>'warning','completed'=>'success','cancelled'=>'danger'] ?>
                                <span class="badge badge-<?php echo e($status_arr[strtolower($data->getMeta('ride_status'))]); ?>"><?php echo e($data->getMeta('ride_status')); ?></span>
                            </td>
                        </tr>
                    </table>
                </div>  
            </div>
        </div>
    <?php endif; ?>
    
    
    
    <?php if($transaction->param_id==19): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td>
                    
                    <?php if(!empty($payroll->user_id)): ?>
                        <?php echo e($payroll->driver->name); ?>

                    <?php else: ?>
                        <span class="badge badge-danger">No Driver Found</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>
                    <?php if(!empty($payroll->driver_vehicle)): ?>
                        <?php echo e($payroll->driver_vehicle->vehicle->make); ?>-<?php echo e($payroll->driver_vehicle->vehicle->model); ?>-<strong><?php echo e($payroll->driver_vehicle->vehicle->license_plate); ?></strong>
                    <?php else: ?>
                        <span class="badge badge-danger">No Vehicle Assigned</span>
                    <?php endif; ?>
                    
                </td>
            </tr>
            <tr>
                <th>For Month</th>
                <td>
                    <?php $month = $payroll->for_month<10 ? "0".$payroll->for_month:$payroll->for_month;  ?>
                    <strong><?php echo e(date("m-Y",strtotime($payroll->for_year."-".$month."-01"))); ?>/
                    <?php echo e(date("F-Y",strtotime($payroll->for_year."-".$month."-01"))); ?></strong>
                </td>
            </tr>
            <tr>
                <th>Working Days</th>
                <td>
                    <?php echo e($payroll->working_days!='' ? $payroll->working_days :'0'); ?> days
                </td>
            </tr>
            <tr>
                <th>Absent Days</th>
                <td>
                    <?php echo e($payroll->absent_days!='' ? $payroll->absent_days :'0'); ?> days
                </td>
            </tr>
            <tr>
                <th>Monthly Salary</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Deducted Salary</th>
				<td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->deduct_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Total Payable Salary</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->total_payable_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Carried Salary</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->carried_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Paid Salary</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->payable_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Remaining Salary</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->remaining_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Salary Advance </th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->advance_salary,1,2)); ?></td>
            </tr>
            <tr>
                <th>Booking Advance </th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($payroll->advance_driver,1,2)); ?></td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>
                    <?php echo e($payroll->payroll_remarks); ?>

                </td>
            </tr>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==20): ?>
        <table class="table table-striped">
            <tr>
                <th>Vehicle</th>
                <td><?php echo e($fuel->vehicle_data->make); ?> - <?php echo e($fuel->vehicle_data->model); ?> - <strong><?php echo e($fuel->vehicle_data->license_plate); ?></strong></td>
            </tr>
            <tr>
                <th>Vendor</th>
                <td><?php echo e($fuel->vendor->name); ?></td>
            </tr>
            <tr>
                <th>Province</th>
                <td><?php echo e($fuel->province); ?></td>
            </tr>
            <tr>
                <th>Fuel</th>
                <td><?php echo e($fuel->fuel_details->fuel_name); ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo e($transaction->income_expense->method->label); ?></td>
            </tr>
            <tr>
                <th>Cost per Unit</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($fuel->cost_per_unit,2,'.','')); ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo e($fuel->qty); ?> ltr</td>
            </tr>
            <tr>
                <th>Total</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($fuel->qty * $fuel->cost_per_unit,2,'.','')); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo e(Helper::getCanonicalDate($fuel->date,'default')); ?></td>
            </tr>
            
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==25): ?>
        <table class="table table-striped">
        <?php if(!empty($advance->driver)): ?>
            <tr>
                <th>Driver Name</th>
                <td><strong><?php echo e($advance->driver->name); ?></strong></td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    <?php echo e(Helper::getCanonicalDate($advance->date,'default')); ?>

                </td>
            </tr>

            <tr>
                <th>Payment Method</th>
                <td>
                    <?php echo e($transaction->income_expense->method->label); ?>

                </td>
            </tr>

            <tr>
                <th>Amount <span class="fa fa-inr"></span></th>
                <td>
                    <strong><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($advance->amount,2,'.','')); ?></strong>
                </td>
            </tr>

            <tr>
                <th>Payroll </th>
                <td>
                    <?php if($advance->payroll_check==1): ?>
                        <span class="badge badge-success"><i class="fa fa-check"></i> Checked</span>
                    <?php else: ?>
                        <span class="badge badge-danger"><i class="fa fa-times"></i> Not Checked</span>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    <?php echo e($advance->remarks!="" ? $advance->remarks : "N/A"); ?>

                </td>
            </tr>
        <?php else: ?>
            <tr>
                <th align="center" style="color:red;">Driver doesn't exist...</th>
            </tr>
        <?php endif; ?>
        </table>

    <?php endif; ?>
    
    
    
    <?php if($transaction->param_id==26): ?>
        <table class="table table-striped" >
            <tr>
                <th colspan="8" class="text-center">
                    <?php if(!$parts->isEmpty()): ?>
                        <span style="font-size: 20px;display: block;"><?php echo e($parts->first()->parts_invoice->billno); ?></span>
                        <span><?php echo e($parts->first()->parts_invoice->vendor->name); ?></span>
                    <?php else: ?>
                        No Parts Found..
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <th>Part</th>
                <th>Category</th> 
                <th><?php echo app('translator')->getFromJson('fleet.manufacturer'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.availability'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.qty_on_hand'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
            </tr>
            <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
            <tr>
                <td> <?php echo e($dat->parts_zero->item); ?></td>
                <td><?php echo e($dat->category->name); ?></td> 
                <td><?php echo e($dat->parts_zero->manufacturer_details->name); ?></td>
                <td>
                <?php if($dat->availability == 1): ?>
                    <?php echo app('translator')->getFromJson('fleet.available'); ?>
                <?php else: ?>
                    <?php echo app('translator')->getFromJson('fleet.not_available'); ?>
                <?php endif; ?>
                </td>
                <td><?php echo e(Hyvikk::get('currency')." ". $dat->unit_cost); ?></td>
                <td><?php echo e($dat->quantity); ?></td>
                <td><?php echo e(Hyvikk::get('currency')." ". $dat->total); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <th colspan="5"></th>
                <th>Total</th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($parts->sum('total')); ?></th>
            </tr>
            <?php if($parts->first()->parts_invoice->cgst_amt!=""): ?>
            <tr>
                <th colspan="4"></th>
                <th>CGST %</th>
                <th style="font-style: italic;">@ <?php echo e($parts->first()->parts_invoice->cgst); ?> %</th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($parts->first()->parts_invoice->cgst_amt); ?></th>
            </tr>
            <?php endif; ?>
            <?php if($parts->first()->parts_invoice->sgst_amt!=""): ?>
            <tr>
                <th colspan="4"></th>
                <th>SGST %</th>
                <th style="font-style: italic;">@ <?php echo e($parts->first()->parts_invoice->sgst); ?> %</th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($parts->first()->parts_invoice->sgst_amt); ?></th>
            </tr>
            <?php endif; ?>
            <tr>
                <th colspan="4"></th>
                <th colspan="2">Grand Total</th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($parts->first()->parts_invoice->grand_total); ?></th>
            </tr>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==27): ?>
        <table class="table table-striped">
            <?php if($advances->count()>0): ?>
                <?php $__currentLoopData = $advances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($advance->param_id==9 && $advance->value!='' ? 'border-refund' : ''); ?>">
                        <th><?php echo e($advance->param_name->label); ?></th>
                        <td>
                            <?php if($advance->value!=''): ?>
                                <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($advance->value); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($advance->remarks); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr style="border-top:2px solid #4fb765;">
                    <th>Grand Total Advance</th>
                    <th>
                        <?php echo e(Hyvikk::get('currency')); ?><?php echo e($advTotal); ?>

                    </th>
                    <th></th>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="2" align="center" style="color: red"><i>No Advances were given in this booking...</i></td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==28): ?>
        <?php $__currentLoopData = $workOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="table table-striped">
            <?php if(!empty($workOrder->created_on)): ?>
            <tr>
                <th>Created On :</th>
                <td><?php echo e($workOrder->created_on); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>Required By :</th>
                <td><?php echo e(Helper::getCanonicalDate($workOrder->required_by,'default')); ?></td>
            </tr>
            <tr>
                <th>Vehicle :</th>
                <td><?php echo e($workOrder->vehicle->make); ?> - <?php echo e($workOrder->vehicle->model); ?> - <strong><?php echo e($workOrder->vehicle->license_plate); ?></strong></td>
            </tr>
            <tr>
                <th>Vendor :</th>
                <td><?php echo e($workOrder->vendor->name); ?></td>
            </tr>
            <tr>
                <th>Price :</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($workOrder->price,2,'.','')); ?></td>
            </tr>
             <tr>
                <th>Status :</th>
                <td><?php echo e($workOrder->status); ?></td>
            </tr>
            <tr>
                <th>Description :</th>
                <td><?php echo e($workOrder->description); ?></td>
            </tr>
            <tr>
                <th>Meter :</th>
                <td><?php echo e($workOrder->meter); ?></td>
            </tr>
            <tr>
                <th>Note :</th>
                <td><?php echo e($workOrder->note); ?></td>
            </tr>
        </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==29): ?>
        <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="table table-striped">
            <tr>
                <th style="width: 145px;">Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Bank</th>
                <td><?php echo e($bankAccount->bank); ?></td>
            </tr>
            <tr>
                <th>Account No.</th>
                <td><?php echo e($bankAccount->account_no); ?></td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td><?php echo e($bankAccount->ifsc_code); ?></td>
            </tr>
            <tr>
                <th>Branch</th>
                <td><?php echo e($bankAccount->branch); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo e($bankAccount->account_holder); ?></td>
            </tr>
            <tr>
                <th>Starting Amount</th>
                <td><strong><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($bankAccount->starting_amount,2,'.','')); ?></strong></td>
            </tr>
            <tr>
                <th>Account Holder</th>
                <td><?php echo e($bankAccount->account_holder); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo e($bankAccount->address); ?></td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td><?php echo e($bankAccount->mobile); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo e($bankAccount->email); ?></td>
            </tr>
        </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==30): ?>
        <?php $__currentLoopData = $bankTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="table table-striped">
            <tr>
                <th style="width: 130px;">Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>To</th>
                <td><strong><?php echo e($deposit->bank->bank); ?></strong></td>
            </tr>
            <?php if(!empty($deposit->refer_bank)): ?>
            <tr>
                <th>From</th>
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
                <th>Remarks</th>
                <td><?php echo e($deposit->remarks); ?></td>
            </tr>
        </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==31): ?>
        <?php $__currentLoopData = $bankTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="table table-striped">
            <tr>
                <th style="width: 130px;">Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>To</th>
                <td><strong><?php echo e($deposit->bank->bank); ?></strong></td>
            </tr>
            <?php if(!empty($deposit->refer_bank)): ?>
            <tr>
                <th>From</th>
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
                <th>Remarks</th>
                <td><?php echo e($deposit->remarks); ?></td>
            </tr>
        </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==32): ?>
        <table class="table table-striped">
        <?php if(!empty($advance->driver)): ?>
            <tr>
                <th>Driver Name</th>
                <td><strong><?php echo e($advance->driver->name); ?></strong></td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    <?php echo e(Helper::getCanonicalDate($advance->date,'default')); ?>

                </td>
            </tr>

            <tr>
                <th>Payment Method</th>
                <td>
                    <?php echo e($transaction->income_expense->method->label); ?>

                </td>
            </tr>

            <tr>
                <th>Amount <span class="fa fa-inr"></span></th>
                <td>
                    <strong><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($advance->amount); ?></strong>
                </td>
            </tr>

            <tr>
                <th>Payroll </th>
                <td>
                    <?php if($advance->payroll_check==1): ?>
                        <span class="badge badge-success"><i class="fa fa-check"></i> Checked</span>
                    <?php else: ?>
                        <span class="badge badge-danger"><i class="fa fa-times"></i> Not Checked</span>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    <?php echo e($advance->remarks!="" ? $advance->remarks : "N/A"); ?>

                </td>
            </tr>
        <?php else: ?>
            <tr>
                <th align="center" style="color:red;">Driver doesn't exist...</th>
            </tr>
        <?php endif; ?>
        </table>

    <?php endif; ?>
    
    
    
    <?php if($transaction->param_id==35): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($row->transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Document</th>
                <td><?php echo e($row->document->label); ?></td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td><?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <label><?php echo e($row->vehicle->license_plate); ?></label></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td>
                    <?php if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver)): ?>
                        <?php echo e($row->drivervehicle->assigned_driver->name); ?>

                    <?php else: ?>
                    <span style="color: red"><small><i>Driver not assigned</i></small></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>On Date</th>
                <td>
                    <?php echo e(Helper::getCanonicalDate($row->date,'default')); ?>

                </td>
            </tr>
            <tr>
                <th>Valid Till</th>
                <td>
                    <?php echo e(Helper::getCanonicalDate($row->till,'default')); ?><br>
                    <?php ($to = \Carbon\Carbon::now()); ?>

                    <?php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till)); ?>

                    <?php ($diff_in_days = $to->diffInDays($from)); ?>
                    <label><?php echo app('translator')->getFromJson('fleet.after'); ?> <?php echo e($diff_in_days); ?> <?php echo app('translator')->getFromJson('fleet.days'); ?></label>
                </td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($row->amount)); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php if($row->status==1): ?>
                    <span class="text-success">Completed</span>
                    <?php else: ?>
                    <span class="text-warning">In Progress</span>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    <?php echo e($row->remarks!="" ? $row->remarks : "N/A"); ?>

                </td>
            </tr>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==43): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td><?php echo e($adv->driver->name); ?></td>
            </tr>
            <tr>
                <th>Bank</th>
                <td><?php echo e($adv->bank_details->bank); ?>(<?php echo e($adv->bank_details->account_no); ?>)</td>
            </tr>
            <tr>
                <th>Method</th>
                <td><?php echo e($adv->method_param->label); ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo e(Helper::properDecimals($adv->amount)); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo e(Helper::getCanonicalDate($adv->date,'default')); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php if($adv->is_adjusted==1): ?>
                        <span class="badge badge-success">Completed</span>
                    <?php elseif($adv->is_adjusted==2): ?>
                        <span class="badge badge-primary">In Progress</span>
                    <?php elseif($adv->is_adjusted==null): ?>
                        <span class="badge badge-danger">Not Yet Done</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><?php echo e($adv->remarks); ?></td>
            </tr>
            <tr>
                <th>Created On</th>
                <td><?php echo e(Helper::getCanonicalDateTime($adv->created_at,'default')); ?></td>
            </tr>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==44): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Head</th>
                <th><?php echo e($oth->head); ?></th>
            </tr>
            <tr>
                <th>Amount</th>
                <td>
                    <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($oth->amount)); ?>

                    <br>
                    <?php if($oth->type==23): ?>
                        <span class="badge badge-success"><?php echo e($oth->payment_type->label); ?></span>
                    <?php elseif($oth->type==24): ?>
                        <span class="badge badge-danger"><?php echo e($oth->payment_type->label); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Method</th>
                <td><?php echo e($oth->method_param->label); ?></td>
            </tr>
            <tr>
                <th>Ref. No.</th>
                <td><?php echo e($oth->ref_no); ?></td>
            </tr>
            <?php if($oth->bank_id!=''): ?>
            <tr>
                <th>Bank</th>
                <td><?php echo e($oth->bank_details->bank); ?>(<?php echo e($oth->bank_details->account_no); ?>)</td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>Date</th>
                <td><?php echo e(Helper::getCanonicalDate($oth->date,'default')); ?></td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><?php echo e($oth->remarks); ?></td>
            </tr>
            <tr>
                <th>Created On</th>
                <td><?php echo e(Helper::getCanonicalDateTime($oth->created_at,'default')); ?></td>
            </tr>
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==49): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td><?php echo e($purchInfo->vehicle->make); ?>-<?php echo e($purchInfo->vehicle->model); ?>-<strong><?php echo e($purchInfo->vehicle->license_plate); ?></strong></td>
            </tr>
            <tr>
                <th>Purchase Date</th>
                <td><?php echo e(Helper::getCanonicalDate($purchInfo->purchase_date,'default')); ?></td>
            </tr>
            <tr>
                <th>Loan Date</th>
                <td><?php echo e(Helper::getCanonicalDate($purchInfo->loan_date,'default')); ?></td>
            </tr>
            <tr>
                <th>EMI Date</th>
                <td><?php echo e(Helper::getCanonicalDate($purchInfo->emi_date,'default')); ?></td>
            </tr>
            <tr>
                <th>Vehicle Cost</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($purchInfo->vehicle_cost,1,2)); ?></td>
            </tr>
            <tr>
                <th>Down Payment</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($purchInfo->amount_paid,1,2)); ?></td>
            </tr>
            <tr>
                <th>Loan Amount</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($purchInfo->loan_amount,1,2)); ?></td>
            </tr>
            <tr>
                <th>EMI Amount</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($purchInfo->emi_amount,1,2)); ?> <small><i> /per month</i></small></td>
            </tr>
            <tr>
                <th>Loan Duration</th>
                <td>
                    <?php echo e($purchInfo->loan_duration); ?>

                    <?php if($purchInfo->duration_unit!=null): ?>
                        <?php echo e($purchInfo->duration_unit); ?>

                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Bank</th>
                <td><?php echo e($purchInfo->bank_details->bank); ?></td>
            </tr>
            <tr>
                <th>Method</th>
                <td><?php echo e($purchInfo->method_details->label); ?></td>
            </tr>
            <tr>
                <th>Reference No.</th>
                <td><?php echo e($purchInfo->reference_no); ?></td>
            </tr>
            
        </table>
    <?php endif; ?>
    

    
    <?php if($transaction->param_id==50): ?>
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td><?php echo e($transaction->transaction_id); ?></td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td><?php echo e($emi->vehicle->license_plate); ?></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td><?php echo e(!empty($emi->driver) ? $emi->driver->name : ''); ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($emi->amount,1,2)); ?></td>
            </tr>

            <tr>
                <th>Due Date</th>
                <td><?php echo e(Helper::getCanonicalDate($emi->date,'default')); ?></td>
            </tr>
            <tr>
                <th>Paid Date</th>
                <td><?php echo e(Helper::getCanonicalDate($emi->pay_date,'default')); ?></td>
            </tr>

            <tr>
                <th>Bank</th>
                <td><?php echo e(!empty($emi->bank) ? $emi->bank->bank : ''); ?></td>
            </tr>

            <tr>
                <th>Reference No</th>
                <td><?php echo e($emi->reference_no); ?></td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td><?php echo e($emi->remarks!="" ? $emi->remarks : ''); ?></td>
            </tr>
        </table>
    <?php endif; ?>
    
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
    </button>
</div><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/where_from.blade.php ENDPATH**/ ?>