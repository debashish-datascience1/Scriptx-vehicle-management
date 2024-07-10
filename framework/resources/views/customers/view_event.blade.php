<table class="table table-striped">
    <tr>
        <th>Name</th>
        <td>{{$customer->name}}</td>
    </tr>
    @if(!empty($customer->gstin))
    <tr>
        <th>GSTIN</th>
        <td>{{$customer->gstin}}</td>
    </tr>
    @endif
    <tr>
        <th>Email</th>
        <td>{{$customer->email}}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{$customer->getMeta('mobno')}}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{$customer->address}}</td>
    </tr>
    <tr>
        <th>Opening Balance</th>
        <td>{{Hyvikk::get('currency')}} {{bcdiv($customer->opening_balance,1,2)}}</td>
    </tr>
    <tr>
        <th>Opening Remarks</th>
        <td>{{$customer->opening_remarks}}</td>
    </tr>
</table>