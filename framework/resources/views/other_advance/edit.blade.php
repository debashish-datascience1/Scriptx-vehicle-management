@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.min.css')}}">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
  #remarks{resize: none;height: 120px;max-height: 120px;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("other-advance.index")}}">@lang('fleet.other_advance')</a></li>
<li class="breadcrumb-item active">Edit @lang('fleet.other_advance')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Edit @lang('fleet.other_advance')</h3>
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

        {!! Form::model($otherAdvance,['route' => ['other-advance.update',$otherAdvance->id],'files'=>true,'method'=>'PATCH']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('driver_id', "Drivers", ['class' => 'form-label required','autofocus']) !!}
              {!! Form::select('driver_id',$drivers,null,['class' => 'form-control','required','placeholder'=>'Select Drivers']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('bank', 'Banks', ['class' => 'form-label required']) !!}
              {!! Form::select('bank',$bank,null,['class' => 'form-control','required','id'=>'bank','placeholder'=>'Select Bank']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('method', 'Payment Method', ['class' => 'form-label required']) !!}
              {!! Form::select('method',$method,null,['class' => 'form-control','required','id'=>'method','placeholder'=>'Select Method']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('ref_no', 'Ref. No.', ['class' => 'form-label required']) !!}
              {!! Form::text('ref_no',null,['class' => 'form-control','id'=>'ref_no','placeholder'=>'Reference No.']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', 'Date', ['class' => 'form-label required']) !!}
              {!! Form::text('date',Helper::indianDateFormat($otherAdvance->date),['class' => 'form-control','id'=>'date','required','readonly']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('amount', 'Amount', ['class' => 'form-label required']) !!}
              {!! Form::text('amount',null,['class' => 'form-control','id'=>'amount','required','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('remarks', 'Remarks', ['class' => 'form-label required']) !!}
              {!! Form::textarea('remarks',null,['class' => 'form-control','id'=>'remarks','placeholder'=>'Enter Remarks']) !!}
            </div>
          </div>
          <div class="col-md-12">
          {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
          </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>

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
$(document).ready(function() {
  $("#driver_id").select2();
  $("#date").datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
    });

    $("#savebtn").click(function(){
      var blankTest = /\S/;
      var date = $("#date").val();
      if(!blankTest.test(date)){
        alert("Date cannot be empty");
        $("#date").focus();
        return false;
      }
    })
});
</script>
@endsection