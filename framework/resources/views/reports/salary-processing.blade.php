@extends('layouts.app')
@php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
@endphp

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Salary Processing Report</li>
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
        <h3 class="card-title">Salary Processing Report
        </h3>
      </div>

      
      <div class="card-body">
        {!! Form::open(['route' => 'reports.salary-processing','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('driver_id', 'Driver', ['class' => 'form-label']) !!}
              {!!Form::select('driver_id[]',$drivers,$request['driver_id'] ?? null,['class'=>'form-control fullsize','multiple'=>'multiple','id'=>'driver_id','placeholder'=>'ALL'])!!}
            </div>
          </div>
          {{-- <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('heads', 'Heads', ['class' => 'form-label']) !!}
              {!!Form::select('heads',$heads,$request['heads'],['class'=>'form-control fullsize','placeholder'=>'Select Head'])!!}
            </div>
          </div> --}}
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('months', "Month(s)", ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::select('months',$months,$request['months'] ?? null,['class'=>'form-control fullsize','required','id'=>'months','placeholder'=>'Select Month'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('years', "Year(s)", ['class' => 'form-label']) !!}
              &nbsp;
              {!!Form::select('years',$years,$request['years'] ?? null,['class'=>'form-control fullsize','required','id'=>'years','placeholder'=>'Select Year'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('payment_type', "Type", ['class' => 'form-label']) !!}
              {!! Form::select('payment_type', ['bank' => 'Bank', 'cash' => 'Cash'], $request['payment_type'] ?? null, ['class' => 'form-control fullsize', 'id' => 'payment_type', 'placeholder' => 'All']) !!}
            </div>
          </div>
        </div>
        <div class="row newrow">
          <div class="col-md-12">
            <button type="submit" class="btn btn-info gen_report" style="margin-right: 10px">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-salary-processing')}}" class="btn btn-danger print_report" formtarget="_blank"><i class="fa fa-print"></i> @lang('fleet.print')</button>
            <button type="submit" formaction="{{ url('admin/export-salary-processing') }}" class="btn btn-success export_excel"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
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
              <th>Name</th>
              <th>Bank</th>
              <th>A/C No.</th>
              <th>Payable Amount</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 1; @endphp
            @foreach($salaries as $k=>$row) 
            @php
              $bankInfo = $row->is_payroll ? $row->driver->bank : $row->bank;
              $showRow = ($row->active_status == 1) && 
                         (!$request['payment_type'] || 
                         ($request['payment_type'] == 'bank' && !empty($bankInfo)) || 
                         ($request['payment_type'] == 'cash' && empty($bankInfo)));
            @endphp
            @if($showRow)
            <tr>
              <td>{{$counter++}}</td>
              <td>
                @if($row->is_payroll)
                  {{$row->driver->name}}
                @else
                  {{$row->driver}}
                @endif
              </td>
              <td>
                @if(!empty($bankInfo))
                  {{$bankInfo}}
                @else
                  Cash
                @endif
              </td>
              <td>
                @if($row->is_payroll)
                  {{$row->driver->account_no ?? 'N/A'}}
                @else
                  {{$row->account_no ?? 'N/A'}}
                @endif
              </td>
              <td>
                {{bcdiv($row->payable_salary,1,2)}}
                @if($row->is_payroll)
                <span title="Paid" class="check"><i class="fa fa-check"></i></span>
                @endif
              </td>
            </tr>
            @endif
            @endforeach
          </tbody>
           <tfoot>
            <tr>
              <th>SL#</th>
              <th>Name</th>
              <th>Bank</th>
              <th>A/C No.</th>
              <th>Payable Amount</th>
            </tr>
          </tfoot> 
        </table>
        <br>
          @php
          $totalPayableSalary = $salaries->filter(function($row) use ($request) {
            $bankInfo = $row->is_payroll ? $row->driver->bank : $row->bank;
            return ($row->active_status == 1) &&
                   (!$request['payment_type'] || 
                   ($request['payment_type'] == 'bank' && !empty($bankInfo)) || 
                   ($request['payment_type'] == 'cash' && empty($bankInfo)));
          })->sum('payable_salary');
        @endphp

        <table class="table">
          <tr>
            <th style="float:right">Total Payable Salary : {{Hyvikk::get('currency')}} {{bcdiv($totalPayableSalary,1,2)}}</th>
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
        format: 'yyyy-mm-dd'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
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