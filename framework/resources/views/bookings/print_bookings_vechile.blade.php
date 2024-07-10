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
   <link href="{{ asset('assets/css/print.css') }}" rel="stylesheet">
   

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  {{-- <link rel="stylesheet" href="{{ asset('assets/css/cdn/fonts.css')}}"> --}}
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
          <h2 class="page-header text-center">
          Booking Invoice <br>
          <strong><small><i>{{$data->customer->name}}</i></small></strong>
          </h2>
          
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table style="width: 100%;">
            <tr>
              <th colspan="3">
               <h4><u>Pickup Address :</u></h4>
                {{$data->pickup_addr}}
                <br>
                {{date('d-F-Y g:i:s A',strtotime($data->pickup))}}
                
              </th>
              <th style="float: right">
                <h4><u>Dropoff Address :</u></h4>
                {{$data->dest_addr}}
                <br>
                {{date('d-F-Y g:i:s A',strtotime($data->dropoff))}}
              </th>
            </tr>
          </table>
          <br>
          <table style="width: 100%">
            <tr>
              <td>
                <span style="display: block;font-weight:600">Customer :</span>
                {{$data->customer->name}}
              </td>
              <td>
                <span style="display: block;font-weight:600">Driver :</span>
                {{ucwords($data->driver->name)}}
              </td>
              <td>
                 <span style="display: block;font-weight:600">Vehicle :</span>
                {{$data->vehicle->make}} - {{$data->vehicle->model}} - {{$data->vehicle->license_plate}}
              </td>
              <td>
                 <span style="display: block;font-weight:600">Material :</span>
                {{ucwords($data->getMeta('material'))}}
              </td>
            </tr>
            <tr>
              <td>
                <span style="display: block;font-weight:600">Load Price :</span>
                {{Hyvikk::get('currency')}}{{$data->getMeta('loadprice')}} per {{$params->label=='Quantity' ? 'Quintals' : $params->label}}
              </td>
              <td>
                <span style="display: block;font-weight:600">Load Quantity :</span>
                {{$data->getMeta('loadqty')}} {{$params->label=='Quantity' ? 'Quintals' : $params->label}}
              </td>
              <td>
                 <span style="display: block;font-weight:600">Total Freight Price :</span>
                {{Hyvikk::get('currency')}}{{$data->getMeta('total_price')}}
              </td>
              <td>
                 <span style="display: block;font-weight:600">Distance :</span>
                {{$data->getMeta('distance')}}
              </td>
            </tr>
            
          </table>
          <br>
          <table style="width: 100%">
            <tr>
              {{-- <th style="float: left;"><i>Driver Signature/Thumb impression</i></th> --}}
              <th style="float: left;">Party Name</th>
              <th style="text-align:center;vertical-align:middle">Narration</th>
              <th style="float: right;"><i>Party Seal and Signature</i></th>
            </tr>
            <tr>
              {{-- <th style="float: left;"><i>Driver Signature/Thumb impression</i></th> --}}
              <td style="float: left;">{{$data->getMeta('party_name')}}</td>
              <td style="text-align:center;vertical-align:middle">{{$data->getMeta('narration')}}</td>
              <td style="float: right;"></td>
              
              
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>
            