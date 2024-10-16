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
          <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime($date,'default')}} / {{Helper::getCanonicalDateTime($date)}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Customer Payment Report</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <thead>
            <tr>
              <th width="10%">SL#</th>
              <th>Date</th>
              <th>From</th>
              <th>Transaction ID</th>
              <th>Method</th>
              <th>Payment Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
             @foreach($transaction as $k=>$row) 
            <tr>
              <td>{{$k+1}}</td>
              <td>
                {{Helper::getCanonicalDate($row->created_at,'default')}}<br>
                {{Helper::getCanonicalDate($row->created_at)}}
              </td>
              <td>
              @if($row->param_id==18)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                  <br>
                  @if($row->advance_for==21)
                  {{$row->advancefor->label}}
                  @endif
                @elseif($row->param_id==19)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==20)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==25)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==26)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==27)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==28)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @elseif($row->param_id==29)
                  {{!empty($row->params) ? $row->params->label : 'N/A'}}
                @else{{dd($row->param_id)}}
              @endif
              </td>
               <td>{{$row->transaction_id}}</td>
               <td>{{$row->incExp->method->label}}</td>
              <td> @if($row->type==23)
                        <span>{{$row->pay_type->label}}</span>
                    @elseif($row->type==24)
                        <span>{{$row->pay_type->label}}</span>
                    @endif
              </td>
              <td>{{Hyvikk::get('currency')}} {{$row->total}}</td>

            </tr>
            @endforeach
            <tr>
              <th colspan="5"></th>
              <th>Grand Total</th>
              <th>{{Hyvikk::get('currency')}} {{Helper::properDecimals($sumoftotal)}}</th>
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