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
          <h3>Work-Order Vendor Report</h3>
          @if($is_vendor)
          <h4><strong><i>{{strtoupper($vendorName)}}</i></strong></h4>
          @endif
          <small>{{Helper::getCanonicalDate($date['from_date'])}}</small> to
          <small>{{Helper::getCanonicalDate($date['to_date'])}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
              <th width="10%">Date</th>
              @if($is_vendor!=true)
              <th>Vendor</th>
              @endif
              <th>Vehicle</th>
              <th>Type</th>
              <th width="15%">Description</th>
              <th>Status</th>
              <th>Amount</th>
            </thead>

            <tbody>
              @foreach($workOrder as $k=>$row)
                <tr>
                    <td>{{$k+1}}</td>
                    <td>{{Helper::getCanonicalDate($row->required_by,'default')}}</td>
                    @if($is_vendor!=true)
                    <td>{{$row->vendor->name}}</td>
                    @endif
                    <td>{{$row->vehicle->make}}-{{$row->vehicle->model}}-<strong>{{strtoupper($row->vehicle->license_plate)}}</strong></td>
                    <td>{{$row->vendor->type}}</td>
                    <td>{{$row->description}}</td>
                    <td>{{$row->status}}</td>
                    <td>{{Hyvikk::get('currency')}} {{number_format($row->price,2)}}</td>
                </tr>
              @endforeach
                <tr>
                  <th colspan="{{$is_vendor ? 4 : 5}}"></th>
                  <th>Grand Total</th>
                  <th>{{Hyvikk::get('currency')}} {{Helper::properDecimals($gtotal)}}</th>
                </tr>
            </tbody>
            <tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>