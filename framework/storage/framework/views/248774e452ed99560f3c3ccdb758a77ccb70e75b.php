
<table class="table table-striped" >
    
    <thead class="thead-inverse">
        <tr>
            <td colspan="6">
            <?php echo Form::open(['method'=>'post','class'=>'form-inline']); ?>

                <input type="hidden" name="vendor_id" value="<?php echo e($vendor_id); ?>">
                <input type="hidden" name="fuel_type" value="<?php echo e($fuel_type); ?>">
                <input type="hidden" name="from_date" value="<?php echo e($from_date); ?>">
                <input type="hidden" name="to_date" value="<?php echo e($to_date); ?>">
                <input type="hidden" name="vendor_name" value="<?php echo e($vendor_name); ?>">
                <input type="hidden" name="fuel_name" value="<?php echo e($fuel_name); ?>">
                <div class="row" style="width: 100%">
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-12">Vendor Name : <strong><?php echo e($vendor_name); ?></strong></div>
                      <div class="col-md-12">Fuel Type : <strong><?php echo e($fuel_name); ?></strong></div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" formaction="<?php echo e(url('admin/print-fuel-modal-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
                  </div>
                </div>
                
                <?php echo Form::close(); ?> 
            </td>
                
        </tr>
      <tr>
        <th>SL#</th>
        <th>Date</th>
        <th>Vehicle</th>
        <th>Quantity(ltr)</th> 
        <th>Cost per unit</th> 
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
       
      <?php $__currentLoopData = $fuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
      <tr>
        <td><?php echo e($k+1); ?></td>  
        <td><?php echo e(Helper::getCanonicalDate($data->date,'default')); ?></td>  
        <td><strong><?php echo e(strtoupper($data->vehicle_data->license_plate)); ?></strong></td>
         <td><?php echo e($data->qty); ?></td>  
         <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($data->cost_per_unit,2,'.','')); ?></td>  
        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($data->qty * $data->cost_per_unit,2,'.','')); ?></td> 
        
      </tr>

      
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     <tr>
         <th colspan="4"></th>
         <th>Grand Total</th>
         <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($fuelSum,2,'.','')); ?></th>
     </tr>
    </tbody>
   
  </table><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/fuel/view_fuel_details.blade.php ENDPATH**/ ?>