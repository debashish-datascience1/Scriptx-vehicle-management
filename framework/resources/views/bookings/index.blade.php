@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
  <style type="text/css">
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .pagination{float: right}
    .google_calculate{padding: 0.2rem 0.75rem;}
  </style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('menu.bookings')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> @lang('fleet.manage_bookings') &nbsp;
          <a href="{{route("bookings.create")}}" class="btn btn-success">@lang('fleet.new_booking')</a>
          <a href="{{url("admin/refresh-json/18")}}" class="btn btn-primary float-right refresh-table"><span class="fa fa-repeat"></span> &nbsp; @lang('fleet.refresh')</a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive display" id="data_table12" style="padding-bottom: 35px; width: 100%">
            <thead class="thead-inverse">
              <tr>
                <th>
                  @if($data->count() > 0)
                  {{-- <input type="checkbox" id="chk_all"> --}}
                  @endif
                </th>
                <th style="width: 2% !important">SL#</th>
                <th style="width: 10% !important">@lang('fleet.customer')</th>
                <th style="width: 10% !important">@lang('fleet.vehicle')</th>
                <th style="width: 10% !important">@lang('fleet.pickup_addr')</th>
                <th style="width: 10% !important">@lang('fleet.dropoff_addr')</th>
                <th style="width: 10% !important">@lang('fleet.pickup')</th>
                <th style="width: 10% !important">@lang('fleet.dropoff')</th>
                <th style="width: 10% !important">Advance to Driver</th>
                {{-- <th style="width: 10% !important">@lang('fleet.passengers')</th> --}}
                {{-- <th style="width: 10% !important">@lang('fleet.journey_status')</th> --}}
                <th>@lang('fleet.booking_status')</th>
                {{-- <th style="width: 10% !important">@lang('fleet.amount')</th> --}}
                <th style="width: 10% !important">@lang('fleet.action')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $k=>$row)
               <tr>
                <td>
                  {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
                </td>
                <td>{{$k+$data->firstItem()}}</td>
                <td style="width: 10% !important">{{$row->customer->name}}</td>
                <td style="width: 10% !important">
                  @if($row->vehicle_id)
                  {{$row->vehicle->make}} - {{$row->vehicle->model}} - {{$row->vehicle->license_plate}}
                  @endif
                </td>
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
                <td style="width: 10% !important">
                  @if($row->advance_pay != null)
                    <i class="fa fa-inr"></i> {{$row->advance_pay}}
                  @else
                    <span class="badge badge-danger">N/A</span>
                  @endif
                </td>
                {{-- <td style="width: 10% !important">{{$row->travellers}}</td> --}}
                {{-- <td style="width: 10% !important">
                @if($row->status == 1)
                <span class="text-success">
                @lang('fleet.completed')
                </span>
                @else
                <span class="text-warning">
                @lang('fleet.not_completed')
                </span>
                @endif
                </td> --}}
                <td>
                  <strong>{{$row->invoice_id}}</strong><br>
                  @if($row->ride_status!='Completed')
                    <span class="text-warning">{{$row->ride_status}}</span>
                  @else
                    <span class="text-success">{{$row->ride_status}}</span>
                  @endif
                </td>
                {{-- <td style="width: 10% !important">
                @if($row->receipt == 1)
                {{Hyvikk::get('currency')}} {{($row->tax_total) ? $row->tax_total : $row->total}}
                @endif
                </td> --}}
                <td style="width: 10% !important">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="fa fa-gear"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu custom" role="menu">
                      <a class="dropdown-item vbook" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"  style="cursor:pointer;"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> @lang('fleet.viewBookingDetails')</a>
                      <a href="print_booking_new/{{$row->id}}" class="dropdown-item" data-id="{{$row->id}}" style="cursor:pointer;" target="_blank"> <span aria-hidden="true" class="fa fa-print" style="color: #1114b4;"></span> Print</a>
                      
                      @if($row->ride_status == 'Completed')
                        <a class="dropdown-item" href="{{ url('admin/bookings/'.$row->id.'/edit')}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                        <a class="dropdown-item vRoute" data-id="{{$row->id}}" data-toggle="modal" data-target="#modalRoute" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-plus" aria-hidden="true" style="color: #0d9c00"></span> Add Route</a>
                      @else
                        @if($row->status==0 && $row->ride_status != "Cancelled" && !empty($row->transid) && $row->inc_rows<2 && Helper::isEligible($row->id,18))
                          <a class="dropdown-item" href="{{ url('admin/bookings/'.$row->id.'/edit')}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                          <a class="dropdown-item vtype" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal" style="cursor:pointer;"> <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39;"></span> @lang('fleet.delete')</a>
                          <a class="dropdown-item vDriverAdvanceLater" data-id="{{$row->id}}" data-toggle="modal" data-target="#modalDriverAdvanceLater" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-inr" aria-hidden="true" style="color: #0d9c00"></span> Late Driver Advance</a>
                          @if($row->receipt != 1)
                            {{-- <a class="dropdown-item vtype" data-id="{{$row->id}}" data-toggle="modal" data-target="#cancelBooking" > <span class="fa fa-times" aria-hidden="true" style="color: #dd4b39"></span> @lang('fleet.cancel_booking')</a> --}}
                          @endif
                        @endif
                        @if($row->vehicle_id != null)
                          @if($row->status==0 && $row->receipt != 1)
                            @if(Auth::user()->user_type != "C" && $row->ride_status != "Cancelled")
                              <a class="dropdown-item vcomplete" data-id="{{$row->id}}" data-toggle="modal" data-target="#modalComplete" data-backdrop='static' data-keyboard='false' style="cursor:pointer;"> <span class="fa fa-check" aria-hidden="true" style="color: #0d9c00;"></span> Mark as Complete</a>
                              <a class="dropdown-item vRoute" data-id="{{$row->id}}" data-toggle="modal" data-target="#modalRoute" data-backdrop='static' data-keyboard='false' style="cursor: pointer;"> <span class="fa fa-plus" aria-hidden="true" style="color: #0d9c00"></span> Add Route</a>
                            @endif
                          @elseif($row->receipt == 1)
                            {{-- <a class="dropdown-item" href="{{ url('admin/bookings/receipt/'.$row->id)}}"><span aria-hidden="true" class="fa fa-list" style="color: #31b0d5;"></span> @lang('fleet.receipt')</a> --}}
                            {{-- @if($row->receipt == 1 && $row->status == 0 && Auth::user()->user_type != "C")
                              <a class="dropdown-item" href="{{ url('admin/bookings/complete/'.$row->id)}}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#journeyModal"><span aria-hidden="true" class="fa fa-check" style="color: #5cb85c;"></span> @lang('fleet.complete')</a>
                            @endif --}}
                          @endif
                        @endif
                      @endif
                    </div>
                  </div>
                {!! Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
              {{-- <tr>
                <td colspan="9"></td>
                <td colspan="11" class="text-center">{{$data->links()}}</td>
              </tr> --}}
            </tbody>
            {{-- <tfoot>
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
                <th>Advance to Driver</th>
                <th>@lang('fleet.passengers')</th>
                <th>@lang('fleet.journey_status')</th>
                <th>@lang('fleet.booking_status')</th>
                <th>@lang('fleet.amount')</th>
                <th>@lang('fleet.action')</th>
              </tr>
            </tfoot> --}}
          </table>
        </div>
        <div class="row">
          <div class="col-md-12 float-right">
            {{$data->links("pagination::bootstrap-4")}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- cancel booking Modal -->
<div id="cancelBooking" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.cancel_booking')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_cancel')</p>
        {!! Form::open(['url'=>url('admin/cancel-booking'),'id'=>'cancel_booking']) !!}
        <div class="form-group">
          {!! Form::hidden('cancel_id',null,['id'=>'cancel_id']) !!}
          {!! Form::label('reason',__('fleet.addReason'),['class'=>"form-label"]) !!}
          {!! Form::text('reason',null,['class'=>"form-control",'required']) !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">@lang('fleet.submit')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- cancel booking Modal -->

<!-- complete journey Modal -->
<div id="journeyModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.complete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_journey')</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success" href="" id="journey_btn">@lang('fleet.submit')</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- complete journey Modal -->

<!-- bulk delete Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/delete-bookings','method'=>'POST','id'=>'form_delete']) !!}
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
<!-- bulk delete Modal -->

<!-- single delete Modal -->
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

<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.viewBookingDetails')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          @lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!-- single delete Modal -->


<!-- generate invoic Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="card card-info">
        <div class="modal-header">
          <h3 class="modal-title">@lang('fleet.add_payment')</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="fleet card-body">
          {!! Form::open(['route' => 'bookings.complete','method'=>'post']) !!}
          <input type="hidden" name="status" id="status" value="1"/>
          <input type="hidden" name="booking_id" id="bookingId" value=""/>
          <input type="hidden" name="userId" id="userId" value=""/>
          <input type="hidden" name="customerId" id="customerId" value=""/>
          <input type="hidden" name="vehicleId" id="vehicleId" value=""/>
          <input type="hidden" name="type" id="type" value=""/>
          <input type="hidden" name="base_km_1" value="" id="base_km_1">
          <input type="hidden" name="base_fare_1" value="" id="base_fare_1">
          <input type="hidden" name="wait_time_1" value="" id="wait_time_1">
          <input type="hidden" name="std_fare_1" value="" id="std_fare_1">
          <input type="hidden" name="base_km_2" value="" id="base_km_2">
          <input type="hidden" name="base_fare_2" value="" id="base_fare_2">
          <input type="hidden" name="wait_time_2" value="" id="wait_time_2">
          <input type="hidden" name="std_fare_2" value="" id="std_fare_2">
          <input type="hidden" name="base_km_3" value="" id="base_km_3">
          <input type="hidden" name="base_fare_3" value="" id="base_fare_3">
          <input type="hidden" name="wait_time_3" value="" id="wait_time_3">
          <input type="hidden" name="std_fare_3" value="" id="std_fare_3">
          @php($no_of_tax = 0)
          @if(Hyvikk::get('tax_charge') != "null")
            @php($no_of_tax = sizeof(json_decode(Hyvikk::get('tax_charge'), true)))
            @php($taxes = json_decode(Hyvikk::get('tax_charge'), true))
            @php($i=0)
            @foreach($taxes as $key => $val)
              <input type="hidden" name="{{ 'tax_'.$i }}" value="{{ $val }}" class="{{ 'tax_'.$i }}">
              @php($i++)
            @endforeach
          @endif
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.incomeType')</label>
                <select id="income_type" name="income_type" class="form-control vehicles" required>
                  <option value="">@lang('fleet.incomeType')</option>
                  @foreach($types as $type)
                  <option value="{{ $type->id }}">{{$type->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.daytype')</label>
                <select id="day" name="day" class="form-control vehicles" required>
                  <option value="1" selected>Weekdays</option>
                  <option value="2">Weekend</option>
                  <option value="3">Night</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.trip_mileage') ({{Hyvikk::get('dis_format')}})</label>
                {!! Form::number('mileage',null,['class'=>'form-control sum','min'=>1,'id'=>'mileage']) !!}
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.waitingtime')</label>
                {!! Form::number('waiting_time',0,['class'=>'form-control sum','min'=>0,'id'=>'waiting_time']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total_tax') (%) </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text fa fa-percent"></span></div>
                  {!! Form::number('total_tax_charge',0,['class'=>'form-control sum','readonly','id'=>'total_tax_charge']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total') @lang('fleet.tax_charge')</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text">{{ Hyvikk::get('currency') }}</span></div>
                  {!! Form::number('total_tax_charge_rs',0,['class'=>'form-control sum','readonly','id'=>'total_tax_charge_rs']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.amount') </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
                  {!! Form::number('total',null,['class'=>'form-control','id'=>'total','required']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total') @lang('fleet.amount') </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <span class="input-group-text">{{Hyvikk::get('currency')}}</span></div>
                  {!! Form::number('tax_total',null,['class'=>'form-control','id'=>'tax_total','readonly']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.date')</label>
                <div class='input-group'>
                  <div class="input-group-prepend">
                    <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                  </div>
                  {!! Form::text('date',date('Y-m-d'),['class'=>'form-control','id'=>'date']) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              {!! Form::submit(__('fleet.invoice'), ['class' => 'btn btn-info']) !!}
            </div>
          </div>
          {!! Form::close() !!}
        </div> 
      </div>
    </div>
  </div>
</div>
<!-- generate invoice modal -->

{{-- Mark as Complete --}}
<div class="modal fade" id="modalComplete" tabindex="-1" role="dialog" aria-labelledby="modalCompleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCompleteLabel">Complete Booking Process</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>
{{-- Add Route --}}
<div class="modal fade" id="modalRoute" role="dialog" aria-labelledby="modalRouteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRouteLabel">Add Route</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>
{{-- Add Late Driver Advance --}}
<div class="modal fade" id="modalDriverAdvanceLater" role="dialog" aria-labelledby="modalDriverAdvanceLaterLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDriverAdvanceLaterLabel">Add Late Driver Advance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
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
  
  function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
  }

  // Complete Modal
  $(".vcomplete").click(function(){
    var id = $(this).data('id');
    $("#modalComplete .modal-body").load('{{url("admin/bookings/modalcomplete")}}/'+id,function(result){
      $("#modalComplete").modal({show:true, backdrop:'static',keyboard:false})
    })
  })
  // Route Modal
  $(".vRoute").click(function(){
    var id = $(this).data('id');
    $("#modalRoute .modal-body").load('{{url("admin/bookings/modalroute")}}/'+id,function(result){
      $("#modalRoute").modal({show:true, backdrop:'static',keyboard:false})
      $(".next_booking").select2();
    })
  })
  // Late Driver Advance Modal
  $(".vDriverAdvanceLater").click(function(){
    var id = $(this).data('id');
    $("#modalDriverAdvanceLater .modal-body").load('{{url("admin/bookings/modal-late-driver-advance")}}/'+id,function(result){
      $("#modalDriverAdvanceLater").modal({show:true, backdrop:'static',keyboard:false})
      // $(".next_booking").select2();
    })
  })

  $("body").on("click",".submit",function(){
    var advance_date = $(".advance_date").val();
    // alert(12);
    if(advance_date==''){
      alert('Please select date');
      $(".advance_date").focus();
      return false;
    }
  })

  $("body").on("focus",".advance_date",function(){
    $(this).datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  })

  $("body").on("keyup",".fodder_km",function(){
    console.log($(this).val())
    var fodder_km = $(this).val();
    var vehicle_id = $("#vehicle_id").val();
    var sendData = {_token:"{{csrf_token()}}",fodder_km:fodder_km,vehicle_id:vehicle_id};
    $.post('{{route("bookings.getMileageDate")}}',sendData).done(function(result){
      console.log(result);
      $(".ajax-class th").html(result.view);
      $(".ajax-class").show();
    })
  })
  
  $(document).on('keyup','#toll_tax,#food,#labour,#advance,#others,#refund,#tyre,#donations,#documents,#fuel,#maintenance,#electrical',function(){
    var total = $("#total_adv").val();
    var toll_tax = $("#toll_tax").val()!='' ? $("#toll_tax").val() : 0;
    var food = $("#food").val()!='' ? $("#food").val() : 0;
    var labour = $("#labour").val()!='' ? $("#labour").val() : 0;
    var advance = $("#advance").val()!='' ? $("#advance").val() : 0;
    var refund = $("#refund").val()!='' ? $("#refund").val() : 0;
    var tyre = $("#tyre").val()!='' ? $("#tyre").val() : 0;
    var donations = $("#donations").val()!='' ? $("#donations").val() : 0;
    var documents = $("#documents").val()!='' ? $("#documents").val() : 0;
    var fuel = $("#fuel").val()!='' ? $("#fuel").val() : 0;
    var maintenance = $("#maintenance").val()!='' ? $("#maintenance").val() : 0;
    var electrical = $("#electrical").val()!='' ? $("#electrical").val() : 0;
    console.log($(this).next().attr('class'));
    // console.log($(this).next());
    if($(this).val()!="" && $(this).next().attr('class')!='prem'){
      var alhead = $(this).attr('name');
      // console.log(alhead);
      $("<div class='prem'><textarea name='remarks["+alhead+"]' class='form-control remarks' style='resize:none;height:100px;margin-top:10px;' placeholder='Remarks...'></textarea></div>").insertAfter($(this));
    }
    if($(this).val()=="")
      $(this).next().remove('.prem');

    

    var gtotal = parseInt(toll_tax)+parseInt(food)+parseInt(labour)+parseInt(advance)+parseInt(refund)+parseInt(tyre)+parseInt(donations)+parseInt(documents)+parseInt(fuel)+parseInt(maintenance)+parseInt(electrical);
    var remain = (total-gtotal);
    
    if(total<gtotal){
      $(this).val('');
    // reserting others
      var othr = {_token:'{{csrf_token()}}',total:total};
      $('.from-input').each(function(){
        var self = $(this);
        var inputName = self.attr("name");
        if(self.val()!=null || self.val()!=0 || self.val()!=0.00 || self.val()!='undefined')
        othr[inputName] = self.val();
        // othr.push(self.val());
      })
      var posted = $.post('{{url("admin/bookings/markascomplete/others")}}',othr);
      posted.done(function(res){
        console.log(res);
        $("#others").val(res.value);
      })
      console.log(othr)
      // $("#others").val(othr);
      $(this).next().remove('.prem');
      $(this).focus();
    }else{
      $("#others").val(remain);
      if($("#others").val()!=0)
        $("#others").next().prop('readonly',false);
      else
        $("#others").next().prop('readonly',true);
      

      // console.log(remain);
      // console.log(typeof remain);
    }
  })



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
  $(document).on("click", ".open-AddBookDialog", function () {
    // alert($(this).data('base_km_1'));
    // window.open("route('bookings.index')/?type="+$(this).data('vehicle-type'));

    // const query = new URLSearchParams(window.location.search);
    // query.append("type", "true");

    // window.location.search = 'type='+$(".fleet #type").val( type );

     var booking_id = $(this).data('booking-id');

     $(".fleet #bookingId").val( booking_id );

     var user_id = $(this).data('user-id');
     $(".fleet #userId").val( user_id );

     var customer_id = $(this).data('customer-id');
     $(".fleet #customerId").val( customer_id );

     var vehicle_id = $(this).data('vehicle-id');
     $(".fleet #vehicleId").val( vehicle_id );

     var type = $(this).data('vehicle-type');
     $(".fleet #type").val( type );

     $(".fleet #mileage").val($(this).data('base-mileage'));
     $(".fleet #total").val($(this).data('base-fare'));

     $(".fleet #base_km_1").val($(this).data('base_km_1'));
     $(".fleet #base_fare_1").val($(this).data('base_fare_1'));
     $(".fleet #wait_time_1").val($(this).data('wait_time_1'));
     $(".fleet #std_fare_1").val($(this).data('std_fare_1'));
     $(".fleet #base_km_2").val($(this).data('base_km_2'));
     $(".fleet #base_fare_2").val($(this).data('base_fare_2'));
     $(".fleet #wait_time_2").val($(this).data('wait_time_2'));
     $(".fleet #std_fare_2").val($(this).data('std_fare_2'));
     $(".fleet #base_km_3").val($(this).data('base_km_3'));
     $(".fleet #base_fare_3").val($(this).data('base_fare_3'));
     $(".fleet #wait_time_3").val($(this).data('wait_time_3'));
     $(".fleet #std_fare_3").val($(this).data('std_fare_3'));

    var total = $("#total").val();

    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

  });

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#book_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#journeyModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#journey_btn").attr("href","{{ url('admin/bookings/complete/') }}/"+id);
  });

  $('.vbook').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('{{ url("admin/vehicle/event")}}/'+id)
    $('#myModal2 .modal-body').load('{{ url("admin/bookings/event")}}/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
      if($('.adexist').length) $("#myModal2 .modal-content").css('width','111%');
      else $("#myModal2 .modal-content").css('width','100%');
    });
  });

  $('#cancelBooking').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#cancel_id").val(id);
  });
</script>

<!-- testing total-->
<script type="text/javascript" language="javascript">
$(".sum").change(function(){
  // alert($("#base_km_1").val());
  // alert($('.vtype').data('base_km_1'));
  // console.log($("#type").val());

    var day = $("#day").find(":selected").val();
    if(day == 1){
      var base_km = $("#base_km_1").val();
      var base_fare = $("#base_fare_1").val();
      var wait_time = $("#wait_time_1").val();
      var std_fare = $("#std_fare_1").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 2){
      var base_km = $("#base_km_2").val();
      var base_fare = $("#base_fare_2").val();
      var wait_time = $("#wait_time_2").val();
      var std_fare = $("#std_fare_2").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }

    if(day == 3){
      var base_km = $("#base_km_3").val();
      var base_fare = $("#base_fare_3").val();
      var wait_time =$("#wait_time_3").val();
      var std_fare = $("#std_fare_3").val();
        if(parseInt($("#mileage").val()) <= parseInt(base_km)){
          var total = parseInt(base_fare) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
        }
        else{
          var sum = parseInt($("#mileage").val() - base_km) * parseInt(std_fare);
      var total = parseInt(base_fare) + parseInt(sum) + (parseInt($("#waiting_time").val()) * parseInt(wait_time));
      }
    }
    $("#total").val(total);
    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);
});

  $("#total").change(function(){
    var total = $("#total").val();
    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

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

  $(function(){
    $("body").on("click",".google_calculate",function(){
      //validate the next reference booking selected or not
      var blankTest = /\S/;
      var booking_id = $("#book_id").val();
      var next_booking = $("#next_booking").val();
      if(!blankTest.test(booking_id) || !blankTest.test(next_booking)){
        alert("Please select next reference booking");
      }
      var data={_token:"{{csrf_token()}}",booking_id:booking_id,next_booking:next_booking};
      console.log(booking_id,next_booking)
      $.post("{{url('admin/bookings/get_distanecfromaddress')}}",data).done(function(result){
        console.log(result);
        if(result.status=="OK"){
          //redo this.
          var obj = result.rows[0].elements[0];
          if(obj.status=="OK"){
            var distance = obj.distance.value/1000;
            $("#fodder_km").val(distance);
            $(".google_time").html("Distance : "+obj.duration.text)
            $(".google_error").html("");
            $("#fodder_km").trigger('keyup');
          }else{
            $("#fodder_km").val('');
            $(".google_time").html('');
            $("tr.ajax-class th").html('');
            $(".google_error").html("Something went wrong. Please enter distance manually.");
          }
        }else{
          $("#fodder_km").val('');
          $(".google_time").html('');
          $("tr.ajax-class th").html('');
          $(".google_error").html("Something went wrong. Please enter distance manually.");
        }
      })
    })
  })

  $(function(){
    $(".refresh-table").click(function(){
      if(confirm("Are you sure to refresh?"))
        return true;
      else
        return false;
    })
  })
</script>
@endsection
