<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  /* Hide default HTML checkbox */
  .switch input {display:none;}

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
  .custom .nav-link.active {

      background-color: #f4bc4b !important;
      color: inherit;
  }
</style>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("vehicles.index")); ?>"><?php echo app('translator')->getFromJson('fleet.vehicles'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_vehicle'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <?php if(count($errors) > 0): ?>
      <div class="alert alert-danger">
        <ul>
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>


    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.edit_vehicle'); ?></h3>
      </div>

      <div class="card-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-pills custom">
            <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#insurance" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.insurance'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#acq-tab" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.purchase_info'); ?> <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#driver" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.assign_driver'); ?> <i class="fa"></i></a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane active" id="info-tab">
            <?php echo Form::open(['route' =>['vehicles.update',$vehicle->id],'files'=>true, 'method'=>'PATCH','class'=>'form-horizontal','id'=>'accountForm1']); ?>

            <?php echo Form::hidden('user_id',Auth::user()->id); ?>

            <?php echo Form::hidden('id',$vehicle->id); ?>

            <div class="row card-body">
              <div class="col-md-6">
                <div class="form-group" >
                  <?php echo Form::label('make', __('fleet.make'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('make', $vehicle->make,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('model', __('fleet.model'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('model', $vehicle->model,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('type', __('fleet.type'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <select name="type_id" class="form-control" required id="type_id">
                      <option></option>
                      <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($type->id); ?>" <?php if($vehicle->type_id == $type->id): ?> selected <?php endif; ?>><?php echo e($type->displayname); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('year', __('fleet.year'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::number('year', $vehicle->year,['class' => 'form-control','required']); ?>

                  </div>
                </div>

              <div class="form-group">
                <?php echo Form::label('average', __('fleet.average')." (".__('fleet.mpg').")", ['class' => 'col-xs-5 control-label']); ?>

                <div class="col-xs-6">
                <?php echo Form::number('average', $vehicle->average,['class' => 'form-control','required','step'=>'any']); ?>

                </div>
              </div>

                <div class="form-group">
                  <?php echo Form::label('int_mileage', __('fleet.intMileage'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('int_mileage', $vehicle->int_mileage,['class' => 'form-control','required']); ?>

                  </div>
                </div>
                <div class="form-group">
                  <?php echo Form::label('vehicle_image', __('fleet.vehicleImage'), ['class' => 'col-xs-5 control-label']); ?>

                  <?php if($vehicle->vehicle_image != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->vehicle_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
                  <?php endif; ?>
                  <br>
                  <?php echo Form::file('vehicle_image',null,['class' => 'form-control']); ?>

                </div>

                <div class="form-group">
                  <?php echo Form::label('reg_exp_date',__('fleet.reg_exp_date'), ['class' => 'col-xs-5 control-label required']); ?>

                  <div class="col-xs-6">
                    <div class="input-group date">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                      <?php echo Form::text('reg_exp_date', $vehicle->reg_exp_date,['class' => 'form-control','required']); ?>

                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <?php echo Form::label('in_service', __('fleet.service'), ['class' => 'col-xs-5 control-label']); ?>

                    </div>
                    <div class="col-ms-6" style="margin-left: -140px">
                      <label class="switch">
                      <input type="checkbox" name="in_service" value="1" <?php if($vehicle->in_service == '1'): ?> checked <?php endif; ?>>
                      <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group" >
                  <?php echo Form::label('engine_type', __('fleet.engine'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::select('engine_type',["Petrol"=>"Petrol","Diesel"=>"Diesel"],$vehicle->engine_type,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('horse_power', __('fleet.horsePower'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('horse_power', $vehicle->horse_power,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('color', __('fleet.color'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('color', $vehicle->color,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('vin', __('fleet.vin'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('vin', $vehicle->vin,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('license_plate', __('fleet.licensePlate'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('license_plate', $vehicle->license_plate,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('lic_exp_date',__('fleet.lic_exp_date'), ['class' => 'col-xs-5 control-label required']); ?>

                  <div class="col-xs-6">
                    <div class="input-group date">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                      <?php echo Form::text('lic_exp_date', $vehicle->lic_exp_date,['class' => 'form-control','required']); ?>

                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <?php echo Form::label('group_id',__('fleet.selectGroup'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <select id="group_id" name="group_id" class="form-control">
                      <option value=""><?php echo app('translator')->getFromJson('fleet.vehicleGroup'); ?></option>
                      <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>" <?php if($group->id == $vehicle->group_id): ?> selected <?php endif; ?>><?php echo e($group->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <?php echo Form::label('udf1',__('fleet.add_udf'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('udf1', null,['class' => 'form-control']); ?>

                    </div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-info add_udf"> <?php echo app('translator')->getFromJson('fleet.add'); ?></button>
                    </div>
                  </div>
                </div>
                <div class="blank"></div>
                <?php if($udfs != null): ?>
                <?php $__currentLoopData = $udfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase"><?php echo e($key); ?></label> <input type="text" name="udf[<?php echo e($key); ?>]" class="form-control" required value="<?php echo e($value); ?>"></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </div>
            </div>
            <div style=" margin-bottom: 20px;">
              <div class="form-group" style="margin-top: 15px;">
                <div class="col-xs-6 col-xs-offset-3">
                <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']); ?>

                </div>
              </div>
            </div>
            <?php echo Form::close(); ?>

          </div>

          <div class="tab-pane " id="insurance">
            <?php echo Form::open(['url' => 'admin/store_insurance','files'=>true, 'method'=>'post','class'=>'form-horizontal','id'=>'accountForm']); ?>

            <?php echo Form::hidden('user_id',Auth::user()->id); ?>

            <?php echo Form::hidden('vehicle_id',$vehicle->id); ?>

            <div class="row card-body">
              <div class="col-md-8">
                <div class="form-group">
                  <?php echo Form::label('insurance_number', __('fleet.insuranceNumber'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('insurance_number', $vehicle->getMeta('ins_number'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="documents" class="control-label"><?php echo app('translator')->getFromJson('fleet.inc_doc'); ?>
                  </label>
                  <?php if($vehicle->getMeta('documents') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('documents'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('documents',null,['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('exp_date', __('fleet.inc_expirationDate'), ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('exp_date', $vehicle->getMeta('ins_exp_date'),['class' => 'form-control','required']); ?>

                  </div>
                </div>
              </div>
            </div>
            <div style=" margin-bottom: 20px;">
              <div class="form-group" style="margin-top: 15px;">
                <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']); ?>

              </div>
            </div>
            <?php echo Form::close(); ?>

          </div>

          <div class="tab-pane " id="acq-tab">
            <div class="row card-body">
              <div class="col-md-12">
                <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.acquisition'); ?> <?php echo app('translator')->getFromJson('fleet.add'); ?></h3>
                  </div>

                  <div class="card-body">
                    <?php echo Form::open(['route' => 'acquisition.store','method'=>'post','class'=>'form-inline','id'=>'add_form']); ?>

                    <?php echo Form::hidden('user_id',Auth::user()->id); ?>

                    <?php echo Form::hidden('vehicle_id',$vehicle->id); ?>

                    <div class="form-group" style="margin-right: 10px;">
                      <?php echo Form::label('exp_name', __('fleet.expenseType'), ['class' => 'form-label']); ?>

                      <?php echo Form::text('exp_name',  null,['class'=>'form-control','required']);; ?>

                    </div>
                    <div class="form-group"></div>
                    <div class="form-group" style="margin-right: 10px;">
                      <?php echo Form::label('exp_amount', __('fleet.expenseAmount'), ['class' => 'form-label']); ?>

                      <div class="input-group" style="margin-right: 10px;">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
                        <?php echo Form::number('exp_amount',null,['class'=>'form-control','required']);; ?>

                      </div>
                    </div>
                    <div class="form-group"></div>
                    <button type="submit" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.add'); ?></button>
                    <?php echo Form::close(); ?>

                  </div>
                </div>
              </div>
            </div>
            <div class="row card-body" >
              <div class="col-md-12">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.acquisition'); ?> :<strong><?php echo e($vehicle->make); ?> <?php echo e($vehicle->model); ?> <?php echo e($vehicle->license_plate); ?></strong>
                    </h3>
                  </div>
                  <div class="card-body" id="acq_table">
                    <div class="row">
                      <div class="col-md-12 table-responsive">
                        <?php ($value = unserialize($vehicle->getMeta('purchase_info'))); ?>
                        <table class="table">
                            <thead>
                              <th><?php echo app('translator')->getFromJson('fleet.expenseType'); ?></th>
                              <th><?php echo app('translator')->getFromJson('fleet.expenseAmount'); ?></th>
                              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
                            </thead>
                          <tbody id="hvk">
                            <?php if($value != null): ?>
                            <?php ($i=0); ?>
                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <?php ($i+=$row['exp_amount']); ?>
                              <td><?php echo e($row['exp_name']); ?></td>
                              <td><?php echo e(Hyvikk::get('currency')." ". $row['exp_amount']); ?></td>
                              <td>
                              <?php echo Form::open(['route' =>['acquisition.destroy',$vehicle->id],'method'=>'DELETE','class'=>'form-horizontal']); ?>

                              <?php echo Form::hidden("vid",$vehicle->id); ?>

                              <?php echo Form::hidden("key",$key); ?>

                              <button type="button" class="btn btn-danger del_info" data-vehicle="<?php echo e($vehicle->id); ?>" data-key="<?php echo e($key); ?>">
                              <span class="fa fa-times"></span>
                              </button>
                              <?php echo Form::close(); ?>

                              </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <td><strong><?php echo app('translator')->getFromJson('fleet.total'); ?></strong></td>
                              <td><strong><?php echo e(Hyvikk::get('currency')." ". $i); ?></strong></td>
                              <td></td>
                            </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane " id="driver">
            <div class="card-body">
              <?php echo Form::open(['url' => 'admin/assignDriver', 'method'=>'post','class'=>'form-horizontal','id'=>'driverForm']); ?>


              <?php echo Form::hidden('vehicle_id',$vehicle->id); ?>


              <div class="col-md-12">
                <div class="form-group">
                  <?php echo Form::label('driver_id',__('fleet.selectDriver'), ['class' => 'form-label']); ?>


                  <select id="driver_id" name="driver_id" class="form-control" required>
                    <option value=""><?php echo app('translator')->getFromJson('fleet.selectDriver'); ?></option>
                    <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($driver->id); ?>" <?php if($vehicle->getMeta('driver_id') == $driver->id): ?> selected <?php endif; ?>><?php echo e($driver->name); ?><?php if($driver->getMeta('is_active') != 1): ?>
                    ( <?php echo app('translator')->getFromJson('fleet.in_active'); ?> ) <?php endif; ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6" style=" margin-bottom: 20px;">
                <div class="form-group" style="margin-top: 15px;">
                  <div class="col-xs-6 col-xs-offset-3">
                    <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']); ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php echo Form::close(); ?>

        </div>
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
  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter field name');
    }

    else{
      $(".blank").append('<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
  $('#group_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectGroup'); ?>"});
  $('#type_id').select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.type'); ?>"});
  <?php if(isset($_GET['tab']) && $_GET['tab']!=""): ?>
    $('.nav-pills a[href="#<?php echo e($_GET['tab']); ?>"]').tab('show')
  <?php endif; ?>
  $('#start_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#end_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#lic_exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#reg_exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#issue_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });

  $(document).on("click",".del_info",function(e){
    var hvk=confirm("Are you sure?");
    if(hvk==true){
      var vid=$(this).data("vehicle");
      var key = $(this).data('key');
      var action="<?php echo e(route('acquisition.index')); ?>/"+vid;

      $.ajax({
        type: "POST",
        url: action,
        data: "_method=DELETE&_token="+window.Laravel.csrfToken+"&key="+key+"&vehicle_id="+vid,
        success: function(data){
          $("#acq_table").empty();
          $("#acq_table").html(data);
          new PNotify({
            title: 'Deleted!',
            text:'<?php echo app('translator')->getFromJson("fleet.deleted"); ?>',
            type: 'wanring'
          })
        }
        ,
        dataType: "HTML",
      });
    }
  });

  $("#add_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
        $("#acq_table").empty();
        $("#acq_table").html(data);
        new PNotify({
          title: 'Success!',
          text: '<?php echo app('translator')->getFromJson("fleet.exp_add"); ?>',
          type: 'success'
        });
        $('#exp_name').val("");
        $('#exp_amount').val("");
      },
      dataType: "HTML"
    });
    e.preventDefault();
  });

  // $("#accountForm").on("submit",function(e){
  //   $.ajax({
  //     type: "POST",
  //     url: $("#accountForm").attr("action"),
  //     data: new FormData(this),
  //     mimeType: 'multipart/form-data',
  //     contentType: false,
  //               cache: false,
  //               processData:false,
  //     success: new PNotify({
  //           title: 'Success!',
  //           text: '<?php echo app('translator')->getFromJson("fleet.ins_add"); ?>',
  //           type: 'success'
  //       }),
  //   dataType: "json",
  //   });
  //   e.preventDefault();
  // });

  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/vehicles/edit.blade.php ENDPATH**/ ?>