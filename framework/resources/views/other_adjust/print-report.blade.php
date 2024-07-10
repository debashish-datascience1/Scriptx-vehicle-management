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
          <h3>Other Advance Report</h3>
          <small>{{Helper::getCanonicalDate($from_date,'default')}} - {{Helper::getCanonicalDate($to_date,'default')}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>SL#</th>
                <th>Driver</th>
                <th>Head</th>
                <th>Date</th>
                <th>Method</th> {{--ref no too--}}
                <th>Bank</th>
                <th>Remarks</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
            @foreach($other_adj as $k=>$oth)
              <tr>
                <td>{{$k+1}}</td>
                <td>{{$oth->other_advance->driver->name}}</td>
                <td>{{$oth->head}}</td>
                <td>{{Helper::getCanonicalDate($oth->date,'default')}}</td>
                <td>{{$oth->method_param->label}}<br><strong>{{$oth->ref_no}}</strong></td>
                <td>
                  {{$oth->bank_details->bank}}<br>
                  <strong>{{$oth->bank_details->account_no}}</strong>
                </td>
                <td>{{Helper::limitText($oth->remarks,30)}}</td>
                <td>
                  {{Helper::properdecimals($oth->amount)}}<br>
                  @if($oth->type==23)
                    <span class="badge badge-success">Credit</span>
                  @else
                    <span class="badge badge-danger">Debit</span>
                  @endif
                </td>
              </tr>
            @endforeach
            <tr>
              <td colspan="6"></td>
              <th>Total Adjust</th>
              <th>{{Hyvikk::get('currency')}}{{Helper::properDecimals($other_adj->sum('amount'))}}</th>
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