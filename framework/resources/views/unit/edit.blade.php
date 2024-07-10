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
<li class="breadcrumb-item"><a href="{{ route("unit.index")}}">@lang('fleet.unit')</a></li>
<li class="breadcrumb-item active">Add @lang('fleet.unit')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Edit @lang('fleet.unit')</h3>
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

        {!! Form::model($unit,['route' => ['unit.update',$unit->id],'files'=>true,'method'=>'PATCH']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('name', __('fleet.name'), ['class' => 'form-label required']) !!}
              {!! Form::text('name',$unit->name,['class' => 'form-control','id'=>'name','required','placeholder'=>'Enter Part\'s Name']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('short_name', __('fleet.short_name'), ['class' => 'form-label required']) !!}
              {!! Form::text('short_name',$unit->short_name,['class' => 'form-control','id'=>'short_name','required','placeholder'=>'Enter Unit Short Name']) !!}
            </div>
          </div>
          <div class="col-md-12">
          {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
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