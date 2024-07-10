@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Vendor Report</li>
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
        <h3 class="card-title">Vendor Report
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'reports.vendor-report','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
					<div class="col-md-3">
            <div class="form-group">
              {!! Form::label('vendor', __('fleet.vendor'), ['class' => 'form-label']) !!}
              {!!Form::select('vendor',$vendors,$request['vendor'],['class'=>'form-control fullsize','placeholder'=>'Select Fuel Vendor'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']) !!}
              &nbsp; <br>
              {!!Form::select('fuel_type',$fuel_types,$request['fuel_type'],['class'=>'form-control fullsize','placeholder'=>'Select Fuel Type'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('from_date',Helper::indianDateFormat($request['from_date']),['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
					<div class="col-md-3">
            <div class="form-group">
              {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('to_date',Helper::indianDateFormat($request['to_date']),['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-12">
            <button type="submit" class="btn btn-info">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-vendor-vehicle-fuel-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
            <button type="submit" formaction="{{url('admin/reports/export/vendor-report-export')}}" formtarget="_blank" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</button>
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
              <th>Date</th>
              @if($is_vendor!=true)
              <th>Vendor</th>
              @endif
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th>Rate</th>
              <th><span class="fa fa-inr"></span> Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fuel as $k=>$row)
            <tr>
              <td>{{$k+1}}</td>
              <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
              @if($is_vendor!=true)
            <td>
              @if(!empty($row->vendor))
                {{$row->vendor->name}}
              @else
                <span class='badge badge-danger'>Unnamed Vendor</span>
              @endif
            </td>
              @endif
              <td>{{$row->vehicle_data->make}}-{{$row->vehicle_data->model}}-<strong>{{strtoupper($row->vehicle_data->license_plate)}}</strong></td>
              <td>
                @if(!empty($row->fuel_details))
                  {{$row->fuel_details->fuel_name}}
                @else
                  <span class='badge badge-danger'>Unnamed Fuel</span>
                @endif
              </td>
              <td>{{$row->qty}}</td>
              <td>{{Hyvikk::get('currency')}} {{$row->cost_per_unit}}</td>
              <td>{{Hyvikk::get('currency')}} {{bcdiv($row->qty * $row->cost_per_unit,1,2)}}</td>
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
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>

                <th style="float:right">Total Fuel :  {{bcdiv($fuelLtr,1,2)}} ltr</th>
                {{-- <th style="float:right">Total PC :  {{bcdiv($fuelPc,1,2)}}</th> --}}
                {{-- <th style="float:right">Total Qty :  {{bcdiv($fuelQtySum,1,2)}}</th> --}}
                <th style="float:right">Total Amount : {{Hyvikk::get('currency')}} {{bcdiv($fuelTotal,1,2)}}</th>
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

        // 'initComplete': function (settings, json){
        //     this.api().columns('.sum').every(function(){
        //         var column = this;

        //         var sum = column
        //             .data()
        //             .reduce(function (a, b) { 
        //             a = parseInt(a, 10);
        //             if(isNaN(a)){ a = 0; }                   

        //             b = parseInt(b, 10);
        //             if(isNaN(b)){ b = 0; }

        //             return a + b;
        //             });

        //         $(column.footer()).html('Sum: ' + sum);
        //     });
        // }
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