@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
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
  .where_from,.advance_for{cursor: pointer !important;}
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
  .badge-renewdocs{background: #2944ca;color:#fff;}
  .badge-vehicleDoc{background: tomato;color: #fff;}
  .badge-otherAdv{background:darkcyan;color: #fff;}
  .badge-advanceRefund{background: deeppink;color: #fff;}
  .badge-vehicle-downpayment{background: darkgoldenrod;color: #fff;}
  .badge-vehicle-purchase{background: darkgray;color: #fff;}
  .badge-vehicle-emi{background: darkslateblue;color: #fff;}
  .badge-viwevent{background: #0091bd;color:#fff!important;cursor: pointer;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.transactions')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Bank @lang('fleet.transactions') &nbsp;
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                @if($transactions->count() > 0)
                <input type="checkbox" id="chk_all">
                @endif
              </th>
              <th>Transaction ID</th>
              {{-- <th style="width: 125px;">Date</th> --}}
              <th>From</th>
              <th>Bank</th>
              {{-- <th>Method</th>
              <th>Previous</th> --}}
              <th>Total</th>
              {{-- <th>Remaining</th> --}}
              <th>Grand Total</th>
              {{-- <th>@lang('fleet.action')</th> --}}
            </tr>
          </thead>
          <tbody>
            @foreach($transactions as $row)
            <tr>
              <td>
                {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
              </td>
              <td>
                <a class="badge badge-viwevent vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')">
                  {{$row->transaction_id}}
                </a>
                <br>
                <strong>{{Helper::getCanonicalDateTime($row->rem->created_at,'default')}}</strong>
              </td>
              {{-- <td>{{$row->rem}}</td> --}}
              {{-- <td>{{!empty($row->rem->date) ? Helper::getCanonicalDate($row->rem->date,'default') : Helper::getCanonicalDate($row->rem->created_at,'default')}}</td> --}}
              {{-- Dont delete <td>..</td> --}}
              <td>
                @if($row->param_id==18)
                  <a class="badge badge-success where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  {{-- <span class="badge badge-success">{{!empty($row->params) ? $row->params->lable : 'N/A'}}</span> --}}
                  <br>
                  @if($row->advance_for==21)
                  <a class="badge badge-warning advance_for" data-id="{{$row->id}}" data-toggle="modal" data-target="#advanceForModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{$row->advancefor->label}}</a>
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
               {{-- View --}}
              </td>
              {{-- <td>{{$row->pay_method->label}}</td> --}}
              {{-- <td>{{number_format($row->prev,2,'.','')}}</td> --}}
              <td>
                <label for="bank">{{empty($row->bank) ? '-' : $row->bank->bank}}</label>
              </td>
              <td>
                {{bcdiv($row->total,1,2)}}<br>
                @if($row->type==23)
                    <span class="badge badge-success">{{$row->pay_type->label}}</span>
                @elseif($row->type==24)
                    <span class="badge badge-danger">{{$row->pay_type->label}}</span>
                @elseif($row->type==48)
                    <span class="badge badge-primary">{{$row->pay_type->label}}</span>
                @endif
              </td>
              {{-- <td>{{number_format($row->rem->remaining,2,'.','')}}</td> --}}
              <td>{{bcdiv($row->grandtotal,1,2)}}</td>
              {{--<td>
               <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                  @if($row->advance_for!=21)
                  <a class="dropdown-item mybtn vadjust" data-id="{{$row->id}}" data-rem="{{base64_encode($row->rem->remaining)}}" data-toggle="modal" data-target="#adjustModal" title="Adjust Transaction"><i class="fa fa-sliders" aria-hidden="true" style="color:#25b900;"></i> &nbsp;Adjust</a>
                  @endif 
                  <a class="dropdown-item" href="{{ url("admin/leave/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a> 
                   <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>  
                </div>
              </div> --}}
              {{-- {!! Form::open(['url' => 'admin/leave/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
              {!! Form::hidden("id",$row->id) !!}
              {!! Form::close() !!} 
              </td>--}}
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
                @if($transactions->count() > 0)
                {{-- <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button> --}}
                @endif
              </th>
              <th>Transaction ID</th>
              <th>From</th>
              <th>Bank</th>
              {{-- <th>Bank</th> --}}
              {{-- <th>Method</th> --}}
              {{-- <th>Previous</th> --}}
              <th>Total</th>
              {{-- <th>Amount</th> --}}
              {{-- <th>Remaining</th> --}}
              <th>Grand Total</th>
              {{-- <th>@lang('fleet.action')</th> --}}
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/delete-drivers','method'=>'POST','id'=>'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
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
<div id="adjustModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 43%">
    <!-- Modal content-->
    <form action="{{route('accounting.store')}}" method="POST">
    {{ csrf_field() }}
      <div class="modal-content" style="width: 150%">
        <div class="modal-header">
          <h4 class="modal-title">Adjusting Transaction</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading...
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success adjustSubmit" value="Submit">
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
          </button>
        </div>
      </div>
    </form>
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
@endsection

@section('script')
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $('body').on("click",".adjustSubmit",function(){
    console.log($(".adjDate").val());
    var ret = true;
    $(".adjDate").each(function(index,element){
      if($(this).val()==""){
        console.log($(this).val());
        alert("Date field can't be empty.");
        $(this).focus();
        ret = false;
      }
    })
    return ret;
  })

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

  // $(function(){
  //   var adj = $(".vadjust").val();
  //   $(".vadjust").each(function(i,e){
  //     var datval = $(this).data("show");
  //     if(datval==null && datval==0)

  //   })
  // })

  $('body').on("keyup",".adjAmount",function(){
    var r = $("#remainingAmt").val();
    var typed = $(this).val();
    if(Number(typed)>Number(r))
      $(this).val('').focus();
    // console.log(typeof r);
    // console.log(typeof typed);
    var sumTotal = 0;
    $(".adjAmount").each(function(i,e){
      var vals = $(this).val();
      sumTotal+=$(this).val()=="" ? 0 : Number(vals);
    })
    if(sumTotal>r)
      $(".adjAmount").val('');
  })

  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/accounting/view_bank_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })

  $(".vadjust").click(function(){
    var id = $(this).data("id");
    var datval = atob($(this).data("rem"));
      
    // console.log(datval)
      $("#adjustModal .modal-body").load('{{url("admin/accounting/adjust")}}/'+id,function(res){
        // console.log(res)
        if(datval==null || datval==0)
          $(".adjustSubmit").hide();
        else $(".adjustSubmit").show();
        $("#adjustModal").modal({show:true});
        
      })
  })

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#changepass').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#driver_id").val(id);
  });

  $("#changepass_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
       new PNotify({
            title: 'Success!',
            text: "@lang('fleet.passwordChanged')",
            type: 'info'
        });
      },
      dataType: "html"
    });
    $('#changepass').modal("hide");
    e.preventDefault();
  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "@lang('fleet.delete_error')",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


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
  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }

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
  $("body").on("focus",".adjDate",function(){
    $(this).datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  })

  $("body").on("click",".addmore",function(){
    var count = $(".parent-row").length;
    console.log(count);
    if(count<=5){
      $(".adjustTable").last().append("<tr class='parent-row'><td><input type='text' name='adjDate[]' class='form-control adjDate' readonly=' placeholder='Choose Date..' required></td><td><select class='form-control adjType' name='adjType[]' required><option value='16'>Cash</option><option value='17'>DD</option></select></td><td><input type='text' name='adjAmount[]' class='form-control adjAmount' placeholder='Enter Amount..' required></td><td><input type='text' name='adjRemarks[]' class='form-control adjRemarks' placeholder='Enter Remarks..' required></td><td><button class='btn btn-danger remove'><span class='fa fa-minus'></span>&nbsp;Remove</button></td></tr>");
    }else{
      alert("Can't add more than 5 rows.");
      return false;
    }
  })

  $("body").on("click",".remove",function(){
    // alert('remove')
    $(this).closest(".parent-row").remove();
  })
</script>
@endsection