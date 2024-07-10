@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{route('accounting.index')}}">Transaction</a></li>
<li class="breadcrumb-item active">Search</li>
@endsection
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
    /* .form-label{display:block !important;} */
    .form-display{display: block!important;}
    .where_from,.advance_for{cursor: pointer;}
    .where_from{color:#fff!important; }
    .border-refund{border:2px solid #02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .badge-deposit{background: #b000bb;color:#fff;}
    .badge-revised{background: #da107f;color:#fff;}
    .badge-liability{background: #004e5c;color:#fff;}
    .badge-renewdocs{background: #2944ca;color:rgb(255, 255, 255);}
    .badge-vehicleDoc{background: tomato;color: #fff;}
    .badge-otherAdv{background:darkcyan;color: #fff;}
    .badge-advanceRefund{background: deeppink;color: #fff;}
    .badge-vehicle-downpayment{background: darkgoldenrod;color: #fff;}
    .badge-vehicle-purchase{background: darkgray;color: #fff;}
    .badge-vehicle-emi{background: darkslateblue;color: #fff;}
    .badge-viwevent{background: #0091bd;color:#fff!important;cursor: pointer;}

</style>
@endsection
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Transaction Search
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'transaction.search','method'=>'post']) !!}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('transaction_id', "Transaction", ['class' => 'form-label']) !!}
                    {!! Form::text('transaction_id',$request['transaction_id'],['class'=>'form-control','placeholder'=>'Enter Transaction ID','autocomplete'=>'off']); !!}
                </div>
            </div>
         {{--<div class="col-md-4">
            <div class="form-group">
                {!! Form::label('method', "Method", ['class' => 'form-label']) !!}
                {!! Form::select('method',$method,null,['class'=>'form-control','placeholder'=>'Select Method']); !!}
            </div> 
           </div>--}}
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('from', "From", ['class' => 'form-label']) !!}
                {!! Form::select('from', $from, $request['from'],['class'=>'form-control','placeholder'=>'Select Head']); !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('type', "Payment Type", ['class' => 'form-label']) !!}
                &nbsp; <br>
                {!!Form::select('type',$type,$request['type'],['class'=>'form-control','placeholder'=>'Select Payment Type'])!!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
                &nbsp;
                {!!Form::text('from_date',$request['from_date'],['class'=>'form-control','readonly'])!!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
                &nbsp;
                {!!Form::text('to_date',$request['to_date'],['class'=>'form-control','readonly'])!!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('is_complete', "Is Complete ?", ['class' => 'form-label']) !!}
                &nbsp;
                {!!Form::select('is_complete',$is_complete,$request['is_complete'],['class'=>'form-control','placeholder'=>'Select Status'])!!}
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('bank', "Bank", ['class' => 'form-label']) !!}
                    &nbsp;
                    {!!Form::select('bank',$bank,$request['bank'],['class'=>'form-control','placeholder'=>'Select Bank'])!!}
                </div>
            </div>
            <div class="col-md-4 advance_for" style="display:{{($request['from']==18) ? 'inline-block' : 'none'}}">
                <div class="form-group">
                    {!! Form::label('advance_for', "Advance For", ['class' => 'form-label']) !!}
                    &nbsp;
                    {!!Form::select('advance_for',$advance_for,$request['advance_for'],['class'=>'form-control','placeholder'=>'Select'])!!}
                </div>
            </div>
            <div class="col-md-4">
                    {!! Form::label('order_by', "Order By", ['class' => 'form-label']) !!}
                <div class="form-group">
                    &nbsp;
                    {!!Form::select('order_by',$order_by,$request['order_by'],['class'=>'form-control','style'=>'width:50%!important;display:inline-block'])!!}
                    {!!Form::select('sort',$sort,$request['sort'],['class'=>'form-control','style'=>'width:40%!important;display:inline-block','id'=>'sort'])!!}
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-info" name="search" id="search">Search</button>
            <button type="submit" formaction="{{url('admin/print-transaction')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
            {!! Form::close() !!}
          </div>
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
              <th>Transaction ID</th> {{--with date--}}
              <th>Date</th>
              <th>From</th>{{--with bank--}}
              <th>Type</th>
              <th>Complete?</th>
              @if(!empty($request['advance_for']) && $request['advance_for']==22)
                <th>Advance</th>
              @endif
              </td>
              <th><span class="fa fa-inr"></span> Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transaction as $k=>$row)
              <tr>
                <td>{{$k+1}}</td>
                <td>
                  <a class="vevent badge badge-viwevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"> {{$row->transaction_id}}</a>
                </td>
                <td>
                  <strong>{{!empty($row->date) ? Helper::getCanonicalDate($row->date,'default') : Helper::getCanonicalDate($row->created_at,'default')}}</strong>
                </td>
                <td>
                 @if($row->param_id==18)
                    <a class="badge badge-success where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                    {{-- <span class="badge badge-success">{{!empty($row->params) ? $row->params->lable : 'N/A'}}</span> --}}
                    @if($row->advance_for==21)
                    <br>
                    <a class="badge badge-warning advance_forr" data-id="{{$row->id}}" data-toggle="modal" data-target="#advanceForModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{$row->advancefor->label}}</a>
                    {{-- <span class="badge badge-warning">{{$row->advancefor->label}}</span> --}}
                    @endif
                  @elseif($row->param_id==19)
                    <a class="badge badge-info where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==20)
                    <a class="badge badge-fuel where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==25)
                    <a class="badge badge-driver-adv where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==26)
                    <a class="badge badge-parts where_from" data-id="{{$row->id}}" data-partsw={{$row->id}} data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==27)
                    <a class="badge badge-refund where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==28)
                    <a class="badge badge-info where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==29)
                    <a class="badge badge-starting-amt where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==30)
                    <a class="badge badge-deposit where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==31)
                    <a class="badge badge-revised where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==32)
                    <a class="badge badge-liability where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==35)
                    <a class="badge badge-renewdocs where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                    <span class="badge badge-vehicleDoc">{{$row->vehicle_document->document->label}}</span>
                  @elseif($row->param_id==43)
                    <a class="badge badge-otherAdv where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==44)
                    <a class="badge badge-advanceRefund where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==49)
                    <a class="badge badge-vehicle-downpayment where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                    <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                  @elseif($row->param_id==50)
                    <a class="badge badge-vehicle-emi where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                    <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                  @else{{dd($row->param_id)}}
                @endif
               <br>
               
               {{-- Bank --}}
               <label for="bank">{{empty($row->bank) ? 'N/A' : $row->bank->bank}}</label>
              </td>
                
              <td>
                @if($row->type==23)
                    <span class="badge badge-success">{{$row->pay_type->label}}</span>
                @elseif($row->type==24)
                    <span class="badge badge-danger">{{$row->pay_type->label}}</span>
                @endif
              </td>
              <td>
                @if($row->is_completed==1)
                    <span class="badge badge-success">Completed</span>
                @elseif($row->is_completed==2)
                    <span class="badge badge-warning">In Progress</span>
                @elseif(!$row->is_completed)
                    <strong>N/A</strong>
                @endif
              </td>
              @if(!empty($request['advance_for']) && $request['advance_for']==22)
              <td>
                <strong> {{bcdiv($row->income_expense->amount,1,2)}}</strong>
              </td>
              @endif
              </td>
              <td>
                {{bcdiv($row->total,1,2)}} <br>
            </tr>
              
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>SL#</th>
              <th>Transaction ID</th> {{--with date--}}
              <th>Date</th>
              <th>From</th>{{--with bank--}}
              <th>Type</th>
              <th>Complete?</th>
              @if(!empty($request['advance_for']) && $request['advance_for']==22)
              <th>Advance</th>
              @endif
              <th><span class="fa fa-inr"></span> Total</th>
            </tr>
          </tfoot>
        </table>
        <br>
        <table class="table">
            <tr>

                <th style="float:right">Grand Total : {{Hyvikk::get('currency')}} {{bcdiv($totalTransaction,1,2)}}</th>
            </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.view')</h4>
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
    $("#search").click(function(){
        var blankTest = /\S/;
        // var idArr = ['transaction_id','from','type','from_date','to_date','is_complete','order_by','sort'];
        var idArr = ['transaction_id','from'];

        // $.each(idArr,function(index,value){
        var tran_id = $("#transaction_id").val();
        var from = $("#from").val();
        if((!blankTest.test(tran_id) && !blankTest.test(from)) || (blankTest.test(tran_id) && blankTest.test(from))){
            alert("Please Enter Transaction ID OR Select From to search");
            $("#transaction_id").focus();
            return false;
        }else if(blankTest.test(tran_id) && !blankTest.test(from) && tran_id.length<7){
          alert("Transaction ID can't be empty or have less than 7 characters");
          $("#transaction_id").focus();
          return false;
        }
        return true;
        // })
    })
  $("#from").change(function(){
    var advDiv = $(".advance_for");
    $(this).val()==18 ? advDiv.show() : advDiv.hide();
  })
  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/accounting/view_bank_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })
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

  $(".advance_forr").on("click",function(){
    var id = $(this).data("id");
    $("#advanceForModal .modal-body").load('{{url("admin/accounting/advance_for")}}/'+id,function(res){
      $("#advanceForModal").modal({show:true})
    })
  })
	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
      // dom: 'Bfrtip',
      // buttons: [{
      //      extend: 'collection',
      //         text: 'Export',
      //         buttons: [
      //             'copy',
      //             'excel',
      //             'csv',
      //             'pdf',
      //         ]}
      // ],
      "aaSorting":[],
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