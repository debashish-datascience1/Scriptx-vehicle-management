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
<li class="breadcrumb-item active">Service Reminder Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Service Reminder Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.service-reminder','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-4">
                        {{-- {{dd($vehicles)}} --}}
						<div class="form-group">
							{!! Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control fullsize','id'=>'vehicle_id','placeholder'=>'Select Vehicle']) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1', $request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', $request['date2'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-service-reminder-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
					Service Reminder Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
                            <th>SL#</th>
                            @if(empty($vehicle_id))
                            <th>@lang('fleet.vehicle')</th>
                            @endif
							<th>@lang('fleet.service_item')</th>
							<th>@lang('fleet.start_date') / @lang('fleet.last_performed') </th>
							<th>@lang('fleet.next_due') (@lang('fleet.date'))</th>
							<th>@lang('fleet.next_due') (@lang('fleet.meter'))</th>
						</tr>
					</thead>
					<tbody>
					@foreach($services as $k=>$reminder)
						<tr>
							<td>{{$k+1}}</td>
                            @if(empty($vehicle_id))
							<td><strong>{{$reminder->vehicle->license_plate}}</strong></td>
                            @endif
                            <td>
								{{$reminder->services['description']}}
								<br>
								@lang('fleet.interval'): {{$reminder->services->overdue_time}} {{$reminder->services->overdue_unit}}
								@if($reminder->services->overdue_meter != null)
								@lang('fleet.or') {{$reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
								@endif
							</td>
							<td> 
								@lang('fleet.start_date'): {{date($date_format_setting,strtotime($reminder->last_date))}}
								<br>
								@lang('fleet.last_performed') @lang('fleet.meter'): {{$reminder->last_meter}}
							</td>
							<td>
								@php($interval = substr($reminder->services->overdue_unit,0,-3))
								@if($reminder->services->overdue_time != null)
								  @php($int = $reminder->services->overdue_time.$interval)
								@else
								  @php($int = Hyvikk::get('time_interval')."day")
								@endif
								  
								@if($reminder->last_date != 'N/D')
								 @php($date = date('Y-m-d', strtotime($int, strtotime($reminder->last_date)))) 
								@else
								 @php($date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))))) 
								@endif
								{{-- {{dd($date)}} --}}
								{{ date($date_format_setting,strtotime($date)) }}
								<br>
								@php   ($to = \Carbon\Carbon::now())
				
								@php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $date))
				
								@php ($diff_in_days = $to->diffInDays($from))
								@lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')
							</td>
							<td>
								@if($reminder->services->overdue_meter != null)
									@if($reminder->last_meter == 0)
										{{$reminder->vehicle->int_mileage + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
									@else
										{{$reminder->last_meter + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
									@endif
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
                            <th>SL#</th>
                            @if(empty($vehicle_id))
                            <th>@lang('fleet.vehicle')</th>
                            @endif
							<th>@lang('fleet.service_item')</th>
							<th>@lang('fleet.start_date') / @lang('fleet.last_performed') </th>
							<th>@lang('fleet.next_due') (@lang('fleet.date'))</th>
							<th>@lang('fleet.next_due') (@lang('fleet.meter'))</th>
						</tr>
					</tfoot>
				</table>
				{{--<br>
				 <table class="table">
					<tr>
                        <th style="float:right">Total : {{Helper::properDecimals($total_salary)}}</th>
						<th style="float:right">Payable : {{Helper::properDecimals($payable_salary)}}</th>
					</tr>
				</table> --}}
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
        format: 'dd-mm-yyyy'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
  });
</script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#vehicle_id").select2();
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