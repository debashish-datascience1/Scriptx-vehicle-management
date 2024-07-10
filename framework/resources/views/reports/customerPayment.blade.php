@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Customer Payment Report</li>
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
    .where_from,.advance_for{cursor: pointer;}
    .where_from{color:#fff!important; }
    .border-refund{border:2px solid #s02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .form-label{display:block !important;}
    .fullsize{width: 100% !important;}
	  .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	  .dateShow{padding-right: 13px}
  </style>
@endsection
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Customer Payment Report
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'reports.customerPayment','method'=>'post','class'=>'form-block']) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('customer_id', 'Customers', ['class' => 'form-label']) !!}
              {!!Form::select('customer_id',$customers,$request['customer_id'] ?? null,['class'=>'form-control','id'=>'customer_id','placeholder'=>'Select Customer'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
              {!!Form::text('from_date',isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,['class'=>'form-control','readonly'])!!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
              {!!Form::text('to_date',isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,['class'=>'form-control','readonly'])!!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <button type="submit" class="btn btn-info gen_report">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-customer-payment')}}" formtarget="_blank"  class="btn btn-danger print_report"><i class="fa fa-print"></i> @lang('fleet.print')</button>
          </div>
        </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>

@if(isset($result))
<div class="container-fluid">
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
                <th>Ref. No.</th>
                <th>Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody>
               @foreach($transactions as $k=>$row) 
              <tr>
                <td>{{$k+1}}</td>
                <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
                <td>{{$row->transaction_id}}</td>
                <td>
                  @if($row->param_id==18)
                    {{-- @if (empty($row->fuel))
                        {{dd($row)}}
                    @endif --}}
                    @if ($row->is_bulk!=1)    
                      Freight of {{Hyvikk::get('currency')}}{{$row->booking->total_price}} containing {{$row->booking->material}}({{$row->booking->loadqty}} {{Helper::getParamFromID($row->booking->loadtype)->label}}) transported by <strong>{{$row->booking->vehicle->license_plate}}</strong>({{$row->booking->driver->name}}) on {{Helper::getCanonicalDateTime($row->booking->pickup,'default')}} for {{$row->booking->distance}} from {{$row->booking->pickup_addr}} to {{$row->booking->dest_addr}} in {{$row->booking->duration_map}}
                    @endif
                    @if ($row->is_bulk==1) 
                      {{-- {{dd($row->bulk_data)}} --}}
                      Bulk Paid towards Booking
                    @endif
                  @else
                    {{dd($row)}}
                  @endif
                </td>
                <td>
                  @if($row->is_bulk!=1)
                      {{bcdiv($row->total,1,2)}}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if($row->is_bulk==1)
                     {{bcdiv($row->total,1,2)}}
                  @else
                    -
                  @endif
                </td>
                <td>{{bcdiv($row->new_total,1,2)}}</td>
              </tr>
              @endforeach
              
            </tbody>
             <tfoot>
              <tr>
                <th>SL#</th>
                <th>Date</th>
                <th>Ref. No.</th>
                <th>Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
              </tr>
            </tfoot> 
          </table>
          <br>
          <table class="table">
            <tr> <th style="float:right">Opening Balance : {{Hyvikk::get('currency')}} {{bcdiv($opening_balance,1,2)}}</th>
            </tr>
        </table>
        </div>
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
    console.log(id);
    console.log(partsw);
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

  $(".gen_report,.print_report").on("click",function(){
    var blankTest = /\S/
    var customer_id = $("#customer_id").val();
    if(!blankTest.test(customer_id)){
      alert("Please choose a customer");
      $("#customer_id").focus();
      return false;
    }
  })
});
</script>
@endsection