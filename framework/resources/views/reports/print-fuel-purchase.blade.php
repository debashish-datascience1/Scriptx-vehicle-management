<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Hyvikk::get('app_name')}} - Fuel Purchase Report</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/font-awesome.min.css')}}">
  <link href="{{ asset('assets/css/cdn/ionicons.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/css/AdminLTE.min.css') }}" rel="stylesheet">

  <style type="text/css">
    body {
      height: auto;
    }
    .table-bordered > thead > tr > th, 
    .table-bordered > tbody > tr > th, 
    .table-bordered > tfoot > tr > th, 
    .table-bordered > thead > tr > td, 
    .table-bordered > tbody > tr > td, 
    .table-bordered > tfoot > tr > td {
      border: 1px solid #000;
    }
    .table > tbody > tr > td, .table > tbody > tr > th, 
    .table > tfoot > tr > td, .table > tfoot > tr > th, 
    .table > thead > tr > td, .table > thead > tr > th {
      padding: 8px;
      line-height: 1.42857143;
      vertical-align: top;
    }
  </style>
</head>
<body onload="window.print();">
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

  <div class="wrapper">
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <span class="logo-lg">
              <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px">
              {{  Hyvikk::get('app_name')  }}
            </span>
            <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date($date_format_setting)}}</small>
          </h2>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Fuel Purchase Report</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">
            <p><strong>Vendor:</strong> {{ $vendor->name }}</p>
            <p><strong>Fuel Type:</strong> {{ $fuel_type_name }}</p>
          </div>
          <div class="col-md-6 text-right">
            <p><strong>From Date:</strong> {{ Helper::indianDateFormat($from_date) }}</p>
            <p><strong>To Date:</strong> {{ Helper::indianDateFormat($to_date) }}</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL#</th>
                  <th>@lang('fleet.vendor')</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                  <th>Remarks</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transactions as $k => $row)
                <tr>
                  <td>{{$k+1}}</td>
                  <td>{{ optional($row->vendor)->name ?? '-' }}</td>
                  <td>{{ $row->quantity }}</td>
                  <td>{{ bcdiv($row->amount,1,2) }}</td>
                  <td>{{ $row->remarks ?? '-' }}</td>
                  <td>{{ Helper::getCanonicalDate($row->date,'default') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-right">
          <h4>Total Amount: {{ Hyvikk::get('currency') }} {{ bcdiv($total_amount,1,2) }}</h4>
        </div>
      </div>
    </section>
  </div>
</body>
</html>