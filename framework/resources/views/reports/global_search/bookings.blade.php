<table class="table table-responsive display" id="myTable" style="padding-bottom: 35px; width: 100%">
    <thead class="thead-inverse">
      <tr>
        <th>SL#</th>
        <th>@lang('fleet.customer')</th>
        <th>@lang('fleet.vehicle')</th>
        <th>Pickup Address</th>
        <th>Dropoff Address</th>
        <th>Pickup Date</th>
        <th>Dropoff Date</th>
        <th>Advance to Driver</th>
        <th>@lang('fleet.booking_status')</th>
        <th>@lang('fleet.action')</th>
      </tr>
    </thead>
    <tbody>
        @foreach($collection as $k=>$row)
         <tr>
          <td>{{$k+1}}</td>
          <td>{{$row->customer->name}}</td>
          <td>
            @if($row->vehicle_id)
            {{$row->vehicle->make}} - {{$row->vehicle->model}} - {{$row->vehicle->license_plate}}
            @endif
          </td>
          <td>{!! str_replace(",", ",<br>", $row->pickup_addr) !!}</td>
          <td>{!! str_replace(",", ",<br>", $row->dest_addr) !!}</td>
          <td>{!! !empty($row->pickup) ? Helper::str_replace_first(" ","<br>",Helper::getCanonicalDateTime($row->pickup,'default')) : '-'!!}</td>
          <td>{!! !empty($row->dropoff) ? Helper::str_replace_first(" ","<br>",Helper::getCanonicalDateTime($row->dropoff,'default')) : '-'!!}</td>
          <td>
            @if($row->advance_pay != null)
              <i class="fa fa-inr"></i> {{$row->advance_pay}}
            @else
              <span class="badge badge-danger">N/A</span>
            @endif
          </td>
          <td>
            <strong>{{$row->invoice_id}}</strong><br>
            @if($row->ride_status!='Completed')
              <span class="text-warning">{{$row->ride_status}}</span>
            @else
              <span class="text-success">{{$row->ride_status}}</span>
            @endif
          </td>
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
      </tbody>
      <tfoot>
        <tr>
            <th>SL#</th>
            <th>@lang('fleet.customer')</th>
            <th>@lang('fleet.vehicle')</th>
            <th>Pickup Address</th>
            <th>Dropoff Address</th>
            <th>Pickup Date</th>
            <th>Dropoff Date</th>
            <th>Advance to Driver</th>
            <th>@lang('fleet.booking_status')</th>
            <th>@lang('fleet.action')</th>
        </tr>
      </tfoot>
</table>