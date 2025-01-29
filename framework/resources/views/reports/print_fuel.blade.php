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
    }
    .grand_total {
      text-align: center;
    }
    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body onload="window.print();">
@php
$date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'
@endphp

<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <span class="logo-lg">
            <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px">
            {{Hyvikk::get('app_name')}}
          </span>
          <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime($date,'default')}} / {{Helper::getCanonicalDateTime($date)}}</small>
        </h2>
      </div>
    </div>

    <!-- Regular Fuel Report -->
    <div class="row">
      <div class="col-md-12 text-center">
        <h3>@lang('fleet.fuelReport')</h3>
        @if(!empty($vehicle))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</h4>
          @if(!empty($fuel_type))
            <h4>{{$fuelType->fuel_name}}</h4>
          @endif
        @endif
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>SL#</th>
              <th>@lang('fleet.date')</th>
              @if(empty($vehicle))
                <th>Vehicle</th>
              @endif
              <th>Fuel</th>
              <th>Vendor</th>
              <th>Quantity</th>
              <th>Per Unit</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>@lang('fleet.total')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fuel as $k=>$f)
              <tr>
                <td>{{$k+1}}</td>
                <td>{{Helper::getCanonicalDate($f->date,'default')}}</td>
                @if(empty($vehicle))
                  <td><strong>{{$f->vehicle_data->license_plate}}</strong></td>
                @endif
                <td>{{$f->fuel_details->fuel_name}}</td>
                <td>
                  @if (!empty($f->vendor_name) && !empty($f->vendor))
                    {{$f->vendor->name}}
                  @else
                    <span style="color: red"><small>No Vendor Selected</small></span>
                  @endif
                </td>
                <td>{{$f->qty}}</td>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($f->cost_per_unit,1,2)}}</td>
                <td>
                  @if (!empty($f->is_gst))
                    {{!empty($f->cgst) ? $f->cgst."%" : ''}} <br>
                    {{!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''}}
                  @endif
                </td>
                <td>
                  @if (!empty($f->is_gst))
                    {{!empty($f->sgst) ? $f->sgst."%" : ''}} <br>
                    {{!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''}}
                  @endif
                </td>
                <td>
                  @if (!empty($f->grand_total))
                    {{Hyvikk::get('currency')}} {{bcdiv($f->grand_total,1,2)}}
                  @else
                    {{Hyvikk::get('currency')}} {{bcdiv($f->qty * $f->cost_per_unit,1,2)}}
                  @endif
                </td>
              </tr>
            @endforeach
            <tr>
              <th colspan="{{ empty($vehicle) ? '8' : '7'}}" class="grand_total"><strong>Grand Total</strong></th>
              <th>{{Hyvikk::get('currency')}} {{bcdiv($fuel->sum('gtotal'),1,2)}}</th>
              <th>{{bcdiv($fuel_totalqty,1,2)}} Liter</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Own Stock Report -->
    <div class="page-break"></div>
    <div class="row">
      <div class="col-md-12 text-center">
        <h3>Own Stock Report</h3>
        @if(!empty($vehicle))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</h4>
        @endif
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>SL#</th>
              <th>@lang('fleet.date')</th>
              @if(empty($vehicle))
                <th>Vehicle</th>
              @endif
              <th>Vendor</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            @foreach($own_stock as $k=>$stock)
              <tr>
                <td>{{$k+1}}</td>
                <td>{{Helper::getCanonicalDate($stock->date,'default')}}</td>
                @if(empty($vehicle))
                  <td>
                    @php
                      $vehicle = App\Model\VehicleModel::find($stock->vehicle_id);
                    @endphp
                    <strong>{{$vehicle ? $vehicle->license_plate : 'N/A'}}</strong>
                  </td>
                @endif
                <td>Own Stock</td>
                <td>{{$stock->quantity}}</td>
              </tr>
            @endforeach
            <tr>
              <th colspan="{{ empty($vehicle) ? '4' : '3'}}" class="grand_total"><strong>Total Quantity</strong></th>
              <th>{{bcdiv($own_stock_total_qty,1,2)}} Liter</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
</body>
</html>