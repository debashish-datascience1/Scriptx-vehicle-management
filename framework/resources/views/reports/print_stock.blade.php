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
          <h3>Parts Stock Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p><strong>Date Range:</strong> {{ $request['date1'] ?? 'N/A' }} to {{ $request['date2'] ?? 'N/A' }}</p>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Part Name</th>
                <th>Category</th>
                <th>Manufacturer</th>
                <th>Stock</th>
                <th>Tyres Used</th>
                <th>Tyre Numbers</th>
              </tr>
            </thead>
            <tbody>
              @php
                  $total_stock = 0;
                  $total_tyres_used = 0;
              @endphp
              @foreach($parts as $k=>$part)
                  @php
                    $total_stock += $part->stock ?? 0;
                    $total_tyres_used += $tyres_used[$part->id]->total_used ?? 0;
                  @endphp
                <tr>
                  <td>{{$k+1}}</td>
                  <td>{{$part->item ?? 'N/A'}}</td>
                  <td>{{$part->category->name ?? 'N/A'}}</td>
                  <td>{{$part->manufacturer_details->name ?? 'N/A'}}</td>
                  <td>{{$part->stock ?? 'N/A'}}</td>
                  <td>{{$tyres_used[$part->id]->total_used ?? 0}}</td>
                  <td>
                    @php
                      $tyre_numbers = $part->tyres_used ?? '';
                      if (!empty($tyre_numbers)) {
                        $numbers_array = explode(',', $tyre_numbers);
                        $formatted_numbers = [];
                        foreach (array_chunk($numbers_array, 4) as $chunk) {
                          $formatted_numbers[] = implode(', ', $chunk);
                        }
                        echo nl2br(implode("\n", $formatted_numbers));
                      } else {
                        echo 'N/A';
                      }
                    @endphp
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <table class="table table-bordered">
            <tr>
              <th>Total Stock</th>
              <th>Total Tyres Used</th>
            </tr>
            <tr>
              <td> {{number_format($total_stock)}}</td>
              <td> {{number_format($total_tyres_used)}}</td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
</body>
</html>

