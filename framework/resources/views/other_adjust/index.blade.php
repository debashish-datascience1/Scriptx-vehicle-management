@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.min.css')}}">
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
  .adjDate,.adjDatePrev,.adjRef,.adjRefPrev,.adjType,.adjTypePrev,.adjBank,.adjBankPrev{margin-top: 10px;}
  .adjDatePrev,.adjTypePrev,.adjMethoPrev,.adjBankPrev{pointer-events: none}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.other_advance')</li>
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
        <h3 class="card-title">@lang('fleet.other_advance') &nbsp;
          <a href="{{ route("other-advance.create") }}" class="btn btn-success"> Add @lang('fleet.other_advance') </a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                {{-- @if($data->count() > 0)
                <input type="checkbox" id="chk_all">
                @endif --}}
              </th>
              <th>SL#</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $k=>$row)
            <tr>
              <td>
                {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
              </td>
              <td>{{$k+1}}</td>
              <td>{{$row->driver->name}}</td>
              <td>{{$row->bank_details->bank}}({{$row->bank_details->account_no}})</td>
              <td>{{$row->method_param->label}}</td>
              <td>{{Helper::properDecimals($row->amount)}}</td>
              <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
              <td>
                @if($row->is_adjusted==1)
                  <span class="badge badge-success">Completed</span>
                @elseif($row->is_adjusted==2)
                  <span class="badge badge-primary">In Progress</span>
                @elseif($row->is_adjusted==null)
                  <span class="badge badge-danger">Not Yet Done</span>
                @endif
              </td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                  @if($row->is_adjusted!=1)
                  <a class="dropdown-item" href="{{ url("admin/other-advance/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  
                  <a class="dropdown-item vadjust" data-id="{{$row->id}}" data-toggle="modal" data-target="#adjustModal" title="Adjust"> <span aria-hidden="true" class="fa fa-balance-scale" style="color: #1316b6;"></span> Adjust</a>
                  @endif
                   {{-- <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>  --}}
                </div>
              </div>
              {{-- {!! Form::open(['url' => 'admin/leave/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
              {!! Form::hidden("id",$row->id) !!}
              {!! Form::close() !!} --}}
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
                @if($data->count() > 0)
                {{-- <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button> --}}
                @endif
              </th>
              <th>SL#</th>
              <th>Driver</th>
              <th>Bank</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="adjustModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="margin-left: 25%">
    <!-- Modal content-->
    <div class="modal-content"  style="width: 180%">
      <div class="modal-header">
        <h4 class="modal-title">Adjust Other Advance</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      {!! Form::open(['route' => 'other-adjust.store','files'=>true,'method'=>'post']) !!}
      <div class="modal-body">
        
      </div>
      {{-- <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div> --}}
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
@endsection

@section('script')
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
  $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/other-adjust/view_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
        $('#viewModal .modal-content').css({width: "100%","margin-left": ""})
      })
  })
  $(".vadjust").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#adjustModal .modal-body").load('{{url("admin/other-adjust/adjust")}}/'+id,function(res){
        // console.log(res)
        $("#adjustModal").modal({show:true});
      })
  })

  $("body").on("click",".adjustments,.gen",function(){
    var classNames = $(this).attr("class");
    if(classNames.includes("adjustments"))
      $('#viewModal .modal-content').css({width: "200%","margin-left": "-103px"})
    else
      $('#viewModal .modal-content').css({width: "100%","margin-left": ""})
  })

  $("body").on("click","#addmore",function(e){
    let arr = {_token:"{{csrf_token()}}"};
    var self = $(this);
    $.post("{{route('other-adjust.addmore')}}",arr).done(function(result){
      console.table(result);
      self.closest("tbody").find(".parent-row:last").after(result)
    });
  })
  $("body").on("click",".remove",function(e){
    if(confirm("Are you sure to remove this ?"))
      $(this).closest(".parent-row").remove()
  })
  $("body").on("focus",".adjDate",function(e){
    $(this).datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
    });
  })

  $("body").on("keyup",".adjAmount",function(){
    var myarr = [];
    var self = $(this);
    $(".adjAmount").each(function(){
      myarr.push($(this).val())
    })
    var postarr = {_token:"{{csrf_token()}}",value:myarr,id:$("#otherAdvance").val()}
    // console.log(postarr)
    $.post("{{route('other-adjust.calculate')}}",postarr).done(function(result){
      // console.log(result)
      if(result.status==false){
        self.val('')
        $(".adjAmount").keyup()
      }else{
        $("#span-remain").html(result.remain)
      }
    })
  })
  $("body").on("change",".adjType",function(){
    if($(this).val()==23){
      $(this).closest("td").find(".adjBank").prop("required",true).show();
    }else{
      $(this).closest("td").find(".adjBank").prop("required",false).hide();
    }
  })



  $(document).on("click","#subAdjust",function(e){
    // if($(".adjDate").val()==''){
    //   alert('Date cannot be empty');
    //   return false;
    // }
    $(".adjDate").each(function(){
      if($(this).val()==''){
        alert('Date cannot be empty');
        $(this).focus();
        return false;
      }
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
  function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
  }
</script>
@endsection