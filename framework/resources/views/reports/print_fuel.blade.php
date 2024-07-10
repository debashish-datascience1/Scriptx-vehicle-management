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
    .grand_total{
      text-align: center;
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
          <h3>@lang('fleet.fuelReport')</h3>
          @if(!empty($vehicle))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</h4>
          @if(!empty($fuel_type))
          <h4>{{$fuelType->fuel_name}}</h4>
          @endif
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>SL#</th>
							<th>@lang('fleet.date')</th>
							@if(empty($vehicle))
              <th>Vehicle</th>
              @endif
							{{-- @if(empty($fuel_types)) --}}
              <th>Fuel</th>
              {{-- @endif --}}
							<th>Vendor</th>
							<th>Quantity</th>
              <th>Per Unit</th>
              <th>CGST</th>
              <th>SGST</th>
							<th>@lang('fleet.total')</th>
              
              
            </thead>

            <tbody>
            @foreach($fuel as $k=>$f)
            <tr>
							<td>{{$k+1}}</td>
							<td nowrap>
								{{Helper::getCanonicalDate($f->date,'default')}}<br>			
							</td>
              @if(empty($vehicle))
							<td><strong>{{$f->vehicle_data->license_plate}}</strong>
              </td>
              @endif
							<td>
								@if (!empty($f->vendor_name) && !empty($f->vendor))
									{{$f->vendor->name}}
								@else
									<span style="color: red"><small>No Vendor Selected</small></span>
								@endif
							</td>
							<td>{{$f->fuel_details->fuel_name}}</td>
							<td>
								{{$f->qty}}
							</td>
              <td nowrap>{{Hyvikk::get('currency')}} {{bcdiv($f->cost_per_unit,1,2)}}</td>
              <td nowrap>
                @if (!empty($f->is_gst))
                {{!empty($f->cgst) ? $f->cgst."%" : ''}} <br>
                {{!empty($f->cgst_amt) ? Hyvikk::get('currency')." ".$f->cgst_amt : ''}}
                @endif
							</td>
              <td nowrap>
								@if (!empty($f->is_gst))
									{{!empty($f->sgst) ? $f->sgst."%" : ''}} <br>
									{{!empty($f->sgst_amt) ? Hyvikk::get('currency')." ".$f->sgst_amt : ''}}
								@endif
							</td>
							<td nowrap>
								@if (!empty($f->grand_total))
								{{Hyvikk::get('currency')}} {{bcdiv($f->grand_total,1,2)}}
								@else
								{{Hyvikk::get('currency')}} {{bcdiv($f->qty * $f->cost_per_unit,1,2)}}
								@endif
							</td>
						</tr>
            @endforeach
            <tr>
              {{-- <th colspan="{{ empty($vehicle) ? '7' : '6'}}"></th> --}}
              <th colspan="{{ empty($vehicle) ? '8' : '7'}}" class="grand_total"><strong>Grand Total</strong></th>
              <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($fuel->sum('gtotal'),1,2)}}</th>
              <th nowrap> {{bcdiv($fuel_totalqty,1,2)}} Liter</th>
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