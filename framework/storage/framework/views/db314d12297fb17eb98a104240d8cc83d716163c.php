<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
  <style type="text/css">
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.bookings'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> <?php echo app('translator')->getFromJson('fleet.manage_bookings'); ?> &nbsp;
          <a href="<?php echo e(route("bookings.create")); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.new_booking'); ?></a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive display" id="data_table1" style="padding-bottom: 35px; width: 100%">
            <thead class="thead-inverse">
              <tr>
                <th>
                  <?php if($data->count() > 0): ?>
                  <input type="checkbox" id="chk_all">
                  <?php endif; ?>
                </th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
                <th style="width: 10% !important">Advance to Driver</th>
                
                
                <th><?php echo app('translator')->getFromJson('fleet.booking_status'); ?></th>
                
                <th style="width: 10% !important"><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <tr>
                <td>
                  <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
                </td>
                <td style="width: 10% !important"><?php echo e($row->customer->name); ?></td>
                <td style="width: 10% !important">
                  <?php if($row->vehicle_id): ?>
                  <?php echo e($row->vehicle->make); ?> - <?php echo e($row->vehicle->model); ?> - <?php echo e($row->vehicle->license_plate); ?>

                  <?php endif; ?>
                </td>
                <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->pickup_addr); ?></td>
                <td style="width:10% !important"><?php echo str_replace(",", ",<br>", $row->dest_addr); ?></td>
                <td style="width: 10% !important">
                <?php if($row->pickup != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->pickup))); ?>

                <?php endif; ?>
                </td>
                <td style="width: 10% !important">
                <?php if($row->dropoff != null): ?>
                <?php echo e(date($date_format_setting.' g:i A',strtotime($row->dropoff))); ?>

                <?php endif; ?>
                </td>
                <td style="width: 10% !important">
                  <?php if($row->advance_pay != null): ?>
                    <i class="fa fa-inr"></i> <?php echo e($row->advance_pay); ?>

                  <?php else: ?>
                    <span class="badge badge-danger">N/A</span>
                  <?php endif; ?>
                </td>
                
                
                <td>
                  <?php if($row->ride_status!='Completed'): ?>
                    <span class="text-warning"><?php echo e($row->ride_status); ?></span>
                  <?php else: ?>
                    <span class="text-success"><?php echo e($row->ride_status); ?></span>
                  <?php endif; ?>
                </td>
                
                <td style="width: 10% !important">
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item vbook" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.viewBookingDetails'); ?></a>
                    <a href="print_booking_new/<?php echo e($row->id); ?>" class="dropdown-item" data-id="<?php echo e($row->id); ?>" style="cursor:pointer;" target="_blank"> <span aria-hidden="true" class="fa fa-print" style="color: #1114b4;"></span> Print</a> 
                    <?php if($row->status==0 && $row->ride_status != "Cancelled" && !empty($row->transid) && $row->inc_rows<2): ?>
                    <a class="dropdown-item" href="<?php echo e(url('admin/bookings/'.$row->id.'/edit')); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                    <a class="dropdown-item vtype" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal" > <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                    <?php if($row->receipt != 1): ?>
                    
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if($row->vehicle_id != null): ?>
                    <?php if($row->status==0 && $row->receipt != 1): ?>
                    <?php if(Auth::user()->user_type != "C" && $row->ride_status != "Cancelled"): ?>
                    <a class="dropdown-item vcomplete" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#modalComplete" data-backdrop='static' data-keyboard='false'> <span class="fa fa-check" aria-hidden="true" style="color: #0d9c00"></span> Mark as Complete</a>
                    
                    <?php endif; ?>
                    <?php elseif($row->receipt == 1): ?>
                    
                    
                    <?php endif; ?>
                    <?php endif; ?>

                    
                  </div>
                </div>
                <?php echo Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]); ?>

                <?php echo Form::hidden("id",$row->id); ?>

                <?php echo Form::close(); ?>

                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
              <tr>
                <th>
                <?php if($data->count() > 0): ?>
                  
                <?php endif; ?>
                </th>
                <th><?php echo app('translator')->getFromJson('fleet.customer'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.vehicle'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.pickup_addr'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.dropoff_addr'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.pickup'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.dropoff'); ?></th>
                <th>Advance to Driver</th>
                
                
                <th><?php echo app('translator')->getFromJson('fleet.booking_status'); ?></th>
                
                <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- cancel booking Modal -->
<div id="cancelBooking" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.cancel_booking'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_cancel'); ?></p>
        <?php echo Form::open(['url'=>url('admin/cancel-booking'),'id'=>'cancel_booking']); ?>

        <div class="form-group">
          <?php echo Form::hidden('cancel_id',null,['id'=>'cancel_id']); ?>

          <?php echo Form::label('reason',__('fleet.addReason'),['class'=>"form-label"]); ?>

          <?php echo Form::text('reason',null,['class'=>"form-control",'required']); ?>

        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.submit'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- cancel booking Modal -->

