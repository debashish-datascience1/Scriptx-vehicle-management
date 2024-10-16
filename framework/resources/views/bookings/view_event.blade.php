<div role="tabpanel" style="margin-bottom: 10px;">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"> General Information <i class="fa"></i></a></li>
        <li class="nav-item"><a href="#load-tab" data-toggle="tab" class="nav-link custom_padding"> Load Details <i class="fa"></i></a></li>
        <li class="nav-item"><a href="#journey-tab" data-toggle="tab" class="nav-link custom_padding"> Journey Details <i class="fa"></i></a></li>
        @if($booking->status==1)
        <li class="nav-item adexist"><a href="#advance-tab" data-toggle="tab" class="nav-link custom_padding"> Advance <i class="fa"></i></a></li>
        @endif
    </ul>

    <div class="tab-content">
        <!-- General Information, Load, and Journey tabs remain unchanged -->
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <tr>
                    <th>Customer </th>
                    <td>{{$booking->customer->name}}</td>
                </tr>
                <tr>
                    <th>Vehicle </th>
                    <td>{{$booking->vehicle->make}} - {{$booking->vehicle->model}} - {{$booking->vehicle->license_plate}}</td>
                </tr>
                <tr>
                    <th>Driver </th>
                    <td>{{$booking->driver->name}}</td>
                </tr>
                <tr>
                    <th>Pickup Address</th>
                    <td>{{$booking->pickup_addr}}</td>
                </tr>
                <tr>
                    <th>Pickup Date & Time</th>
                    <td>{{Helper::getCanonicalDateTime($booking->pickup,'default')}}</td>
                </tr>
                <tr>
                    <th>Dropoff Address</th>
                    <td>{{$booking->dest_addr}}</td>
                </tr>
                <tr>
                    <th>Dropoff Date & Time</th>
                    <td>{{Helper::getCanonicalDateTime($booking->dropoff,'default')}}</td>
                </tr>
                <tr>
                    <th>Party Name</th>
                    <td>{{$booking->getMeta('party_name')}}</td>
                </tr>
                <tr>
                    <th>Narration</th>
                    <td>{{$booking->getMeta('narration')}}</td>
                </tr>
                @if($booking->status==1)
                <tr>
                    <th>Booking Status</th>
                    <td><span class="badge badge-success">Completed</span></td>
                </tr>
                @endif
            </table>
        </div>
        <!-- Load Tab-->
        <div class="tab-pane" id="load-tab">
            <table class="table table-striped">
                <tr>
                    <th>Load Price</th>
                    <td><span class="fa fa-inr"></span> {{$booking->getMeta('loadprice')}} per {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
                </tr>
                <tr>
                    <th>Load Quantity</th>
                    <td>{{$booking->getMeta('loadqty')}} {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
                </tr>
                <tr>
                    <th>Fuel Per Litre</th>
                    <td><span class="fa fa-inr"></span> {{$booking->getMeta('perltr')}}</td>
                </tr>
                <tr>
                    <th>Material</th>
                    <td>{{$booking->getMeta('material')}}</td>
                </tr>
            </table>
        </div>
        <!-- Journey Tab-->
        <div class="tab-pane" id="journey-tab">
            <table class="table table-striped">
                <tr>
                    <th>Initial KM. on Vehicle</th>
                    <td>{{$booking->getMeta('initial_km')}} {{Hyvikk::get('dis_format')}}</td>
                </tr>
                <tr>
                    <th>Distance</th>
                    <td>{{$booking->getMeta('distance')}}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{{$booking->getMeta('duration_map')}}</td>
                </tr>
                <tr>
                    <th>Vehicle Mileage</th>
                    <td>{{$booking->getMeta('mileage')}} km/ltr</td>
                </tr>
                <tr>
                    <th>Fuel Required(ltr)</th>
                    <td>{{$booking->getMeta('pet_required')}} litre</td>
                </tr>
                <tr>
                    <th>Fuel Per Litre</th>
                    <td> <span class="fa fa-inr"></span> {{$booking->getMeta('perltr')}}</td>
                </tr>
                <tr>
                    <th>Total Fuel Price</th>
                    <td><span class="fa fa-inr"></span>  {{$booking->getMeta('petrol_price')}}</td>
                </tr>
                <tr>
                    <th>Total Freight Price</th>
                    <td><span class="fa fa-inr"></span>  {{$booking->getMeta('total_price')}}</td>
                </tr>
                <tr>
                    <th>Advance to Driver</th>
                    <td>
                        @if($booking->getMeta('advance_pay')!='')
                            <span class="fa fa-inr"></span> {{$booking->getMeta('advance_pay')}}
                        @else
                            <span class="badge badge-warning"><i>No Advance was given...</i></span>
                        @endif
                    </td>
                </tr>
                @if(!empty($booking->getMeta('fodder_km')))
                <tr>
                    <th>Addtional Route</th>
                    <td>
                        {{!empty($booking->getMeta('fodder_km')) ? $booking->getMeta('fodder_km')."km" :null}}<br>
                        <small>{{$booking->dest_addr}} <span class="fa fa-long-arrow-right"></span> {{$booking->transaction_details->booking->pickup_addr}}</small>
                        <br>
                        <small>References Booking <strong>{{$booking->transaction_details->transaction_id}}</strong></small>
                    </td>
                </tr>
                @endif
                @if(!empty($booking->getMeta('fodder_consumption')))
                <tr>
                    <th>Addtional Fuel Consumption</th>
                    <td>{{!empty($booking->getMeta('fodder_consumption')) ? $booking->getMeta('fodder_consumption')."ltr" :null}}</td>
                </tr>
                @endif
                <tr>
                    <th>Ride Status</th>
                    <td>
                        @php $status_arr = ['upcoming'=>'warning','completed'=>'success','cancelled'=>'danger'] @endphp
                        <span class="badge badge-{{$status_arr[strtolower($booking->getMeta('ride_status'))]}}">{{$booking->getMeta('ride_status')}}</span>
                    </td>
                </tr>
            </table>
        </div>
        @if($booking->status==1)
        <div class="tab-pane" id="advance-tab">
            <table class="table table-striped">
            @if($advances->count() > 0)
                @php
                    $groupedAdvances = $advances->groupBy('param_id');
                    $totalAdvance = 0;
                @endphp
                @foreach($groupedAdvances as $paramId => $advanceGroup)
                    @php
                        // Get the advance with the highest id (assuming it's the most recent)
                        $latestAdvance = $advanceGroup->sortByDesc('id')->first();
                        $totalAdvance += $latestAdvance->value;
                    @endphp
                    <tr>
                        <th>{{$latestAdvance->param_name->label}}</th>
                        <td>
                            @if($latestAdvance->value != '')
                                <i class="fa fa-inr"></i> {{$latestAdvance->value}}
                            @else
                                <span class="badge badge-warning">N/A</span>
                            @endif
                        </td>
                        <td>{{$latestAdvance->remarks}}</td>
                    </tr>
                @endforeach
                <tr style="border-top:2px solid #4fb765;">
                    <th>Grand Total Advance</th>
                    <th>
                        {{Hyvikk::get('currency')}}{{$totalAdvance}}
                    </th>
                    <th></th>
                </tr>
            @else
                <tr>
                    <td colspan="3" align="center" style="color: red"><i>No Advances were given in this booking...</i></td>
                </tr>
            @endif
            </table>
        </div>
        @endif
    </div>
</div>


