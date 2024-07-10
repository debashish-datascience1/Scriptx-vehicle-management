<table class="table table-striped">
    <tr>
        <th>Vendor</th>
        <td>
            {{$vendor->name}}
            @if($vendor->photo!="")
            <a href="{{asset("uploads/".$vendor->photo)}}" class="col-xs-3 control-label" target="_blank" >(View)</a>
            @endif
        </td>
    </tr>
    <tr>
        <th>Type</th>
        <td>{{$vendor->type}}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{$vendor->phone}}</td>
    </tr>
    <tr>
        <th>Address 1</th>
        <td>{{$vendor->address1}}</td>
    </tr>
    <tr>
        <th>Address 2</th>
        <td>{{$vendor->address2}}</td>
    </tr>
    <tr>
        <th>City</th>
        <td>{{$vendor->city}}</td>
    </tr>
    <tr>
        <th>Postal Code</th>
        <td>{{$vendor->postal_code}}</td>
    </tr>
    <tr>
        <th>Country</th>
        <td>{{$vendor->country}}</td>
    </tr>
    <tr>
        <th>State/Province</th>
        <td>{{$vendor->province}}</td>
    </tr>
    <tr>
        <th>Note</th>
        <td>{{$vendor->note}}</td>
    </tr>
    <tr>
        <th>Opening Balance</th>
        <td>{{bcdiv($vendor->opening_balance,1,2)}}</td>
    </tr>
    <tr>
        <th>Opening Details</th>
        <td>{{$vendor->opening_comment}}</td>
    </tr>
</table>