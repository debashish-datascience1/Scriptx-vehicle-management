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
<li class="breadcrumb-item"><a href="{{route('vehicle-docs.index')}}">Vehicle Documents</a></li>
<li class="breadcrumb-item active">Upcoming Renewal Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Upcoming Renewal Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'vehicle-docs.upcoming-report','method'=>'post','class'=>'form-inline']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicle_Array,$request['vehicle_id'],['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('documents', "Documents", ['class' => 'form-label']) !!}
							{!! Form::select('documents',$documents,$request['documents'],['class'=>'form-control vehicles fullsize','id'=>'documents','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-upcoming-renewal-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
							
						</tr>
					</thead>
					<tbody>
					@foreach($data as $k=>$d)
					{{-- Check Last Date, Amount, Duration --}}
						<tr>
							<td>{{$k+1}}</td>
							<td>{{$d->vehicle_id}}</td>
							<td>
								@if(!empty($d->driver_id))
									{{$d->driver_id}}
								@else
								<span style="color: red"><small><i>Driver not assigned</i></small></span>
								@endif
							</td>
							<?php
								$vahan = $d->vehicleObj;
								$duration = $vahan->getMeta($docparamArray[$d->pid][0]);
								$paisa = $vahan->getMeta($docparamArray[$d->pid][1]);
							?>
							<td>
								{{$d->vendor_id}}
							</td>
							<td>
								{{$d->param_id}}
								{{-- @if($d->daysleft>0 && !empty($duration) && !empty($paisa))
								@elseif($d->daysleft<=0 && !empty($duration) && !empty($paisa))
								<small>Renewable</small>
								@else
								<small>Duration/Amount not set</small>
								@endif --}}
							</td>
              				<td>{{Helper::getCanonicalDate($d->date,'default')}}</td>
              				<td>{{Helper::getCanonicalDate($d->till,'default')}}</td>
							<td>
								@if($d->daysleft>0 && !empty($duration) && !empty($paisa))
									@lang('fleet.after') {{$d->daysleft}} @lang('fleet.days')
								@elseif($d->daysleft<=0 && !empty($duration) && !empty($paisa))
									<small>due since {{abs($d->daysleft)}} day(s)</small>
									<span class="badge badge-danger">Renewable</span>
								@else
								<small>Duration/Amount not set.</small><br>
								<a href="../vehicles/{{$d->vid}}/edit?tab=insurance" target="_blank">set here</a>
								@endif
							</td>
							<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($d->amount)}}</td>
							<td>{{$d->remarks}}</td>
							{{-- @else 
							<td colspan="7" align="center">
								<label for="">{{$d->param_id}} duration and amount is not set</label> <a href="../vehicles/{{$d->vid}}/edit?tab=insurance" target="_blank">click here</a> to set
							</td>
							@endif --}}
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							<th>Driver</th>
							<th>Vendor</th>
							<th>Documents</th>
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
						<th style="float:right">Grand Total : {{Hyvikk::get('currency')}} {{Helper::properDecimals($data->sum('amount'))}}</th>
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
		$("#vehicle_id,#documents").select2();
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