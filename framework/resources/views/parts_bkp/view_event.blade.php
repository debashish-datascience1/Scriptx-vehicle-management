<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Unit Cost</th>
      <th>Quantity</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    @foreach($parts as $dat)
    <tr>
      <td>{{$dat->title}}</td>
      <td>{{$dat->category->name}}</td>
      <td>{{Hyvikk::get('currency')." ". $dat->unit_cost}}</td>
      <td>{{$dat->quantity}}</td>
      <td>{{Hyvikk::get('currency')." ". $dat->total}}</td>
    </tr>
    @endforeach
    <tr>
      <th colspan="3"></th>
      <th>Total</th>
      <th>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->sub_total)}}</th>
    </tr>
    @if($row->is_gst==1 && !empty($row->cgst) && !empty($row->sgst))
    <tr style="font-size: 14px;font-weight: 600;">
      <td colspan="1"></td>
      <td style="text-align: center;">CGST <br> SGST</td>
      <td>{{$row->cgst}} % <br> {{$row->sgst}} %</td>
      <td>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->cgst_amt)}} <br> {{Hyvikk::get('currency')}}{{Helper::properDecimals($row->sgst_amt)}}</td>
      <td style="vertical-align: middle;font-size: 16px;">
      {{Hyvikk::get('currency')}}{{Helper::properDecimals($row->cgst_amt + $row->sgst_amt)}}
      </td>
    <tr>
      <th colspan="3"></th>
      <th>Grand Total</th>
      <th>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->grand_total)}}</th>
    </tr>
    @endif
  </tbody>
</table>