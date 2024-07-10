<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.manage_income'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.addRecord'); ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php echo Form::open(['route' => 'income.store','method'=>'post','class'=>'form-inline','id'=>'income_form']); ?>


          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'col-xs-12 control-label']); ?>

              <div class="col-md-12">
                <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" required style="width: 100%">
                  <option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
                  <?php $__currentLoopData = $vehicels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($vehicle->id); ?>" data-mileage="<?php echo e($vehicle->mileage); ?>"><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-3" style=" margin-top: 5px;">
            <div class="form-group">
              <?php echo Form::label('income_type', __('fleet.incomeType'), ['class' => 'col-xs-12 control-label']); ?>

              <div class="col-md-12">
                <select id="income_type" name="income_type" class="form-control vehicles" required style="width: 100%">
                  <option value=""><?php echo app('translator')->getFromJson('fleet.incomeType'); ?></option>
                  <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('mileage', __('fleet.mileage'), ['class' => 'col-xs-12 control-label']); ?>

              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
                  <input required="required" name="mileage" type="number" id="mileage" class="form-control" min="0">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('date', __('fleet.date'), ['class' => 'col-xs-12 control-label']); ?>

              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                  <input required="required" name="date" type="text" value="<?php echo e(date('Y-m-d')); ?>"  id="date" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3" style="margin-top: 5px;">
            <div class="form-group">
              <?php echo Form::label('revenue', __('fleet.amount'), ['class' => 'col-xs-5 control-label']); ?>

              <div class="col-xs-6">
            <div class="input-group">
              <div class="input-group-prepend">
              <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
              <input required="required" name="revenue" type="number" step="0.01" id="revenue" class="form-control">
            </div>
          </div>
        </div>
          </div>
          <div class="col-md-3" style="margin-top: 5px;">
            <?php ($tax_percent=0); ?>
            <?php if(Hyvikk::get('tax_charge') != "null"): ?>
              <?php ($taxes = json_decode(Hyvikk::get('tax_charge'), true)); ?>
              <?php $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php ($tax_percent += $val ); ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div class="form-group">
              <?php echo Form::label('tax_percent', __('fleet.total_tax'). " (%)", ['class' => 'col-xs-5 control-label']); ?>

              <div class="col-xs-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-percent"></i></span></div>
                  <input name="tax_percent" type="text" id="tax_percent" class="form-control" readonly value="<?php echo e($tax_percent); ?>">
                </div>
              </div>
            </div>

          </div>
          <div class="col-md-3" style=" margin-top: 5px;">
            <div class="form-group">
              <?php echo Form::label('tax_charge_rs', __('fleet.total')." ". __('fleet.tax_charge'), ['class' => 'col-xs-5 control-label']); ?>

              <div class="col-xs-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <input required="required" name="tax_charge_rs" type="text" id="tax_charge_rs" class="form-control" readonly value="0">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3" style=" margin-top: 5px;">
            <div class="form-group">
              <?php echo Form::label('tax_total', __('fleet.total')." ". __('fleet.amount'), ['class' => 'col-xs-5 control-label']); ?>

              <div class="col-xs-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                  <input required="required" name="tax_total" type="text" id="tax_total" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6" style=" margin-top: 5px;">
            <button type="submit" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.add'); ?></button>
          </div>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-4">
              <h3 class="card-title">
                <?php echo app('translator')->getFromJson('fleet.todayIncome'); ?>: <strong><span id="total_today"> <?php echo e(Hyvikk::get('currency')." ". $total); ?> </span> </strong>
              </h3>
            </div>

            <div class="col-md-8 pull-right">
              <?php echo Form::open(['url'=>'admin/income_records','class'=>'form-inline']); ?>

              <div class="form-group">
                <?php echo Form::label('date1','From'); ?>

                <div class="input-group date">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                  <?php echo Form::text('date1', $date1,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'required']); ?>

                </div>
              </div>
              <div class="form-group" style="margin-right: 10px">
                <?php echo Form::label('date2','To'); ?>

                <div class="input-group date">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                  <?php echo Form::text('date2', $date2,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'required']); ?>

                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-search"></i>
                </button>
              </div>
              <?php echo Form::close(); ?>

            </div>
          </div>
        </div>
      </div>
      <div class="card-body table-responsive" id="income">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
                <?php if($today->count() > 0): ?>
                  <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licensePlate'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.incomeType'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.mileage'); ?> (<?php echo e(Hyvikk::get('dis_format')); ?>)</th>
              <th><?php echo app('translator')->getFromJson('fleet.delete'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $today; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
              </td>
              <td>
              <?php if($row->vehicle_id != null): ?>
              <?php echo e($row->vehicle->make); ?>

              <?php endif; ?>
              </td>
              <td>
              <?php if($row->vehicle_id != null): ?>
              <?php echo e($row->vehicle->model); ?>

              <?php endif; ?>
              </td>
              <td>
              <?php if($row->vehicle_id != null): ?>
              <?php echo e($row->vehicle->license_plate); ?>

              <?php endif; ?>
              </td>
              <td><?php echo e($row->category->name); ?></td>
              <td><?php echo e(date($date_format_setting,strtotime($row->date))); ?></td>
              <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->amount); ?></td>
              <td><?php echo e($row->mileage); ?></td>
              <td>
              <?php echo Form::open(['url' => 'admin/income/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

              <?php echo Form::hidden("id",$row->id); ?>

              <button type="button" class="btn btn-danger delete" data-id="<?php echo e($row->id); ?>" title="<?php echo app('translator')->getFromJson('fleet.delete'); ?>"><span class="fa fa-times" aria-hidden="true"></span></button>
              <?php echo Form::close(); ?>

              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th>
                <?php if($today->count() > 0): ?>
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
                <?php endif; ?>
              </th>
              <th><?php echo app('translator')->getFromJson('fleet.make'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.model'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.licensePlate'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.incomeType'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.mileage'); ?> (<?php echo e(Hyvikk::get('dis_format')); ?>)</th>
              <th><?php echo app('translator')->getFromJson('fleet.delete'); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/delete-income','method'=>'POST','id'=>'form_delete']); ?>

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
<!-- Modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
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
<!-- Modal -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $('#revenue').on('change',function(){
    var amount = $('#revenue').val();
    var tax_percent = "<?php echo e($tax_percent); ?>";
    var tax_charges = (Number('<?php echo e($tax_percent); ?>') * Number(amount))/100;
  $('#tax_charge_rs').val(tax_charges);
  $('#tax_total').val(Number(amount) + Number(tax_charges));
    // console.log(tax_percent);
  });
$(document).ready(function() {
  $('#vehicle_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
  $('#income_type').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.incomeType'); ?>"});

  $("#vehicle_id").on("change",function(){
    $("#mileage").val($(this).find(':selected').data("mileage"));
    $("#mileage").attr("min",$(this).find(':selected').data("mileage"));
  });

  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $('#date1').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $('#date2').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

$(document).on("click",".delete",function(e){
  var hvk=confirm("Are you sure?");
  if(hvk==true){
    var id=$(this).data("id");
    var action="<?php echo e(url('admin/income')); ?>"+"/" +id;

      $.ajax({
        type: "POST",
        url: action,
        data: "_method=DELETE&_token="+window.Laravel.csrfToken+"&id="+id,
        success: function(data){
          // alert(data);
          $("#income").empty();
          $("#income").html(data);

          new PNotify({
              title: 'Deleted!',
              text: '<?php echo app('translator')->getFromJson("fleet.deleted"); ?>',
              type: 'wanring'
          })
        }
      ,
      dataType: "HTML"
    });
  }
});

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/income/index.blade.php ENDPATH**/ ?>