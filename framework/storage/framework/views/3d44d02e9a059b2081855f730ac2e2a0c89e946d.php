<?php $__env->startSection('extra_css'); ?>
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
  .emi_pay{color: white !important}
  #remarks{resize: none;height: 100px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("vehicle-emi.index")); ?>"><?php echo app('translator')->getFromJson('menu.vehicle_emi'); ?></a></li>
<li class="breadcrumb-item active">Pay <?php echo app('translator')->getFromJson('menu.vehicle_emi'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Pay <?php echo app('translator')->getFromJson('menu.vehicle_emi'); ?></h3>
      </div>

      <div class="card-body">
        <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger">
          <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
        <?php endif; ?>

        <?php echo Form::open(['route' => 'vehicle-emi.search','files'=>true,'method'=>'post']); ?>

        <div class="row toprow">
          <div class="col-md-12">
            <div class="form-group">
              <?php echo Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label required','autofocus']); ?>

              <?php echo Form::select('vehicle_id',$vehicles,old('vehicle_id') ?? $request['vehicle_id'],['class' => 'form-control drivers','required','id'=>'vehicle_id','placeholder'=>'Please select a Vehicle']); ?>

            </div>
          </div>
            <div class="col-md-12">
            <?php echo Form::submit('Search', ['class' => 'btn btn-success','id'=>'sub']); ?>

            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <?php if(isset($result)): ?>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">
                    Search Result
                  </h3>
                </div>

                <div class="card-body table-responsive">
                  <table class="table table-bordered table-striped table-hover"  id="myTable">
                    <thead>
                      <tr>
                        <th>SL#</th>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Amount</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $emi_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($k+1); ?></td>
                        <td><?php echo e(Helper::getCanonicalDate($row->date,'default')); ?></td>
                        <td>
                          <?php if($row->is_paid): ?>
                          <?php echo e($row->vehicle->license_plate); ?>

                          <?php else: ?>
                          <?php echo e($row->vehicle); ?>

                          <?php endif; ?>
                        </td>
                        <td><?php echo e(!empty($row->driver_id) ? $row->driver->name : '-'); ?></td>
                        <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(bcdiv($row->amount,1,2)); ?></td>
                        <td>
                          <?php if($row->is_paid): ?>
                            <span class="badge badge-success"><i class="fa fa-check"></i> Completed</span>
                          <?php else: ?>
                            
                            <?php if($row->is_eligible): ?>
                              <a class="btn btn-primary emi_pay" data-purch="<?php echo e($row->purchase_id); ?>" data-vehicle="<?php echo e($row->vehicle_id); ?>" data-date="<?php echo e(Helper::getCanonicalDate($row->date,'default')); ?>"  data-toggle="modal" data-target="#payModal" title="Pay EMI">Pay</a>
                            <?php else: ?>
                                <span class="badge badge-info">Not Yet</span>
                            <?php endif; ?>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
        <?php echo Form::close(); ?>

  </div>

</div>
<!-- Modal -->
<div id="payModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h4 class="modal-title">Pay EMI</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#vehicle_id").select2();
  $(".emi_pay").click(function(){
    var purch = $(this).data("purch");
    var vehicle = $(this).data("vehicle");
    var date = $(this).data("date");
    
    var dataSet = {_token:"<?php echo e(csrf_token()); ?>",purch:purch,vehicle:vehicle,date:date};
    $.post("<?php echo e(route('vehicle-emi.pay-show')); ?>",dataSet).done(function(result){
      console.table(result);
      $("#payModal .modal-body").html(result);
    })
  })
  // $("#sub").click(function(){
  //   if($("#date").val()=="" || $("#date").val()==null){
  //     alert("Date cannot be empty");
  //     $("#date").css('border','1px solid red').focus();
  //     return false;
  //   }else{
  //     $("#date").css('border','');
  //   }
  // });
  $('#date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });

  $("body").on("focus","#pay_date",function(){
    $(this).datepicker({autoclose: true,format: 'dd-mm-yyyy'});
  })

  $("body").on("click","#payEmi",function(){
    var blankTest = /\S/;
    var purchase_id = $("#purchase_id").val();
    var vehicle_id = $("#vehicle_id").val();
    var date = $("#date").val();
    var amount = $("#amount").val();
    var pay_date = $("#pay_date").val();
    var bank = $("#bank").val();
    var method = $("#method").val();
    var reference_no = $("#reference_no").val();
    var remarks = $("#remarks").val();

    if(!blankTest.test(pay_date)){
      alert("Please select Pay Date");
      $("#pay_date").focus();
      return false;
    }
    if(!blankTest.test(bank)){
      alert("Please select Bank");
      $("#bank").focus();
      return false;
    }
    if(!blankTest.test(method)){
      alert("Please select Method of Payment");
      $("#method").focus();
      return false;
    }
    if(!blankTest.test(reference_no)){
      alert("Please enter Reference No");
      $("#reference_no").focus();
      return false;
    }

    if(confirm("Are you sure to Pay EMI")){
      //write for save
      var dataSet = {_token:"<?php echo e(csrf_token()); ?>",purchase_id:purchase_id,vehicle_id:vehicle_id,date:date,amount:amount,pay_date:pay_date,bank_id:bank,method:method,reference_no:reference_no,remarks:remarks};
      $.post("<?php echo e(route('vehicle-emi.store')); ?>",dataSet).done(function(result){
        // console.table(result);return false;
        if(result.status){
          alert(result.msg)
          $("#payModal").modal('hide');
          $('a[data-date='+result.date+']').replaceWith("<label class='badge badge-success'><i class='fa fa-check'></i> Paid</label>")
          // $("#payEmi").replaceWith("<label class='badge badge-success'><i class='fa fa-check'></i> Paid</label>")
        }
      })
    }
  })
});
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/emi/create.blade.php ENDPATH**/ ?>