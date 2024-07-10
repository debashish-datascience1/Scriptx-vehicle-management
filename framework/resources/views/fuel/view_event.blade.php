
<table class="table table-striped">
    
    <tr>
        <th>Vendor</th>
        <td>{{$row->vendor->name}}</td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td>{{$row->vehicle_data->make}}-{{$row->vehicle_data->model}}-{{$row->vehicle_data->license_plate}}</td>
    </tr>
    <tr>
        <th>Date </th>
        <td>{{Helper::getCanonicalDate($row->date)}}</td>
    </tr>
    <tr>
        <th>Fuel </th>
        <td>{{$row->fuel_details->fuel_name}}</td>
    </tr>
    <tr>
        <th>Quantity</th>
        <td>{{$row->qty}}</td>
    </tr>
    <tr>
        <th>Per Unit</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->cost_per_unit)}}</td>
    </tr>
    <tr>
        <th>Fuel Price</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->qty*$row->cost_per_unit)}}</td>
    </tr>
    @if(!empty($row->cgst))
    <tr>
        <th>Is GST ?</th>
        <td>
            @if($row->is_gst==1)
            <span class="badge badge-success">Yes</span>
            @else
            <span class="badge badge-danger">No</span>
            @endif
        </td>
    </tr>
    @endif
    @if(!empty($row->cgst))
    <tr>
        <th>CGST Rate</th>
        <td>{{$row->cgst}} %</td>
    </tr>
    <tr>
        <th>CGST Amount</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->cgst_amt)}}</td>
    </tr>
    @endif
    @if(!empty($row->sgst))
    <tr>
        <th>SGST Rate</th>
        <td>{{$row->sgst}} %</td>
    </tr>
    <tr>
        <th>SGST Amount</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->sgst_amt)}}</td>
    </tr>
    @endif
    @if(!empty($row->cgst) || !empty($row->sgst))
    <tr>
        <th>GST Amount</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->cgst_amt+$row->sgst_amt)}}</td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals(($row->qty*$row->cost_per_unit)+$row->cgst_amt+$row->sgst_amt)}}</td>
    </tr>
    @endif
    <tr>
        <th>Reference</th>
        <td>{{$row->reference}}</td>
    </tr>
    <tr>
        <th>Start Meter</th>
        <td>{{$row->start_meter}}</td>
    </tr>
    <tr>
        <th>Province</th>
        <td>{{$row->province}}</td>
    </tr>
    <tr>
        <th>Note</th>
        <td>{{$row->note}}</td>
    </tr>
    <tr>
        <th>Complete Fill Up</th>
        <td>
            @if($row->complete==1)
            <span class="badge badge-success">Yes</span>
            @else
            <span class="badge badge-danger">No</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>is Paid ?</th>
        <td>
            @if(!empty(Helper::getTransaction($row->id,20)) && Helper::getTransaction($row->id,20)->is_completed==1)
                <span class="badge badge-success">Yes</span>
            @elseif(!empty(Helper::getTransaction($row->id,20)) && Helper::getTransaction($row->id,20)->is_completed==null)
                <span class="badge badge-danger">No</span>
            @else
                <span class="badge badge-warning">In Progress</span>
            @endif
        </td>
    </tr>
</table>
		