<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-striped table-hover" id="myTable1">
      <tr>
        <td align="center" style="font-size:23px;">
          <strong>{{$vehicleData['vehicle']->make}}-{{$vehicleData['vehicle']->model}}-{{$vehicleData['vehicle']->license_plate}}</strong>
          @if(!empty($vehicleData['vehicle']->driver))
            <br><span>{{ucwords(strtolower($vehicleData['vehicle']->driver->assigned_driver->name))}}</span>
          @endif
        </td>
      </tr>
      
      <!-- Bookings -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
            </tr>
            <tr>
              <th>No. of Booking(s)</th>
              <th>Total KM</th>
              <th>Total Fuel</th>
              <th>Total Amount</th>
            </tr>
          </thead>
          <tbody>
            @if($vehicleData['bookings']->totalbooking != 0)
              <tr>
                <td>{{$vehicleData['bookings']->totalbooking}} bookings</td>
                <td>{{$vehicleData['bookings']->totalkms}} {{Hyvikk::get('dis_format')}}</td>
                <td>{{$vehicleData['bookings']->totalfuel}} {{Hyvikk::get('fuel_unit')}}</td>
                <td>{{Hyvikk::get('currency')}} {{$vehicleData['bookings']->totalprice}}</td>
              </tr>
            @else
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            @endif
          </tbody>
        </table>
      </tr>
      
      <!-- Fuel -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
            </tr>
            <tr>
              <th>Fuel Type</th>
              <th>No. of Refuel(s)</th>
              <th>Quantity</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @if(!empty($vehicleData['fuels']))
              @foreach($vehicleData['fuels'] as $k=>$fs)
                <tr>
                  <td>{{$k}}</td>
                  <td>{{count($fs->id)}} time(s)</td>
                  <td>{{array_sum($fs->ltr)}} {{ $k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'}}</td>
                  <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals(array_sum($fs->total))}}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            @endif
          </tbody>
        </table>
      </tr>
      
      <!-- Driver Advance -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
            </tr>
          </thead>
          <tbody>
            @if(!empty($vehicleData['advances']->details))
              <tr>
                <td>
                  <table class="table tabl-bordered table-striped">
                    <thead>
                      <th>#</th>
                      <th>Head</th>
                      <th>No. of Time(s)</th>
                      <th>Amount</th>
                    </thead>
                    <tbody>
                      @foreach($vehicleData['advances']->details as $k=>$det)
                        <tr>
                          <td>{{$k+1}}</td>
                          <td>{{$det->label}}</td>
                          <td>{{$det->times}}</td>
                          <td>{{Hyvikk::get('currency')}} {{!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)}}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <th colspan="3" style="text-align:right;">Total</th>
                        <th>{{Hyvikk::get('currency')}} {{!empty($vehicleData['advances']->amount) ? Helper::properDecimals(array_sum($vehicleData['advances']->amount)) : Helper::properDecimals(0)}}</th>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            @else
              <tr>
                <td colspan="4" align='center' style="color: red">No Records Found...</td>
              </tr>
            @endif
          </tbody>
        </table>
      </tr>
      
      <!-- Work Order -->
      <tr>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
            </tr>
            <tr>
              <th>No. of Work Order(s)</th>
              <th>GST</th>
              <th>Total</th>
              <th>No. of Vendors</th>
              <th>Status</th>
              <th>Parts Used</th>
            </tr>
          </thead>
          <tbody>
            @if(!empty($vehicleData['wo']->count) && $vehicleData['wo']->count!=0)
              <tr>
                <td>{{$vehicleData['wo']->count}}</td>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th>CGST</th>
                      <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($vehicleData['wo']->cgst)}}</td>
                    </tr>
                    <tr>
                      <th>SGST</th>
                      <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($vehicleData['wo']->sgst)}}</td>
                    </tr>
                  </table>
                </td>
                <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($vehicleData['wo']->grand_total)}}</td>
                <td>{{$vehicleData['wo']->vendors}}</td>
                <td>
                  <table class="table table-striped">
                    @foreach($vehicleData['wo']->status as $k=>$s)
                      <tr>
                        <th>{{$k}}</th>
                        <td>{{count($s)}}</td>
                      </tr>
                    @endforeach
                  </table>
                </td>
                <td>
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Part</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(!empty($vehicleData['partsUsed']))
                        @foreach($vehicleData['partsUsed'] as $pu)
                          <tr>
                            <td>{{$pu->part->title}}</td>
                            <td>{{$pu->qty}}</td>
                            <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($pu->total)}}</td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="3" align='center' style="color: red">No Parts Used...</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </td>
              </tr>
            @else
              <tr>
                <td colspan="6" align='center' style="color: red">No Records Found...</td>
              </tr>
            @endif
          </tbody>
        </table>
      </tr>
    </table>
  </div>
</div>