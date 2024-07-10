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
{{-- @php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y') --}}

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

            {{-- <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date($date_format_setting)}}</small> --}}
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Booking Details Report</h3>
        
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>@lang('fleet.customer')</th>
                <th>@lang('fleet.vehicle')</th>
                <th>Advance</th>
                <th>Payment Amount</th>
                <th>Total Amount</th>
                <th>@lang('fleet.status')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bookings as $row)
              <tr style="text-align: left">
                <td>{{$row->customer->name}}</td>
                <td>
                  @if($row->vehicle_id != null)
                  {{$row->vehicle->make}} - {{$row->vehicle->model}} - {{$row->vehicle->license_plate}}
                  @endif
                  </td>
                <td>@if($row->advance_pay != null) 
                  <i class="fa fa-inr"></i> {{$row->advance_pay}} 
                @else
                  <span>N/A</span>
                @endif</td>
                <td>@if($row->payment_amount != null)
                  <i class="fa fa-inr"></i> {{$row->payment_amount}}
                @else
                  <span>N/A</span>
                @endif</td>
                <td>{{$row->total_price}}</td>
                <td>@if($row->status==0)<span style="color:orange;">@lang('fleet.journey_not_ended') @else <span style="color:green;">@lang('fleet.journey_ended') @endif</span></td>
              </tr>
              @endforeach
            </tbody>
          
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>