<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.booking_report'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.booking_report'); ?>
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.booking','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
          <div class="form-group" style="margin-right: 5px">
            <?php echo Form::label('year', __('fleet.year1'), ['class' => 'form-label']); ?>

            <?php echo Form::select('year', $years, $year_select,['class'=>'form-control','placeholder'=>'Select Year']);; ?>

          </div>
          <div class="form-group" style="margin-right: 5px">
            <?php echo Form::label('month', __('fleet.month'), ['class' => 'form-label']); ?>

            <?php echo Form::selectMonth('month',$month_select,['class'=>'form-control','placeholder'=>'Select Month']);; ?>

          </div>
          <div class="form-group" style="margin-right: 5px">
            <?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

            <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 150px">
              <option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
              <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vehicle->id); ?>" <?php if($vehicle_select == $vehicle->id): ?> selected <?php endif; ?>><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="form-group" style="margin-right: 5px">
            <?php echo Form::label('customer_id', __('fleet.selectCustomer'), ['class' => 'form-label']); ?>

            <select id="customer_id" name="customer_id" class="form-control vehicles" style="width: 150px">
              <option value=""><?php echo app('translator')->getFromJson('fleet.selectCustomer'); ?></option>
              <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($customer->id); ?>" <?php if($customer_select == $customer->id): ?> selected <?php endif; ?>><?php echo e($customer->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <button type="submit" class="btn btn-info" style="margin-right: 1px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-booking-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
        </div>
        <?php echo Form::close(); ?>


      </div>
    </div>
  </div>
</div>
<?php if(isset($result)): ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.booking_count'); ?> : <?php echo e($bookings->count()); ?>

        </h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table" id="myTable">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
              <th>Advance</th>
              <th>Payment Amount</th>
              <th>Total Amount</th>
              
              
              
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($row->customer->name); ?></td>
              <td>
                <?php if($row->vehicle_id != null): ?>
                <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?>

                <?php endif; ?>
                </td>
              <td><?php if($row->advpay_array != null): ?>
                <i class="fa fa-inr"></i> <?php echo e($row->advpay_array); ?>

              <?php else: ?>
                <span class="badge badge-danger">N/A</span>
              <?php endif; ?></td>
              <td><?php if($row->payment_amount != null): ?>
                <i class="fa fa-inr"></i> <?php echo e($row->payment_amount); ?>

              <?php else: ?>
                <span class="badge badge-danger">N/A</span>
              <?php endif; ?></td>
              <td><?php echo e($row->total_price); ?></td>
               
              
              
              
              <td><a class="DetailsChk"  data-customer_id="<?php echo e($row->customer_id); ?>" data-vehicle_id="<?php echo e($vehicle_select); ?>" data-year="<?php echo e($year_select); ?>" data-month="<?php echo e($month_select); ?>" data-toggle="modal" data-target="#bookingDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green"></span>Details</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
              <th>Vehicle</th>
               <th>Advance</th>
              <th>Payment Amount</th>
              <th>Total Amount</th>
              
              
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- Modal view-->
<div id="bookingDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:158%">
      <div class="modal-header" style="border-bottom:none;">
        <h5>Booking Details Report</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
          Loading...
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
      
    </div>
  </div>
</div>
<!-- Modal -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection("script"); ?>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#customer_id').select2();
    $('#vehicle_id').select2();
    $('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [{
             extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                ]}
        ],

        "language": {
                 "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
              },
        "initComplete": function() {
                myTable.columns().every(function () {
                  var that = this;
                  $('input', this.footer()).on('keyup change', function () {
                      that.search(this.value).draw();
                  });
                });
              }
    });

    $(".DetailsChk").on("click",function(){
    var customer_id = $(this).data("customer_id");
    var vehicle_id = $(this).data("vehicle_id");
    var year = $(this).data("year");
    var month = $(this).data("month");
    
     var arr = [customer_id,vehicle_id,month,year];
    
    $("#bookingDetailsModal .modal-body").load('<?php echo e(url("admin/reports/view_booking_details")); ?>/'+arr,function(res){
      $("#bookingDetailsModal").modal({show:true})
    })
    
  })
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/booking.blade.php ENDPATH**/ ?>