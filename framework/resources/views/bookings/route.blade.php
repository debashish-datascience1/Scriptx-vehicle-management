{!!Form::open(['route'=>'bookings.addroute_save','method'=>'POST'])!!}
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">
                <input type="hidden" name="book_id" id="book_id" value="{{$bookings->id}}">
                <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{$bookings->vehicle_id}}">
                <small>Transaction ID :</small> {{Helper::getTransaction($bookings->id,18)->transaction_id}} <br>
                <small>Vehicle No :</small> {{$bookings->vehicle->license_plate}} <br>
                <small>{{$bookings->pickup_addr}} <span class="fa fa-long-arrow-right"></span> {{$bookings->dest_addr}}</small>
                <br>
                <small><strong>{{Helper::getCanonicalDatetime($bookings->pickup,'default')}} - {{Helper::getCanonicalDatetime($bookings->dropoff,'default')}}</strong></small>
            </th>
        </tr>
        @if(!empty($bookings->vehicle) && !empty($bookings->vehicle->average))
        <tr>
            <th>Kilometers</th>
            <th>Next Reference Booking</th>
        </tr>
        <tr>
            <td>
                {!!Form::text('fodder_km',$routed_km,['class'=>'form-control fodder_km','id'=>'fodder_km','required','placeholder'=>'Enter Kilometers..','onkeypress'=>'return isNumber(event,this)'])!!}
                <label for="google_time" class="google_time"></label>
            </td>
            <td>
                {!!Form::select('next_booking',$next_booking,$routed_bookid,['class'=>'form-control next_booking','id'=>'next_booking','placeholder'=>'Select Next Booking'])!!}
                <input type="button" value="Calculate" class="btn btn-info google_calculate" id="google_calculate">
                <br>
                <label for="google_error" class="google_error"></label>
                {{-- {{$routed_bookid}} --}}
            </td>
        </tr>
        <tr class="ajax-class" {{!empty($routed_consumption) ? "style='display: none'" : ''}}>
            <th colspan="2">Fuel Consumption : {{$routed_consumption}} ltr</th>
        </tr>
        <tr>
            <td colspan="2">{!!Form::submit('Confirm',['class'=>'btn btn-success'])!!}</td>
        </tr>
        @else
        <tr>
            <td colspan="2" class="text-center" style="color: red;font-style: italic;letter-spacing: 0.09px;">Average of this vehicle is not set. To proceed further, set average  <a target="_blank" href="{{route('vehicles.edit',$bookings->vehicle_id)}}">here</a></td>
        </tr>
        @endif
    </table>
</form>