<!-- complete journey Modal -->
<div id="journeyModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.complete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_journey'); ?></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success" href="" id="journey_btn"><?php echo app('translator')->getFromJson('fleet.submit'); ?></a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- complete journey Modal -->

<!-- bulk delete Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/delete-bookings','method'=>'POST','id'=>'form_delete']); ?>

        <div id="bulk_hidden"></div>
        <p><?php echo app('translator')->getFromJson('fleet.confirm_bulk_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
        <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- bulk delete Modal -->

<!-- single delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>

<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.viewBookingDetails'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- single delete Modal -->


<!-- generate invoic Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="card card-info">
        <div class="modal-header">
          <h3 class="modal-title"><?php echo app('translator')->getFromJson('fleet.add_payment'); ?></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="fleet card-body">
          <?php echo Form::open(['route' => 'bookings.complete','method'=>'post']); ?>

          <input type="hidden" name="status" id="status" value="1"/>
          <input type="hidden" name="booking_id" id="bookingId" value=""/>
          <input type="hidden" name="userId" id="userId" value=""/>
          <input type="hidden" name="customerId" id="customerId" value=""/>
          <input type="hidden" name="vehicleId" id="vehicleId" value=""/>
          <input type="hidden" name="type" id="type" value=""/>
          <input type="hidden" name="base_km_1" value="" id="base_km_1">
          <input type="hidden" name="base_fare_1" value="" id="base_fare_1">
          <input type="hidden" name="wait_time_1" value="" id="wait_time_1">
          <input type="hidden" name="std_fare_1" value="" id="std_fare_1">
          <input type="hidden" name="base_km_2" value="" id="base_km_2">
          <input type="hidden" name="base_fare_2" value="" id="base_fare_2">
          <input type="hidden" name="wait_time_2" value="" id="wait_time_2">
          <input type="hidden" name="std_fare_2" value="" id="std_fare_2">
          <input type="hidden" name="base_km_3" value="" id="base_km_3">
          <input type="hidden" name="base_fare_3" value="" id="base_fare_3">
          <input type="hidden" name="wait_time_3" value="" id="wait_time_3">
          <input type="hidden" name="std_fare_3" value="" id="std_fare_3">
          <?php ($no_of_tax = 0); ?>
          <?php if(Hyvikk::get('tax_charge') != "null"): ?>
            <?php ($no_of_tax = sizeof(json_decode(Hyvikk::get('tax_charge'), true))); ?>
            <?php ($taxes = json_decode(Hyvikk::get('tax_charge'), true)); ?>
            <?php ($i=0); ?>
            <?php $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <input type="hidden" name="<?php echo e('tax_'.$i); ?>" value="<?php echo e($val); ?>" class="<?php echo e('tax_'.$i); ?>">
              <?php ($i++); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.incomeType'); ?></label>
                <select id="income_type" name="income_type" class="form-control vehicles" required>
                  <option value=""><?php echo app('translator')->getFromJson('fleet.incomeType'); ?></option>
                  <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.daytype'); ?></label>
                <select id="day" name="day" class="form-control vehicles" required>
                  <option value="1" selected>Weekdays</option>
                  <option value="2">Weekend</option>
                  <option value="3">Night</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.trip_mileage'); ?> (<?php echo e(Hyvikk::get('dis_format')); ?>)</label>
                <?php echo Form::number('mileage',null,['class'=>'form-control sum','min'=>1,'id'=>'mileage']); ?>

              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.waitingtime'); ?></label>
                <?php echo Form::number('waiting_time',0,['class'=>'form-control sum','min'=>0,'id'=>'waiting_time']); ?>

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.total_tax'); ?> (%) </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text fa fa-percent"></span></div>
                  <?php echo Form::number('total_tax_charge',0,['class'=>'form-control sum','readonly','id'=>'total_tax_charge']); ?>

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.tax_charge'); ?></label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <?php echo Form::number('total_tax_charge_rs',0,['class'=>'form-control sum','readonly','id'=>'total_tax_charge_rs']); ?>

                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.amount'); ?> </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <?php echo Form::number('total',null,['class'=>'form-control','id'=>'total','required']); ?>

                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.total'); ?> <?php echo app('translator')->getFromJson('fleet.amount'); ?> </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <?php echo Form::number('tax_total',null,['class'=>'form-control','id'=>'tax_total','readonly']); ?>

                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label"><?php echo app('translator')->getFromJson('fleet.date'); ?></label>
                <div class='input-group'>
                  <div class="input-group-prepend">
                    <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                  </div>
                  <?php echo Form::text('date',date('Y-m-d'),['class'=>'form-control','id'=>'date']); ?>

                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <?php echo Form::submit(__('fleet.invoice'), ['class' => 'btn btn-info']); ?>

            </div>
          </div>
          <?php echo Form::close(); ?>

        </div> 
      </div>
    </div>
  </div>
</div>
<!-- generate invoice modal -->


<div class="modal fade" id="modalComplete" tabindex="-1" role="dialog" aria-labelledby="modalCompleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCompleteLabel">Complete Booking Process</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>

<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">

  // Complete Modal
  $(".vcomplete").click(function(){
    var id = $(this).data('id');
    $("#modalComplete .modal-body").load('<?php echo e(url("admin/bookings/modalcomplete")); ?>/'+id,function(result){
      $("#modalComplete").modal({show:true, backdrop:'static',keyboard:false})
    })
  })
  
  $(document).on('keyup','#toll_tax,#food,#labour,#advance,#others,#refund,#tyre,#donations,#documents,#fuel,#maintenance,#electrical',function(){
    var total = $("#total_adv").val();
    var toll_tax = $("#toll_tax").val()!='' ? $("#toll_tax").val() : 0;
    var food = $("#food").val()!='' ? $("#food").val() : 0;
    var labour = $("#labour").val()!='' ? $("#labour").val() : 0;
    var advance = $("#advance").val()!='' ? $("#advance").val() : 0;
    var refund = $("#refund").val()!='' ? $("#refund").val() : 0;
    var tyre = $("#tyre").val()!='' ? $("#tyre").val() : 0;
    var donations = $("#donations").val()!='' ? $("#donations").val() : 0;
    var documents = $("#documents").val()!='' ? $("#documents").val() : 0;
    var fuel = $("#fuel").val()!='' ? $("#fuel").val() : 0;
    var maintenance = $("#maintenance").val()!='' ? $("#maintenance").val() : 0;
    var electrical = $("#electrical").val()!='' ? $("#electrical").val() : 0;
    console.log($(this).next().attr('class'));
    // console.log($(this).next());
    if($(this).val()!="" && $(this).next().attr('class')!='prem'){
      var alhead = $(this).attr('name');
      // console.log(alhead);
      $("<div class='prem'><textarea name='remarks["+alhead+"]' class='form-control remarks' style='resize:none;height:100px;margin-top:10px;' placeholder='Remarks...' required></textarea></div>").insertAfter($(this));
    }
    if($(this).val()=="")
      $(this).next().remove('.prem');

    

    var gtotal = parseInt(toll_tax)+parseInt(food)+parseInt(labour)+parseInt(advance)+parseInt(refund)+parseInt(tyre)+parseInt(donations)+parseInt(documents)+parseInt(fuel)+parseInt(maintenance)+parseInt(electrical);
    var remain = (total-gtotal);
    
    if(total<gtotal){
      $(this).val('');
    // reserting others
      var othr = {_token:'<?php echo e(csrf_token()); ?>',total:total};
      $('.from-input').each(function(){
        var self = $(this);
        var inputName = self.attr("name");
        if(self.val()!=null || self.val()!=0 || self.val()!=0.00 || self.val()!='undefined')
        othr[inputName] = self.val();
        // othr.push(self.val());
      })
      var posted = $.post('<?php echo e(url("admin/bookings/markascomplete/others")); ?>',othr);
      posted.done(function(res){
        console.log(res);
        $("#others").val(res.value);
      })
      console.log(othr)
      // $("#others").val(othr);
      $(this).next().remove('.prem');
      $(this).focus();
    }else{
      $("#others").val(remain);
      if($("#others").val()!=0)
        $("#others").next().prop('readonly',false);
      else
        $("#others").next().prop('readonly',true);
      

      // console.log(remain);
      // console.log(typeof remain);
    }
  })



  <?php if(Session::get('msg')): ?>
    new PNotify({
        title: 'Success!',
        text: '<?php echo e(Session::get('msg')); ?>',
        type: 'success'
      });
  <?php endif; ?>

  $(document).ready(function() {
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript">
  $(document).on("click", ".open-AddBookDialog", function () {
    // alert($(this).data('base_km_1'));
    // window.open("route('bookings.index')/?type="+$(this).data('vehicle-type'));

    // const query = new URLSearchParams(window.location.search);
    // query.append("type", "true");

    // window.location.search = 'type='+$(".fleet #type").val( type );

     var booking_id = $(this).data('booking-id');

     $(".fleet #bookingId").val( booking_id );

     var user_id = $(this).data('user-id');
     $(".fleet #userId").val( user_id );

     var customer_id = $(this).data('customer-id');
     $(".fleet #customerId").val( customer_id );

     var vehicle_id = $(this).data('vehicle-id');
     $(".fleet #vehicleId").val( vehicle_id );

     var type = $(this).data('vehicle-type');
     $(".fleet #type").val( type );

     $(".fleet #mileage").val($(this).data('base-mileage'));
     $(".fleet #total").val($(this).data('base-fare'));

     $(".fleet #base_km_1").val($(this).data('base_km_1'));
     $(".fleet #base_fare_1").val($(this).data('base_fare_1'));
     $(".fleet #wait_time_1").val($(this).data('wait_time_1'));
     $(".fleet #std_fare_1").val($(this).data('std_fare_1'));
     $(".fleet #base_km_2").val($(this).data('base_km_2'));
     $(".fleet #base_fare_2").val($(this).data('base_fare_2'));
     $(".fleet #wait_time_2").val($(this).data('wait_time_2'));
     $(".fleet #std_fare_2").val($(this).data('std_fare_2'));
     $(".fleet #base_km_3").val($(this).data('base_km_3'));
     $(".fleet #base_fare_3").val($(this).data('base_fare_3'));
     $(".fleet #wait_time_3").val($(this).data('wait_time_3'));
     $(".fleet #std_fare_3").val($(this).data('std_fare_3'));

    var total = $("#total").val();

    var i;
    var tax_size = '<?php echo e($no_of_tax); ?>';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

  });

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#journeyModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#journey_btn").attr("href","<?php echo e(url('admin/bookings/complete/')); ?>/"+id);
  });

  $('.vbook').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('<?php echo e(url("admin/vehicle/event")); ?>/'+id)
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/bookings/event")); ?>/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
      if($('.adexist').length) $("#myModal2 .modal-content").css('width','111%');
      else $("#myModal2 .modal-content").css('width','100%');
    });
  });

  $('#cancelBooking').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#cancel_id").val(id);
  });
