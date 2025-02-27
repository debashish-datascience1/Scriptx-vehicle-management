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
    body {
      height: auto;
      font-size: 10px;  /* Reduced base font size */
    }
    .grand_total {
      text-align: right;
      font-weight: bold;
    }
    .table {
      margin-bottom: 0;
    }
    .table > thead > tr > th,
    .table > tbody > tr > td {
      padding: 4px;    /* Reduced padding */
      vertical-align: middle;
      font-size: 11px; /* Slightly larger than base for readability */
    }
    .totals-row {
      border-top: 2px solid #000;
    }
    .totals-row td {
      font-weight: bold;
    }
    .nowrap {
      white-space: nowrap;
    }
    @media print {
      .table {
        font-size: 10px !important;
      }
      .page-header {
        margin: 10px 0 20px;
      }
      .table > thead > tr > th,
      .table > tbody > tr > td {
        padding: 4px !important;
      }
    }
  </style>
</head>
<body onload="window.print();">
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

<div class="wrapper">
  <section class="invoice">
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header" style="margin-top: 0;">
          <span class="logo-lg">
            <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px; height: 45px;">
            {{Hyvikk::get('app_name')}}
          </span>
          <small class="pull-right" style="margin-top: 5px;"><b>@lang('fleet.date'): </b> {{Helper::getCanonicalDateTime($date,'default')}}</small>
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 text-center">
        <h3 style="margin: 10px 0;">Transporter Report</h3>
        @if(!empty($transport_group))
          <h4 style="margin: 5px 0;">Transport Group: {{$transport_group->group_name}}</h4>
        @endif
        @if(!empty($fuelType))
          <h4 style="margin: 5px 0;">Fuel Type: {{$fuelType->fuel_name}}</h4>
        @endif
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-striped" style="width: 100%">
          <thead>
            <tr>
              <th style="width: 3%">SL#</th>
              <th style="width: 8%">@lang('fleet.date')</th>
              <th style="width: 12%">Transporter</th>
              <th style="width: 10%">Vehicle</th>
              <th style="width: 10%">Vendor</th>
              <th style="width: 8%">Fuel</th>
              <th style="width: 7%">Qty</th>
              <th style="width: 10%">Price/Ltr</th>
              <th style="width: 8%">CGST</th>
              <th style="width: 8%">SGST</th>
              <th style="width: 12%">@lang('fleet.total')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fuel as $k=>$f)
              <tr>
                <td>{{$k+1}}</td>
                <td class="nowrap">{{Helper::getCanonicalDate($f->date,'default')}}</td>
                <td>{{$f->group_transport->group_name ?? 'N/A'}}</td>
                <td>{{$f->vehicle_data->license_plate ?? 'N/A'}}</td>
                <td>{{!empty($f->vendor) ? $f->vendor->name : 'N/A'}}</td>
                <td>{{$f->fuel_details->fuel_name}}</td>
                <td>{{$f->qty}}</td>
                <td class="nowrap">{{Hyvikk::get('currency')}} {{number_format($f->cost_per_unit, 2)}}</td>
                <td class="nowrap">
                  @if (!empty($f->is_gst))
                    {{$f->cgst ?? 0}}%<br>
                    {{Hyvikk::get('currency')}} {{number_format($f->cgst_amt ?? 0, 2)}}
                  @endif
                </td>
                <td class="nowrap">
                  @if (!empty($f->is_gst))
                    {{$f->sgst ?? 0}}%<br>
                    {{Hyvikk::get('currency')}} {{number_format($f->sgst_amt ?? 0, 2)}}
                  @endif
                </td>
                <td class="nowrap">
                  {{Hyvikk::get('currency')}} {{number_format($f->grand_total ?? ($f->qty * $f->cost_per_unit), 2)}}
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="totals-row">
              <td colspan="6" class="grand_total">Grand Total:</td>
              <td>{{number_format($fuel_totalqty, 2)}} Ltr</td>
              <td colspan="3" class="text-right">Total Amount:</td>
              <td class="nowrap">{{Hyvikk::get('currency')}} {{number_format($fuel->sum('gtotal'), 2)}}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>
</div>
</body>
</html>