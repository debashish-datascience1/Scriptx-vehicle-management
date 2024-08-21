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
    .balances{font-weight: 700;}
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

          <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime(date("Y-m-d H:i:s"),'default')}} / {{Helper::getCanonicalDateTime(date("Y-m-d H:i:s"))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Account Statement</h3>
          <h6>{{Helper::getCanonicalDate($from_date,'default')}} - {{Helper::getCanonicalDate($to_date,'default')}}</h6>
          {{-- <table>
            <tr>
              <th>Opening Balance</th>
              <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($openingBalance)}}</td>
            </tr>
            <tr>
              <th>Closing Balance</th>
              <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($closingBalance)}}</td>
            </tr>
          </table> --}}
          <span class='balances'>Opening Balance : {{Hyvikk::get('currency')}} {{bcdiv($openingBalance,1,2)}}</span> &nbsp;&nbsp;&nbsp;
         | &nbsp;&nbsp;&nbsp;<span class='balances'>Closing Balance : {{Hyvikk::get('currency')}} {{bcdiv($closingBalance,1,2)}}</span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>SL#</th>
                <th>@lang('fleet.date')</th>
                <th>Invoice ID</th>
                <th>Method</th>
                <th>Type</th>
                <th>Particulars</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
            @foreach($transactions as $k=>$t)
                <tr>
                  <td>{{$k+1}}</td>
                  <td nowrap>{{Helper::getCanonicalDate($t->dateof,'default')}}</td>
                  <td>{{ $t->transaction->transaction_id ?? 'N/A' }}</td>
                  <td>{{$t->method->label ?? 'N/A'}}</td>
                  <td>{{$t->transaction->pay_type->label ?? 'N/A'}}</td>
                  <td>
                      @if($t->transaction)
                          @if($t->transaction->param_id==18 && $t->transaction->advance_for==21)
                              {{Hyvikk::get('currency')}}  {{$t->transaction->booking->advance_pay ?? 'N/A'}} advance given to {{$t->transaction->booking->driver->name ?? 'N/A'}} for Booking references <strong>{{Helper::getTransaction($t->transaction->from_id,$t->transaction->param_id)->transaction_id ?? 'N/A'}}</strong>   on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
                          @elseif($t->transaction->param_id==18 && $t->transaction->advance_for==22)
                              {{Hyvikk::get('currency')}}  {{$t->transaction->booking->payment_amount ?? 'N/A'}} paid by {{$t->transaction->booking->customer->name ?? 'N/A'}} for Booking on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
                          @elseif($t->transaction->param_id==19)
                              {{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label ?? 'N/A'}}ed towards {{$t->transaction->payroll->driver->name ?? 'N/A'}} for the month of <strong>{{date('F-Y',strtotime($t->transaction->payroll->for_date))}}/{{date('m-Y',strtotime($t->transaction->payroll->for_date))}}</strong>  {{$t->transaction->type==23 ? "to" : "from"}} {{$t->transaction->params->label ?? 'N/A'}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
                          @elseif($t->transaction->param_id==20)
                              {{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label ?? 'N/A'}}ed towards {{$t->transaction->fuel->vendor->name ?? 'N/A'}} for <strong>{{$t->transaction->fuel->vehicle_data->license_plate ?? 'N/A'}}</strong> {{$t->transaction->type==23 ? "to" : "from"}}  {{$t->transaction->params->label ?? 'N/A'}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
                          @else
                              {{Hyvikk::get('currency')}} {{bcdiv($t->transaction->total,1,2)}} {{$t->transaction->pay_type->label ?? 'N/A'}}ed {{$t->transaction->type==23 ? "to" : "from"}} {{$t->transaction->params->label ?? 'N/A'}} on <strong>{{Helper::getCanonicalDate($t->dateof,'default')}}</strong>
                          @endif
                      @else
                          N/A
                      @endif
                  </td>
                  <td>
                      @if($t->transaction)
                          @if (!in_array($t->transaction->param_id,[18,20,26]))
                              {{bcdiv($t->transaction->total,1,2)}}
                          @else
                              {{bcdiv($t->amount,1,2)}}
                          @endif
                      @else
                          N/A
                      @endif
                  </td>
                </tr>
            @endforeach
            <tr>
              <th colspan="3"></th>
              <th><strong>Closing Amount</strong></th>
              <th>{{Hyvikk::get('currency')}} {{bcdiv($closingAmount,1,2)}}</th>
              <th><strong>Grand Total</strong></th>
              <th>{{Hyvikk::get('currency')}} {{bcdiv($transactions->sum('amount'),1,2)}}</th>
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