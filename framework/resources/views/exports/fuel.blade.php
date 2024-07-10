<table>
    <thead>
     <tr>
        <th><strong>SL#</strong></th>
        <th><strong>Date</strong></th>
        <th><strong>Vendor</strong></th>
        <th><strong>Vehicle</strong></th>
        <th><strong>Fuel</strong></th>
        <th><strong>Rate</strong></th>
        <th><strong>Qty(ltr)</strong></th>
        <th><strong>Amount</strong></th>
     </tr>
    </thead>

    <tbody>
        @foreach($fuel as $k=>$row)
            <tr>
                <td>{{$k+1}}</td>
                <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
                
                <td>
                  @if(!empty($row->vendor))
                    {{$row->vendor->name}}
                  @else
                    <span class='badge badge-danger'>{{$row-id}}Unnamed Vendor</span>
                  @endif
                </td>
                
                <td>{{$row->vehicle_data->make}}-{{$row->vehicle_data->model}}-<strong>{{strtoupper($row->vehicle_data->license_plate)}}</strong></td>
                <td>
                    @if(!empty($row->fuel_details))
                      {{$row->fuel_details->fuel_name}}
                    @else
                      <span class='badge badge-danger'>Unnamed Fuel</span>
                    @endif
                </td>
                <td>{{$row->cost_per_unit}}</td>
                <td>{{$row->qty}}</td>
                <td>{{bcdiv($row->qty * $row->cost_per_unit,1,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="5"></th>
            <th>Total</th>
            <th>{{bcdiv($fuelQtySum,1,2)}} ltr</th>
            <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($fuelTotal,1,2)}}</th>
        </tr>
    </tbody>
  </table>