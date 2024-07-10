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
  <style>
    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
    .itaDates{font-weight: 600;}
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
          <h3>Upcoming Renewal Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover"  id="myTable">
            <thead>
              <tr>
                <th>SL#</th>
                <th>Vehicle</th>
                @foreach ($documents as $dt)	
                <th>{{$dt}}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($data as $k=>$d)
              {{-- Check Last Date, Amount, Duration --}}
                <tr>
                  <td>{{$k+1}}</td>
                  <th>{{$d->license_plate}}</th>
                  @foreach ($documents as $kb=>$db)
                  <td>
                    <?php
                    $dbDocs = Helper::dynamicLastDate($d->id,$kb);
                    if ($dbDocs->status)
                      $date = $dbDocs->date;
                    else
                       $date = !empty($d->getMeta($docparamArray[$kb][2])) ? $d->getMeta($docparamArray[$kb][2]) : '';
                    ?>
                    {{-- eligible renew --}}
                    @if (!empty($date) && strtotime($date)<=strtotime(date('Y-m-d')))
                      <strong>{{Helper::getCanonicalDate($date,'default')}}</strong>
                    @elseif(!empty($date) && strtotime($date)<=strtotime(date('Y-m-d')."+15 days"))
                      <i class="itaDates">{{Helper::getCanonicalDate($date,'default')}}</i>
                    @else
                      {{Helper::getCanonicalDate($date,'default')}}
                    @endif
                  </td>
                  @endforeach
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
