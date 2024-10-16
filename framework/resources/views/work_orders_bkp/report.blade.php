@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Work Orders</a></li>
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
        {!! Form::open(['route' => 'work_order.report-vendor','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
					<div class="col-md-4">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']) !!}
              {!!Form::select('vendor',$vendors,$request['vendor'],['class'=>'form-control fullsize','placeholder'=>'Select Vendor'])!!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('from_date',$request['from_date'],['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('to_date',$request['to_date'],['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-4">
            <div class="form-group" style="margin-right: 10px">
              {!! Form::label('status', __('fleet.status'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::select('status',$status,$request['status'],['class'=>'form-control fullsize','placeholder'=>'Select Status'])!!}
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
              <th width="15%">Description</th>
              <th>Status</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($workOrder as $k=>$row)
            <tr>
              <td>{{$k+1}}</td>
              <td>{{Helper::getCanonicalDate($row->required_by,'default')}}</td>
              @if($is_vendor!=true)
              <td>{{$row->vendor->name}}</td>
              @endif
              <td>{{$row->vehicle->make}}-{{$row->vehicle->model}}-<strong>{{strtoupper($row->vehicle->license_plate)}}</strong></td>
              <td>{{$row->vendor->type}}</td>
              <td>{{$row->description}}</td>
              <td>{{$row->status}}</td>
              <td>{{Hyvikk::get('currency')}} {{number_format($row->price,2)}}</td>
            </tr>
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
              <th>Description</th>
              <th>Status</th>
              <th>Amount</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>
                <th style="float:right">Grand Total : {{Hyvikk::get('currency')}} {{Helper::properDecimals($gtotal)}}</th>
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
      dom: 'Bfrtip',
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
        format: 'yyyy-mm-dd'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
});
</script>
@endsection