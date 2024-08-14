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
          <h3>Salary Report</h3>
          <small>Salary for the month of {{$date1}} ({{$date2}})</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover" style="font-size: 12px;">
            <thead>
              <th style="width: 50px;">SL#</th>
              <th style="width: 150px;">Name</th>
              <th style="width: 100px;">Vehicle</th>
              <th style="width: 100px;">Present/Absent</th>
              <th style="width: 100px;">Net Salary</th>
              <th style="width: 100px;">Booking Adv. Salary</th>
              <th style="width: 100px;">Salary Advance</th>
              <th style="width: 100px;">Total Advance</th>
              <th style="width: 100px;">Absent Deduct</th>
              <th style="width: 150px;">Payable Amount</th>
            </thead>
            <tbody>
              @foreach($salaries as $k=>$row)
              <tr>
                <td>{{$k+1}}</td>
                <td>
                  @if($row->is_payroll)
                  {{$row->driver->name}}
                  @else
                  {{$row->driver}}
                  @endif
                </td>
                <td>
                  @if($row->is_payroll)
                  {{$row->driver->driver_vehicle->vehicle->license_plate}}
                  @else
                  {{$row->vehicle}}
                  @endif
                </td>
                <td>{{$row->days_present}}/{{$row->days_absent}}</td>
                <td>{{bcdiv($row->gross_salary,1,2)}}</td>
                <td>{{bcdiv($row->bookingAdvance,1,2)}}</td>
                <td>{{bcdiv($row->salary_advance,1,2)}}</td>
                <td>{{bcdiv($row->bookingAdvance + $row->salary_advance, 1, 2)}}</td>
                <td>{{bcdiv($row->deduct_amount,1,2)}}</td>
                <td style="word-wrap: break-word;">{{bcdiv($row->payable_salary,1,2)}}</td>
              </tr>
              @endforeach
              <tr>
                <th colspan="3"></th>
                <th><strong>Total Amount(s)</strong></th>
                <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('gross_salary'),1,2)}}</th>
                <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('bookingAdvance'),1,2)}}</th>
                <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('salary_advance'),1,2)}}</th>
                <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('bookingAdvance') + $salaries->sum('salary_advance'), 1, 2)}}</th>
                <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('deduct_amount'),1,2)}}</th>
                <th style="word-wrap: break-word;">{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('payable_salary'),1,2)}}</th>
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