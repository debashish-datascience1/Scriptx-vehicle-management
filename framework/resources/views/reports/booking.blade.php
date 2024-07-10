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
<li class="breadcrumb-item active">@lang('fleet.booking_report')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">@lang('fleet.booking_report')
        </h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.booking','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,isset($request['vehicle_id']) ? $request['vehicle_id'] : null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('customer_id', "Customer", ['class' => 'form-label']) !!}
							{!! Form::select('customer_id',$customers,isset($request['customer_id']) ? $request['customer_id'] : null,['class'=>'form-control vehicles fullsize','id'=>'customer_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-booking-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
          Bookings
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<th>Customer</th>
							<th>From-To</th>
							<th>Distance</th>
							<th>Fuel Consumption</th>
							<th>Pickup Date</th>
							<th>Dropoff Date</th>
							<th>Material</th>
							<th>Quantity</th>
							<th>Driver Advance</th>
							<th>Freight Price</th>
						</tr>
					</thead>
					<tbody>
					    
					@foreach($bookings as $k=>$bk)
						<tr>
							<td>{{$k+1}}</td>
							<th>{{$bk->vehicle->license_plate}}</th>
							<td>{{$bk->customer->name}}</td>
							<td>
								@if(!empty($bk->transaction_det))
								<strong>({{$bk->transaction_det->transaction_id}})</strong><br>
								@endif
								{{$bk->pickup_addr}} <i class="fa fa-long-arrow-right "></i> {{$bk->dest_addr}} 
								{{$bk->getMeta('fodder_km')}}
								@if(!empty($bk->getMeta('fodder_km')))
								@if(!empty($bk->transaction_details) && !empty($bk->transaction_details->booking))
								<br>
								<small>{{$bk->dest_addr}} <span class="fa fa-long-arrow-right"></span>
									{{$bk->transaction_details->booking->pickup_addr}}
								</small><br>
								<small>Distance : {{!empty($bk->getMeta('fodder_km')) ? Helper::properDecimal($bk->getMeta('fodder_km') ?? 0)."km" :null}}</small><br>
								<small>Fuel : {{!empty($bk->getMeta('fodder_consumption')) ? Helper::properDecimal($bk->getMeta('fodder_consumption') ?? 0)."ltr" :null}}</small><br>
								<small>References Booking <strong>{{$bk->transaction_details->transaction_id}}</strong></small>
								@endif
								@endif
							</td>
							<td>
								{{$bk->getMeta('distance')}}
								@if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption')))
								<br>
								<strong>+ {{Helper::properDecimal($bk->getMeta('fodder_km') ?? 0)}} km</strong>
								@endif
							</td>
							<td>@if($bk->getMeta('pet_required') != "Infinity")
								{{Helper::properDecimal($bk->getMeta('pet_required') ?? 0)}} ltr
								@else
								0
								@endif
								@if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption')))
								<br>
								<strong>+ {{$bk->getMeta('fodder_consumption') ?? 0}} ltr</strong>
								@endif
							</td>
							<td nowrap>{{Helper::getCanonicalDate($bk->pickup,'default')}}</td>
							<td nowrap>{{Helper::getCanonicalDate($bk->dropoff,'default')}}</td>
							<td>{{$bk->material}}</td>
							<td>{{$bk->loadqty}} {{$loadset[$bk->getMeta('loadtype')]}}</td>
							<td>{{$bk->advance_pay}}</td>
							<td>{{$bk->total_price}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<th>Customer</th>
							<th>From-To</th>
							<th>Distance</th>
							<th>Fuel Consumption</th>
							<th>Pickup Date</th>
							<th>Dropoff Date</th>
							<th>Material</th>
							<th>Quantity</th>
							<th>Driver Advance</th>
							<th>Freight Price</th>
						</tr>
					</tfoot>
				</table>
				<br>
			
				<table class="table">
					<tr>
						<th style="float:right">Addtional Distance : {{bcdiv($fodderdistance,1,2)}} km</th>
						<th style="float:right">Total Distance : {{bcdiv($total_distance,1,2)}} km</th>
						<th style="float:right">Additional Fuel : {{bcdiv($fodderfuel,1,2)}} ltr</th>
						<th style="float:right">Total Fuel: {{bcdiv($total_fuel,1,2)}} ltr</th>
						<th style="float:right">Grand Total: {{Hyvikk::get('currency')}} {{bcdiv($total_price,1,2)}}</th>
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
    $('#date1,#date2').datepicker({
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
		$("#vehicle_id,#customer_id").select2();
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