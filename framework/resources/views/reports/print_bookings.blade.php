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
          <h3>@lang('fleet.booking_report')</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" id="data_table">
            <thead class="thead-inverse">
              <tr>
                <th>SL#</th>
                <th>Vehicle</th>
                <th>Customer</th>
                <th>From-To</th>
                <th>Distance</th>
                <th>Fuel Consumption</th>
                <th>Pickup Date</th>
                <th>Dropoff Date</th>
                <th>Material</th>
                <th>Quantity</th>
                <th>Driver Advance</th>
                <th>Freight Price</th>
              </tr>
            </thead>
            <tbody>
            @foreach($bookings as $k=>$bk)
              <tr>
                <td>{{$k+1}}</td>
                <th>{{$bk->vehicle->license_plate}}</th>
                <td>{{$bk->customer->name}}</td>
                <td>
                  @if(!empty($bk->transaction_det))
                  <strong>({{$bk->transaction_det->transaction_id}})</strong><br>
                  @endif
                  {{$bk->pickup_addr}} <i class="fa fa-long-arrow-right "></i> {{$bk->dest_addr}} 
                  
                  @if(!empty($bk->getMeta('fodder_km')))
                  @if(!empty($bk->transaction_details) && !empty($bk->transaction_details->booking))
                  <br>
                  <small>{{$bk->dest_addr}} <span class="fa fa-long-arrow-right"></span>
                    {{$bk->transaction_details->booking->pickup_addr}}
                  </small><br>
                  <small>Distance : {{!empty($bk->getMeta('fodder_km')) ? $bk->getMeta('fodder_km')."km" :null}}</small><br>
                  <small>Fuel : {{!empty($bk->getMeta('fodder_consumption')) ? bcdiv($bk->getMeta('fodder_consumption'),1,2)."ltr" :null}}</small><br>
                  <small>References Booking <strong>{{$bk->transaction_details->transaction_id}}</strong></small>
                  @endif
                  @endif
                </td>
                <td>
                   
                  {{$bk->getMeta('distance')}}
                  @if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption')))
                    <br>
                    <strong>+ {{preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->getMeta('fodder_km')) === 1 ? bcdiv($bk->getMeta('fodder_km'),1,2) : 0}} km</strong>
                  @endif
                </td>
				<td>
				    @if($bk->getMeta('pet_required') != "Infinity")
							    {{bcdiv($bk->getMeta('pet_required'),1,2)}} ltr
								@else
								0
								@endif
              
                  @if(!empty($bk->getMeta('fodder_km')) && !empty($bk->getMeta('fodder_consumption')))
                    <br>
                    <strong>+ {{bcdiv($bk->getMeta('fodder_consumption'),1,2)}} ltr</strong>
                  @endif
                </td>
                <td>{{Helper::getCanonicalDate($bk->pickup,'default')}}</td>
                <td>{{Helper::getCanonicalDate($bk->dropoff,'default')}}</td>
                <td>{{$bk->material}}</td>
                <td>{{$bk->loadqty}} {{$loadset[$bk->getMeta('loadtype')]}}</td>
                <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->advance_pay) === 1 ? bcdiv($bk->advance_pay,1,2) : 0}}</td>
                <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $bk->total_price) === 1 ? bcdiv($bk->total_price,1,2) : 0}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
          <table class="table table-bordered">
            <tr>
              <th>Addtional Distance</th>
              <th>Total Distance</th>
              <th>Additional Fuel</th>
              <th>Total Fuel</th>
              <th>Grand Total</th>
            </tr>
           
            <tr>
              <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $fodderdistance) === 1 ? bcdiv($fodderdistance,1,2) : 0}} km</td>
              <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_distance) === 1 ? bcdiv($total_distance,1,2) : 0}} ltr</td>
              <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $fodderfuel) === 1 ? bcdiv($fodderfuel,1,2) : 0}} ltr</td>
              <td>{{preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_fuel) === 1 ? bcdiv($total_fuel,1,2) : 0 }} ltr</td>
              <td>{{Hyvikk::get('currency')}} {{preg_match('/^[0-9]+(\.[0-9]+)?$/', $total_price) === 1 ? bcdiv($total_price,1,2) : 0}}</td>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
  <!-- ./wrapper -->
</body>
</html>