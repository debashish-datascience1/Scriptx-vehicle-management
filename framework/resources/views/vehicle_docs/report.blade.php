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
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Vehicle Documents Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Vehicle Documents Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.vehicle-docs','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('documents', "Documents", ['class' => 'form-label']) !!}
							{!! Form::select('documents',$documents,$request['documents'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'documents','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1', isset($date1) ? Helper::indianDateFormat($date1) : null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', isset($date2) ? Helper::indianDateFormat($date2) : null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-vehicle-docs-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Vehicle Document Renewal Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							{{-- <th>Driver</th> --}}
							<th>Vendor</th>
							<th>Documents</th>
							<th>Method / Ref. No.</th>
							<th>Renewed On</th>
              				<th>Valid Till</th>
              				<th>Remaining Days</th>
              				<th>Amount</th>{{--method,ddno,status--}}
							<th>Remarks</th>
						</tr>
					</thead>
					<tbody>
					@foreach($docs as $k=>$d)
						<tr>
							<td>{{$k+1}}</td>
							<td>{{$d->vehicle->license_plate}}</td>
							{{-- <td>
								@if(!empty($d->driver_id) && !empty($d->drivervehicle) && !empty($d->drivervehicle->assigned_driver))
									{{$d->drivervehicle->assigned_driver->name}}
								@else
								<span style="color: red"><small><i>Driver not assigned</i></small></span>
								@endif
							</td> --}}
							<td>
								{{$d->vendor->name}}
							</td>
							<td>{{$d->document->label}}</td>
							<td>
								<span class="badge badge-primary">{{$d->method_param->label}}</span><br>
								{{$d->ddno}}
							</td>
              				<td>{{Helper::getCanonicalDate($d->date,'default')}}</td>
              				<td>{{Helper::getCanonicalDate($d->till,'default')}}</td>
              				<td>@lang('fleet.after') {{Helper::renewLastday($d->till)}} @lang('fleet.days')</td>
							<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($d->amount)}}</td>
							<td>{{$d->remarks}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>Vehicle</th>
							<th>Driver</th>
							<th>Vendor</th>
							<th>Documents</th>
							<th>Method</th>
							<th>Renewed On</th>
              				<th>Valid Till</th>
              				<th>Remaining Days</th>
              				<th>Amount</th>{{--method,ddno,status--}}
							<th>Remarks</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
						<th style="float:right">Grand Total : {{Helper::properDecimals($docs->sum('amount'))}}</th>
						{{-- <th style="float:right">Total Fuel: {{Helper::properDecimals($fuel_totalqty)}} ltr</th>
						<th style="float:right">Total KM: {{Helper::properDecimals($total_km)}} km</th> --}}
					
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