@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
	.fullsize{width: 100% !important;}
	.newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	.dateShow{padding-right: 13px;}
	.vevent{cursor: pointer;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">@lang('menu.reports')</a></li>
<li class="breadcrumb-item active">Vehicle Advance Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Vehicle Advance Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.vehicle-advance-report','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control fullsize','id'=>'vehicle_id','placeholder'=>'Select Vehicle']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('param_id', "Heads", ['class' => 'form-label']) !!}
							{!! Form::select('param_id',$heads,$request['param_id'] ?? null,['class'=>'form-control fullsize','id'=>'param_id','placeholder'=>'Select Head']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1',isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2',isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-vehicle-advance-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Vehicle Advance Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
                            <th>Vehicle</th>
                            <th>Head</th>
                            <th>{{Hyvikk::get('currency')}} Amount</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					@foreach($advanceDriver as $k=>$adv)
						<tr>
                            <td>{{$k+1}}</td>
							<td><strong>{{$adv->vehicle->license_plate}}</strong></td>
							<td>{{$adv->param_name->label}}</td>
							<td>{{Hyvikk::get('currency')}} {{bcdiv($adv->total,1,2)}}</td>
							<td>
								<a class="vevent" data-id="{{$adv->vehicle_id}}" data-param="{{$adv->param_id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
                            <th>SL#</th>
                            <th>Vehicle</th>
                            <th>Head</th>
                            <th>{{Hyvikk::get('currency')}} Amount</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
                        <th style="float:right">Total : {{Hyvikk::get('currency')}} {{bcdiv($advanceDriver->sum('total'),1,2)}}</th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal view-->
<div id="viewModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content" style="width:158%">
		<div class="modal-header" style="border-bottom:none;">
		  <h5>Fuel Details Report</h5>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		
		<div class="modal-body">
			Loading...
		</div>
		<div class="modal-footer">
		 
		  <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
		</div>
		
	  </div>
	</div>
  </div>
  <!-- Modal -->
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
		$("#vehicle_id,#param_id").select2();
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

		$(".vevent").on("click",function(){
			var vehicle = $(this).data("id");
			var param = $(this).data("param");
			var from = $("#date1").val();
			var to = $("#date2").val();
			// var arr = {id:id,fuel:fuel};
			var arr = [vehicle,param,from,to];
			$("#viewModal .modal-body").load('{{url("admin/reports/vehicle-advance/vehicle-head-advance-report")}}/'+arr,function(res){
				$("#viewModal").modal({show:true})
			})
			
		})


	});

	
</script>
@endsection