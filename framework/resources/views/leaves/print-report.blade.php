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

            <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date($date_format_setting)}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Driver Leave Report</h3>
          @if(!empty($driver_id))
          <h4>{{$driver_data->name}}</h4>
          @endif
          <?php $vehicle = !empty($driver_data->driver_vehicle) ? $driver_data->driver_vehicle->vehicle : null; ?>
          @if(!empty($vehicle))
          <h5>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</h4>
          @endif
          <small>{{Helper::getCanonicalDate($from_date)}} - {{Helper::getCanonicalDate($to_date)}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <th>#</th>
              <th>Date</th>
              @if(empty($driver_id))
              <th>Driver</th>
              <th>Vehicle</th>
              @endif
              <th>Status</th>
              <th>Remarks</th>
            </thead>

            <tbody>
              @foreach($leaves as $k=>$l)
              <tr>
                <td>{{$k+1}}</td>
                <td>{{Helper::getCanonicalDate($l->date,'default')}}</td>
                @if(empty($driver_id))
                <td>{{$l->driver->name}}</td>
                <td>
                  <?php $vehicle = !empty($l->driver_vehicle) ? $l->driver_vehicle->vehicle : null; ?>
                  @if(!empty($vehicle))
                  {{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}
                  @else
                    <label>N/A</label>
                  @endif
                </td>
                @endif
                <td>
                  {{ empty($l->is_present) ? "N/A" : Helper::getLeaveTypes()[$l->is_present]}}
                </td>
                <td>{{$l->remarks}}</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="{{ empty($driver_id) ? '4' : '2'}}"></td>
              <td><strong>Total Present : {{$total_present}}</strong></td>
              <td><strong>Total Absent : {{$total_absent}}</strong></td>
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