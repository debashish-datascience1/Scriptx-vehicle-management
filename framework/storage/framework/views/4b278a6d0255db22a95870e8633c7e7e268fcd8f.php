<div role="tabpanel" style="margin-bottom: 10px;">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"> General Information <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#load-tab" data-toggle="tab" class="nav-link custom_padding"> Load Details <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#journey-tab" data-toggle="tab" class="nav-link custom_padding"> Journey Details <i class="fa"></i></a>
        </li>
        <?php if($booking->status==1): ?>
        <li class="nav-item adexist"><a href="#advance-tab" data-toggle="tab" class="nav-link custom_padding"> Advance <i class="fa"></i></a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
    <!-- General Information Tab-->
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <tr>
                    <th>Customer </th>
                    <td><?php echo e($booking->customer->name); ?></td>
                </tr>
                <tr>
                    <th>Vehicle </th>
                    <td><?php echo e($booking->vehicle->make); ?> - <?php echo e($booking->vehicle->model); ?> - <?php echo e($booking->vehicle->license_plate); ?></td>
                </tr>
                <tr>
                    <th>Driver </th>
                    <td><?php echo e($booking->driver->name); ?></td>
                </tr>
                <tr>
                    <th>Pickup Address</th>
                    <td><?php echo e($booking->pickup_addr); ?></td>
                </tr>
                <tr>
                    <th>Pickup Date & Time</th>
                    <td><?php echo e(Helper::getCanonicalDateTime($booking->pickup,'default')); ?></td>
                </tr>
                <tr>
                    <th>Dropoff Address</th>
                    <td><?php echo e($booking->dest_addr); ?></td>
                </tr>
                <tr>
                    <th>Dropoff Date & Time</th>
                    <td><?php echo e(Helper::getCanonicalDateTime($booking->dropoff,'default')); ?></td>
                </tr>
                <tr>
                    <th>Party Name</th>
                    <td><?php echo e($booking->getMeta('party_name')); ?></td>
                </tr>
                <tr>
                    <th>Narration</th>
                    <td><?php echo e($booking->getMeta('narration')); ?></td>
                </tr>
                <?php if($booking->status==1): ?>
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
                    <td><span class="fa fa-inr"></span> <?php echo e($booking->getMeta('loadprice')); ?> per <?php echo e($params->label=='Quantity' ? 'Quintals' : $params->label); ?></td>
                </tr>
                <tr>
                    <th>Load Quantity</th>
                    <td><?php echo e($booking->getMeta('loadqty')); ?> <?php echo e($params->label=='Quantity' ? 'Quintals' : $params->label); ?></td>
                </tr>
                <tr>
                    <th>Fuel Per Litre</th>
                    <td><span class="fa fa-inr"></span> <?php echo e($booking->getMeta('perltr')); ?></td>
                </tr>
                <tr>
                    <th>Material</th>
                    <td><?php echo e($booking->getMeta('material')); ?></td>
                </tr>
            </table>
        </div>
        <!-- Journey Tab-->
        <div class="tab-pane" id="journey-tab">
            <table class="table table-striped">
                <tr>
                    <th>Initial KM. on Vehicle</th>
                    <td><?php echo e($booking->getMeta('initial_km')); ?> <?php echo e(Hyvikk::get('dis_format')); ?></td>
                </tr>
                <tr>
                    <th>Distance</th>
                    <td><?php echo e($booking->getMeta('distance')); ?></td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td><?php echo e($booking->getMeta('duration_map')); ?></td>
                </tr>
                <tr>
                    <th>Vehicle Mileage</th>
                    <td><?php echo e($booking->getMeta('mileage')); ?> km/ltr</td>
                </tr>
                <tr>
                    <th>Fuel Required(ltr)</th>
                    <td><?php echo e($booking->getMeta('pet_required')); ?> litre</td>
                </tr>
                <tr>
                    <th>Fuel Per Litre</th>
                    <td> <span class="fa fa-inr"></span> <?php echo e($booking->getMeta('perltr')); ?></td>
                </tr>
                <tr>
                    <th>Total Fuel Price</th>
                    <td><span class="fa fa-inr"></span>  <?php echo e($booking->getMeta('petrol_price')); ?></td>
                </tr>
                <tr>
                    <th>Total Freight Price</th>
                    <td><span class="fa fa-inr"></span>  <?php echo e($booking->getMeta('total_price')); ?></td>
                </tr>
                <tr>
                    <th>Advance to Driver</th>
                    <td>
                        <?php if($booking->getMeta('advance_pay')!=''): ?>
                            <span class="fa fa-inr"></span> <?php echo e($booking->getMeta('advance_pay')); ?>

                        <?php else: ?>
                            <span class="badge badge-warning"><i>No Advance was given...</i></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if(!empty($booking->getMeta('fodder_km'))): ?>
                <tr>
                    <th>Addtional Route</th>
                    <td>
                        <?php echo e(!empty($booking->getMeta('fodder_km')) ? $booking->getMeta('fodder_km')."km" :null); ?><br>
                        <small><?php echo e($booking->dest_addr); ?> <span class="fa fa-long-arrow-right"></span> <?php echo e($booking->transaction_details->booking->pickup_addr); ?></small>
                        <br>
                        <small>References Booking <strong><?php echo e($booking->transaction_details->transaction_id); ?></strong></small>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if(!empty($booking->getMeta('fodder_consumption'))): ?>
                <tr>
                    <th>Addtional Fuel Consumption</th>
                    <td><?php echo e(!empty($booking->getMeta('fodder_consumption')) ? $booking->getMeta('fodder_consumption')."ltr" :null); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Ride Status</th>
                    <td>
                        <?php $status_arr = ['upcoming'=>'warning','completed'=>'success','cancelled'=>'danger'] ?>
                        <span class="badge badge-<?php echo e($status_arr[strtolower($booking->getMeta('ride_status'))]); ?>"><?php echo e($booking->getMeta('ride_status')); ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <?php if($booking->status==1): ?>
        <div class="tab-pane" id="advance-tab">
            <table class="table table-striped">
            <?php if($advances->count()>0): ?>
                <?php $__currentLoopData = $advances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th><?php echo e($advance->param_name->label); ?></th>
                        <td>
                            <?php if($advance->value!=''): ?>
                                <i class="fa fa-inr"></i> <?php echo e($advance->value); ?>

                            <?php else: ?>
                                <span class="badge badge-warning">N/A</span>
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
        </div>
        <?php endif; ?>    
    </div>
</div>


<?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bookings/view_event.blade.php ENDPATH**/ ?>