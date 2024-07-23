<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Hyvikk::get('app_name')}}</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/font-awesome.min.css')}}">
  <link href="{{ asset('assets/css/cdn/ionicons.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/css/AdminLTE.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/fonts.css')}}">
  <style type="text/css">
    body { height: auto; }
  </style>
</head>
<body onload="window.print();">
  @php
    $date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
  @endphp

  <div class="wrapper">
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <span class="logo-lg">
              <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px">
              {{  Hyvikk::get('app_name')  }}
            </span>
            <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date('Y-m-d')}}</small>
          </h2>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Parts Invoice Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          @if(isset($request['vendor_id']) && $request['vendor_id'])
            <p><strong>Vendor:</strong> {{ $vendors[$request['vendor_id']] }}</p>
          @endif
          <p><strong>Date Range:</strong> {{ $request['date1'] ?? 'N/A' }} to {{ $request['date2'] ?? 'N/A' }}</p>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Vendor</th>
                <th>Bill No</th>
                <th>Date of Purchase</th>
                <th>Parts</th>
                <th>Tyre Numbers</th>
                <th>Sub Total</th>
                <th>Grand Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $k=>$invoice)
                <tr>
                  <td>{{$k+1}}</td>
                  <td>{{$invoice->vendor->name}}</td>
                  <td>{{$invoice->billno}}</td>
                  <td>{{date($date_format_setting, strtotime($invoice->date_of_purchase))}}</td>
                  <td>
                    @foreach($invoice->partsDetails as $detail)
                      @if($detail->parts_zero)
                        {{$detail->parts_zero->item ?? 'N/A'}} 
                        {{$detail->parts_zero->category->name ?? 'N/A'}} 
                        ({{$detail->parts_zero->manufacturer_details->name ?? 'N/A'}})
                      @else
                        N/A
                      @endif
                      <br>
                    @endforeach
                  </td>
                  <td>
                    @foreach($invoice->partsDetails as $detail)
                      @php
                        $partsModel = App\Model\PartsModel::find($detail->parts_id);
                        $tyre_numbers = $partsModel ? $partsModel->tyres_used : '';
                        $numbers_array = explode(',', $tyre_numbers);
                        $formatted_numbers = [];
                        foreach (array_chunk($numbers_array, 4) as $chunk) {
                          $formatted_numbers[] = implode(', ', $chunk);
                        }
                        echo nl2br(implode("\n", $formatted_numbers));
                      @endphp
                      <br>
                    @endforeach
                  </td>
                  <td>{{Hyvikk::get('currency')}} {{number_format($invoice->sub_total, 2)}}</td>
                  <td>{{Hyvikk::get('currency')}} {{number_format($invoice->grand_total, 2)}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          
          <table class="table table-bordered">
            <tr>
              <th>Total Sub Total</th>
              <th>Total Grand Total</th>
            </tr>
            <tr>
              <td>{{Hyvikk::get('currency')}} {{number_format($total_sub_total, 2)}}</td>
              <td>{{Hyvikk::get('currency')}} {{number_format($total_grand_total, 2)}}</td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
</body>
</html>