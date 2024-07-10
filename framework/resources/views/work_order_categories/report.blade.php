@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">@lang('menu.reports')</a></li>
<li class="breadcrumb-item active">Leave Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Leave Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'leave.report','method'=>'post','class'=>'form-inline']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('driver_id', __('fleet.driver'), ['class' => 'form-label']) !!}
							{!! Form::select('driver_id',$drivers,$request['driver_id'],['class'=>'form-control fullsize','id'=>'driver_id','placeholder'=>'Select Driver']) !!}
						</div>
					</div>
					<div class="col-md-4" style="margin-top: 23px">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1',$request['date1'],['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4" style="margin-top: 23px">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', $request['date2'],['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-leave-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@if(isset($result))
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">
				Leave Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
                            @if(empty($driver_id))
							<th>Driver</th>
                            <th>Vehicle</th>
                            @endif
							<th>Status</th>
							<th>Remarks</th>
						</tr>
					</thead>
					<tbody>
					@foreach($leaves as $k=>$l)
						<tr>
                            <td>{{$k+1}}</td>
                            <td>{{Helper::getCanonicalDate($l->date)}}</td>
                            @if(empty($driver_id))
							<td>{{$l->driver->name}}</td>
                            <td>
								<?php $vehicle = !empty($l->driver_vehicle) ? $l->driver_vehicle->vehicle : null; ?>
								@if(!empty($vehicle))
								{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}
								@else
									<label>N/A</label>
								@endif
							</td>
                            @endif
                            <td>
								{{ empty($l->is_present) ? "N/A" : Helper::getLeaveTypes()[$l->is_present]}}
							</td>
							<td>{{$l->remarks}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
                            <th>#</th>
                            <th>Date</th>
                            @if(empty($driver_id))
							<th>Driver</th>
                            <th>Vehicle</th>
                            @endif
							<th>From - To</th>
							<th>Remarks</th>
						</tr>
					</tfoot>
				</table>
				@if(!empty($driver_id))
				<br>
				<table class="table">
					<tr>
                        <th style="float:right">Total Absent: {{($total_absent)}}</th>
                        <th style="float:right">Total Present: {{($total_present)}}</th>
					</tr>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
@endif
@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#date1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#driver_id").select2();
		$('#myTable tfoot th').each( function () {
	      var title = $(this).text();
	      $(this).html( '<input type="text" placeholder="'+title+'" />' );
	    });
	    var myTable = $('#myTable').DataTable( {
	        // dom: 'Bfrtip',
	        buttons: [{
	             extend: 'collection',
	                text: 'Export',
	                buttons: [
	                    'copy',
	                    'excel',
	                    'csv',
	                    'pdf',
	                ]}
	        ],

	        "language": {
	                 "url": '{{ __("fleet.datatable_lang") }}',
	              },
	        "initComplete": function() {
	                myTable.columns().every(function () {
	                  var that = this;
	                  $('input', this.footer()).on('keyup change', function () {
	                      that.search(this.value).draw();
	                  });
	                });
	              }
	    });
	});

	
</script>
@endsection