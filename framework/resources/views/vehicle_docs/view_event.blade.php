
<table class="table table-striped">
    <tr>
        <th>Transaction ID</th>
        <td>{{$row->transaction->transaction_id}}</td>
    </tr>
    <tr>
        <th>Document</th>
        <td>{{$row->document->label}}</td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td>{{$row->vehicle->make}} - {{$row->vehicle->model}} - <label>{{$row->vehicle->license_plate}}</label></td>
    </tr>
    <tr>
        <th>Driver</th>
        <td>
            @if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver))
                {{$row->drivervehicle->assigned_driver->name}}
            @else
            <span style="color: red"><small><i>Driver not assigned</i></small></span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Vendor</th>
        <td>
            {{$row->vendor->name}}
        </td>
    </tr>
    <tr>
        <th>On Date</th>
        <td>
            {{Helper::getCanonicalDate($row->date)}}
        </td>
    </tr>
    <tr>
        <th>Valid Till</th>
        <td>
            {{Helper::getCanonicalDate($row->till)}}<br>
            @php   ($to = \Carbon\Carbon::now())

            @php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till))

            @php ($diff_in_days = $to->diffInDays($from))
            <label>@lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')</label>
        </td>
    </tr>
    <tr>
        <th>Amount</th>
        <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->amount)}}</td>
    </tr>
    <tr>
        <th>Method</th>
        <td>{{$row->method_param->label}}</td>
    </tr>
    <tr>
        <th>Reference No.</th>
        <td>{{$row->ddno}}</td>
    </tr>
</table>
