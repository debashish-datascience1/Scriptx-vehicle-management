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
<li class="breadcrumb-item"><a href="{{ route("work-order-category.index")}}">Manage Order Head</a></li>
<li class="breadcrumb-item active">Add Order Head</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Add Order Head</h3>
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

        {!! Form::open(['route' => 'work-order-category.store','files'=>true,'method'=>'post']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('name', __('fleet.name'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::text('name',null,['class' => 'form-control','required','placeholder'=>'Enter Order Head Name..']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('status', __('fleet.status'), ['class' => 'form-label required']) !!}
              {!! Form::select('status',$workOrderCategory->statusOptions(),null,['class' => 'form-control','required','placeholder'=>'Select Status']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('description', __('fleet.description'), ['class' => 'form-label required']) !!}
              {!! Form::textarea('description',null,['class' => 'form-control','placeholder'=>'Select Description','style'=>'height:100px;resize:none;']) !!}
            </div>
          </div>
          <div class="col-md-12">
          {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
          </div>
        </div>
        {!! Form::close() !!}
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
        format: 'yyyy-mm-dd',
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