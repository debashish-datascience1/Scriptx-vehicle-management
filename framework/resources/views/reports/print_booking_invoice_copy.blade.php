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

          
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>Customer Name</th>
              <th>Vehicle</th>
              <th>Pick-up Address</th>
              <th>Drop-off Address</th>
              <th>Pick-up date & time</th>
              <th>Dropoff date & time</th>
              <th>Material</th>
              <th>Driver Name</th>
              <th>Total Freight Price</th>
              <th>Party Signature</th>
            </thead>
            
            <tbody>
            
            
            <td>{{$data->customer->name}}</td>
             <td>@if($data->vehicle_id)
                  {{$data->vehicle->make}} - {{$data->vehicle->model}} - {{$data->vehicle->license_plate}}
                  @endif</td>
            <td>{!! str_replace(",", ",<br>", $data->pickup_addr) !!}</td>
            <td>{!! str_replace(",", ",<br>", $data->dest_addr) !!}</td>
            <td>@if($data->pickup != null)
                {{date($date_format_setting.' g:i A',strtotime($data->pickup))}}
                @endif</td>
            <td>@if($data->dropoff != null)
                {{date($date_format_setting.' g:i A',strtotime($data->dropoff))}}
                @endif</td>
                <td>{{$data->getMeta('material')}}</td>
                <td>{{$data->driver->name}}</td>
                <td>{{$data->getMeta('total_price')}}</td>
                <td></td>
            
           
            </tbody>

          </table>
         <!-- <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>Load Price</th>
              <th>Load Quantity</th>
              <th>Fuel Per Litre</th>
              <th>Material</th>
              
            </thead>
           
            <tbody>
            
            
            <td>{{$data->getMeta('loadprice')}} per {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
             <td>{{$data->getMeta('loadqty')}} {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
            <td>{{$data->getMeta('perltr')}}</td>
            <td>{{$data->getMeta('material')}}</td>
            </tbody>

          </table> 

          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>Initial KM. on Vehicle</th>
              <th>Distance</th>
              <th>Duration</th>
              <th>Vehicle Mileage</th>
              <th>Fuel Required(ltr)</th>
              <th>Fuel Per Litre</th>
              <th>Total Fuel Price</th>
              <th>Total Freight Price</th>
              <th>Advance to Driver</th>
              
            </thead>
            
            <tbody>
            
            
            <td>{{$data->getMeta('initial_km')}} {{Hyvikk::get('dis_format')}}</td>
             <td>{{$data->getMeta('distance')}}</td>
            <td>{{$data->getMeta('duration_map')}}</td>
            <td>{{$data->getMeta('mileage')}} km/ltr</td>
            <td>{{$data->getMeta('pet_required')}} litre</td>
            <td>{{$data->getMeta('perltr')}}</td>
            <td>{{$data->getMeta('petrol_price')}}</td>
            <td>{{$data->getMeta('total_price')}}</td>
            <td>@if($data->getMeta('advance_pay')!='')
                            <span class="fa fa-inr"></span> {{$data->getMeta('advance_pay')}}
                        @else
                            <span class="badge badge-warning"><i>No Advance was given...</i></span>
                        @endif</td>
            </tbody>

          </table>-->
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>