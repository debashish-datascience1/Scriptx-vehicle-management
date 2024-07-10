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
          <h3>Salary Processing Report</h3>
          <small>Salary for the month of {{$date1}} ({{$date2}})</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
              <th>Name</th>
              <th>Bank</th>
              <th>A/C No.</th>
              <th>Payable Amount</th>
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
                      {{$row->driver->bank}}
                    @else
                      {{$row->bank}}
                    @endif
                  </td>
                  <td>
                    @if($row->is_payroll)
                      {{$row->driver->account_no}}
                    @else
                      {{$row->account_no}}
                    @endif
                  </td>
                  <td>
                    {{bcdiv($row->payable_salary,1,2)}}
                    @if($row->is_payroll)
                    <span title="Paid"><i class="fa fa-check"></i></span>
                    @endif
                  </td>
                </tr>
            @endforeach
            <tr>
              <th colspan="3"></th>
              <th><strong>Total Amount</strong></th>
              <th>{{Hyvikk::get('currency')}} {{bcdiv($salaries->sum('payable_salary'),1,2)}}</th>
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