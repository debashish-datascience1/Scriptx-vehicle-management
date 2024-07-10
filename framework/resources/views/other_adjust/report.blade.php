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
<li class="breadcrumb-item active">Other Adjust Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Other Adjust Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.other-adjust','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('head', "Head", ['class' => 'form-label']) !!}
							{!! Form::select('head',$heads,$request['head'] ?? null,['class'=>'form-control fullsize','id'=>'head','placeholder'=>'Select Head']) !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('method', "Method", ['class' => 'form-label']) !!}
							{!! Form::select('method',$methods,$request['method'] ?? null,['class'=>'form-control fullsize','id'=>'method','placeholder'=>'Select Method']) !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('type', "Type", ['class' => 'form-label']) !!}
							{!! Form::select('type',$types,$request['type'] ?? null,['class'=>'form-control fullsize','id'=>'type','placeholder'=>'Select Type']) !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('ref_no', "Reference No.", ['class' => 'form-label']) !!}
							{!! Form::text('ref_no',$request['ref_no'] ?? null,['class'=>'form-control fullsize','id'=>'ref_no','placeholder'=>'Enter Reference No..']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('bank_id', "Bank", ['class' => 'form-label']) !!}
							{!! Form::select('bank_id',$banks,$request['bank_id'] ?? null,['class'=>'form-control fullsize','id'=>'bank_id','placeholder'=>'Select Bank']) !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1',$request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', $request['date2'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-other-adjust-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
				Other Adjust Report
				</h3>
			</div>

			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table-hover"  id="myTable">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Driver</th>
							<th>Head</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Method</th> {{--ref no too--}}
							<th>Bank</th>
							<th>Remarks</th>
						</tr>
					</thead>
					<tbody>
					@foreach($other_adj as $k=>$oth)
						<tr>
                            <td>{{$k+1}}</td>
							<td>{{$oth->other_advance->driver->name}}</td>
							<td>{{$oth->head}}</td>
                            <td>{{Helper::getCanonicalDate($oth->date,'default')}}</td>
                            <td>
								{{Helper::properdecimals($oth->amount)}}<br>
								@if($oth->type==23)
									<span class="badge badge-success">Credit</span>
								@else
									<span class="badge badge-danger">Debit</span>
								@endif
							</td>
							<td>{{$oth->method_param->label}}<br><strong>{{$oth->ref_no}}</strong></td>
							<td>
								{{$oth->bank_details->bank}}<br>
								<strong>{{$oth->bank_details->account_no}}</strong>
							</td>
							<td>{{Helper::limitText($oth->remarks,30)}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
                            <th>SL#</th>
							<th>Driver</th>
							<th>Head</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Method</th> {{--ref no too--}}
							<th>Bank</th>
							<th>Remarks</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table class="table">
					<tr>
                        <th style="float:right">Total Advance: {{Hyvikk::get('currency')}}{{Helper::properDecimals($other_adj->sum('amount'))}}</th>
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
	  $("#head").select2();
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