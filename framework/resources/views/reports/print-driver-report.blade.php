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

          <small class="pull-right"> <b>Print @lang('fleet.date') : </b> {{Helper::getCanonicalDateTime(date('Y-m-d H:i:s'),'default')}} / {{Helper::getCanonicalDateTime(date('Y-m-d H:i:s'))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Driver Payroll Report</h3>
          @if(!empty($driver_id))
          <h4>{{$driver_name}}</h4>
          @endif
          @if(!empty($vehicleData))
          <h5>{{$vehicleData['license_plate']}}</h4>
          @endif
          <small>{{$from_date}} - {{$to_date}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                {{-- @if(empty($driver_id)) --}}
                <th>SL#</th>
                <th>Driver</th>
                <th>Vehicle</th>
                {{-- @endif --}}
                <th>For Month-Year</th>
                <th>Date Paid</th>
                <th>Salary</th>
                <th>Payable Salary</th>
            </thead>

            <tbody>
            @foreach($payroll as $k=>$p)
            <tr>
                {{-- @if(empty($driver_id)) --}}
                <td>{{$k+1}}</td>
                <td>{{$p->driver->name}}</td>
                <td>{{$p->vehicle_det}}</td>
                {{-- @endif --}}
                <td nowrap>{{date("d-m-Y",strtotime($p->for_date))}}</td>
                <td nowrap>{{Helper::getCanonicalDate($p->created_at,'default')}}</td>
                <td> {{bcdiv($p->salary,1,2)}}</td>
                <td>{{bcdiv($p->payable_salary,1,2)}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="{{ empty($driver_id) ? '4' : '3'}}"></td>
              <td><strong>Grand Total</strong></td>
              <td nowrap><strong>{{Hyvikk::get('currency')}} {{bcdiv($total_salary,1,2)}}</strong></td>
              <td nowrap><strong>{{Hyvikk::get('currency')}} {{bcdiv($payable_salary,1,2)}}</strong></td>
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