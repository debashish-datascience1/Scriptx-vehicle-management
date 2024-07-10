@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{route('bank-account.index')}}">@lang('fleet.bankAccount')</a></li>
<li class="breadcrumb-item active">@lang('fleet.bulkPay')</li>
@endsection
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
    .form-label{display:block !important;}
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
</style>
@endsection
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Select Customer or Vendor
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'bank-account.bulk_pay','method'=>'post','class'=>'form-inline']) !!}
        <div class="row">
          <div class="form-group" style="margin-right: 10px">
            {!! Form::label('customer', 'Customer', ['class' => 'form-label']) !!}
            {!!Form::select('customer',$customers,$customer,['class'=>'form-control','placeholder'=>'Select Customer',$customerSelect])!!}
          </div>
          <div class="form-group" style="margin-right: 10px">
            {!! Form::label('vendor', 'Vendor', ['class' => 'form-label']) !!}
            {!!Form::select('vendor',$vendors,$vendor,['class'=>'form-control','placeholder'=>'Select Vendor',$vendorSelect])!!}
          </div>
          <button type="submit" class="btn btn-success" style="margin-right: 10px" id="sub">Submit</button>
          {{-- <button type="submit" formaction="{{url('admin/print-vendor-vehicle-fuel-report')}}" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button> --}}
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
          @lang('fleet.bulkPay')
        </h3>
      </div>

      <div class="card-body table-responsive">
      {!! Form::open(['route' => 'bulk_pay.store','method'=>'post','id'=>'formSub']) !!}
        <table class="table table-bordered table-striped table-hover"  id="">
          <thead>
            @if(!empty($custvend))
            <tr>
                <td colspan="6" align="center" style="font-size:23px;"><strong>{{$custvend}}</strong></td>
            </tr>
            @endif
            <tr>
              <th>
                @if(count($transactions)>0)
                <input type="checkbox" id="chk_all">
                @endif
              </th>
              <th>Transaction ID</th>
              <th>Date</th>
              <th>From</th>
              <th>Amount</th>
              <th>Remaining</th>
            </tr>
          </thead>
          <tbody>
            @if(count($transactions)>0)
                @foreach($transactions as $row)
                <tr>
                <td>
                    <input type="checkbox" name="ids[{{ $row->id }}]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();' data-id="{{base64_encode(number_format($row->remainingAmt,2,'.',''))}}" data-rem="{{number_format($row->incExp->remaining,2,'.','')}}">
                    <input type="hidden" name="vals[{{ $row->id }}]" value="{{base64_encode(number_format($row->remainingAmt,2,'.',''))}}">
                </td>
                <td>{{$row->transaction_id}}</td>
                <td>{{Helper::getCanonicalDateTime($row->date)}}</td>
                <td>
                    @if($row->param_id==18)
                    <a class="badge badge-success where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="@lang('fleet.view')">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                    <br>
                    @if($row->advance_for==21)
                    <a class="badge badge-warning advance_for" data-id="{{$row->id}}" data-toggle="modal" data-target="#advanceForModal" title="@lang('fleet.view')">{{$row->advancefor->label}}</a>
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
                    @else{{dd($row->param_id)}}
                    @endif
                </td>
                <td>{{number_format($row->total,2,'.','')}}</td>
                <td class="remnants">{{number_format($row->incExp->remaining,2,'.','')}}</td>
                {{-- <td>{{Hyvikk::get('currency')}} {{$row->cost_per_unit}}</td>
                <td>{{Hyvikk::get('currency')}} {{number_format(round($row->qty * $row->cost_per_unit),2)}}</td> --}}
                </tr>
                @endforeach
            @else
            <tr>
                <td align="center" colspan="6" style="color:red">No Records Found...</td>
            </tr>
            @endif
          </tbody>
          @if(count($transactions)>0)
          <tfoot>
            <tr> 
                <th colspan="3"></th>
                <th>
                    Selected : 
                    {{Hyvikk::get('currency')}} <span id="showedAmt">0.00</span>
                </th>
                <th>
                    Grand Total :
                {{Hyvikk::get('currency')}} {{number_format($totalAmount,2,'.','')}}
                
                {!!Form::hidden('total',base64_encode(number_format($remm,2,'.','')),['id'=>'total'])!!}
                {!!Form::hidden('amount',null,['id'=>'amount'])!!}
                </th>
                <th>
                    Total :
                {{Hyvikk::get('currency')}} {{number_format($remm,2,'.','')}}
                </th>
            </tr>
            <tr>
              <th colspan="4">
                {!! Form::label('remarks','Remarks', ['class' => 'form-label']) !!}
                {!!Form::textarea('remarks',null,['class'=>'form-control','placeholder'=>'Remarks for this Bulk Pay....','style'=>'resize:none;height:120px;','cols'=>'30'])!!}
              </th>
              <th>
                {!! Form::label('bank_account','Bank Account', ['class' => 'form-label']) !!}
                {!!Form::select('bank_account',$bankAccounts,null,['class'=>'form-control','placeholder'=>'Select Account','required'])!!}
                <br>
                {!! Form::label('date','Date', ['class' => 'form-label']) !!}
                {!!Form::text('date',date("Y-m-d H:i:s"),['class'=>'form-control','required'])!!}
              </th>
              <th colspan="2">
                {!! Form::label('paid','Paid', ['class' => 'form-label']) !!}
                {!!Form::text('paid',null,['class'=>'form-control','id'=>'paid','style'=>'margin-right:-89px!important','placeholder'=>'Paid..','required'])!!}
              </th>
            </tr>
            <tr>
                <th colspan="4"></th>
                <th colspan="2">
                    {!!Form::submit('Confirm',['class'=>'btn btn-success','id'=>'finalSub','name'=>'finalSub','style'=>'width:20%;padding:0px;','disabled'])!!}
                </th>
            </tr>
          </tfoot>
          @endif
        </table>
      </div>
      {!!Form::close()!!}
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
{{-- <script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script> --}}
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
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
    
    $(function(){
      $("#customer").change(function(){
          $(this).val()=="" ? $("#vendor").prop('disabled',false) : $("#vendor").prop('disabled',true);
      })
      $("#vendor").change(function(){
          $(this).val()=="" ? $("#customer").prop('disabled',false) : $("#customer").prop('disabled',true);
      })

      $('#date').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss',sideBySide: true,icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }});
      
      $("#sub").click(function(){
          var blankTest = /\S/;
          var vendor = $("#vendor").val();
          var customer = $("#customer").val();
          if(!blankTest.test(vendor) && !blankTest.test(customer)){
              alert("Please choose Vendor or Customer");
              return false;
          }
      })
    })
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
        console.log(id);
        console.log(partsw);
        $("#whereModal .modal-content").load('{{url("admin/accounting/where_from")}}/'+id,function(res){
        typeof partsw!="undefined" ? $(this).css('width','160%') : $(this).css('width','');
        $("#whereModal").modal({show:true})
        })
    })
    $('#chk_all').on('click',function(){
        if(this.checked){
        $('.checkbox').each(function(){
            $('.checkbox').prop("checked",true);
        });
        }else{
        $('.checkbox').each(function(){
            $('.checkbox').prop("checked",false);
        });
        }
    });
    
    $("body").on("click",".checkbox,#chk_all",function(){
        var chkbx = $(".checkbox:checked").length;
        // if($(".checkbox").is(":checked")){
        //     alert(123);
        // }
        // console.log(atob($(this).data("id")));
        // return false;
        var sendData={_token:"{{csrf_token()}}",sum:[]};
        // console.log(sendData);
        $(".checkbox:checked").each(function(i,el){
            sendData.sum.push(atob($(this).data("id")));
            // var amt = parseFloat().toFixed(2);
            // sendData= parseFloat(sendData+amt);
        });
            console.log(sendData);
        $.post('{{route("bulk_pay.getAmount")}}',sendData).done(function(result){
            console.log(result);
            // return false;
            $("#showedAmt").html(result.total);
            $("#amount").val(result.encoded);
            $("#paid").val(atob(result.encoded));
        })
        
        chkbx>0 ? $("#finalSub").prop('disabled',false) : $("#finalSub").prop('disabled',true);
    })

    // $("#paid").keyup(function(){
    //     var amount = $("#amount").val(btoa($(this).val()));

    // })
    
    $("#formSub").on('submit',function(e){
        // alert()
        var float= /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
        var kamount = $("#paid").val();
        var rl = window.atob($('#total').val());
        if(!float.test(kamount)){
            $("#paid").val('');
            alert("Invalid Amount..");
            $("#paid").focus();
            return false;
        }else{
          var remTotal=0;
          var tRem = $(".checkbox:checked").length;
          // console.log(tRem);
          $(".checkbox:checked").each(function(k,v){
            var amountRemnant = parseFloat($(this).data("rem"));
            if((tRem>1 && tRem-1!=k))
              remTotal+=amountRemnant;
            else if(tRem==1 && k==0)
              remTotal=0;
          })
            console.log(remTotal);
            var aamount = window.atob($('#amount').val());
            console.log(aamount)

            if(aamount==null || aamount==0 || aamount==0.00){
                alert('Please enter payment amount..');
                $("#paid").val('').css('border','1px solid red').fadeIn('2000').focus();
                return false;
            }else{
                $("#paid").css('border','');
            }
            console.log(Math.round(remTotal * 100));
            console.log(Math.round(kamount * 100));
            console.log(1);

            if(Math.round(remTotal * 100)>=Math.round(kamount * 100)){
              $("#paid").val('');
                alert('Pay more than '+remTotal+' or unselect some transactions');
                return false;
            }else if(Math.round(kamount * 100)>Math.round(aamount * 100)){
                $("#paid").val('');
                // alert('Can\'t ')
                return false;
            }
            // e.preventDefault();return false;
        }
        
        // console.log(kamount);
        // console.log(aamount);
        // e.preventDefault();
        // return false;
    })

});
// Checkbox checked
    function checkcheckbox(){
        // Total checkboxes
        var length = $('.checkbox').length;
        // Total checked checkboxes
        var totalchecked = 0;
        $('.checkbox').each(function(){
            if($(this).is(':checked')){
                totalchecked+=1;
            }
        });
        // console.log(length+" "+totalchecked);
        // Checked unchecked checkbox
        if(totalchecked == length){
            $("#chk_all").prop('checked', true);
        }else{
            $('#chk_all').prop('checked', false);
        }
    }
</script>
@endsection