@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')@endphp
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
				<h3 class="card-title">@lang('fleet.stock_report')
        </h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.stock','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
                            {!! Form::select("vendor_id",$vendors,null,['class'=>'form-control vendor_id','id'=>'vendor_id','placeholder'=>'Select Vendor','required']) !!}
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']) !!}
							</div>
						</div>
					</div>
				</div>	
				<div class="row newrow">
					<div class="col-md-12">
						<button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
						<button type="submit" formaction="{{url('admin/print-stock-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@if(isset($invoices))
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    Parts Invoices
                </h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Vendor</th>
                            <th>Bill No</th>
                            <th>Date of Purchase</th>
							<th>Parts</th>
                            <th>Tyre Numbers</th>
                            <th>Sub Total</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $k=>$invoice)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$invoice->vendor->name}}</td>
                            <td>{{$invoice->billno}}</td>
                            <td>{{$invoice->date_of_purchase}}</td>
							<td>
                                @foreach($invoice->partsDetails as $detail)
                                    @if($detail->parts_zero)
                                        {{$detail->parts_zero->item ?? 'N/A'}} 
                                        {{$detail->parts_zero->category->name ?? 'N/A'}} 
                                        ({{$detail->parts_zero->manufacturer_details->name ?? 'N/A'}})
                                    @else
                                        N/A
                                    @endif
                                    <br>
                                @endforeach
                            </td>
							<td>
								@foreach($invoice->partsDetails as $dat)
									@php
										$partsModel = App\Model\PartsModel::find($dat->parts_id);
										$tyre_numbers = $partsModel ? $partsModel->tyres_used : '';
										$numbers_array = explode(',', $tyre_numbers);
										$formatted_numbers = [];

										foreach (array_chunk($numbers_array, 4) as $chunk) {
											$formatted_numbers[] = implode(', ', $chunk);
										}

										echo nl2br(implode("\n", $formatted_numbers));
									@endphp
								@endforeach
							</td>
                            <td>{{$invoice->sub_total}}</td>
                            <td>{{$invoice->grand_total}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>SL#</th>
                            <th>Vendor</th>
                            <th>Bill No</th>
                            <th>Date of Purchase</th>
							<th>Parts</th>
                            <th>Tyre Numbers</th>
                            <th>Sub Total</th>
                            <th>Grand Total</th>
                        </tr>
                    </tfoot>
                </table>
                <br>
            
                <table class="table">
                    <tr>
                        <th style="float:right">Total Sub Total: {{Hyvikk::get('currency')}} {{$total_sub_total}}</th>
                        <th style="float:right">Total Grand Total: {{Hyvikk::get('currency')}} {{$total_grand_total}}</th>
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
		// $("#vehicle_id,#customer_id").select2();
        $("#vendor_id").select2();

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