<table class="table table-striped" >
    
  <thead class="thead-inverse">
      <tr>
        <td colspan="6">
          {!! Form::open(['method'=>'post','class'=>'form-inline']) !!}
          <input type="hidden" name="customer_id" value="{{$customer_select}}">
          <input type="hidden" name="vehicle_id" value="{{$vehicle_select}}">
          <input type="hidden" name="month" value="{{$month_select}}">
          <input type="hidden" name="year" value="{{$year_select}}">  
        {{-- <h3>Customer Name : {{$customer_name}}</h3> --}}
          <button  type="submit" formaction="{{url('admin/print-booking-modal-report')}}" class="btn btn-danger" style="margin-left:665px;"><i class="fa fa-print"></i> @lang('fleet.print')</button>
          {!! Form::close() !!} 
        </td>
      </tr>
      <tr>
        <th>@lang('fleet.customer')</th>
        <th>@lang('fleet.vehicle')</th>
        <th>Advance</th>
        <th>Payment Amount</th>
        <th>Total Amount</th>
        <th>@lang('fleet.status')</th>
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
        <td>@if($row->advance_pay != null) 
          <i class="fa fa-inr"></i> {{$row->advance_pay}} 
        @else
          <span class="badge badge-danger">N/A</span>
        @endif</td>
        <td>@if($row->payment_amount != null)
          <i class="fa fa-inr"></i> {{$row->payment_amount}}
        @else
          <span class="badge badge-danger">N/A</span>
        @endif</td>
        <td>{{$row->total_price}}</td>
        <td>@if($row->status==0)<span style="color:orange;">@lang('fleet.journey_not_ended') @else <span style="color:green;">@lang('fleet.journey_ended') @endif</span></td>
      </tr>
      @endforeach
    </tbody>
  </table>