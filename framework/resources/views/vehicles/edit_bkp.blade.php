@extends('layouts.app')
@section('extra_css')
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
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("vehicles.index")}}">@lang('fleet.vehicles')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_vehicle')</h3>
      </div>

      <div class="card-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-pills custom">
            <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab"> @lang('fleet.general_info') <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#insurance" data-toggle="tab"> @lang('fleet.vehicleDocs') <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#acq-tab" data-toggle="tab"> @lang('fleet.purchase_info') <i class="fa"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="#driver" data-toggle="tab"> @lang('fleet.assign_driver') <i class="fa"></i></a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane active" id="info-tab">
            {!! Form::open(['route' =>['vehicles.update',$vehicle->id],'files'=>true, 'method'=>'PATCH','class'=>'form-horizontal','id'=>'accountForm1']) !!}
            {!! Form::hidden('user_id',Auth::user()->id) !!}
            {!! Form::hidden('id',$vehicle->id) !!}
            <div class="row card-body">
              <div class="col-md-6">
                <div class="form-group" >
                  {!! Form::label('make', __('fleet.make'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('make', $vehicle->make,['class' => 'form-control','required']) !!}
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('model', __('fleet.model'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('model', $vehicle->model,['class' => 'form-control','required']) !!}
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('type', __('fleet.type'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    <select name="type_id" class="form-control" required id="type_id">
                      <option></option>
                      @foreach($types as $type)
                      <option value="{{$type->id}}" @if($vehicle->type_id == $type->id) selected @endif>{{$type->displayname}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('year', __('fleet.registrationDate'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('year', $vehicle->year,['class' => 'form-control','required','readonly']) !!}
                  </div>
                </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    {!! Form::label('average', __('fleet.average')." (".__('fleet.mpg').")", ['class' =>'']) !!}
                    {!! Form::text('average', $vehicle->average,['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']) !!}
                  </div>
                  <div class="col-md-6">
                    {!! Form::label('average', __('fleet.average')." (".__('fleet.tpl').")", ['class' => 'control-label']) !!}
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::select('hours',Helper::timeArray(100), empty($vehicle->time_average) ? null :  explode(":",$vehicle->time_average)[0],['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Hours']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::select('mins',Helper::timeArray(59), empty($vehicle->time_average) ? null : explode(":",$vehicle->time_average)[1],['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Minutes']) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

                <div class="form-group">
                  {!! Form::label('int_mileage', __('fleet.intMileage'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('int_mileage', $vehicle->int_mileage,['class' => 'form-control','required']) !!}
                  </div>
                </div>
                <div class="form-group">
                {!! Form::label('owner_name', __('fleet.ownerName'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('owner_name', $vehicle->owner_name,['class' => 'form-control']) !!}
                </div>
              </div>
                <div class="form-group">
                  {!! Form::label('rc_image', __('fleet.rcUpload'), ['class' => 'col-xs-5 control-label']) !!}
                  @if($vehicle->rc_image != null)
                  <a href="{{ asset('uploads/'.$vehicle->rc_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
                  @endif
                  <br>
                  {!! Form::file('rc_image',null,['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('vehicle_image', __('fleet.vehicleImage'), ['class' => 'col-xs-5 control-label']) !!}
                  @if($vehicle->vehicle_image != null)
                  <a href="{{ asset('uploads/'.$vehicle->vehicle_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
                  @endif
                  <br>
                  {!! Form::file('vehicle_image',null,['class' => 'form-control']) !!}
                </div>

                {{-- <div class="form-group">
                  {!! Form::label('reg_exp_date',__('fleet.reg_exp_date'), ['class' => 'col-xs-5 control-label required']) !!}
                  <div class="col-xs-6">
                    <div class="input-group date">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                      {!! Form::text('reg_exp_date', $vehicle->reg_exp_date,['class' => 'form-control','required']) !!}
                    </div>
                  </div>
                </div> --}}

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      {!! Form::label('in_service', __('fleet.service'), ['class' => 'col-xs-5 control-label']) !!}
                    </div>
                    <div class="col-ms-6" style="margin-left: -140px">
                      <label class="switch">
                      <input type="checkbox" name="in_service" value="1" @if($vehicle->in_service == '1') checked @endif>
                      <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group" >
                  {!! Form::label('engine_type', __('fleet.engine'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::select('engine_type',["Petrol"=>"Petrol","Diesel"=>"Diesel"],$vehicle->engine_type,['class' => 'form-control','required']) !!}
                  </div>
                </div>

                {{-- <div class="form-group">
                  {!! Form::label('horse_power', __('fleet.horsePower'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!! Form::text('horse_power', $vehicle->horse_power,['class' => 'form-control','required']) !!}
                  </div>
                </div> --}}

                <div class="form-group">
                  {!! Form::label('color', __('fleet.color'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!! Form::text('color', $vehicle->color,['class' => 'form-control','required']) !!}
                  </div>
                </div>

                {{-- <div class="form-group">
                  {!! Form::label('vin', __('fleet.vin'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!! Form::text('vin', $vehicle->vin,['class' => 'form-control','required']) !!}
                  </div>
                </div> --}}

              <div class="form-group">
                {!! Form::label('engine_no', __('fleet.engine_no'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('engine_no', $vehicle->engine_no,['class' => 'form-control','required']) !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('chassis_no', __('fleet.chassis_no'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('chassis_no', $vehicle->chassis_no,['class' => 'form-control','required']) !!}
                </div>
              </div>

                <div class="form-group">
                  {!! Form::label('license_plate', __('fleet.licenseNo'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!! Form::text('license_plate', $vehicle->license_plate,['class' => 'form-control','required']) !!}
                  </div>
                </div>

                {{-- <div class="form-group">
                  {!! Form::label('lic_exp_date',__('fleet.lic_exp_date'), ['class' => 'col-xs-5 control-label required']) !!}
                  <div class="col-xs-6">
                    <div class="input-group date">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                      {!! Form::text('lic_exp_date', $vehicle->lic_exp_date,['class' => 'form-control','required']) !!}
                    </div>
                  </div>
                </div> --}}

                <div class="form-group">
                  {!! Form::label('group_id',__('fleet.selectGroup'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    <select id="group_id" name="group_id" class="form-control">
                      <option value="">@lang('fleet.vehicleGroup')</option>
                      @foreach($groups as $group)
                        <option value="{{$group->id}}" @if($group->id == $vehicle->group_id) selected @endif>{{$group->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('owner_number', __('fleet.ownerNumber'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('owner_number', $vehicle->owner_number,['class' => 'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('rc_number', __('fleet.rcNumber'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                  {!! Form::text('rc_number', $vehicle->rc_number,['class' => 'form-control']) !!}
                  </div>
                </div>
                {{-- <hr> --}}
                {{-- <div class="form-group">
                  {!! Form::label('udf1',__('fleet.add_udf'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('udf1', null,['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-info add_udf"> @lang('fleet.add')</button>
                    </div>
                  </div>
                </div> --}}
                <div class="blank"></div>
                @if($udfs != null)
                @foreach($udfs as $key => $value)
                <div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase">{{$key}}</label> <input type="text" name="udf[{{$key}}]" class="form-control" required value="{{$value}}"></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>
                @endforeach
                @endif
              </div>
            </div>
            <div style=" margin-bottom: 20px;">
              <div class="form-group" style="margin-top: 15px;">
                <div class="col-xs-6 col-xs-offset-3">
                {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>

          <div class="tab-pane " id="insurance">
            {!! Form::open(['url' => 'admin/store_insurance','files'=>true, 'method'=>'post','class'=>'form-horizontal','id'=>'accountForm']) !!}
            {!! Form::hidden('user_id',Auth::user()->id) !!}
            {!! Form::hidden('vehicle_id',$vehicle->id) !!}
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('insurance_number', __('fleet.insuranceNumber'), ['class' => 'control-label']) !!}
                  {!! Form::text('insurance_number', $vehicle->getMeta('ins_number'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="documents" class="control-label">@lang('fleet.inc_doc')
                  </label>
                  @if($vehicle->getMeta('documents') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('documents')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('documents',['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  @php
                        $insurance_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,36);
                        $fitness_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,37);
                        $roadtax_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,38);
                        $permit_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,39);
                        $pollution_NewExpiryDate = Helper::dynamicLastDate($vehicle->id,40);
                    @endphp
                  {!! Form::label('exp_date',$insurance_NewExpiryDate->status ? __('fleet.inc_expirationDate')."(OLD)" : __('fleet.inc_expirationDate') , ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('exp_date',$vehicle->getMeta('ins_exp_date'),['class' => 'form-control','required','readonly','style'=>$insurance_NewExpiryDate->status ? 'pointer-events:none' : '']) !!}
                    {{-- @if($insurance_NewExpiryDate->status)
                    <label for="">Valid Till : {{$insurance_NewExpiryDate->date}}</label>
                    @endif --}}
                  </div>
                </div>
                @if($insurance_NewExpiryDate->status)
                <div class="form-group">
                  {!! Form::label('insurance_valid', "Insurance Valid Till (Renewed)", ['class' => 'control-label']) !!}
                  <br>
                  {!! Form::label('insurance_valid', Helper::getCanonicalDate($insurance_NewExpiryDate->date,'default'), ['class' => 'control-label']) !!} 
                  /
                  {!! Form::label('insurance_valid', Helper::getCanonicalDate($insurance_NewExpiryDate->date,true), ['class' => 'control-label']) !!}
                </div>
                @endif
                <div class="form-group">
                  {!! Form::label('ins_renew_duration', __('fleet.ins_renew_duration'), ['class' => 'control-label required']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('ins_renew_duration', $vehicle->getMeta('ins_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']) !!}
                    </div>
                    <div class="col-md-4 show-days">
                      <strong>days(s)</strong>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('ins_renew_amount', __('fleet.ins_renew_amount'), ['class' => 'control-label required']) !!}
                  {!! Form::text('ins_renew_amount', $vehicle->getMeta('ins_renew_amount'),['class' => 'form-control','placeholder'=>'Enter Renewal Amount','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('fitness_tax', __('fleet.fitnessTax'), ['class' => 'control-label']) !!}
                  {!! Form::text('fitness_tax', $vehicle->getMeta('fitness_tax'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="fitness_taxdocs" class="control-label">@lang('fleet.fitnessDocuments')
                  </label>
                  @if($vehicle->getMeta('fitness_taxdocs') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('fitness_taxdocs')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('fitness_taxdocs',['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('fitness_expdate', $fitness_NewExpiryDate->status ? __('fleet.fitnessExpirationDate')."(OLD)" : __('fleet.fitnessExpirationDate'), ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('fitness_expdate',$vehicle->getMeta('fitness_expdate'),['class' => 'form-control','required','readonly', 'style'=>$fitness_NewExpiryDate->status ? 'pointer-events:none' : '']) !!}
                  </div>
                </div>
                @if($fitness_NewExpiryDate->status)
                <div class="form-group">
                  {!! Form::label('fitness_valid', "Fitness Valid Till (Renewed)", ['class' => 'control-label']) !!}
                  <br>
                  {!! Form::label('fitness_valid', Helper::getCanonicalDate($fitness_NewExpiryDate->date,'default'), ['class' => 'control-label']) !!} 
                  /
                  {!! Form::label('fitness_valid', Helper::getCanonicalDate($fitness_NewExpiryDate->date,true), ['class' => 'control-label']) !!}
                </div>
                @endif
                <div class="form-group">
                  {!! Form::label('fitness_renew_duration', __('fleet.fitness_renew_duration'), ['class' => 'control-label required']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('fitness_renew_duration', $vehicle->getMeta('fitness_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']) !!}
                    </div>
                    <div class="col-md-4 show-days">
                      <strong>days(s)</strong>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('fitness_renew_amount', __('fleet.fitness_renew_amount'), ['class' => 'control-label required']) !!}
                  {!! Form::text('fitness_renew_amount', $vehicle->getMeta('fitness_renew_amount'),['class' => 'form-control','placeholder'=>'Enter Renewal Amount','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('road_tax', __('fleet.roadTax'), ['class' => 'control-label']) !!}
                  {!! Form::text('road_tax', $vehicle->getMeta('road_tax'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="road_docs" class="control-label">@lang('fleet.roadTaxDocuments')
                  </label>
                  @if($vehicle->getMeta('road_docs') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('road_docs')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('road_docs',['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('road_expdate', $roadtax_NewExpiryDate->status ? __('fleet.roadTaxExpDate')."(OLD)" : __('fleet.roadTaxExpDate'), ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('road_expdate', $vehicle->getMeta('road_expdate'),['class' => 'form-control','required','readonly','style'=> $roadtax_NewExpiryDate->status ? 'pointer-events:none' : '']) !!}
                  </div>
                </div>
                @if($roadtax_NewExpiryDate->status)
                <div class="form-group">
                  {!! Form::label('roadtax_valid', "Road Tax Valid Till (Renewed)", ['class' => 'control-label']) !!}
                  <br>
                  {!! Form::label('roadtax_valid', Helper::getCanonicalDate($roadtax_NewExpiryDate->date,'default'), ['class' => 'control-label']) !!} 
                  /
                  {!! Form::label('roadtax_valid', Helper::getCanonicalDate($roadtax_NewExpiryDate->date,true), ['class' => 'control-label']) !!}
                </div>
                @endif
                <div class="form-group">
                  {!! Form::label('roadtax_renew_duration', __('fleet.roadtax_renew_duration'), ['class' => 'control-label required']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('roadtax_renew_duration', $vehicle->getMeta('roadtax_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']) !!}
                    </div>
                    <div class="col-md-4 show-days">
                      <strong>days(s)</strong>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('roadtax_renew_amount', __('fleet.roadtax_renew_amount'), ['class' => 'control-label required']) !!}
                  {!! Form::text('roadtax_renew_amount', $vehicle->getMeta('roadtax_renew_amount'),['class' => 'form-control','placeholder'=>'Enter Renewal Amount','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
            </div>
            <hr>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('permit_number', __('fleet.permitNumber'), ['class' => 'control-label']) !!}
                  {!! Form::text('permit_number', $vehicle->getMeta('permit_number'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="permit_docs" class="control-label">@lang('fleet.permitDocuments')
                  </label>
                  @if($vehicle->getMeta('permit_docs') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('permit_docs')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('permit_docs',['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('permit_expdate', $permit_NewExpiryDate->status ? __('fleet.permitExpDate')."(OLD)" : __('fleet.permitExpDate'), ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('permit_expdate',$vehicle->getMeta('permit_expdate'),['class' => 'form-control','required','readonly','style'=>$permit_NewExpiryDate->status ? 'pointer-events:none' : '']) !!}
                  </div>
                </div>
                @if($permit_NewExpiryDate->status)
                <div class="form-group">
                  {!! Form::label('permit_valid', "Permit Valid Till (Renewed)", ['class' => 'control-label']) !!}
                  <br>
                  {!! Form::label('permit_valid', Helper::getCanonicalDate($permit_NewExpiryDate->date,'default'), ['class' => 'control-label']) !!} 
                  /
                  {!! Form::label('permit_valid', Helper::getCanonicalDate($permit_NewExpiryDate->date,true), ['class' => 'control-label']) !!}
                </div>
                @endif
                <div class="form-group">
                  {!! Form::label('permit_renew_duration', __('fleet.permit_renew_duration'), ['class' => 'control-label required']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('permit_renew_duration', $vehicle->getMeta('permit_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']) !!}
                    </div>
                    <div class="col-md-4 show-days">
                      <strong>days(s)</strong>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('permit_renew_amount', __('fleet.permit_renew_amount'), ['class' => 'control-label required']) !!}
                  {!! Form::text('permit_renew_amount', $vehicle->getMeta('permit_renew_amount'),['class' => 'form-control','placeholder'=>'Enter Renewal Amount','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('pollution_tax', __('fleet.pollutionNumber'), ['class' => 'control-label']) !!}
                  {!! Form::text('pollution_tax', $vehicle->getMeta('pollution_tax'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="pollution_docs" class="control-label">@lang('fleet.pollutionDocuments')
                  </label>
                  @if($vehicle->getMeta('pollution_docs') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('pollution_docs')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('pollution_docs',['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('pollution_expdate', $pollution_NewExpiryDate->status ? __('fleet.pollutionExpDate')."(OLD)" : __('fleet.pollutionExpDate'), ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('pollution_expdate', $pollution_NewExpiryDate->status ? $pollution_NewExpiryDate->date : $vehicle->getMeta('pollution_expdate'),['class' => 'form-control','required','readonly','style'=>$pollution_NewExpiryDate->status ? 'pointer-events:none' : '']) !!}
                  </div>
                </div>
                @if($pollution_NewExpiryDate->status)
                <div class="form-group">
                  {!! Form::label('pollution_valid', "Pollution Valid Till (Renewed)", ['class' => 'control-label']) !!}
                  <br>
                  {!! Form::label('pollution_valid', Helper::getCanonicalDate($pollution_NewExpiryDate->date,'default'), ['class' => 'control-label']) !!} 
                  /
                  {!! Form::label('pollution_valid', Helper::getCanonicalDate($pollution_NewExpiryDate->date,true), ['class' => 'control-label']) !!}
                </div>
                @endif
                <div class="form-group">
                  {!! Form::label('pollution_renew_duration', __('fleet.pollution_renew_duration'), ['class' => 'control-label required']) !!}
                  <div class="row">
                    <div class="col-md-8">
                      {!! Form::text('pollution_renew_duration', $vehicle->getMeta('pollution_renew_duration'),['class' => 'form-control','placeholder'=>'Enter Renewal Duration','onkeypress'=>'return isWholeNumber(event)']) !!}
                    </div>
                    <div class="col-md-4 show-days">
                      <strong>days(s)</strong>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('pollution_renew_amount', __('fleet.pollution_renew_amount'), ['class' => 'control-label required']) !!}
                  {!! Form::text('pollution_renew_amount', $vehicle->getMeta('pollution_renew_amount'),['class' => 'form-control','placeholder'=>'Enter Renewal Amount','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('fast_tag', __('fleet.fastTagNumber'), ['class' => 'control-label']) !!}
                  {!! Form::text('fast_tag', $vehicle->getMeta('fast_tag'),['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group">
                  <label for="fasttag_docs" class="control-label">@lang('fleet.fastTagDoc')
                  </label>
                  @if($vehicle->getMeta('fasttag_docs') != null)
                  <a href="{{ asset('uploads/'.$vehicle->getMeta('fasttag_docs')) }}" target="_blank">View</a>
                  @endif
                  {!! Form::file('fasttag_docs',['class' => 'form-control']) !!}
                </div>
                {{-- <div class="form-group">
                  {!! Form::label('fasttag_expdate', __('fleet.fastTagExpDate'), ['class' => 'control-label required']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    {!! Form::text('fasttag_expdate', $vehicle->getMeta('fasttag_expdate'),['class' => 'form-control','required','readonly']) !!}
                  </div>
                </div> --}}
              </div>
            </div>
            <hr>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('gps_number', __('fleet.gpsNumber'), ['class' => 'control-label']) !!}
                  {!! Form::text('gps_number', $vehicle->getMeta('gps_number'),['class' => 'form-control','required']) !!}
                </div>
              </div>
            </div>
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group" style="margin-top: 15px;">
                  {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning','id'=>'doc_sub']) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>

          <div class="tab-pane " id="acq-tab">
          {!! Form::open(['route' => 'purchase-info','method'=>'post','id'=>'add_form']) !!}
                    {!! Form::hidden('user_id',Auth::user()->id) !!}
                    {!! Form::hidden('vehicle_id',$vehicle->id)  !!}
            <div class="row card-body">
              <div class="col-md-4">
                  <div class="form-group" >
                    {!! Form::label('purchase_date', __('fleet.dateOfPurchase'), ['class' => 'col-xs-5 control-label']) !!}
                    <div class="col-xs-6">
                    {!! Form::text('purchase_date',$purchase_info->purchase_date ?? "",['class' => 'form-control','required','id'=>'purchase_date','readonly']) !!}
                    </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group" >
                    {!! Form::label('vehicle_cost', __('fleet.vehicleCost'), ['class' => 'col-xs-5 control-label']) !!}
                    <div class="col-xs-6">
                    {!! Form::text('vehicle_cost',$purchase_info->vehicle_cost ?? "",['class' => 'form-control','required']) !!}
                    </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group" >
                    {!! Form::label('amount_paid', __('fleet.amountPaid'), ['class' => 'col-xs-5 control-label']) !!}
                    <div class="col-xs-6">
                    {!! Form::number('amount_paid',$purchase_info->amount_paid ?? "",['class' => 'form-control','required','min'=>1]) !!}
                    </div>
                  </div>
              </div>
            </div>
            <div class="card card-success">
                <div class="card-header">
                  <h4 class="card-title">@lang('fleet.loanDetails')</h4>
                </div>
                <div class="card-body">
                  <div class="row form-group">
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('loan_date', __('fleet.loanDate'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('loan_date',$purchase_info->loan_date ?? "",['class' => 'form-control','required','id'=>'loan_date','readonly']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('loan_account', __('fleet.loanAccount'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('loan_account',$purchase_info->loan_account ?? "",['class' => 'form-control','required']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('loan_amount', __('fleet.loanAmount'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('loan_amount',$purchase_info->loan_amount ?? "",['class' => 'form-control','required','min'=>1]) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('bank_name', __('fleet.bankName'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('bank_name',$purchase_info->bank_name ?? "",['class' => 'form-control','required']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('emi_amount', __('fleet.emiAmount'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('emi_amount',$purchase_info->emi_amount ?? "",['class' => 'form-control','required']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('emi_date', __('fleet.emiDate'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('emi_date',$purchase_info->emi_date ?? "",['class' => 'form-control','required','min'=>1,'readonly']) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('loan_duration', __('fleet.loanDuration'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('loan_duration',$purchase_info->loan_duration ?? "",['class' => 'form-control','required']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('gst_amount', __('fleet.gstAmount'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::number('gst_amount',$purchase_info->gst_amount ?? "",['class' => 'form-control','required','min'=>1]) !!}
                        </div>
                      </div>
                    </div>
                    {{-- <div class="col-md-4">
                      <div class="form-group" >
                        {!! Form::label('emi_date', __('fleet.emiDate'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-xs-6">
                        {!! Form::text('emi_date',null,['class' => 'form-control','required','id'=>'emi_date']) !!}
                        </div>
                      </div>
                    </div> --}}
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <div class="form-group" >
                        <div class="col-xs-6">
                        {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            {!!Form::close()!!}
              {{-- <div class="col-md-12"> 
                 <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title">@lang('fleet.acquisition') @lang('fleet.add')</h3>
                  </div>

                  <div class="card-body">
                    {!! Form::open(['route' => 'acquisition.store','method'=>'post','class'=>'form-inline','id'=>'add_form']) !!}
                    {!! Form::hidden('user_id',Auth::user()->id) !!}
                    {!! Form::hidden('vehicle_id',$vehicle->id)  !!}
                    <div class="form-group" style="margin-right: 10px;">
                      {!! Form::label('exp_name', __('fleet.expenseType'), ['class' => 'form-label']) !!}
                      {!! Form::text('exp_name',  null,['class'=>'form-control','required']); !!}
                    </div>
                    <div class="form-group"></div>
                    <div class="form-group" style="margin-right: 10px;">
                      {!! Form::label('exp_amount', __('fleet.expenseAmount'), ['class' => 'form-label']) !!}
                      <div class="input-group" style="margin-right: 10px;">
                        <div class="input-group-prepend">
                        <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
                        {!! Form::number('exp_amount',null,['class'=>'form-control','required']); !!}
                      </div>
                    </div>
                    <div class="form-group"></div>
                    <button type="submit" class="btn btn-success">@lang('fleet.add')</button>
                    {!! Form::close() !!}
                  </div>
                </div> 
              </div>--}}
              
            {{-- <div class="row card-body" >
              <div class="col-md-12">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">@lang('fleet.acquisition') :<strong>{{ $vehicle->make }} {{ $vehicle->model }} {{ $vehicle->license_plate }}</strong>
                    </h3>
                  </div>
                  <div class="card-body" id="acq_table">
                    <div class="row">
                      <div class="col-md-12 table-responsive">
                        @php($value = unserialize($vehicle->getMeta('purchase_info')))
                        <table class="table">
                            <thead>
                              <th>@lang('fleet.expenseType')</th>
                              <th>@lang('fleet.expenseAmount')</th>
                              <th>@lang('fleet.action')</th>
                            </thead>
                          <tbody id="hvk">
                            @if($value != null)
                            @php($i=0)
                            @foreach($value as $key=>$row)
                            <tr>
                              @php($i+=$row['exp_amount'])
                              <td>{{$row['exp_name']}}</td>
                              <td>{{Hyvikk::get('currency')." ". $row['exp_amount']}}</td>
                              <td>
                              {!! Form::open(['route' =>['acquisition.destroy',$vehicle->id],'method'=>'DELETE','class'=>'form-horizontal']) !!}
                              {!! Form::hidden("vid",$vehicle->id) !!}
                              {!! Form::hidden("key",$key) !!}
                              <button type="button" class="btn btn-danger del_info" data-vehicle="{{$vehicle->id}}" data-key="{{$key}}">
                              <span class="fa fa-times"></span>
                              </button>
                              {!! Form::close() !!}
                              </td>
                            </tr>
                            @endforeach
                            <tr>
                              <td><strong>@lang('fleet.total')</strong></td>
                              <td><strong>{{Hyvikk::get('currency')." ". $i}}</strong></td>
                              <td></td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> --}}
          </div>

          <div class="tab-pane " id="driver">
            <div class="card-body">
              {!! Form::open(['url' => 'admin/assignDriver', 'method'=>'post','class'=>'form-horizontal','id'=>'driverForm']) !!}

              {!! Form::hidden('vehicle_id',$vehicle->id) !!}

              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('driver_id',__('fleet.selectDriver'), ['class' => 'form-label']) !!}

                  <select id="driver_id" name="driver_id" class="form-control" >
                    <option value="">@lang('fleet.selectDriver')</option>
                    @foreach($drivers as $driver)
                    <option value="{{$driver->id}}" @if($vehicle->getMeta('driver_id') == $driver->id) selected @endif>{{$driver->name}}@if($driver->getMeta('is_active') != 1)
                    ( @lang('fleet.in_active') ) @endif</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6" style=" margin-bottom: 20px;">
                <div class="form-group" style="margin-top: 15px;">
                  <div class="col-xs-6 col-xs-offset-3">
                    {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  // datepickers
  $("#purchase_date").datepicker({
    'autoclose':true,
    'format':'yyyy-mm-dd'
  })

  $("#loan_date").datepicker({
    'autoclose':true,
    'format':'yyyy-mm-dd'
  })

  $("#emi_date").datepicker({
    'autoclose':true,
    'format':'yyyy-mm-dd'
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
  $('#group_id').select2({placeholder: "@lang('fleet.selectGroup')"});
  $('#type_id').select2({placeholder:"@lang('fleet.type')"});
  @if(isset($_GET['tab']) && $_GET['tab']!="")
    $('.nav-pills a[href="#{{$_GET['tab']}}"]').tab('show')
  @endif
  $('#exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#fitness_expdate').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#road_expdate').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });

  $('#permit_expdate').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
  $('#pollution_expdate').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $(document).on("click",".del_info",function(e){
    var hvk=confirm("Are you sure?");
    if(hvk==true){
      var vid=$(this).data("vehicle");
      var key = $(this).data('key');
      var action="{{ route('acquisition.index')}}/"+vid;

      $.ajax({
        type: "POST",
        url: action,
        data: "_method=DELETE&_token="+window.Laravel.csrfToken+"&key="+key+"&vehicle_id="+vid,
        success: function(data){
          $("#acq_table").empty();
          $("#acq_table").html(data);
          new PNotify({
            title: 'Deleted!',
            text:'@lang("fleet.deleted")',
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
  //         text: '@lang("fleet.exp_add")',
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
  //           text: '@lang("fleet.ins_add")',
  //           type: 'success'
  //       }),
  //   dataType: "json",
  //   });
  //   e.preventDefault();
  // });

  $("#doc_sub").on("click",function(){
    var blankTest = /\S/;
    var insu_dur = $("#ins_renew_duration").val();
    var insu_amt = $("#ins_renew_amount").val();
    var fitness_dur = $("#fitness_renew_duration").val();
    var fitness_amt = $("#fitness_renew_amount").val();
    var roadtax_dur = $("#roadtax_renew_duration").val();
    var roadtax_amt = $("#roadtax_renew_amount").val();
    var permit_dur = $("#permit_renew_duration").val();
    var permit_amt = $("#permit_renew_amount").val();
    var pollution_dur = $("#pollution_renew_duration").val();
    var pollution_amt = $("#pollution_renew_amount").val();
    
    if(!blankTest.test(insu_dur) && blankTest.test(insu_amt)){
      alert("Please enter Insurance duration when Insurance amount is given.")
      $("#ins_renew_duration").focus();
      return false;
    }

    if(blankTest.test(insu_dur) && !blankTest.test(insu_amt)){
      alert("Please enter Insurance amount when Insurance duration is given.")
      $("#ins_renew_amount").focus();
      return false;
    }

    if(!blankTest.test(fitness_dur) && blankTest.test(fitness_amt)){
      alert("Please enter Fitness duration when Fitness amount is given.")
      $("#fitness_renew_duration").focus();
      return false;
    }

    if(blankTest.test(fitness_dur) && !blankTest.test(fitness_amt)){
      alert("Please enter Fitness amount when Fitness duration is given.")
      $("#fitness_renew_amount").focus();
      return false;
    }

    if(!blankTest.test(roadtax_dur) && blankTest.test(roadtax_amt)){
      alert("Please enter Road Tax duration when Road Tax amount is given.")
      $("#roadtax_renew_duration").focus();
      return false;
    }

    if(blankTest.test(roadtax_dur) && !blankTest.test(roadtax_amt)){
      alert("Please enter Road Tax amount when Road Tax duration is given.")
      $("#roadtax_renew_amount").focus();
      return false;
    }

    if(!blankTest.test(permit_dur) && blankTest.test(permit_amt)){
      alert("Please enter Permit duration when Permit amount is given.")
      $("#permit_renew_duration").focus();
      return false;
    }

    if(blankTest.test(permit_dur) && !blankTest.test(permit_amt)){
      alert("Please enter Permit amount when Permit duration is given.")
      $("#permit_renew_amount").focus();
      return false;
    }

    if(!blankTest.test(pollution_dur) && blankTest.test(pollution_amt)){
      alert("Please enter Pollution duration when Pollution amount is given.")
      $("#pollution_renew_duration").focus();
      return false;
    }

    if(blankTest.test(pollution_dur) && !blankTest.test(pollution_amt)){
      alert("Please enter Pollution amount when Pollution duration is given.")
      $("#pollution_renew_amount").focus();
      return false;
    }
    
  })

  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

});
</script>
@endsection