@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Vehicle EMI Report</li>
@endsection
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">

<style type="text/css">
.form-label{display:block !important;}
    .mybtn1
    {
     padding-top: 4px;
      padding-right: 8px;
      padding-bottom: 4px;
      padding-left: 8px;
    }
  
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
    
    .fullsize{width: 100% !important;}
	  .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	  .dateShow{padding-right: 13px}
    .check{color: green;font-size: 15px;}
  </style>
@endsection
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Vehicle EMI
        </h3>
      </div>

      
      <div class="card-body">
        {!! Form::open(['route' => 'reports.vehicle-emi','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('vehicle_id', 'Vehicle', ['class' => 'form-label']) !!}
              {!!Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control fullsize','id'=>'driver_id','placeholder'=>'Select Vehicle'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('driver_id', 'Driver', ['class' => 'form-label']) !!}
              {!!Form::select('driver_id',$drivers,$request['driver_id'] ?? null,['class'=>'form-control fullsize','id'=>'driver_id','placeholder'=>'Select Driver'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('bank_id', 'Bank', ['class' => 'form-label']) !!}
              {!!Form::select('bank_id',$banks,$request['bank_id'] ?? null,['class'=>'form-control fullsize','id'=>'bank_id','placeholder'=>'Select Bank'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('reference_no', 'Reference No.', ['class' => 'form-label']) !!}
              {!!Form::text('reference_no',$request['reference_no'] ?? null,['class'=>'form-control fullsize','id'=>'reference_no','placeholder'=>'Please Enter Reference No.'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('from_date', 'From Date', ['class' => 'form-label']) !!}
              {!!Form::text('from_date',$request['from_date'] ?? null,['class'=>'form-control fullsize','id'=>'from_date','placeholder'=>'Please Select From Date','readonly'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('to_date', 'To Date', ['class' => 'form-label']) !!}
              {!!Form::text('to_date',$request['to_date'] ?? null,['class'=>'form-control fullsize','id'=>'to_date','placeholder'=>'Please Select To Date','readonly'])!!}
            </div>
          </div>
          <div class="col-md-1">
            <div class="form-group">
              {!! Form::label('search_type', 'Search By', ['class' => 'form-label']) !!}
              {!!Form::select('search_type',['date'=>'Due Date','pay_date'=>'Paid Date'],$request['search_type'] ?? null,['class'=>'form-control fullsize','id'=>'search_type'])!!}
            </div>
          </div>
        </div>
        <div class="row newrow">
          <div class="col-md-12">
            <button type="submit" class="btn btn-info gen_report" style="margin-right: 10px">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-vehicle-emi')}}" class="btn btn-danger print_report" formtarget="_blank"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
          @lang('fleet.report')
        </h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover"  id="myTable">
          <thead>
            <tr>
              <th>SL#</th>
              <th>Due Date</th>
              <th>Paid Date</th>
              <th>Vehicle</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Reference No</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($emiData as $k=>$row) 
            <tr>
              <td>{{$k+1}}</td>
              <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
              <td>{{Helper::getCanonicalDate($row->pay_date,'default')}}</td>
              <td>{{$row->vehicle->license_plate}}</td>
              <td>{{!empty($row->driver) ? $row->driver->name : ''}}</td>
              <td>{{$row->bank->bank_name}}</td>
              <td>{{$row->reference_no}}</td>
              <td>{{bcdiv($row->amount,1,2)}}</td>
            </tr>
            @endforeach
          </tbody>
           <tfoot>
            <tr>
              <th>SL#</th>
              <th>Due Date</th>
              <th>Paid Date</th>
              <th>Vehicle</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Reference No</th>
              <th>Amount</th>
            </tr>
          </tfoot> 
        </table>
        <br>
        <table class="table">
          <tr> <th style="float:right">Total Amount : {{Hyvikk::get('currency')}} {{bcdiv($emiData->sum('amount'),1,2)}}</th>
          </tr>
      </table>
      </div>
    </div>
  </div>
</div>

@endif


<!-- Modal -->
<div id="whereModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <div class="modal-content">
          Loading....
        </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div id="advanceForModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Advance Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            Loading...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
            </button>
          </div>
        </div>
    </div>
  </div>
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
  $("#driver_id").select2();
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


  $(".where_from").on("click",function(){
    var id = $(this).data("id");
    var partsw = $(this).data("partsw");
    // console.log(id);
    // console.log(partsw);
    $("#whereModal .modal-content").load('{{url("admin/accounting/where_from")}}/'+id,function(res){
      typeof partsw!="undefined" ? $(this).css('width','160%') : $(this).css('width','');
      $("#whereModal").modal({show:true})
    })
  })

  $(".advance_for").on("click",function(){
    var id = $(this).data("id");
    $("#advanceForModal .modal-body").load('{{url("admin/accounting/advance_for")}}/'+id,function(res){
      $("#advanceForModal").modal({show:true})
    })
  })

  // $(".gen_report,.print_report").on("click",function(){
  //   var blankTest = /\S/
  //   var driver_id = $("#driver_id").val();
  //   console.log(driver_id);
  //   if(!blankTest.test(driver_id)){
  //     alert("Please choose  Driver(s)");
  //     $("#driver_id").focus();
  //     return false;
  //   }
  // })
});
</script>
@endsection