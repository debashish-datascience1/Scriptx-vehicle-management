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
          <h3>Service Reminder Report</h3>
          @if(!empty($vehicle_id))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}- <strong>{{$vehicle->license_plate}}</strong></h4>
          @endif
          <small>{{$from_date}} - {{$to_date}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>SL#</th>
                @if(empty($vehicle_id))
                <th>@lang('fleet.vehicle')</th>
                @endif
                <th>@lang('fleet.service_item')</th>
                <th>@lang('fleet.start_date') / @lang('fleet.last_performed') </th>
                <th>@lang('fleet.next_due') (@lang('fleet.date'))</th>
                <th>@lang('fleet.next_due') (@lang('fleet.meter'))</th>
            </thead>

            <tbody>
                @foreach($services as $k=>$reminder)
                <tr>
                    <td>{{$k+1}}</td>
                    @if(empty($vehicle_id))
                    <td><strong>{{$reminder->vehicle->license_plate}}</strong></td>
                    @endif
                    <td>
                        {{$reminder->services['description']}}
                        <br>
                        @lang('fleet.interval'): {{$reminder->services->overdue_time}} {{$reminder->services->overdue_unit}}
                        @if($reminder->services->overdue_meter != null)
                        @lang('fleet.or') {{$reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                        @endif
                    </td>
                    <td> 
                        @lang('fleet.start_date'): {{date($date_format_setting,strtotime($reminder->last_date))}}
                        <br>
                        @lang('fleet.last_performed') @lang('fleet.meter'): {{$reminder->last_meter}}
                    </td>
                    <td>
                        @php($interval = substr($reminder->services->overdue_unit,0,-3))
                        @if($reminder->services->overdue_time != null)
                          @php($int = $reminder->services->overdue_time.$interval)
                        @else
                          @php($int = Hyvikk::get('time_interval')."day")
                        @endif
                          
                        @if($reminder->last_date != 'N/D')
                         @php($date = date('Y-m-d', strtotime($int, strtotime($reminder->last_date)))) 
                        @else
                         @php($date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))))) 
                        @endif
                        {{-- {{dd($date)}} --}}
                        {{ date($date_format_setting,strtotime($date)) }}
                        <br>
                        @php   ($to = \Carbon\Carbon::now())
        
                        @php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $date))
        
                        @php ($diff_in_days = $to->diffInDays($from))
                        @lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')
                    </td>
                    <td>
                        @if($reminder->services->overdue_meter != null)
                            @if($reminder->last_meter == 0)
                                {{$reminder->vehicle->int_mileage + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                            @else
                                {{$reminder->last_meter + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>