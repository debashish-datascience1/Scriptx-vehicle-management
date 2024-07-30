@extends('layouts.app')
@php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
@endphp

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Work Order Vendor Report</li>
@endsection
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
</style>
@endsection
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Work Order Vendor Report
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'reports.vendor-work-order','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
					<div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']) !!}
              {!!Form::select('vendor',$vendors,$request['vendor'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Vendor'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('from_date',isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('to_date',isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
					<div class="col-md-3">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('status', __('fleet.status'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::select('status',$status,$request['status'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Status'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-4">
            <button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-work-order-vendor-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
          </div>
        </div>
          {!! Form::close() !!}
        </div>
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
          @lang('fleet.report')
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover"  id="myTable">
        <thead>
        <tr>
              <th>SL#</th>
              <th width="10%">Date</th>
              @if($is_vendor!=true)
              <th>Vendor</th>
              @endif
              <th>Vehicle</th>
              <th>Type</th>
              <th>Is Own</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Part Name</th>
              <th>Quantity</th>
              <th>Tyres Used</th>
              <th>Source</th>
            </tr>
          </thead>
          <tbody>
            @php $slNo = 1; @endphp
            @foreach($processedData as $order)
              @foreach($order['parts'] as $partName => $partData)
                <tr>
                  <td>{{$slNo++}}</td>
                  <td>{{Helper::getCanonicalDate($order['required_by'],'default')}}</td>
                  @if($is_vendor!=true)
                  <td>{{$order['vendor']->name}}</td>
                  @endif
                  <td><strong>{{strtoupper($order['vehicle']->license_plate)}}</strong></td>
                  <td>{{$order['vendor']->type}}</td>
                  <td>
                      @if($order['is_own'] == 1)
                          Yes
                      @elseif($order['is_own'] == 2)
                          No
                      @else
                          Unknown
                      @endif
                  </td>
                  <td>{{$order['status']}}</td>
                  <td>{{Hyvikk::get('currency')}} {{number_format($order['price'],2)}}</td>
                  <td>{{ $partName }}</td>
                  <td>{{ $partData['qty'] }}</td>
                  <td>{{ implode(', ', $partData['tyres']) }}</td>
                  <td>{{ $partData['is_own'] ? 'Own Inventory' : 'Vendor' }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              @if($is_vendor!=true)
              <th>Vendor</th>
              @endif
              <th>Vehicle</th>
              <th>Type</th>
              <th>Is Own</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Part Name</th>
              <th>Quantity</th>
              <th>Parts Used</th>
              <th>Tyres Used</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>
                <th style="float:right">Grand Total : {{Hyvikk::get('currency')}} {{bcdiv($gtotal,1,2)}}</th>
            </tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endif
@endsection

@section("script")

<script type="text/javascript">
	$(document).ready(function() {
		$("#user_id").select2();
	});
</script>

<script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    // $("#driver").select2();
	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
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

    // Dates
    $('#from_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
});
</script>
@endsection