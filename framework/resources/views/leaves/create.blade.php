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
<li class="breadcrumb-item active">@lang('fleet.addLeave')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.addLeave')</h3>
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

        {!! Form::open(['route' => 'leave.store','files'=>true,'method'=>'post']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('present_type', __('fleet.presentType'), ['class' => 'form-label required','autofocus']) !!}
              @php $ptype = [1=>'All Present',2=>'Not Present/Half Day']; @endphp
              {!! Form::select('present_type',$ptype,null,['class' => 'form-control','required','placeholder'=>'Select Type']) !!}
            </div>
          </div>
          <div class="col-md-4 absent-group">
            <div class="form-group">
              {!! Form::label('drivers', 'Select Drivers', ['class' => 'form-label required']) !!}
              {!! Form::select('driver_id[]',$data,null,['class' => 'form-control drivers','required','id'=>'driver_id','multiple'=>'multiple','disabled']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('date', 'Date', ['class' => 'form-label required']) !!}
              {!! Form::text('date',null,['class' => 'form-control','id'=>'date','required','readonly']) !!}
            </div>
          </div>
          <div class="col-md-12 remarks">
            {{-- <div class="form-group remarks">
              {!! Form::label('remarks', 'Absent Remark', ['class' => 'form-label required']) !!}
              {!! Form::text('remarks[]',null,['class' => 'form-control remarks','required']) !!}
              {!! Form::label('remarks', 'Absent Remark', ['class' => 'form-label required']) !!}
              {!! Form::text('remarks[]',null,['class' => 'form-control remarks','required']) !!}
            </div> --}}
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
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
    // $("#driver_id").attr('disabled',true);
    var date = new Date();
    date.setDate(date.getDate()-1);
    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        // startDate: date,
        endDate: '+1d'
    });

    $(".drivers").select2();

    $("#present_type").change(function(){
        if($(this).val()==1 || $(this).val()=='')
            $("#driver_id").attr('disabled',true);
        else
            $("#driver_id").attr('disabled',false);
    })

    $("#driver_id").on("change",function(){
        var darr = $(this).val();
        console.log(darr)
        $('.remarks').load('{{url("admin/leave/get_remarks")}}/'+darr,function(result){
            // console.log(result);
        })
    })

    

    $("#savebtn").click(function(){
        if($("#date").val()==""){
            alert('Date field can\'t be empty.');
            return false;
        }
            
    })
});
</script>
@endsection