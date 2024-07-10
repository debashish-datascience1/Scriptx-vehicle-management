<table class="table table-striped" >
    <thead class="thead-inverse">
      <tr>
        <th><?php echo app('translator')->getFromJson('fleet.title'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.parts_category'); ?></th> 
        <th><?php echo app('translator')->getFromJson('fleet.status'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.availability'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.unit_cost'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.qty_on_hand'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
        <th><?php echo app('translator')->getFromJson('fleet.manufacturer'); ?></th>
      </tr>
    </thead>
    <tbody>
       
      <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
      <tr>
        
        <td> <?php echo e($dat->title); ?>

        </td>
         <td><?php echo e($dat->category->name); ?></td> 
        
        <td><?php echo e($dat->status); ?></td>
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
        
        <td><?php echo e($dat->manufacture); ?></td>
        
      </tr>
      
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     
    </tbody>
   
  </table><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/parts/view_event.blade.php ENDPATH**/ ?>