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
<li class="breadcrumb-item active">@lang('fleet.fuelReport')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">@lang('fleet.fuelReport')
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.fuel_post','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']) !!}
							{!! Form::select('fuel_type',$fuel_types,$request['fuel_type'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'fuel_type','placeholder'=>'ALL']) !!}
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
						<button type="submit" class="btn btn-info">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-fuel-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Fuel Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th width="12%">@lang('fleet.date')</th>
							<th>@lang('fleet.vehicle')</th>
							<th>Vendor</th>
							<th>Fuel</th>
							<th>Quantity</th>
              				<th>Price Per Litre</th>
              				<th>CGST</th>
              				<th>SGST</th>
							<th>@lang('fleet.total')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($fuel as $k=>$f)
						<tr>
							<td>{{$k+1}}</td>
							<td>
								{{Helper::getCanonicalDate($f->date,'default')}}			
							</td>
							<td><strong>{{$f->vehicle_data->license_plate}}</strong></td>
							<td>
								@if (!empty($f->vendor_name) && !empty($f->vendor))
									{{$f->vendor->name}}
								@else
									<span style="color: red"><small>No Vendor Selected</small></span>
								@endif
							</td>
							<td>{{$f->fuel_details->fuel_name}}</td>
							<td>
								{{$f->qty}}
							</td>
              				<td>{{Hyvikk::get('currency')}} {{bcdiv($f->cost_per_unit,1,2)}}</td>
              				<td>
								  @if (!empty($f->is_gst))
									{{!empty($f->cgst) ? $f->cgst."%" : ''}} <br>
									{{!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''}}
								  @endif
							</td>
              				<td>
								@if (!empty($f->is_gst))
									{{!empty($f->sgst) ? $f->sgst."%" : ''}} <br>
									{{!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''}}
								@endif
							</td>
							<td>
								@if (!empty($f->grand_total))
								{{Hyvikk::get('currency')}} {{bcdiv($f->grand_total,1,2)}}
								@else
								{{Hyvikk::get('currency')}} {{bcdiv($f->qty * $f->cost_per_unit,1,2)}}
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>@lang('fleet.date')</th>
							<th>@lang('fleet.vehicle')</th>
							<th>Vendor</th>
							<th>Fuel</th>
							<th>Quantity</th>
              				<th>Price Per Litre</th>
              				<th>CGST</th>
              				<th>SGST</th>
							<th>@lang('fleet.total')</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
						<th style="float:right">Total Amount: {{Hyvikk::get('currency')}} {{bcdiv($fuel->sum('gtotal'),1,2)}}</th>
						<th style="float:right">Total Liter:  {{bcdiv($fuel_totalqty,1,2)}} ltr</th>
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