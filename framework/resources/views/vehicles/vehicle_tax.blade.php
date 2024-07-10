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
</style>
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("vehicles.index")}}">@lang('fleet.vehicles')</a></li>
<li class="breadcrumb-item active">@lang('fleet.vehicle_tax')</li>
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
        </div>
    </div>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.vehicle_tax')</h3>
      </div>
      {!! Form::open(['route'=>['vehicle_tax_update',$vehicle->id],'files'=>true,'method'=>'PUT','class'=>'form-horizontal','id'=>'accountForm1']) !!}
        {{-- <div class="row card-body">
            <div class="form-group" >
                {!! Form::label('make', __('fleet.make'), ['class' => 'col-xs-5 control-label']) !!}
                <div class="col-xs-6">
                    {!! Form::text('make', $vehicle->id,['class' => 'form-control','required']) !!}
                </div>
            </div>
        </div> --}}
    </div>
    {{-- {!! Form::close()!!} --}}
@endsection