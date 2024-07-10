@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
	.itaDates{font-weight: 600;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
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
				{!! Form::open(['route' => 'reports.upcoming-report','method'=>'post','class'=>'form-inline']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicle_Array,$request['vehicle_id'] ?? null,['class'=>'form-control vehicles fullsize','id'=>'vehicle_id','placeholder'=>'ALL']) !!}
						</div>
					</div>
					{{-- <div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('documents', "Documents", ['class' => 'form-label']) !!}
							{!! Form::select('documents',$documents,$request['documents'],['class'=>'form-control vehicles fullsize','id'=>'documents','placeholder'=>'ALL']) !!}
						</div>
					</div>
					<div class="col-md-4"></div> --}}
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
				Upcoming Renewal Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							@foreach ($documents as $dt)	
							<th>{{$dt}}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
					@foreach($data as $k=>$d)
					{{-- Check Last Date, Amount, Duration --}}
						<tr>
							<td>{{$k+1}}</td>
							<th>{{$d->license_plate}}</th>
							@foreach ($documents as $kb=>$db)
							<td>
							<?php	$dbDocs = Helper::dynamicLastDate($d->id,$kb); ?>
								{{-- {{dd($db)}} --}}
								{{-- @if ($dbDocs->status)
									
									{{Helper::getCanonicalDate($dbDocs->date,'default')}}
								@else
								{{!empty($d->getMeta($docparamArray[$kb][2])) ? Helper::getCanonicalDate($d->getMeta($docparamArray[$kb][2]),'default') : ''}}
								@endif --}}

								<?php
									if ($dbDocs->status)
										$date = $dbDocs->date;
									else
									 	$date = !empty($d->getMeta($docparamArray[$kb][2])) ? $d->getMeta($docparamArray[$kb][2]) : '';
								?>
								{{-- eligible renew --}}
								@if(!empty($date) && strtotime($date)<=strtotime(date('Y-m-d')))
									<strong>{{Helper::getCanonicalDate($date,'default')}}</strong>
								@elseif(!empty($date) && strtotime($date)<=strtotime(date('Y-m-d')."+15 days"))
									<i class="itaDates">{{Helper::getCanonicalDate($d->getMeta($docparamArray[$kb][2]),'default')}}</i>
								@else
									{{Helper::getCanonicalDate($date,'default')}}
								@endif
							</td>
							@endforeach
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>Vehicle</th>
							@foreach ($documents as $df)	
							<th>{{$df}}</th>
							@endforeach 
						</tr>
					</tfoot>
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