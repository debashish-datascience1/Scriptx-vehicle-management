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
            <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime($date,'default')}} / {{Helper::getCanonicalDateTime($date)}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vehicle Documents Report</h3>
          @if(!empty($vehicle))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</h4>
          @if(!empty($d->driver_id) && !empty($d->drivervehicle) && !empty($d->drivervehicle->assigned_driver))
          <h4>{{$d->drivervehicle->assigned_driver->name}}</h4>
          @else
          <span style="color: red"><small><i>Driver not assigned</i></small></span><br>
          @endif
          @endif
          <small>{{$from_date}} - {{$to_date}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover"  id="myTable">
            <thead>
              <tr>
                <th>SL#</th>
                @if(empty($vehicle))
                <th>Vehicle</th>
                {{-- <th>Driver</th> --}}
                @endif
                <th>Vendor</th>
                <th>Documents</th>
                <th>Method / Ref. No.</th>
                <th>Renewed On</th>
                <th>Valid Till</th>
                <th>Remaining Days</th>
                <th>Amount</th>{{--method,ddno,status--}}
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
            @foreach($docs as $k=>$d)
              <tr>
                <td>{{$k+1}}</td>
                @if(empty($vehicle))
                <td>{{$d->vehicle->license_plate}}</td>
                {{-- <td>
                  @if(!empty($d->driver_id) && !empty($d->drivervehicle) && !empty($d->drivervehicle->assigned_driver))
                    {{$d->drivervehicle->assigned_driver->name}}
                  @else
                  <span style="color: red"><small><i>Driver not assigned</i></small></span>
                  @endif
                </td> --}}
                @endif
                <td>
                  {{$d->vendor->name}}
                </td>
                <td>{{$d->document->label}}</td>
                <td>
                  <span class="badge badge-primary">{{$d->method_param->label}}</span><br>
                  {{$d->ddno}}
                </td>
                <td>{{Helper::getCanonicalDate($d->date,'default')}}</td>
                <td>{{Helper::getCanonicalDate($d->till,'default')}}</td>
                <td>@lang('fleet.after') {{Helper::renewLastday($d->till)}} @lang('fleet.days')</td>
                <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($d->amount)}}</td>
                <td width="17%">{{$d->remarks}}</td>
              </tr>
            @endforeach
              <tr>
                <td colspan="{{ empty($vehicle) ? '7' : '6'}}"></td>
                <td><strong>Grand Total</strong></td>
                <td><strong>{{Hyvikk::get('currency')}} {{Helper::properDecimals($docs->sum('amount'))}}</strong></td>
                <td></td>
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
