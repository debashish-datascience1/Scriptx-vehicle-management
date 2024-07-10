<table class="table table-striped">
    <tr>
        <th colspan="4"  style="font-size: 25px;text-align:center">{{ucwords($bulk->name)}}</th>
    </tr>
    <tr>
        <th colspan="2">
            {!! Form::label('bank',"Bank :", ['class' => 'form-label']) !!}
            <br>
            {{$bulk->bank->bank}}
        </th>
        <th>
            {!! Form::label('amount', "Amount :", ['class' => 'form-label']) !!}
            <br>
            {{Hyvikk::get('currency')}}{{Helper::properDecimals($bulk->amount)}}
        </th>
        <th>
            {!! Form::label('date', "Date :", ['class' => 'form-label']) !!}
            <br>
            {{Helper::getCanonicalDate($bulk->date,'default')}}
        </th>
    </tr>
    <tr>
        <th>Transaction</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Remarks</th>
    </tr>
    @foreach($bulk_list as $h)
    <tr>
        <td>{{$h->trash->transaction_id}}</td>
        <td>{{Hyvikk::get('currency')}} {{$h->amount}}</td>
        <td>{{$faults[$h->fault]}}</td>
        <td>{{!empty($h->comment) ? Helper::limitText($h->comment,400) : '-'}}</td>
    </tr>
    @endforeach
</table>