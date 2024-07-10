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
          <h3>Fuel Details Report</h3>
           {{-- <small>{{Helper::getCanonicalDate($date['from_date'])}}</small> to
          <small>{{Helper::getCanonicalDate($date['to_date'])}}</small>   --}}
          <h5>Vendor : <strong>{{$vendor_name}}</strong> &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;  Fuel : <strong>{{$fuel_name}}</strong></h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <tr>
          <th>SL#</th>
          <th>Date</th>
          <th>Vehicle</th>
          <th>Quantity(ltr)</th> 
          <th>Cost per unit</th> 
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
         
        @foreach($fuel as $k=>$data) 
        <tr>
          <td>{{$k+1}}</td>  
          <td>{{Helper::getCanonicalDate($data->date,'default')}}</td> 
          <td><strong>{{strtoupper($data->vehicle_data->license_plate)}}</strong></td>
           <td>{{$data->qty}}</td>  
           <td>{{$data->cost_per_unit}}</td>  
          <td>{{Hyvikk::get('currency')}} {{number_format($data->qty * $data->cost_per_unit,2,'.','')}}</td> 
          
        </tr>
  
        
        @endforeach
        <tr>
           <th colspan="4"></th>
           <th>Grand Total</th>
           <th>{{Hyvikk::get('currency')}} {{number_format($fuelSum,2,'.','')}}</th>
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