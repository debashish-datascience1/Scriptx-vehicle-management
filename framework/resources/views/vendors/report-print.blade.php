<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Hyvikk::get('app_name')}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Bootstrap 3.3.7 -->
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/bootstrap.min.css')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link href="{{ asset('assets/css/cdn/ionicons.min.css')}}" rel="stylesheet">
  <!-- Theme style -->
   <link href="{{ asset('assets/css/AdminLTE.min.css') }}" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/fonts.css')}}">
  <style type="text/css">
    body {
      height: auto;
    }
  </style>
</head>
<body onload="window.print();">
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

  <div class="wrapper">
  <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <span class="logo-lg">
          <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px">
          {{  Hyvikk::get('app_name')  }}
          </span>
          <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime(date('Y-m-d H:i:s'),'default')}} / {{Helper::getCanonicalDateTime(date('Y-m-d H:i:s'))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vendor Fuel Report</h3>
          @if($is_vendor)
          <h4><strong><i>{{strtoupper($vendorName)}}</i></strong></h4>
          @endif
          <small>{{Helper::getCanonicalDate($date['from_date'])}}</small> to
          <small>{{Helper::getCanonicalDate($date['to_date'])}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover table-responsive">
            <thead>
              <tr>
                <th>SL#</th>
                <th>Date</th>
                @if($is_vendor!=true)
                <th>Vendor</th>
                @endif
                <th>Vehicle</th>
                <th>Fuel</th>
                <th>Rate</th>
                <th>Qty(ltr)</th>
                <th><span class="fa fa-inr"></span> Amount</th>
              </tr>
            </thead>

            <tbody>
                @foreach($fuel as $k=>$row)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td nowrap>{{Helper::getCanonicalDate($row->date,'default')}}</td>
                        @if($is_vendor!=true)
                        <td>
                          @if(!empty($row->vendor))
                            {{$row->vendor->name}}
                          @else
                            <span class='badge badge-danger'>Unnamed Vendor</span>
                          @endif
                        </td>
                        @endif
                        <td>{{$row->vehicle_data->make}}-{{$row->vehicle_data->model}}-<strong>{{strtoupper($row->vehicle_data->license_plate)}}</strong></td>
                        <td>
                            @if(!empty($row->fuel_details))
                              {{$row->fuel_details->fuel_name}}
                            @else
                              <span class='badge badge-danger'>Unnamed Fuel</span>
                            @endif
                        </td>
                        <td nowrap>{{Hyvikk::get('currency')}} {{$row->cost_per_unit}}</td>
                        <td nowrap>{{$row->qty}}</td>
                        <td nowrap>{{Hyvikk::get('currency')}} {{bcdiv($row->qty * $row->cost_per_unit,1,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="{{ !$is_vendor ? 5 : 4}}"></th>
                    <th>Total</th>
                    <th nowrap>{{bcdiv($fuelQtySum,1,2)}} ltr</th>
                    <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($fuelTotal,1,2)}}</th>
                </tr>
            </tbody>
            {{-- <tfoot> --}}
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>