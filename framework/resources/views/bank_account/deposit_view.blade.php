<table class="table table-striped">
    <tr>
        <th style="width:150px;">Bank</th>
        <td><strong>{{$deposit->bank->bank}}</strong></td>
    </tr>
    @if(!empty($deposit->refer_bank))
    <tr>
        <th>From Bank</th>
        <td><strong>{{$deposit->referBank->bank}}</strong></td>
    </tr>
    @endif
    <tr>
        <th>Date</th>
        <td>{{Helper::getCanonicalDate($deposit->date,'default')}}</td>
    </tr>
    <tr>
        <th>Amount</th>
        <td>{{Helper::properDecimals($deposit->amount)}}</td>
    </tr>
    <tr>
        <th style="width: 50px;">Remarks</th>
        <td>{{$deposit->remarks}}</td>
    </tr>
</table>