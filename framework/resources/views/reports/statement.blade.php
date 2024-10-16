@extends('layouts.app')
@php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
@endphp
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
<li class="breadcrumb-item active">Account Statement</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Account Statement
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.statement','method'=>'post','class'=>'form-inline']) !!}
				<div class="row newrow">
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							{!! Form::text('date1', isset($from_date) ? Helper::indianDateFormat($from_date) : null,['class' => 'form-control fullsize','placeholder'=>__('fleet.start_date'),'readonly']) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							{!! Form::text('date2', isset($to_date) ? Helper::indianDateFormat($to_date) : null,['class' => 'form-control fullsize','placeholder'=>__('fleet.end_date'),'readonly']) !!}
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_statement')</button>
						<button type="submit" formaction="{{url('admin/print-statement')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Account Statement
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>@lang('fleet.date')</th>
							<th>Invoice ID</th>
							<th>Method</th>
							<th>Type</th>
							<th>Particulars</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					@foreach($transactions as $k=>$t)
						<tr>
							<td>{{$k+1}}</td>
							<td>{{Helper::getCanonicalDate($t->dateof,'default')}}</td>
							<td>{{$t->transaction->transaction_id}}</td>
							<td>{{$t->method->label}}</td>
							<td>{{$t->transaction->pay_type->label}}</td>
							<td>
								@if($t->transaction->param_id==18 && $t->transaction->advance_for==21)
									{{Hyvikk::get('currency')}}  {{$t->transaction->booking->advance_pay}} advance given to {{$t->transaction->booking->driver->name}} for Booking references <strong>{{!empty(Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)) ? Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)->transaction_id : 'n/a'}} </strong>  on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@elseif($t->transaction->param_id==18 && $t->transaction->advance_for==22)
									{{Hyvikk::get('currency')}}  {{$t->transaction->booking->payment_amount}} paid by {{$t->transaction->booking->customer->name}} for Booking on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@elseif($t->transaction->param_id==18)
									{{Hyvikk::get('currency')}} {{bcdiv($t->amount,1,2)}} {{$t->transaction->pay_type->label}}ed {{$t->transaction->type==23 ? "to" : "from"}} {{$t->transaction->params->label}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@elseif($t->transaction->param_id==19)
									{{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label}}ed towards {{$t->transaction->payroll->driver->name}} for the month of <strong>{{date('F-Y',strtotime($t->transaction->payroll->for_date))}}/{{date('m-Y',strtotime($t->transaction->payroll->for_date))}}</strong>  {{$t->transaction->type==23 ? "to" : "from"}} {{$t->transaction->params->label}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@elseif($t->transaction->param_id==20)
									{{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label}}ed towards {{$t->transaction->fuel->vendor->name}} for <strong>{{$t->transaction->fuel->vehicle_data->license_plate}}</strong> {{$t->transaction->type==23 ? "to" : "from"}}  {{$t->transaction->params->label}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@else
									{{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label}}ed {{$t->transaction->type==23 ? "to" : "from"}} {{$t->transaction->params->label}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
								@endif
							</td>
							<td>
								@if (!in_array($t->transaction->param_id,[18,20,26]))
									{{bcdiv($t->transaction->total,1,2)}}
								@else
									{{bcdiv($t->amount,1,2)}}
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>SL#</th>
							<th>@lang('fleet.date')</th>
							<th>Invoice ID</th>
							<th>Method</th>
							<th>Type</th>
							<th>Action</th>
							<th>Amount</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
						<th style="float:right">Closing Balance: {{Hyvikk::get('currency')}} {{bcdiv($closingBalance,1,2)}}</th>
						<th style="float:right">Opening Balance: {{Hyvikk::get('currency')}} {{bcdiv($openingBalance,1,2)}}</th>
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