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
          <h3>Driver Advance Report</h3>
          @if(!empty($driver_id))
          <h4>{{$driver_name}}</h4>
          @endif
          @if(!empty($vehicleData))
          <h5>{{$vehicleData['make']}}-{{$vehicleData['model']}}-{{$vehicleData['license_plate']}}</h4>
          @endif
          <small>{{$from_date}} - {{$to_date}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover table-responsive">
            <thead>
                <th>SL#</th>
                <th>Date</th>
                {{-- @if(empty($driver_id)) --}}
                <th>Driver</th>
                <th>Vehicle</th>
                {{-- @endif --}}
                <th >From - To</th>
                <th>Distance Duration</th>
                <th>Party</th>
                <th style="width: 12%">{{Hyvikk::get('currency')}} Advance</th>
            </thead>

            <tbody>
            @foreach($advance_bookings as $key => $adv)
                <tr>
                    <td>{{$key+1}}</td>
                    <td nowrap>{{Helper::getCanonicalDate($adv->pickup,'default')}}<br>{{date("h:i:s A",strtotime($adv->pickup))}}</td>
                    {{-- @if(empty($driver_id)) --}}
                    <td>{{$adv->driver->name}}</td>
                    <td>{{$adv->vehicle->license_plate}}</td>
                    {{-- @endif --}}
                    <td>{{$adv->pickup_addr}} <span class="fa fa-long-arrow-right"></span> {{$adv->dest_addr}}</td>
                    <td>{{$adv->distance}} <br>{{$adv->duration_map}}</td>
                    <td>{{$adv->party_name}}</td>
                    <td nowrap>{{Hyvikk::get('currency')}} {{bcdiv($adv->advance_pay,1,2)}}</td>
                </tr>
            @endforeach
            <tr>
              <td colspan="6"></td>
              <td><strong>Total Advance</strong></td>
              <td><strong>{{Hyvikk::get('currency')}} {{bcdiv($total_advance,1,2)}}</strong></td>
            </tr>
            </tbody>

          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>