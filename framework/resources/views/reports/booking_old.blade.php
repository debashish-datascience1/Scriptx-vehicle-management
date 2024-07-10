@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">@lang('menu.reports')</a></li>
<li class="breadcrumb-item active">@lang('fleet.booking_report')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.booking_report')
        </h3>
      </div>

      <div class="card-body">
        {!! Form::open(['route' => 'reports.booking','method'=>'post','class'=>'form-inline']) !!}
        <div class="row">
          <div class="form-group" style="margin-right: 5px">
            {!! Form::label('year', __('fleet.year1'), ['class' => 'form-label']) !!}
            {!! Form::select('year', $years, $year_select,['class'=>'form-control','placeholder'=>'Select Year']); !!}
          </div>
          <div class="form-group" style="margin-right: 5px">
            {!! Form::label('month', __('fleet.month'), ['class' => 'form-label']) !!}
            {!! Form::selectMonth('month',$month_select,['class'=>'form-control','placeholder'=>'Select Month']); !!}
          </div>
          <div class="form-group" style="margin-right: 5px">
            {!! Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']) !!}
            <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 150px">
              <option value="">@lang('fleet.selectVehicle')</option>
              @foreach($vehicles as $vehicle)
              <option value="{{ $vehicle->id }}" @if($vehicle_select == $vehicle->id) selected @endif>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group" style="margin-right: 5px">
            {!! Form::label('customer_id', __('fleet.selectCustomer'), ['class' => 'form-label']) !!}
            <select id="customer_id" name="customer_id" class="form-control vehicles" style="width: 150px">
              <option value="">@lang('fleet.selectCustomer')</option>
              @foreach($customers as $customer)
              <option value="{{ $customer->id }}" @if($customer_select == $customer->id) selected @endif>{{$customer->name}}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-info" style="margin-right: 1px">@lang('fleet.generate_report')</button>
          <button type="submit" formaction="{{url('admin/print-booking-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
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
        @lang('fleet.booking_count') : {{$bookings->count()}}
        </h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table" id="myTable">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.customer')</th>
              <th>@lang('fleet.vehicle')</th>
              <th>Advance</th>
              <th>Payment Amount</th>
              <th>Total Amount</th>
              {{-- <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.pickup_addr')</th>
              <th>@lang('fleet.dropoff_addr')</th>
              <th>@lang('fleet.from_date')</th>
              <th>@lang('fleet.to_date')</th>
              <th>@lang('fleet.passengers')</th> --}}
              
              {{-- <th>@lang('fleet.status')</th> --}}
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $row)
            <tr>
              <td>{{$row->customer->name}}</td>
              <td>
                @if($row->vehicle_id != null)
                {{$row->vehicle->make}} - {{$row->vehicle->model}} - {{$row->vehicle->license_plate}}
                @endif
                </td>
              <td>@if($row->advpay_array != null)
                <i class="fa fa-inr"></i> {{$row->advpay_array}}
              @else
                <span class="badge badge-danger">N/A</span>
              @endif</td>
              <td>@if($row->payment_amount != null)
                <i class="fa fa-inr"></i> {{$row->payment_amount}}
              @else
                <span class="badge badge-danger">N/A</span>
              @endif</td>
              <td>{{$row->total_price}}</td>
               
              
              {{--<td style="width:10% !important">{!! str_replace(",", ",<br>", $row->pickup_addr) !!}</td>
              <td style="width:10% !important">{!! str_replace(",", ",<br>", $row->dest_addr) !!}</td>
              <td>{{date($date_format_setting.' g:i A',strtotime($row->pickup))}}</td>
              <td>{{date($date_format_setting.' g:i A',strtotime($row->dropoff))}}</td>
              <td>{{$row->travellers}}</td> --}}
              {{-- <td>@if($row->status==0)<span style="color:orange;">@lang('fleet.journey_not_ended') @else <span style="color:green;">@lang('fleet.journey_ended') @endif</span></td> --}}
              <td><a class="DetailsChk"  data-customer_id="{{$row->customer_id}}" data-vehicle_id="{{$vehicle_select}}" data-year="{{$year_select}}" data-month="{{$month_select}}" data-toggle="modal" data-target="#bookingDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green"></span>Details</a></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>@lang('fleet.customer')</th>
              <th>Vehicle</th>
               <th>Advance</th>
              <th>Payment Amount</th>
              <th>Total Amount</th>
              {{--<th>@lang('fleet.from_date')</th>
              <th>@lang('fleet.to_date')</th>
              <th>@lang('fleet.passengers')</th> --}}
              {{-- <th>@lang('fleet.status')</th> --}}
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endif
<!-- Modal view-->
<div id="bookingDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:158%">
      <div class="modal-header" style="border-bottom:none;">
        <h5>Booking Details Report</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
          Loading...
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      
    </div>
  </div>
</div>
<!-- Modal -->
@endsection


@section("script")

<script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#customer_id').select2();
    $('#vehicle_id').select2();
    $('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable( {
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
    });

    $(".DetailsChk").on("click",function(){
    var customer_id = $(this).data("customer_id");
    var vehicle_id = $(this).data("vehicle_id");
    var year = $(this).data("year");
    var month = $(this).data("month");
    
     var arr = [customer_id,vehicle_id,month,year];
    
    $("#bookingDetailsModal .modal-body").load('{{url("admin/reports/view_booking_details")}}/'+arr,function(res){
      $("#bookingDetailsModal").modal({show:true})
    })
    
  })
  });
</script>
@endsection