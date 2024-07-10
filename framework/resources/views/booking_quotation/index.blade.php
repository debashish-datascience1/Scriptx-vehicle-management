@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section('extra_css')
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
  <style type="text/css">
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
  </style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.booking_quotes')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> @lang('fleet.manageBookingQuotations') &nbsp;
          <a href="{{route("booking-quotation.create")}}" class="btn btn-success">@lang('fleet.add_quote')</a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive display" id="data_table" style="padding-bottom: 35px; width: 100%">
            <thead class="thead-inverse">
              <tr>
                <th>
                  @if($data->count() > 0)
                  <input type="checkbox" id="chk_all">
                  @endif
                </th>
                <th style="width: 10% !important">@lang('fleet.customer')</th>
                <th style="width: 10% !important">@lang('fleet.vehicle')</th>
                <th style="width: 10% !important">@lang('fleet.pickup_addr')</th>
                <th style="width: 10% !important">@lang('fleet.dropoff_addr')</th>
                <th style="width: 10% !important">@lang('fleet.pickup')</th>
                <th style="width: 10% !important">@lang('fleet.dropoff')</th>
                <th style="width: 10% !important">@lang('fleet.passengers')</th>
                <th style="width: 10% !important">@lang('fleet.total') @lang('fleet.amount')</th>
                <th>@lang('fleet.approve')/@lang('fleet.reject') </th>
                <th style="width: 10% !important">@lang('fleet.action')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>
                  <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
                </td>
                <td style="width: 10% !important">{{$row->customer['name']}}</td>
                <td style="width: 10% !important">{{$row->vehicle['make']}} - {{$row->vehicle['model']}} - {{$row->vehicle['license_plate']}}</td>
                <td style="width:10% !important">{!! str_replace(",", ",<br>", $row->pickup_addr) !!}</td>
                <td style="width:10% !important">{!! str_replace(",", ",<br>", $row->dest_addr) !!}</td>
                <td style="width: 10% !important">
                @if($row->pickup != null)
                {{date($date_format_setting.' g:i A',strtotime($row->pickup))}}
                @endif
                </td>
                <td style="width: 10% !important">
                @if($row->dropoff != null)
                {{date($date_format_setting.' g:i A',strtotime($row->dropoff))}}
                @endif
                </td>
                <td style="width: 10% !important">{{$row->travellers}}</td>
                <td style="width: 10% !important">

                {{Hyvikk::get('currency')}} {{($row->tax_total) ? $row->tax_total : $row->total}}

                </td>
                <td>
                  <a href="{{ url('admin/booking-quotation/approve/'.$row->id) }}" class="btn btn-success" title="@lang('fleet.approve')"><i class="fa fa-check"></i></a> &nbsp;
                  <a class="btn btn-danger" data-id="{{$row->id}}" data-toggle="modal" data-target="#rejectModal" href="" title="@lang('fleet.reject')"><i class="fa fa-times"></i> </a>
                </td>
                <td style="width: 10% !important">
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="{{ url('admin/booking-quotation/invoice/'.$row->id)}}"> <span aria-hidden="true" class="fa fa-list" style="color: #31b0d5;"></span> @lang('fleet.receipt')</a>
                    <a class="dropdown-item" href="{{ url('admin/booking-quotation/'.$row->id.'/edit')}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                    <a class="dropdown-item vtype" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal" > <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                  </div>
                </div>
                {!! Form::open(['url' => 'admin/booking-quotation/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>
                @if($data->count() > 0)
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button>
                @endif
                </th>
                <th>@lang('fleet.customer')</th>
                <th>@lang('fleet.vehicle')</th>
                <th>@lang('fleet.pickup_addr')</th>
                <th>@lang('fleet.dropoff_addr')</th>
                <th>@lang('fleet.pickup')</th>
                <th>@lang('fleet.dropoff')</th>
                <th>@lang('fleet.passengers')</th>
                <th>@lang('fleet.total') @lang('fleet.amount')</th>
                <th>@lang('fleet.approve')/@lang('fleet.reject')</th>
                <th>@lang('fleet.action')</th>
              </tr>
            </tfoot>
          </table>
        </div>
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
        {!! Form::open(['url'=>'admin/delete-quotes','method'=>'POST','id'=>'form_delete']) !!}
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
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>

{{-- reject modal --}}
<div id="rejectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.reject') @lang('fleet.bookingQuote')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_reject')</p>
      </div>
      <div class="modal-footer">
        <button id="del_btn2" class="btn btn-danger" type="button" data-submit="">@lang('fleet.reject')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section("script")

<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  @if(Session::get('msg'))
    new PNotify({
        title: 'Success!',
        text: '{{ Session::get('msg') }}',
        type: 'success'
      });
  @endif

  $(document).ready(function() {
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $("#del_btn2").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#rejectModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn2").attr("data-submit",id);
  });
</script>

<script type="text/javascript" language="javascript">
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