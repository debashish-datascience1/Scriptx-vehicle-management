@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">@lang('fleet.reportVehicle')</li>
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
        <h3 class="card-title">@lang('fleet.reportVehicle')
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'reports.vehicle-fuel-type','method'=>'post','class'=>'form-block']) !!}
        <div class="row newrow">
					<div class="col-md-3">
            <div class="form-group">
              {!! Form::label('vehicle', __('fleet.vehicle'), ['class' => 'form-label']) !!}
              {!!Form::select('vehicle',$vehicles,$request['vehicle'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Vehicle'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']) !!}
              &nbsp; <br>
              {!!Form::select('fuel_type',$fuel_types,$request['fuel_type'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Fuel Type'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('from_date',$request['from_date'] ?? null,['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
					<div class="col-md-3">
            <div class="form-group">
              {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::text('to_date',$request['to_date'] ?? null,['class'=>'form-control fullsize','readonly'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
					<div class="col-md-12">
            <button type="submit" class="btn btn-info">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-vehicle-fuel-type-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Quantity (ltr)</th>
              <th><span class="fa fa-inr"></span> Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fuelv as $k=>$row)
            <tr>
              <td>{{$k+1}}</td>
              <td>
                {{$row->vehicle_data->make}}-{{$row->vehicle_data->model}}
                <strong>{{strtoupper($row->vehicle_data->license_plate)}}</strong>
              </td>
              <td>{{$row->fuel_details->fuel_name}}</td>
              <td>{{bcdiv($row->qty,1,2)}}</td>
              <td>{{Hyvikk::get('currency')}} {{bcdiv($row->total_cost,1,2)}}</td>
              <td><a class="DetailsChk cursor-pointer" data-id="{{$row->vehicle_id}}"  data-fuel="{{$row->fuel_type}}" data-toggle="modal" data-target="#FuelDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green;"></span> Details</a></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Quantity</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
          <tr>
              <th style="float:right">Total Amount: {{Hyvikk::get('currency')}} {{bcdiv($grandtotal_cost,1,2)}}</th>
              <th style="float:right">Total Quantity: {{bcdiv($grandtotal_qty,1,2)}}</th>
          </tr>
      </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal view-->
<div id="FuelDetailsModal" class="modal fade" role="dialog">
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
  $("#vehicle").select2();
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

    $(".DetailsChk").on("click",function(){
      var id = $(this).data("id");
      var fuel = $(this).data("fuel");
      var from = $("#from_date").val();
      var to = $("#to_date").val();
      // var arr = {id:id,fuel:fuel};
      var arr = [id,fuel,from,to];
      // console.log(arr);
      $("#FuelDetailsModal .modal-body").load('{{url("admin/reports/view_vehicle_fuel_details")}}/'+arr,function(res){
        $("#FuelDetailsModal").modal({show:true})
      })
      
    })
});
</script>
@endsection