</script>

<!-- testing total-->
<script type="text/javascript" language="javascript">
$(".sum").change(function(){
  // alert($("#base_km_1").val());
  // alert($('.vtype').data('base_km_1'));
  // console.log($("#type").val());

    var day = $("#day").find(":selected").val();
    if(day == 1){
      var base_km = $("#base_km_1").val();
      var base_fare = $("#base_fare_1").val();
      var wait_time = $("#wait_time_1").val();
      var std_fare = $("#std_fare_1").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 2){
      var base_km = $("#base_km_2").val();
      var base_fare = $("#base_fare_2").val();
      var wait_time = $("#wait_time_2").val();
      var std_fare = $("#std_fare_2").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 3){
      var base_km = $("#base_km_3").val();
      var base_fare = $("#base_fare_3").val();
      var wait_time =$("#wait_time_3").val();
      var std_fare = $("#std_fare_3").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }
    $("#total").val(total);
    var i;
    var tax_size = '<?php echo e($no_of_tax); ?>';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);
});

  $("#total").change(function(){
    var total = $("#total").val();
    var i;
    var tax_size = '<?php echo e($no_of_tax); ?>';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "<?php echo app('translator')->getFromJson('fleet.delete_error'); ?>",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
    }
  });

  // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/bookings/index.blade.php ENDPATH**/ ?>