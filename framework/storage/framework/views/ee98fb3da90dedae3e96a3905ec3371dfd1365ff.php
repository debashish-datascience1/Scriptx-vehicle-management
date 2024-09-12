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
  .show-days{margin-top: 6px;}
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
            <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.general_info'); ?> </a></li>
            <li class="nav-item"><a class="nav-link" href="#insurance" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.vehicleDocs'); ?> </a></li>
            <li class="nav-item"><a class="nav-link" href="#acq-tab" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.purchase_info'); ?> </a></li>
            <li class="nav-item"><a class="nav-link" href="#driver" data-toggle="tab"> <?php echo app('translator')->getFromJson('fleet.assign_driver'); ?> </a></li>
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
                  <?php echo Form::label('year', __('fleet.registrationDate'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('year', Helper::indianDateFormat($vehicle->year),['class' => 'form-control','required','readonly']); ?>

                  </div>
                </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <?php echo Form::label('average', __('fleet.average')." (".__('fleet.mpg').")", ['class' =>'']); ?>

                    <?php echo Form::text('average', $vehicle->average,['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']); ?>

                  </div>
                  <div class="col-md-6">
                    <?php echo Form::label('average', __('fleet.average')." (".__('fleet.tpl').")", ['class' => 'control-label']); ?>

                    <div class="row">
                      <div class="col-md-6">
                        <?php echo Form::select('hours',Helper::timeArray(100), empty($vehicle->time_average) ? null :  explode(":",$vehicle->time_average)[0],['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Hours']); ?>

                      </div>
                      <div class="col-md-6">
                        <?php echo Form::select('mins',Helper::timeArray(59), empty($vehicle->time_average) ? null : explode(":",$vehicle->time_average)[1],['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Minutes']); ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

                <div class="form-group">
                  <?php echo Form::label('int_mileage', __('fleet.intMileage'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('int_mileage', $vehicle->int_mileage,['class' => 'form-control','required']); ?>

                  </div>
                </div>
                <div class="form-group">
                <?php echo Form::label('owner_name', __('fleet.ownerName'), ['class' => 'col-xs-5 control-label']); ?>

                <div class="col-xs-6">
                <?php echo Form::text('owner_name', $vehicle->owner_name,['class' => 'form-control']); ?>

                </div>
              </div>
                <div class="form-group">
                  <?php echo Form::label('rc_image', __('fleet.rcUpload'), ['class' => 'col-xs-5 control-label']); ?>

                  <?php if($vehicle->rc_image != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->rc_image)); ?>" target="_blank" class="col-xs-3 control-label">View</a>
                  <?php endif; ?>
                  <br>
                  <?php echo Form::file('rc_image',null,['class' => 'form-control']); ?>

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
                  <?php echo Form::label('color', __('fleet.color'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('color', $vehicle->color,['class' => 'form-control','required']); ?>

                  </div>
                </div>

                

              <div class="form-group">
                <?php echo Form::label('engine_no', __('fleet.engine_no'), ['class' => 'col-xs-5 control-label']); ?>

                <div class="col-xs-6">
                 <?php echo Form::text('engine_no', $vehicle->engine_no,['class' => 'form-control','required']); ?>

                </div>
              </div>

              <div class="form-group">
                <?php echo Form::label('chassis_no', __('fleet.chassis_no'), ['class' => 'col-xs-5 control-label']); ?>

                <div class="col-xs-6">
                 <?php echo Form::text('chassis_no', $vehicle->chassis_no,['class' => 'form-control','required']); ?>

                </div>
              </div>

                <div class="form-group">
                  <?php echo Form::label('license_plate', __('fleet.licenseNo'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                    <?php echo Form::text('license_plate', $vehicle->license_plate,['class' => 'form-control','required']); ?>

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
                <div class="form-group">
                  <?php echo Form::label('owner_number', __('fleet.ownerNumber'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('owner_number', $vehicle->owner_number,['class' => 'form-control']); ?>

                  </div>
                </div>
                <div class="form-group">
                  <?php echo Form::label('rc_number', __('fleet.rcNumber'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('rc_number', $vehicle->rc_number,['class' => 'form-control']); ?>

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
              <div class="col-md-4">
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
                  <?php echo Form::file('documents',['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php
                        $insurance_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,36);
                        $fitness_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,37);
                        $roadtax_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,38);
                        $permit_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,39);
                        $pollution_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,40);
                    ?>
                  <?php echo Form::label('exp_date',$insurance_NewExpiryDate->status ? __('fleet.inc_expirationDate')."(OLD)" : __('fleet.inc_expirationDate') , ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('exp_date',!empty($vehicle->getMeta('ins_exp_date')) ? Helper::indianDateFormat($vehicle->getMeta('ins_exp_date')) : null,['class' => 'form-control','required','readonly','style'=>$insurance_NewExpiryDate->status ? 'pointer-events:none' : '']); ?>

                    
                  </div>
                </div>
                <?php if($insurance_NewExpiryDate->status): ?>
                <div class="form-group">
                  <?php echo Form::label('insurance_valid', "Insurance Valid Till (Renewed)", ['class' => 'control-label']); ?>

                  <br>
                  <?php echo Form::label('insurance_valid', Helper::getCanonicalDate($insurance_NewExpiryDate->date,'default'), ['class' => 'control-label']); ?> 
                  /
                  <?php echo Form::label('insurance_valid', Helper::getCanonicalDate($insurance_NewExpiryDate->date,true), ['class' => 'control-label']); ?>

                </div>
                <?php endif; ?>
                <div class="form-group">
                  <?php echo Form::label('ins_renew_duration', __('fleet.ins_renew_duration'), ['class' => 'control-label required']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('ins_renew_duration', $vehicle->getMeta('ins_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']); ?>

                    </div>
                    <div class="col-md-4">
                      <?php echo Form::select('insurance_duration_unit',Helper::durationUnits(),$vehicle->getMeta('insurance_duration_unit'),['class'=>'form-control','placeholder'=>'Select Duration','id'=>'insurance_duration_unit']); ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('fitness_tax', __('fleet.fitnessTax'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('fitness_tax', $vehicle->getMeta('fitness_tax'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="fitness_taxdocs" class="control-label"><?php echo app('translator')->getFromJson('fleet.fitnessDocuments'); ?>
                  </label>
                  <?php if($vehicle->getMeta('fitness_taxdocs') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('fitness_taxdocs'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('fitness_taxdocs',['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('fitness_expdate', $fitness_NewExpiryDate->status ? __('fleet.fitnessExpirationDate')."(OLD)" : __('fleet.fitnessExpirationDate'), ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('fitness_expdate',!empty($vehicle->getMeta('fitness_expdate')) ? Helper::indianDateFormat($vehicle->getMeta('fitness_expdate')) : null,['class' => 'form-control','required','readonly', 'style'=>$fitness_NewExpiryDate->status ? 'pointer-events:none' : '']); ?>

                  </div>
                </div>
                <?php if($fitness_NewExpiryDate->status): ?>
                <div class="form-group">
                  <?php echo Form::label('fitness_valid', "Fitness Valid Till (Renewed)", ['class' => 'control-label']); ?>

                  <br>
                  <?php echo Form::label('fitness_valid', Helper::getCanonicalDate($fitness_NewExpiryDate->date,'default'), ['class' => 'control-label']); ?> 
                  /
                  <?php echo Form::label('fitness_valid', Helper::getCanonicalDate($fitness_NewExpiryDate->date,true), ['class' => 'control-label']); ?>

                </div>
                <?php endif; ?>
                <div class="form-group">
                  <?php echo Form::label('fitness_renew_duration', __('fleet.fitness_renew_duration'), ['class' => 'control-label required']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('fitness_renew_duration', $vehicle->getMeta('fitness_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']); ?>

                    </div>
                    <div class="col-md-4">
                      <?php echo Form::select('fitness_duration_unit',Helper::durationUnits(),$vehicle->getMeta('fitness_duration_unit'),['class'=>'form-control','placeholder'=>'Select Duration','id'=>'fitness_duration_unit']); ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('road_tax', __('fleet.roadTax'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('road_tax', $vehicle->getMeta('road_tax'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="road_docs" class="control-label"><?php echo app('translator')->getFromJson('fleet.roadTaxDocuments'); ?>
                  </label>
                  <?php if($vehicle->getMeta('road_docs') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('road_docs'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('road_docs',['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('road_expdate', $roadtax_NewExpiryDate->status ? __('fleet.roadTaxExpDate')."(OLD)" : __('fleet.roadTaxExpDate'), ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('road_expdate', !empty($vehicle->getMeta('road_expdate')) ? Helper::indianDateFormat($vehicle->getMeta('road_expdate')) : null,['class' => 'form-control','required','readonly','style'=> $roadtax_NewExpiryDate->status ? 'pointer-events:none' : '']); ?>

                  </div>
                </div>
                <?php if($roadtax_NewExpiryDate->status): ?>
                <div class="form-group">
                  <?php echo Form::label('roadtax_valid', "Road Tax Valid Till (Renewed)", ['class' => 'control-label']); ?>

                  <br>
                  <?php echo Form::label('roadtax_valid', Helper::getCanonicalDate($roadtax_NewExpiryDate->date,'default'), ['class' => 'control-label']); ?> 
                  /
                  <?php echo Form::label('roadtax_valid', Helper::getCanonicalDate($roadtax_NewExpiryDate->date,true), ['class' => 'control-label']); ?>

                </div>
                <?php endif; ?>
                <div class="form-group">
                  <?php echo Form::label('roadtax_renew_duration', __('fleet.roadtax_renew_duration'), ['class' => 'control-label required']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('roadtax_renew_duration', $vehicle->getMeta('roadtax_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']); ?>

                    </div>
                    <div class="col-md-4">
                      <?php echo Form::select('roadtax_duration_unit',Helper::durationUnits(),$vehicle->getMeta('roadtax_duration_unit'),['class'=>'form-control','placeholder'=>'Select Duration','id'=>'roadtax_duration_unit']); ?>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('permit_number', __('fleet.permitNumber'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('permit_number', $vehicle->getMeta('permit_number'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="permit_docs" class="control-label"><?php echo app('translator')->getFromJson('fleet.permitDocuments'); ?>
                  </label>
                  <?php if($vehicle->getMeta('permit_docs') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('permit_docs'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('permit_docs',['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('permit_expdate', $permit_NewExpiryDate->status ? __('fleet.permitExpDate')."(OLD)" : __('fleet.permitExpDate'), ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('permit_expdate',!empty($vehicle->getMeta('permit_expdate')) ? Helper::indianDateFormat($vehicle->getMeta('permit_expdate')) :null,['class' => 'form-control','required','readonly','style'=>$permit_NewExpiryDate->status ? 'pointer-events:none' : '']); ?>

                  </div>
                </div>
                <?php if($permit_NewExpiryDate->status): ?>
                <div class="form-group">
                  <?php echo Form::label('permit_valid', "Permit Valid Till (Renewed)", ['class' => 'control-label']); ?>

                  <br>
                  <?php echo Form::label('permit_valid', Helper::getCanonicalDate($permit_NewExpiryDate->date,'default'), ['class' => 'control-label']); ?> 
                  /
                  <?php echo Form::label('permit_valid', Helper::getCanonicalDate($permit_NewExpiryDate->date,true), ['class' => 'control-label']); ?>

                </div>
                <?php endif; ?>
                <div class="form-group">
                  <?php echo Form::label('permit_renew_duration', __('fleet.permit_renew_duration'), ['class' => 'control-label required']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('permit_renew_duration', $vehicle->getMeta('permit_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']); ?>

                    </div>
                    <div class="col-md-4">
                      <?php echo Form::select('permit_duration_unit',Helper::durationUnits(),$vehicle->getMeta('permit_duration_unit'),['class'=>'form-control','placeholder'=>'Select Duration','id'=>'permit_duration_unit']); ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('pollution_tax', __('fleet.pollutionNumber'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('pollution_tax', $vehicle->getMeta('pollution_tax'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="pollution_docs" class="control-label"><?php echo app('translator')->getFromJson('fleet.pollutionDocuments'); ?>
                  </label>
                  <?php if($vehicle->getMeta('pollution_docs') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('pollution_docs'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('pollution_docs',['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                  <?php echo Form::label('pollution_expdate', $pollution_NewExpiryDate->status ? __('fleet.pollutionExpDate')."(OLD)" : __('fleet.pollutionExpDate'), ['class' => 'control-label required']); ?>

                  <div class="input-group date">
                    
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?php echo Form::text('pollution_expdate', $pollution_NewExpiryDate->status ? Helper::indianDateFormat($pollution_NewExpiryDate->date) : Helper::indianDateFormat($vehicle->getMeta('pollution_expdate')),['class' => 'form-control','required','readonly','style'=>$pollution_NewExpiryDate->status ? 'pointer-events:none' : '']); ?>

                  </div>
                </div>
                <?php if($pollution_NewExpiryDate->status): ?>
                <div class="form-group">
                  <?php echo Form::label('pollution_valid', "Pollution Valid Till (Renewed)", ['class' => 'control-label']); ?>

                  <br>
                  <?php echo Form::label('pollution_valid', Helper::getCanonicalDate($pollution_NewExpiryDate->date,'default'), ['class' => 'control-label']); ?> 
                  /
                  <?php echo Form::label('pollution_valid', Helper::getCanonicalDate($pollution_NewExpiryDate->date,true), ['class' => 'control-label']); ?>

                </div>
                <?php endif; ?>
                <div class="form-group">
                  <?php echo Form::label('pollution_renew_duration', __('fleet.pollution_renew_duration'), ['class' => 'control-label required']); ?>

                  <div class="row">
                    <div class="col-md-8">
                      <?php echo Form::text('pollution_renew_duration', $vehicle->getMeta('pollution_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']); ?>

                    </div>
                    <div class="col-md-4">
                      <?php echo Form::select('pollution_duration_unit',Helper::durationUnits(),$vehicle->getMeta('pollution_duration_unit'),['class'=>'form-control','placeholder'=>'Select Duration','id'=>'pollution_duration_unit']); ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('fast_tag', __('fleet.fastTagNumber'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('fast_tag', $vehicle->getMeta('fast_tag'),['class' => 'form-control','required']); ?>

                </div>
                <div class="form-group">
                  <label for="fasttag_docs" class="control-label"><?php echo app('translator')->getFromJson('fleet.fastTagDoc'); ?>
                  </label>
                  <?php if($vehicle->getMeta('fasttag_docs') != null): ?>
                  <a href="<?php echo e(asset('uploads/'.$vehicle->getMeta('fasttag_docs'))); ?>" target="_blank">View</a>
                  <?php endif; ?>
                  <?php echo Form::file('fasttag_docs',['class' => 'form-control']); ?>

                </div>
                
              </div>
            </div>
            <hr>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo Form::label('gps_number', __('fleet.gpsNumber'), ['class' => 'control-label']); ?>

                  <?php echo Form::text('gps_number', $vehicle->getMeta('gps_number'),['class' => 'form-control','required']); ?>

                </div>
              </div>
            </div>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group" style="margin-top: 15px;">
                  <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning','id'=>'doc_sub']); ?>

                </div>
              </div>
            </div>
            <?php echo Form::close(); ?>

          </div>

          <div class="tab-pane " id="acq-tab">
          <?php echo Form::open(['route' => 'purchase-info','method'=>'post','id'=>'add_form']); ?>

            <?php echo Form::hidden('user_id',Auth::user()->id); ?>

            <?php echo Form::hidden('vehicle_id',$vehicle->id); ?>

            <div class="row card-body">
              <div class="col-md-2">
                  <div class="form-group" >
                    <?php echo Form::label('purchase_date', __('fleet.dateOfPurchase'), ['class' => 'col-xs-5 control-label']); ?>

                    <div class="col-xs-6">
                    <?php echo Form::text('purchase_date',!empty($purchase_info->purchase_date) ? Helper::indianDateFormat($purchase_info->purchase_date) : null,['class' => 'form-control','required','id'=>'purchase_date','readonly']); ?>

                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group" >
                    <?php echo Form::label('vehicle_cost', __('fleet.vehicleCost'), ['class' => 'col-xs-5 control-label']); ?>

                    <div class="col-xs-6">
                    <?php echo Form::text('vehicle_cost',$purchase_info->vehicle_cost ?? "",['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']); ?>

                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group" >
                    <?php echo Form::label('amount_paid', __('fleet.amountPaid'), ['class' => 'col-xs-5 control-label']); ?>

                    <div class="col-xs-6">
                    <?php echo Form::text('amount_paid',$purchase_info->amount_paid ?? "",['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']); ?>

                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group" >
                    <?php echo Form::label('bank', __('fleet.downpayment_bank'), ['class' => 'col-xs-5 control-label']); ?>

                    <div class="col-xs-6">
                    <?php echo Form::select('bank',$banks,$purchase_info->bank ?? "",['class' => 'form-control','required','placeholder'=>'Select Bank']); ?>

                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group" >
                    <?php echo Form::label('method', __('fleet.downpayment_method'), ['class' => 'col-xs-5 control-label']); ?>

                    <div class="col-xs-6">
                    <?php echo Form::select('method',$methods,$purchase_info->method ?? "",['class' => 'form-control','required','placeholder'=>'Select Method']); ?>

                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                <div class="form-group" >
                  <?php echo Form::label('reference_no', __('fleet.reference_no'), ['class' => 'col-xs-5 control-label']); ?>

                  <div class="col-xs-6">
                  <?php echo Form::text('reference_no',$purchase_info->reference_no ?? "",['class' => 'form-control','required','placeholder'=>'Enter Reference No.']); ?>

                  </div>
                </div>
            </div>
            </div>
            <div class="card card-success">
                <div class="card-header">
                  <h4 class="card-title"><?php echo app('translator')->getFromJson('fleet.loanDetails'); ?></h4>
                </div>
                <div class="card-body">
                  <div class="row form-group">
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('loan_date', __('fleet.loanDate'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('loan_date',!empty($purchase_info->loan_date) ? Helper::indianDateFormat($purchase_info->loan_date) : null,['class' => 'form-control','required','id'=>'loan_date','readonly']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('loan_amount', __('fleet.loanAmount'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('loan_amount',$purchase_info->loan_amount ?? "",['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        <?php echo Form::label('bank_name', __('fleet.bankName'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('bank_name',$purchase_info->bank_name ?? "",['class' => 'form-control','required']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        <?php echo Form::label('loan_account', __('fleet.loanAccount'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('loan_account',$purchase_info->loan_account ?? "",['class' => 'form-control','required']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('emi_date', __('fleet.emiDate'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('emi_date',!empty($purchase_info->emi_date) ? Helper::indianDateFormat($purchase_info->emi_date) : null,['class' => 'form-control','required','readonly']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('emi_amount', __('fleet.emiAmount'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('emi_amount',$purchase_info->emi_amount ?? "",['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']); ?>

                        <small><i>per month</i></small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('loan_duration', __('fleet.loanDuration'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::text('loan_duration',$purchase_info->loan_duration ?? "",['class' => 'form-control','required','onkeypress'=>'return isWholeNumber(event)']); ?>

                        <small><i>how many months?</i></small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" >
                        <?php echo Form::label('duration_unit', __('fleet.durationUnit'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::select('duration_unit',['months'=>'Months'],$purchase_info->duration_unit ?? "",['class' => 'form-control','required']); ?>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        <?php echo Form::label('loan_about', __('fleet.loanRemarks'), ['class' => 'col-xs-5 control-label']); ?>

                        <div class="col-xs-6">
                        <?php echo Form::textarea('loan_about',$purchase_info->loan_about ?? "",['class' => 'form-control','placeholder'=>'About Loan...','style'=>'resize:none;height:120px;']); ?>

                        </div>
                      </div>
                    </div>
                    
                    
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <div class="form-group" >
                        <div class="col-xs-6">
                        <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning','id'=>'loan_submit']); ?>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php echo Form::close(); ?>

              
              
            
          </div>

          <div class="tab-pane " id="driver">
            <div class="card-body">
              <?php echo Form::open(['url' => 'admin/assignDriver', 'method'=>'post','class'=>'form-horizontal','id'=>'driverForm']); ?>


              <?php echo Form::hidden('vehicle_id',$vehicle->id); ?>


              <div class="col-md-12">
                <div class="form-group">
                  <?php echo Form::label('driver_id',__('fleet.selectDriver'), ['class' => 'form-label']); ?>


                  <select id="driver_id" name="driver_id" class="form-control" >
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
  // datepickers
  $("#purchase_date").datepicker({
    'autoclose':true,
    'format':'dd-mm-yyyy'
  })

  $("#loan_date").datepicker({
    'autoclose':true,
    'format':'dd-mm-yyyy'
  })

  $("#emi_date").datepicker({
    'autoclose':true,
    'format':'dd-mm-yyyy'
  })

</script>
<script type="text/javascript">
// Check Number and Decimal
function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
}
function isWholeNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }
$(document).ready(function() {
  $('#group_id').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectGroup'); ?>"});
  $('#type_id').select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.type'); ?>"});
  <?php if(isset($_GET['tab']) && $_GET['tab']!=""): ?>
    $('.nav-pills a[href="#<?php echo e($_GET['tab']); ?>"]').tab('show')
  <?php endif; ?>
  $('#exp_date').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });
  $('#fitness_expdate').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });
  $('#road_expdate').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });

  $('#permit_expdate').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#pollution_expdate').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });
  $('#year').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
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

  // $("#add_form").on("submit",function(e){
  //   $.ajax({
  //     type: "POST",
  //     url: $(this).attr("action"),
  //     data: $(this).serialize(),
  //     success: function(data){
  //       $("#acq_table").empty();
  //       $("#acq_table").html(data);
  //       new PNotify({
  //         title: 'Success!',
  //         text: '<?php echo app('translator')->getFromJson("fleet.exp_add"); ?>',
  //         type: 'success'
  //       });
  //       $('#exp_name').val("");
  //       $('#exp_amount').val("");
  //     },
  //     dataType: "HTML"
  //   });
  //   e.preventDefault();
  // });

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

  $("#doc_sub").on("click",function(){
    var blankTest = /\S/;
    var insu_dur = $("#ins_renew_duration").val();
    var insu_dur_unit = $("#insurance_duration_unit").val();
    var fitness_dur = $("#fitness_renew_duration").val();
    var fitness_dur_unit = $("#fitness_duration_unit").val();
    var roadtax_dur = $("#roadtax_renew_duration").val();
    var roadtax_dur_unit = $("#roadtax_duration_unit").val();
    var permit_dur = $("#permit_renew_duration").val();
    var permit_dur_unit = $("#permit_duration_unit").val();
    var pollution_dur = $("#pollution_renew_duration").val();
    var pollution_dur_unit = $("#pollution_duration_unit").val();
    
    if(!blankTest.test(insu_dur) && blankTest.test(insu_dur_unit)){
      alert("Please enter Renew Insurance Duration")
      $("#ins_renew_duration").focus();
      return false;
    }

    if(blankTest.test(insu_dur) && !blankTest.test(insu_dur_unit)){
      alert("Please enter Insurance duration unit (days/months/years)")
      $("#insurance_duration_unit").focus();
      return false;
    }

    if(!blankTest.test(fitness_dur) && blankTest.test(fitness_dur_unit)){
      alert("Please enter Fitness duration")
      $("#fitness_renew_duration").focus();
      return false;
    }

    if(blankTest.test(fitness_dur) && !blankTest.test(fitness_dur_unit)){
      alert("Please enter Fitness duration unit (days/months/years)")
      $("#fitness_duration_unit").focus();
      return false;
    }

    if(!blankTest.test(roadtax_dur) && blankTest.test(roadtax_dur_unit)){
      alert("Please enter Road Tax duration")
      $("#roadtax_renew_duration").focus();
      return false;
    }

    if(blankTest.test(roadtax_dur) && !blankTest.test(roadtax_dur_unit)){
      alert("Please enter Roadtax duration unit (days/months/years)")
      $("#roadtax_duration_unit").focus();
      return false;
    }

    if(!blankTest.test(permit_dur) && blankTest.test(permit_dur_unit)){
      alert("Please enter Permit duration")
      $("#permit_renew_duration").focus();
      return false;
    }

    if(blankTest.test(permit_dur) && !blankTest.test(permit_dur_unit)){
      alert("Please enter Permit duration unit (days/months/years)")
      $("#permit_duration_unit").focus();
      return false;
    }

    if(!blankTest.test(pollution_dur) && blankTest.test(pollution_dur_unit)){
      alert("Please enter Pollution duration")
      $("#pollution_renew_duration").focus();
      return false;
    }

    if(blankTest.test(pollution_dur) && !blankTest.test(pollution_dur_unit)){
      alert("Please enter Pollution duration unit (days/months/years)")
      $("#pollution_duration_unit").focus();
      return false;
    }
    
  })

  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $("#loan_submit").click(function(){
    var blankTest = /\S/;
    var purchase_date = $("#purchase_date").val();
    var vehicle_cost = $("#vehicle_cost").val();
    var amount_paid = $("#amount_paid").val();
    var method = $("#method").val();
    var bank = $("#bank").val();
    var method = $("#method").val();
    var reference_no = $("#reference_no").val();
    var loan_date = $("#loan_date").val();
    var loan_amount = $("#loan_amount").val();
    var bank_name = $("#bank_name").val();
    var loan_account = $("#loan_account").val();
    var emi_date = $("#emi_date").val();
    var emi_amount = $("#emi_amount").val();
    var loan_duration = $("#loan_duration").val();
    var duration_unit = $("#duration_unit").val();

    if(!blankTest.test(purchase_date)){
      alert("Please select purchase date")
      $("#purchase_date").focus();
      return false;
    }

    if(!blankTest.test(loan_date)){
      alert("Please select loan date")
      $("#loan_date").focus();
      return false;
    }

    if(!blankTest.test(emi_date)){
      alert("Please select emi date")
      $("#emi_date").focus();
      return false;
    }

  })
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/vehicles/edit.blade.php ENDPATH**/ ?>