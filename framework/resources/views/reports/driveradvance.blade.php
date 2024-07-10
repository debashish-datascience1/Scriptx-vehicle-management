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
<li class="breadcrumb-item active">Driver Advance Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Driver Advance Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.drivers-advance-report','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('driver_id', __('fleet.driver'), ['class' => 'form-label']) !!}
							{!! Form::select('driver_id',$drivers,$request['driver_id'] ?? null,['class'=>'form-control fullsize','id'=>'driver_id','placeholder'=>'Select Driver']) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1',$request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
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
						<button type="submit" class="btn btn-info">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-drivers-advance-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Driver Advance Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Date</th>
                            {{-- @if(empty($driver_id)) --}}
							<th>Driver</th>
                            <th>Vehicle</th>
                            {{-- @endif --}}
							<th>From - To</th>
							<th>Distance Duration</th>
							<th>Party</th>
                            <th>{{Hyvikk::get('currency')}} Advance</th>
						</tr>
					</thead>
					<tbody>
					@foreach($advance_bookings as $k=>$adv)
						<tr>
							<td>{{$k+1}}</td>
                            <td>{{Helper::getCanonicalDate($adv->pickup,'default')}}<br>{{date("h:i:s A",strtotime($adv->pickup))}}</td>
                            {{-- @if(empty($driver_id)) --}}
							<td>{{$adv->driver->name}}</td>
                            <td>{{$adv->vehicle->license_plate}}</td>
                            {{-- @endif --}}
                            <td>{{$adv->pickup_addr}} <span class="fa fa-long-arrow-right"></span> {{$adv->dest_addr}}</td>
							<td>{{$adv->distance}} <br>{{$adv->duration_map}}</td>
							<td>{{$adv->party_name}}</td>
							<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($adv->advance_pay)}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
                            <th>Date</th>
                            {{-- @if(empty($driver_id)) --}}
							<th>Driver</th>
                            <th>Vehicle</th>
                            {{-- @endif --}}
							<th>From - To</th>
							<th>Distance Duration</th>
							<th>Party</th>
                            <th>{{Hyvikk::get('currency')}} Advance</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
                        <th style="float:right">Total : {{Hyvikk::get('currency')}} {{Helper::properDecimals($total_advance)}}</th>
					</tr>
				</table>
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