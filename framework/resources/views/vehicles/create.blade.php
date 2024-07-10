@extends('layouts.app')
@section('extra_css')
<style type="text/css">
.nav-tabs-custom>.nav-tabs>li.active{border-top-color:#00a65a !important;}

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

    background-color: #21bc6c !important;

}

</style>
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("vehicles.index")}}">@lang('fleet.vehicles')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addVehicle')</li>


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
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.addVehicle')</h3>
      </div>

    <div class="card-body">
      <div class="nav-tabs-custom">
        <ul class="nav nav-pills custom">
          <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab"> @lang('fleet.general_info') <i class="fa"></i></a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane active" id="info-tab">
          {!! Form::open(['route' => 'vehicles.store','files'=>true, 'method'=>'post','class'=>'form-block','id'=>'accountForm']) !!}
          {!! Form::hidden('user_id',Auth::user()->id) !!}
          <div class="row card-body">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('make', __('fleet.make'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('make', null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('model', __('fleet.model'), ['class' => 'col-xs-5 control-label']) !!}

                <div class="col-xs-6">
                {!! Form::text('model', null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('type_id', __('fleet.type'), ['class' => 'col-xs-5 control-label']) !!}

                <div class="col-xs-6">
                 <select name="type_id" class="form-control" required id="type_id">
                   <option></option>
                   @foreach($types as $type)
                    <option value="{{$type->id}}">{{$type->displayname}}</option>
                   @endforeach
                 </select>
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('year', __('fleet.registrationDate'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('year', null,['class' => 'form-control','required','readonly']) !!}
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    {!! Form::label('average', __('fleet.average')." (".__('fleet.mpg').")", ['class' =>'']) !!}
                    {!! Form::text('average', null,['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)']) !!}
                  </div>
                  <div class="col-md-6">
                    {!! Form::label('average', __('fleet.average')." (".__('fleet.tpl').")", ['class' => 'control-label']) !!}
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::select('hours',Helper::timeArray(100), null,['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Hours']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::select('mins',Helper::timeArray(59), null,['class' => 'form-control','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Minutes']) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- time_perltr --}}
              <div class="form-group">
                {!! Form::label('int_mileage', __('fleet.intMileage'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::number('int_mileage', null,['class' => 'form-control','required','min'=>'0']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('owner_name', __('fleet.ownerName'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('owner_name', null,['class' => 'form-control']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('rc_image', __('fleet.rcUpload'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::file('rc_image',null,['class' => 'form-control']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('vehicle_image', __('fleet.vehicleImage'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::file('vehicle_image',null,['class' => 'form-control']) !!}
                </div>
              </div>

              {{-- <div class="form-group">
                {!! Form::label('reg_exp_date',__('fleet.reg_exp_date'), ['class' => 'col-xs-5 control-label required']) !!}
                <div class="col-xs-6">
                  <div class="input-group date">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                  {!! Form::text('reg_exp_date', null,['class' => 'form-control','required']) !!}
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
                      <input type="checkbox" name="in_service" value="1">
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
                {!! Form::select('engine_type',["Petrol"=>"Petrol","Diesel"=>"Diesel"],null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              {{-- <div class="form-group">
                {!! Form::label('horse_power', __('fleet.horsePower'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::number('horse_power', null,['class' => 'form-control','required']) !!}
                </div>
              </div> --}}

              <div class="form-group">
                {!! Form::label('color', __('fleet.color'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('color', null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              {{-- <div class="form-group">
                {!! Form::label('vin', __('fleet.vin'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('vin', null,['class' => 'form-control','required']) !!}
                </div>
              </div> --}}

              <div class="form-group">
                {!! Form::label('engine_no', __('fleet.engine_no'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('engine_no', null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('chassis_no', __('fleet.chassis_no'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('chassis_no', null,['class' => 'form-control','required']) !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('license_plate', __('fleet.licenseNo'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                 {!! Form::text('license_plate', null,['class' => 'form-control','required']) !!}
                </div>
              </div>
              {{-- <div class="form-group">
                {!! Form::label('lic_exp_date',__('fleet.lic_exp_date'), ['class' => 'col-xs-5 control-label required']) !!}
                <div class="col-xs-6">
                  <div class="input-group date">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                  {!! Form::text('lic_exp_date', null,['class' => 'form-control','required']) !!}
                  </div>
                </div>
              </div> --}}

              <div class="form-group">
                {!! Form::label('group_id',__('fleet.selectGroup'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                  <select id="group_id" name="group_id" class="form-control">
                    <option value="">@lang('fleet.vehicleGroup')</option>
                    @foreach($groups as $group)
                    @if($group->id == 1)
                    <option value="{{$group->id}}" selected>{{$group->name}}</option>
                    @else
                    <option value="{{$group->id}}" >{{$group->name}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('owner_number', __('fleet.ownerNumber'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('owner_number', null,['class' => 'form-control']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('rc_number', __('fleet.rcNumber'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                {!! Form::text('rc_number', null,['class' => 'form-control']) !!}
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
            </div>
          </div>
          <div style=" margin-bottom: 20px;">
            <div class="form-group" style="margin-top: 15px;">
              <div class="col-xs-6 col-xs-offset-3">
                {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
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

    $(document).ready(function() {
      $('#group_id').select2({placeholder: "@lang('fleet.selectGroup')"});
      $('#type_id').select2({placeholder:"@lang('fleet.type')"});
      $('#start_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      $('#end_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      $('#exp_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      $('#lic_exp_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      $('#reg_exp_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      $('#issue_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      
      $('#year').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
      });

    //Flat green color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
      });
    });
    // Check Number and Decimal
    function isNumber(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (            
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;
            return true;
    }
</script>
@endsection
