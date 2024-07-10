@if(!empty($advance))
<table class="table table-striped">
    @foreach($advance as $adv)
    <tr>
        <th>{{$adv->param_name->label}}</th>
        <th>
            {{Hyvikk::get('currency')}} {{number_format($adv->value,2)}}
        </th>
    </tr>
    @endforeach
    <tr style="border-top:2px solid #02bcd1">
        <th>Grand Total</th>
        <th>{{Hyvikk::get('currency')}} {{number_format($gtotal,2)}}</th>
    </tr>
</table>
@else
<span>Advance to {{$transaction->booking->driver->name}} is <span class="badge badge-danger">{{Hyvikk::get('currency')}} {{$transaction->total}}</span> for this booking. You need to <span class="badge badge-success">Mark as Complete</span>in <strong>Bookings</strong> to view the adjusted details.</span>
@endif