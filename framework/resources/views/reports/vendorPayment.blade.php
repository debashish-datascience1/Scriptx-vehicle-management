@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Vendor Payment Report</li>
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
    .border-refund{border:2px solid #02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
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
        <h3 class="card-title">Vendor Payment Report
        </h3>
      </div>

      
      <div class="card-body">
        {!! Form::open(['route' => 'reports.vendorPayment','method'=>'post','class'=>'form-inline']) !!}
        <div class="row newrow">
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('vendor_id', 'Vendor', ['class' => 'form-label']) !!}
              {!!Form::select('vendor_id',$vendors,$request['vendor_id'] ?? null,['class'=>'form-control fullsize','placeholder'=>'Select Vendor'])!!}
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
            <button type="submit" class="btn btn-info gen_report" style="margin-right: 10px">@lang('fleet.generate_report')</button>
            <button type="submit" formaction="{{url('admin/print-vendor-payment')}}" class="btn btn-danger print_report" formtarget="_blank"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
              <td nowrap>{{$row->transaction_id}}</td>
              <td>
                @if($row->param_id==20)
                  {{-- @if (empty($row->fuel))
                      {{dd($row)}}
                  @endif --}}
                  @if ($row->is_bulk!=1)    
                    {{$row->fuel->qty}} ltr {{$row->fuel->fuel_details->fuel_name}} {{$row->fuel->cost_per_unit}} per unit total of  {{bcdiv($row->fuel->qty * $row->fuel->cost_per_unit,1,2)}} fuel filled for {{$row->fuel->vehicle_data->license_plate}} 
                  @endif
                  @if ($row->is_bulk==1) 
                    {{-- {{dd($row->bulk_data)}} --}}
                    Bulk Paid towards Fuel
                    {{-- {{$row->fuel->qty}} ltr {{$row->fuel->fuel_details->fuel_name}} {{$row->fuel->cost_per_unit}} per unit total of  {{bcdiv($row->fuel->qty * $row->fuel->cost_per_unit,1,2)}} fuel filled for {{$row->fuel->vehicle_data->license_plate}}  --}}
                  @endif
                @elseif($row->param_id==26)
                  @if ($row->is_bulk!=1)    
                   {{$row->parts->partsDetails->count()}} items added to sum total of {{$row->parts->partsDetails->sum('total')}}
                  @endif
                  @if ($row->is_bulk==1) 
                    Bulk Paid towards PartsInvoice
                  @endif
                @elseif($row->param_id==28)
                  @if ($row->is_bulk!=1)    
                    WorkOrder having bill {{$row->workorders->bill_no}} amount {{Hyvikk::get('currency')}} {{bcdiv($row->workorders->grand_total,1,2)}} paid to {{$row->workorders->vendor->name}} for {{$row->workorders->vehicle->license_plate}}
                  @endif
                  @if ($row->is_bulk==1) 
                    Bulk Paid towards WorkOrder
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
  $("#vendor_id,#heads").select2();
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
    var vendor_id = $("#vendor_id").val();
    if(!blankTest.test(vendor_id)){
      alert("Please choose a vendor");
      $("#vendor_id").focus();
      return false;
    }
  })
});
</script>
@endsection