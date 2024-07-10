@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("leave.index")}}">@lang('fleet.leave')</a></li>
<li class="breadcrumb-item active">@lang('fleet.editLeave')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.editLeave')</h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        {!! Form::model($leave,['route' => ['leave.update',$leave->id],'files'=>true,'method'=>'PATCH']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('drivers', 'Driver Name', ['class' => 'form-label required']) !!}
              {!! Form::select('driver_id',$drivers,$leave->driver_id,['class' => 'form-control drivers','required','id'=>'driver_id','readonly']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', 'Date', ['class' => 'form-label required']) !!}
              {!! Form::text('date',Helper::indianDateFormat($leave->date),['class' => 'form-control','id'=>'date','required','readonly']) !!}
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                @php
                    $style = $isSalaryPaid ? "pointer-events:none;" : "";
                    $isReadOnly = $isSalaryPaid ? "readonly" : "";
                @endphp
                {!! Form::label('is_present','Is Present?', ['class' => 'form-label required']) !!}
                {!! Form::select('is_present',Helper::getLeaveTypes(),$leave->is_present,['class' => 'form-control remarks','required','placeholder'=>'Select Status','style'=>$style,$isReadOnly]) !!}
                <small class="text-danger"><i>salary is already paid for this month</i></small>
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group remarks">
              {!! Form::label('remarks', 'Remark', ['class' => 'form-label required']) !!}
              {!! Form::text('remarks',$leave->remarks,['class' => 'form-control remarks']) !!}
            </div>
          </div>
            <div class="col-md-12">
            {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success']) !!}
            </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
    // $("#driver_id").attr('disabled',true);
    // var date = new Date();
    // date.setDate(date.getDate()-1);
    // $('#date').datepicker({
    //     autoclose: true,
    //     format: 'yyyy-mm-dd',
    //     startDate: date,
    //     endDate: '+0d'
    // });

    // $(".drivers").select2();

    // $("#present_type").change(function(){
    //     if($(this).val()==1 || $(this).val()=='')
    //         $("#driver_id").attr('disabled',true);
    //     else
    //         $("#driver_id").attr('disabled',false);
    // })

    // $("#driver_id").on("change",function(){
    //     var darr = $(this).val();
    //     console.log(darr)
    //     $('.remarks').load('{{url("admin/leave/get_remarks")}}/'+darr,function(result){
    //         console.log(result);
    //     })
    // })
});
</script>
@endsection